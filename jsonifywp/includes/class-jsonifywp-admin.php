<?php

if (!defined('ABSPATH')) exit;

class JsonifyWP_Admin {
    public function __construct() {
        add_action('admin_menu', [$this, 'menu']);
        add_action('admin_init', [$this, 'handle_actions']);
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

            if ($editing) {
                JsonifyWP_DB::update(intval($_GET['id']), $title, $language, $api_domain, $api_url, $list_template, $detail_template, $detail_page_url, $detail_api_field);
                $status = 'updated';
            } else {
                JsonifyWP_DB::insert($title, $language, $api_domain, $api_url, $list_template, $detail_template, $detail_page_url, $detail_api_field);
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
                    $orig->detail_api_field
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
                        <td><input type="url" name="api_url" id="api_url" value="<?php echo esc_attr($item->api_url); ?>" class="regular-text" required></td>
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
            });
        </script>
        <?php
    }
}

new JsonifyWP_Admin();
