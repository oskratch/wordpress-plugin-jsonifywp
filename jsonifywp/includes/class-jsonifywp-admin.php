<?php
/*
Plugin Name: JsonifyWP
Description: Plugin to manage custom entries with its own table and shortcode, providing JSON integration.
Version: 1.0
Author: <a href="https://metalinked.net" target="_blank">Oscar Periche | Metalinked</a>
*/

if (!defined('ABSPATH')) exit;

class JsonifyWP_Admin {
    public function __construct() {
        add_action('admin_menu', [$this, 'menu']);
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

    public function list_page() {
        if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
            check_admin_referer('jsonifywp_delete_' . $_GET['delete']);
            JsonifyWP_DB::delete(intval($_GET['delete']));
            echo '<div class="notice notice-success"><p>' . __('Record deleted.', 'jsonifywp') . '</p></div>';
        }
        $items = JsonifyWP_DB::get_all();
        ?>
        <div class="wrap">
            <h1><?php _e('Endpoints', 'jsonifywp'); ?> <a href="<?php echo admin_url('admin.php?page=jsonifywp-add'); ?>" class="page-title-action"><?php _e('Add New', 'jsonifywp'); ?></a></h1>
            <p>
                <?php _e('Below is a list of all the created endpoints. These endpoints must return a JSON response for listing records. You can edit or delete them as needed.', 'jsonifywp'); ?>
            </p>
            <table class="widefat">
                <thead>
                    <tr>
                        <th><?php _e('Title', 'jsonifywp'); ?></th>
                        <th><?php _e('Language', 'jsonifywp'); ?></th>
                        <th><?php _e('API DOMAIN', 'jsonifywp'); ?></th>
                        <th><?php _e('API URL', 'jsonifywp'); ?></th>
                        <th><?php _e('List Template', 'jsonifywp'); ?></th>
                        <th><?php _e('Detail Template', 'jsonifywp'); ?></th>
                        <th><?php _e('Detail Page URL', 'jsonifywp'); ?></th>
                        <th><?php _e('Shortcode', 'jsonifywp'); ?></th>
                        <th><?php _e('Actions', 'jsonifywp'); ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($items)): ?>
                    <tr>
                        <td colspan="8"><?php _e("No entries found.", "jsonifywp"); ?></td>
                    </tr>
                <?php else: ?>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?php echo esc_html($item->title); ?></td>
                        <td><?php echo esc_html($item->language); ?></td>
                        <td><?php echo esc_html($item->api_domain); ?></td>
                        <td><?php echo esc_html($item->api_url); ?></td>
                        <td><?php echo esc_html($item->list_template); ?></td>
                        <td><?php echo esc_html($item->detail_template); ?></td>
                        <td><?php echo esc_html($item->detail_page_url); ?></td>
                        <td><code>[jsonifywp-<?php echo $item->id; ?>]</code></td>
                        <td>
                            <a href="<?php echo admin_url('admin.php?page=jsonifywp-add&id=' . $item->id); ?>"><?php _e('Edit', 'jsonifywp'); ?></a> | 
                            <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=jsonifywp&delete=' . $item->id), 'jsonifywp_delete_' . $item->id); ?>" onclick="return confirm('<?php _e('Are you sure you want to delete?', 'jsonifywp'); ?>');"><?php _e('Delete', 'jsonifywp'); ?></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php
    }

    public function add_edit_page() {
        $editing = false;
        $item = (object)[
            'id' => '',
            'title' => '',
            'language' => '',
            'api_domain' => '',
            'api_url' => '',
            'list_template' => 'default.php',
            'detail_template' => 'default_detail.php',
            'detail_page_url' => '',
            'detail_api_field' => 'employee_profile'
        ];
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $editing = true;
            $item = JsonifyWP_DB::get(intval($_GET['id']));
            if (!$item) {
                echo '<div class="notice notice-error"><p>' . __("No entries found.", 'jsonifywp') . '</p></div>';
                return;
            }
        }

        // Read available templates
        $list_templates_dir = plugin_dir_path(__FILE__) . '../templates/list/';
        $list_templates = is_dir($list_templates_dir) ? array_diff(scandir($list_templates_dir), ['.', '..']) : [];

        $detail_templates_dir = plugin_dir_path(__FILE__) . '../templates/detail/';
        $detail_templates = is_dir($detail_templates_dir) ? array_diff(scandir($detail_templates_dir), ['.', '..']) : [];

        // Process form
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['jsonifywp_nonce']) && wp_verify_nonce($_POST['jsonifywp_nonce'], 'jsonifywp_save')) {
            $title = sanitize_text_field($_POST['title']);
            $language = sanitize_text_field($_POST['language']);
            $api_domain = sanitize_text_field($_POST['api_domain']);
            // Remove trailing slash if present
            $api_domain = rtrim($api_domain, '/');
            $api_url = esc_url_raw($_POST['api_url']);
            $list_template = sanitize_file_name($_POST['list_template']);
            $detail_template = sanitize_file_name($_POST['detail_template']);
            $detail_page_url = sanitize_text_field($_POST['detail_page_url']);
            $detail_api_field = sanitize_text_field($_POST['detail_api_field']);
            if ($editing) {
                JsonifyWP_DB::update($item->id, $title, $language, $api_domain, $api_url, $list_template, $detail_template, $detail_page_url, $detail_api_field);
                echo '<div class="notice notice-success"><p>' . __('Record updated.', 'jsonifywp') . '</p></div>';
            } else {
                JsonifyWP_DB::insert($title, $language, $api_domain, $api_url, $list_template, $detail_template, $detail_page_url, $detail_api_field);
                echo '<div class="notice notice-success"><p>' . __('Record created.', 'jsonifywp') . '</p></div>';
            }
            // Redirect to list
            echo '<script>window.location="' . admin_url('admin.php?page=jsonifywp') . '";</script>';
            return;
        }

        ?>
        <div class="wrap">
            <h1><?php echo $editing ? __('Edit Entry', 'jsonifywp') : __('Add Entry', 'jsonifywp'); ?></h1>
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
                    <tr>
                        <th><label for="api_url"><?php _e("API URL", 'jsonifywp'); ?></label></th>
                        <td><input type="url" name="api_url" id="api_url" value="<?php echo esc_attr($item->api_url); ?>" class="regular-text" required></td>
                    </tr>
                    <tr>
                        <th><label for="list_template"><?php _e('List Template', 'jsonifywp'); ?></label></th>
                        <td>
                            <select name="list_template" id="list_template" aria-describedby="template-description" required>
                                <?php foreach ($list_templates as $tpl): ?>
                                    <option value="<?php echo esc_attr($tpl); ?>" <?php selected($item->list_template, $tpl); ?>>
                                        <?php echo esc_html($tpl); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <p class="description" id="template-description">
                                <?php _e('Choose the template to display the list.', 'jsonifywp'); ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="detail_template"><?php _e('Detail Template', 'jsonifywp'); ?></label></th>
                        <td>
                            <select name="detail_template" id="detail_template" aria-describedby="detail_template-description" required>
                                <?php foreach ($detail_templates as $tpl): ?>
                                    <option value="<?php echo esc_attr($tpl); ?>" <?php selected($item->detail_template, $tpl); ?>>
                                        <?php echo esc_html($tpl); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <p class="description" id="detail_template-description">
                                <?php _e('Choose the template to display the detail.', 'jsonifywp'); ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="detail_page_url"><?php _e('Detail Page URL', 'jsonifywp'); ?></label></th>
                        <td>
                            <input type="text" name="detail_page_url" id="detail_page_url" value="<?php echo esc_attr($item->detail_page_url); ?>" aria-describedby="detail_page_url-description" class="regular-text" required>
                            <p class="description" id="detail_page_url-description">
                                <?php _e('Relative URL of the detail page (e.g.: /detail/).', 'jsonifywp'); ?> <?php _e('On the corresponding detail page you must add this shortcode for it to work:', 'jsonifywp'); ?> <code>[jsonifywp_detail]</code>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="detail_api_field"><?php _e('Detail API Field', 'jsonifywp'); ?></label></th>
                        <td>
                            <input type="text" name="detail_api_field" id="detail_api_field" value="<?php echo esc_attr($item->detail_api_field); ?>" aria-describedby="detail_api_field-description" class="regular-text" required>
                            <p class="description" id="detail_api_field-description">
                                <?php _e("Name of the JSON field in the list that contains the detail API URL (e.g.: employee_profile)", 'jsonifywp'); ?>							
                            </p>
                        </td>
                    </tr>
                </table>
                <?php submit_button($editing ? __('Update', 'jsonifywp') : __('Add', 'jsonifywp')); ?>
            </form>
        </div>
        <?php
    }
}

new JsonifyWP_Admin();