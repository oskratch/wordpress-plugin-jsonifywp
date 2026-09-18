<?php

if (!defined('ABSPATH')) exit;

class JsonifyWP_Admin {
    public function __construct() {
        add_action('admin_menu', [$this, 'menu']);
        add_action('admin_init', [$this, 'handle_actions']);
        add_action('wp_ajax_jsonifywp_test_connection', [$this, 'ajax_test_connection']);
    }

    /**
     * Returns the list of allowed template filenames for a given templates
     * subfolder ('list' or 'detail'), so submitted values can be validated
     * against what actually exists on disk instead of trusting the POST body.
     */
    private static function allowed_templates($type) {
        $dir = JSONIFYWP_DIR . 'templates/' . $type . '/';
        if (!is_dir($dir)) return [];
        return array_values(array_filter(
            scandir($dir),
            function ($tpl) use ($dir) {
                return is_file($dir . $tpl) && pathinfo($tpl, PATHINFO_EXTENSION) === 'php';
            }
        ));
    }

    /**
     * AJAX: fetches an API URL from the add/edit form (before it's saved)
     * and returns a short preview so the user can verify it before saving.
     */
    public function ajax_test_connection() {
        check_ajax_referer('jsonifywp_test_connection', 'nonce');
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => __('Not allowed.', 'jsonifywp')], 403);
        }

        $url = isset($_POST['url']) ? esc_url_raw(wp_unslash($_POST['url'])) : '';
        if (empty($url)) {
            wp_send_json_error(['message' => __('No URL provided.', 'jsonifywp')]);
        }

        $response = wp_remote_get($url, ['timeout' => 10]);
        if (is_wp_error($response)) {
            wp_send_json_error(['message' => $response->get_error_message()]);
        }

        $code = wp_remote_retrieve_response_code($response);
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if ($code !== 200) {
            wp_send_json_error(['message' => sprintf(__('API returned status %d.', 'jsonifywp'), $code)]);
        }
        if (!is_array($data)) {
            wp_send_json_error(['message' => __('Response is not valid JSON.', 'jsonifywp')]);
        }

        $first_item = isset($data[0]) ? $data[0] : (isset($data['items'][0]) ? $data['items'][0] : $data);
        wp_send_json_success([
            'fields'  => is_array($first_item) ? array_keys($first_item) : [],
            'preview' => wp_json_encode($first_item, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
        ]);
    }

    public function menu() {
        add_menu_page(
            __('JsonifyWP', 'jsonifywp'),
            __('JsonifyWP', 'jsonifywp'),
            'manage_options',
            'jsonifywp',
            [$this, 'list_page'],
            'dashicons-list-view'
        );
        add_submenu_page(
            'jsonifywp',
            __('Add New', 'jsonifywp'),
            __('Add New', 'jsonifywp'),
            'manage_options',
            'jsonifywp-add',
            [$this, 'add_edit_page']
        );
    }

    public function handle_actions() {
        if (!is_admin() || !current_user_can('manage_options')) return;

        $page = isset($_GET['page']) ? $_GET['page'] : '';

        // Handle add/edit form submission
        if ($page === 'jsonifywp-add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['jsonifywp_nonce']) || !wp_verify_nonce($_POST['jsonifywp_nonce'], 'jsonifywp_save')) {
                wp_die(__('Security check failed.', 'jsonifywp'));
            }
            $editing         = isset($_GET['id']) && is_numeric($_GET['id']);
            $title           = sanitize_text_field($_POST['title'] ?? '');
            $language        = sanitize_text_field($_POST['language'] ?? '');
            $api_domain      = rtrim(sanitize_text_field($_POST['api_domain'] ?? ''), '/');
            $api_url         = esc_url_raw($_POST['api_url'] ?? '');
            $list_template   = sanitize_file_name($_POST['list_template'] ?? '');
            $detail_template = sanitize_file_name($_POST['detail_template'] ?? '');
            $detail_page_url = sanitize_text_field($_POST['detail_page_url'] ?? '');
            $detail_api_field = sanitize_text_field($_POST['detail_api_field'] ?? '');

            // Only accept template filenames that actually exist on disk.
            if (!in_array($list_template, self::allowed_templates('list'), true)) {
                wp_die(__('Invalid list template.', 'jsonifywp'));
            }
            if ($detail_template !== 'none' && !in_array($detail_template, self::allowed_templates('detail'), true)) {
                wp_die(__('Invalid detail template.', 'jsonifywp'));
            }

            // Field-mapping rows: json_field => label, used by the generic templates.
            $field_keys    = isset($_POST['field_key']) ? (array) wp_unslash($_POST['field_key']) : [];
            $field_labels_ = isset($_POST['field_label']) ? (array) wp_unslash($_POST['field_label']) : [];
            $field_labels  = [];
            foreach ($field_keys as $i => $key) {
                $key = sanitize_key($key);
                if ($key === '' || !isset($field_labels_[$i])) continue;
                $label = sanitize_text_field($field_labels_[$i]);
                if ($label === '') continue;
                $field_labels[$key] = $label;
            }
            $field_labels_json = !empty($field_labels) ? wp_json_encode($field_labels) : '';

            if ($editing) {
                JsonifyWP_DB::update(intval($_GET['id']), $title, $language, $api_domain, $api_url, $list_template, $detail_template, $detail_page_url, $detail_api_field, $field_labels_json);
                $status = 'updated';
            } else {
                JsonifyWP_DB::insert($title, $language, $api_domain, $api_url, $list_template, $detail_template, $detail_page_url, $detail_api_field, $field_labels_json);
                $status = 'created';
            }

            wp_redirect(add_query_arg('saved', $status, admin_url('admin.php?page=jsonifywp')));
            exit;
        }

        // Handle delete
        if ($page === 'jsonifywp' && isset($_GET['delete']) && is_numeric($_GET['delete'])) {
            check_admin_referer('jsonifywp_delete_' . intval($_GET['delete']));
            JsonifyWP_DB::delete(intval($_GET['delete']));
            wp_redirect(add_query_arg('saved', 'deleted', admin_url('admin.php?page=jsonifywp')));
            exit;
        }

        // Handle duplicate
        if ($page === 'jsonifywp' && isset($_GET['duplicate']) && is_numeric($_GET['duplicate'])) {
            check_admin_referer('jsonifywp_duplicate_' . intval($_GET['duplicate']));
            $orig = JsonifyWP_DB::get(intval($_GET['duplicate']));
            if ($orig) {
                JsonifyWP_DB::insert(
                    $orig->title . ' (copy)',
                    $orig->language,
                    $orig->api_domain,
                    $orig->api_url,
                    $orig->list_template,
                    $orig->detail_template,
                    $orig->detail_page_url,
                    $orig->detail_api_field,
                    $orig->field_labels
                );
            }
            wp_redirect(add_query_arg('saved', 'duplicated', admin_url('admin.php?page=jsonifywp')));
            exit;
        }
    }

    public function list_page() {
        $notices = [
            'created'   => __('Record created.', 'jsonifywp'),
            'updated'   => __('Record updated.', 'jsonifywp'),
            'deleted'   => __('Record deleted.', 'jsonifywp'),
            'duplicated'=> __('Record duplicated.', 'jsonifywp'),
        ];
        if (isset($_GET['saved'])) {
            $key = sanitize_key($_GET['saved']);
            if (isset($notices[$key])) {
                echo '<div class="notice notice-success is-dismissible"><p>' . esc_html($notices[$key]) . '</p></div>';
            }
        }

        $items = JsonifyWP_DB::get_all();
        ?>
        <div class="wrap">
            <h1><?php _e('Endpoints', 'jsonifywp'); ?> <a href="<?php echo admin_url('admin.php?page=jsonifywp-add'); ?>" class="page-title-action"><?php _e('Add New', 'jsonifywp'); ?></a></h1>
            <p>
                <?php _e('Below is a list of all the created endpoints. These endpoints must return a JSON response for listing records. You can edit or delete them as needed.', 'jsonifywp'); ?>
            </p>
            <table class="widefat striped">
                <thead>
                    <tr>
                        <th><?php _e('Title', 'jsonifywp'); ?></th>
                        <th><?php _e('Language', 'jsonifywp'); ?></th>
                        <th><?php _e('API Domain', 'jsonifywp'); ?></th>
                        <th><?php _e('API URL', 'jsonifywp'); ?></th>
                        <th><?php _e('List Template', 'jsonifywp'); ?></th>
                        <th><?php _e('Detail Template', 'jsonifywp'); ?></th>
                        <th><?php _e('Shortcode', 'jsonifywp'); ?></th>
                        <th><?php _e('Actions', 'jsonifywp'); ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($items)): ?>
                    <tr>
                        <td colspan="8"><?php _e('No entries found.', 'jsonifywp'); ?></td>
                    </tr>
                <?php else: ?>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><strong><?php echo esc_html($item->title); ?></strong></td>
                        <td><?php echo esc_html(strtoupper($item->language)); ?></td>
                        <td><code><?php echo esc_html($item->api_domain); ?></code></td>
                        <td style="max-width:200px; word-break:break-all;"><small><?php echo esc_html($item->api_url); ?></small></td>
                        <td><?php echo esc_html($item->list_template); ?></td>
                        <td><?php echo esc_html($item->detail_template); ?></td>
                        <td>
                            <code>[jsonifywp-<?php echo intval($item->id); ?>]</code>
                            <button type="button" class="button-link jsonifywp-copy-btn" data-shortcode="[jsonifywp-<?php echo intval($item->id); ?>]" title="<?php esc_attr_e('Copy shortcode', 'jsonifywp'); ?>">
                                <?php _e('Copy', 'jsonifywp'); ?>
                            </button>
                        </td>
                        <td>
                            <a href="<?php echo admin_url('admin.php?page=jsonifywp-add&id=' . intval($item->id)); ?>"><?php _e('Edit', 'jsonifywp'); ?></a> |
                            <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=jsonifywp&duplicate=' . intval($item->id)), 'jsonifywp_duplicate_' . intval($item->id)); ?>" onclick="return confirm('<?php esc_attr_e('Are you sure you want to duplicate?', 'jsonifywp'); ?>');"><?php _e('Duplicate', 'jsonifywp'); ?></a> |
                            <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=jsonifywp&delete=' . intval($item->id)), 'jsonifywp_delete_' . intval($item->id)); ?>" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete?', 'jsonifywp'); ?>');" style="color:#b32d2e;"><?php _e('Delete', 'jsonifywp'); ?></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <script>
        document.querySelectorAll('.jsonifywp-copy-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                navigator.clipboard.writeText(btn.dataset.shortcode).then(function() {
                    var orig = btn.textContent.trim();
                    btn.textContent = '✓';
                    setTimeout(function() { btn.textContent = orig; }, 2000);
                });
            });
        });
        </script>
        <?php
    }

    public function add_edit_page() {
        $editing = false;
        $item = (object)[
            'id'               => '',
            'title'            => '',
            'language'         => '',
            'api_domain'       => '',
            'api_url'          => '',
            'list_template'    => 'default.php',
            'detail_template'  => 'none',
            'detail_page_url'  => '',
            'detail_api_field' => '',
            'field_labels'     => '',
        ];

        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $editing = true;
            $item = JsonifyWP_DB::get(intval($_GET['id']));
            if (!$item) {
                echo '<div class="notice notice-error"><p>' . esc_html__('No entries found.', 'jsonifywp') . '</p></div>';
                return;
            }
        }

        $list_templates_dir = JSONIFYWP_DIR . 'templates/list/';
        $list_templates = is_dir($list_templates_dir)
            ? array_filter(
                scandir($list_templates_dir),
                function($tpl) use ($list_templates_dir) {
                    return is_file($list_templates_dir . $tpl) && pathinfo($tpl, PATHINFO_EXTENSION) === 'php';
                }
            )
            : [];

        $detail_templates_dir = JSONIFYWP_DIR . 'templates/detail/';
        $detail_templates = is_dir($detail_templates_dir)
            ? array_filter(
                scandir($detail_templates_dir),
                function($tpl) use ($detail_templates_dir) {
                    return is_file($detail_templates_dir . $tpl) && pathinfo($tpl, PATHINFO_EXTENSION) === 'php';
                }
            )
            : [];

        $field_labels = jsonifywp_get_field_labels($item);
        ?>
        <div class="wrap">
            <h1><?php echo $editing ? esc_html__('Edit Entry', 'jsonifywp') : esc_html__('Add Entry', 'jsonifywp'); ?></h1>
            <form method="post">
                <?php wp_nonce_field('jsonifywp_save', 'jsonifywp_nonce'); ?>
                <table class="form-table">
                    <tr>
                        <th><label for="title"><?php _e('Title', 'jsonifywp'); ?></label></th>
                        <td><input type="text" name="title" id="title" value="<?php echo esc_attr($item->title); ?>" class="regular-text" required></td>
                    </tr>
                    <tr>
                        <th><label for="language"><?php _e('Language', 'jsonifywp'); ?></label></th>
                        <td>
                            <select name="language" id="language" required>
                                <option value="ca" <?php selected($item->language, 'ca'); ?>>Catalan</option>
                                <option value="es" <?php selected($item->language, 'es'); ?>>Spanish</option>
                                <option value="en" <?php selected($item->language, 'en'); ?>>English</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="api_domain"><?php _e('API Domain', 'jsonifywp'); ?></label></th>
                        <td><input type="url" name="api_domain" id="api_domain" value="<?php echo esc_attr($item->api_domain); ?>" class="regular-text" required></td>
                    </tr>
                    <tr>
                        <th><label for="api_url"><?php _e('API URL', 'jsonifywp'); ?></label></th>
                        <td>
                            <input type="url" name="api_url" id="api_url" value="<?php echo esc_attr($item->api_url); ?>" class="regular-text" required>
                            <button type="button" class="button" id="jsonifywp-test-connection"><?php _e('Test connection', 'jsonifywp'); ?></button>
                            <p class="description"><?php _e('Calls the URL as entered above (without domain prefixing) and previews the first item.', 'jsonifywp'); ?></p>
                            <div id="jsonifywp-test-result"></div>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="list_template"><?php _e('List Template', 'jsonifywp'); ?></label></th>
                        <td>
                            <select name="list_template" id="list_template" required>
                                <?php foreach ($list_templates as $tpl): ?>
                                    <option value="<?php echo esc_attr($tpl); ?>" <?php selected($item->list_template, $tpl); ?>>
                                        <?php echo esc_html($tpl); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <p class="description"><?php _e('Choose the template to display the list.', 'jsonifywp'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="detail_template"><?php _e('Detail Template', 'jsonifywp'); ?></label></th>
                        <td>
                            <select name="detail_template" id="detail_template">
                                <option value="none" <?php selected($item->detail_template, 'none'); ?>><?php esc_html_e('No detail page', 'jsonifywp'); ?></option>
                                <?php foreach ($detail_templates as $tpl): ?>
                                    <option value="<?php echo esc_attr($tpl); ?>" <?php selected($item->detail_template, $tpl); ?>>
                                        <?php echo esc_html($tpl); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <p class="description"><?php _e('Choose the template to display the detail.', 'jsonifywp'); ?></p>
                        </td>
                    </tr>
                    <tr class="jsonifywp-detail-field">
                        <th><label for="detail_page_url"><?php _e('Detail Page URL', 'jsonifywp'); ?></label></th>
                        <td>
                            <input type="text" name="detail_page_url" id="detail_page_url" class="regular-text" value="<?php echo esc_attr($item->detail_page_url); ?>">
                            <p class="description">
                                <?php _e('Relative URL of the detail page (e.g.: /detail/).', 'jsonifywp'); ?>
                                <?php _e('On the corresponding detail page you must add this shortcode for it to work:', 'jsonifywp'); ?>
                                <code>[jsonifywp_detail]</code>
                            </p>
                        </td>
                    </tr>
                    <tr class="jsonifywp-detail-field">
                        <th><label for="detail_api_field"><?php _e('Detail API Field', 'jsonifywp'); ?></label></th>
                        <td>
                            <input type="text" name="detail_api_field" id="detail_api_field" value="<?php echo esc_attr($item->detail_api_field); ?>">
                            <p class="description"><?php _e('Name of the JSON field in the list that contains the detail API URL (e.g.: employee_profile)', 'jsonifywp'); ?></p>
                        </td>
                    </tr>
                </table>

                <h2><?php _e('Field labels', 'jsonifywp'); ?></h2>
                <p class="description"><?php _e('Optional. Renames JSON fields when displayed by the generic templates (default.php / default_detail.php). Custom templates ignore this and keep their own hardcoded labels.', 'jsonifywp'); ?></p>
                <table class="widefat striped" id="jsonifywp-field-labels-table" style="max-width:600px;">
                    <thead>
                        <tr>
                            <th><?php _e('JSON field', 'jsonifywp'); ?></th>
                            <th><?php _e('Label', 'jsonifywp'); ?></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($field_labels as $key => $label): ?>
                            <tr>
                                <td><input type="text" name="field_key[]" value="<?php echo esc_attr($key); ?>" class="regular-text"></td>
                                <td><input type="text" name="field_label[]" value="<?php echo esc_attr($label); ?>" class="regular-text"></td>
                                <td><button type="button" class="button jsonifywp-remove-row" aria-label="<?php esc_attr_e('Remove', 'jsonifywp'); ?>">&times;</button></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <p><button type="button" class="button" id="jsonifywp-add-field-row"><?php _e('+ Add field', 'jsonifywp'); ?></button></p>

                <?php submit_button($editing ? __('Update', 'jsonifywp') : __('Add', 'jsonifywp')); ?>
            </form>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                function toggleDetailFields() {
                    var detailTemplate = document.getElementById('detail_template');
                    var detailFields = document.querySelectorAll('.jsonifywp-detail-field');
                    var required = detailTemplate.value !== 'none';
                    detailFields.forEach(function(field) {
                        field.style.display = required ? '' : 'none';
                        var input = field.querySelector('input');
                        if (input) input.required = required;
                    });
                }
                var detailTemplate = document.getElementById('detail_template');
                if (detailTemplate) {
                    detailTemplate.addEventListener('change', toggleDetailFields);
                    toggleDetailFields();
                }

                // Field-labels table: add/remove rows.
                var fieldTable = document.querySelector('#jsonifywp-field-labels-table tbody');
                var addRowBtn  = document.getElementById('jsonifywp-add-field-row');

                function addFieldRow(key, label) {
                    var tr = document.createElement('tr');
                    tr.innerHTML =
                        '<td><input type="text" name="field_key[]" class="regular-text"></td>' +
                        '<td><input type="text" name="field_label[]" class="regular-text"></td>' +
                        '<td><button type="button" class="button jsonifywp-remove-row" aria-label="<?php echo esc_js(__('Remove', 'jsonifywp')); ?>">&times;</button></td>';
                    tr.querySelectorAll('input')[0].value = key || '';
                    tr.querySelectorAll('input')[1].value = label || '';
                    fieldTable.appendChild(tr);
                }

                if (addRowBtn) {
                    addRowBtn.addEventListener('click', function() { addFieldRow('', ''); });
                }
                if (fieldTable) {
                    fieldTable.addEventListener('click', function(e) {
                        if (e.target.classList.contains('jsonifywp-remove-row')) {
                            e.target.closest('tr').remove();
                        }
                    });
                }

                // Test connection button.
                var testBtn    = document.getElementById('jsonifywp-test-connection');
                var testResult = document.getElementById('jsonifywp-test-result');
                if (testBtn) {
                    testBtn.addEventListener('click', function() {
                        var url = document.getElementById('api_url').value;
                        if (!url) {
                            testResult.textContent = '<?php echo esc_js(__('Enter an API URL first.', 'jsonifywp')); ?>';
                            return;
                        }
                        testBtn.disabled = true;
                        testResult.textContent = '<?php echo esc_js(__('Testing…', 'jsonifywp')); ?>';

                        var body = new URLSearchParams({
                            action: 'jsonifywp_test_connection',
                            nonce: '<?php echo esc_js(wp_create_nonce('jsonifywp_test_connection')); ?>',
                            url: url
                        });

                        fetch(ajaxurl, { method: 'POST', body: body })
                            .then(function(r) { return r.json(); })
                            .then(function(res) {
                                testBtn.disabled = false;
                                testResult.innerHTML = '';
                                if (!res.success) {
                                    var errP = document.createElement('p');
                                    errP.style.color = '#b32d2e';
                                    errP.textContent = res.data.message;
                                    testResult.appendChild(errP);
                                    return;
                                }

                                var okP = document.createElement('p');
                                okP.style.color = '#1e7e34';
                                okP.textContent = '<?php echo esc_js(__('Connection OK. Detected fields:', 'jsonifywp')); ?>';
                                testResult.appendChild(okP);

                                if (res.data.fields.length) {
                                    var chipsP = document.createElement('p');
                                    res.data.fields.forEach(function(f) {
                                        var chip = document.createElement('button');
                                        chip.type = 'button';
                                        chip.className = 'button button-small jsonifywp-field-chip';
                                        chip.style.margin = '2px';
                                        chip.title = '<?php echo esc_js(__('Add as mapped field', 'jsonifywp')); ?>';
                                        chip.textContent = f; // textContent only: field names come from an external API response.
                                        chip.addEventListener('click', function() { addFieldRow(f, ''); });
                                        chipsP.appendChild(chip);
                                    });
                                    testResult.appendChild(chipsP);
                                }

                                var pre = document.createElement('pre');
                                pre.style.cssText = 'max-height:200px; overflow:auto; background:#fff; padding:8px; border:1px solid #ccd0d4;';
                                pre.textContent = res.data.preview; // textContent only: preview holds untrusted API content.
                                testResult.appendChild(pre);
                            })
                            .catch(function() {
                                testBtn.disabled = false;
                                testResult.innerHTML = '<p style="color:#b32d2e;"><?php echo esc_js(__('Request failed.', 'jsonifywp')); ?></p>';
                            });
                    });
                }
            });
        </script>
        <?php
    }
}

new JsonifyWP_Admin();
