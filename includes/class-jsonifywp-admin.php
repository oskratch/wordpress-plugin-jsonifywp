<?php
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
            __('Afegeix nova', 'jsonifywp'),
            __('Afegeix nova', 'jsonifywp'),
            'manage_options',
            'jsonifywp-add',
            [$this, 'add_edit_page']
        );
    }

    public function list_page() {
        if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
            check_admin_referer('jsonifywp_delete_' . $_GET['delete']);
            JsonifyWP_DB::delete(intval($_GET['delete']));
            echo '<div class="notice notice-success"><p>' . __('Registre eliminat.', 'jsonifywp') . '</p></div>';
        }
        $items = JsonifyWP_DB::get_all();
        ?>
        <div class="wrap">
            <h1><?php _e('Entrades JsonifyWP', 'jsonifywp'); ?> <a href="<?php echo admin_url('admin.php?page=jsonifywp-add'); ?>" class="page-title-action"><?php _e('Afegeix nova', 'jsonifywp'); ?></a></h1>
            <table class="widefat">
                <thead>
                    <tr>
                        <th><?php _e('Títol', 'jsonifywp'); ?></th>
                        <th><?php _e('Idioma', 'jsonifywp'); ?></th>
                        <th><?php _e('URL API', 'jsonifywp'); ?></th>                
                        <th><?php _e('Template', 'jsonifywp'); ?></th>
                        <th><?php _e('Shortcode', 'jsonifywp'); ?></th>
                        <th><?php _e('Accions', 'jsonifywp'); ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?php echo esc_html($item->titol); ?></td>
                        <td><?php echo esc_html($item->idioma); ?></td>
                        <td><?php echo esc_html($item->api_url); ?></td>                
                        <td><?php echo esc_html($item->template ?? ''); ?></td>
                        <td><code>[jsonifywp id="<?php echo $item->id; ?>"]</code></td>
                        <td>
                            <a href="<?php echo admin_url('admin.php?page=jsonifywp-add&id=' . $item->id); ?>"><?php _e('Edita', 'jsonifywp'); ?></a> | 
                            <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=jsonifywp&delete=' . $item->id), 'jsonifywp_delete_' . $item->id); ?>" onclick="return confirm('<?php _e('Segur que vols eliminar?', 'jsonifywp'); ?>');"><?php _e('Elimina', 'jsonifywp'); ?></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
    }

    public function add_edit_page() {
        $editing = false;
        $item = (object)[
            'id' => '',
            'titol' => '',
            'idioma' => '',
            'api_url' => '',
            'template' => ''
        ];
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $editing = true;
            $item = JsonifyWP_DB::get(intval($_GET['id']));
            if (!$item) {
                echo '<div class="notice notice-error"><p>' . __('No trobat.', 'jsonifywp') . '</p></div>';
                return;
            }
        }

        // Processa el formulari
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['jsonifywp_nonce']) && wp_verify_nonce($_POST['jsonifywp_nonce'], 'jsonifywp_save')) {
            $titol = sanitize_text_field($_POST['titol']);
            $idioma = sanitize_text_field($_POST['idioma']);
            $api_url = esc_url_raw($_POST['api_url']);
            $template = sanitize_file_name($_POST['template']);
            if ($editing) {
                JsonifyWP_DB::update($item->id, $titol, $idioma, $api_url, $template);
                echo '<div class="notice notice-success"><p>' . __('Registre actualitzat.', 'jsonifywp') . '</p></div>';
            } else {
                JsonifyWP_DB::insert($titol, $idioma, $api_url, $template);
                echo '<div class="notice notice-success"><p>' . __('Registre creat.', 'jsonifywp') . '</p></div>';
            }
            // Redirigeix a la llista
            echo '<script>window.location="' . admin_url('admin.php?page=jsonifywp') . '";</script>';
            return;
        }
        
            $templates_dir = plugin_dir_path(__FILE__) . '../templates/';
            $templates = array_diff(scandir($templates_dir), ['.', '..']);
        ?>
        <div class="wrap">
            <h1><?php echo $editing ? __('Edita entrada', 'jsonifywp') : __('Afegeix nova entrada', 'jsonifywp'); ?></h1>
            <form method="post">
                <?php wp_nonce_field('jsonifywp_save', 'jsonifywp_nonce'); ?>
                <table class="form-table">
                    <tr>
                        <th><label for="titol"><?php _e('Títol', 'jsonifywp'); ?></label></th>
                        <td><input type="text" name="titol" id="titol" value="<?php echo esc_attr($item->titol); ?>" class="regular-text" required></td>
                    </tr>
                    <tr>
                        <th><label for="idioma"><?php _e('Idioma', 'jsonifywp'); ?></label></th>
                        <td>
                            <select name="idioma" id="idioma" required>
                                <option value="ca" <?php selected($item->idioma, 'ca'); ?>>Català</option>
                                <option value="es" <?php selected($item->idioma, 'es'); ?>>Castellà</option>
                                <option value="en" <?php selected($item->idioma, 'en'); ?>>Anglès</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="api_url"><?php _e('URL de l\'API', 'jsonifywp'); ?></label></th>
                        <td><input type="url" name="api_url" id="api_url" value="<?php echo esc_attr($item->api_url); ?>" class="regular-text" required></td>
                    </tr>
                    <tr>
                        <th><label for="template"><?php _e('Template', 'jsonifywp'); ?></label></th>
                        <td>
                            <select name="template" id="template" required>
                                <?php foreach ($templates as $tpl): ?>
                                    <option value="<?php echo esc_attr($tpl); ?>" <?php selected($item->template ?? 'default.php', $tpl); ?>>
                                        <?php echo esc_html($tpl); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                </table>
                <?php submit_button($editing ? __('Actualitza', 'jsonifywp') : __('Afegeix', 'jsonifywp')); ?>
            </form>
        </div>
        <?php
    }
}

new JsonifyWP_Admin();