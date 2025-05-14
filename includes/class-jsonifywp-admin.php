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
                        <th><?php _e('Llistat Template', 'jsonifywp'); ?></th>
                        <th><?php _e('Detall Template', 'jsonifywp'); ?></th>
                        <th><?php _e('Detall Page URL', 'jsonifywp'); ?></th>
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
                        <td><?php echo esc_html($item->template); ?></td>
                        <td><?php echo esc_html($item->detail_template); ?></td>
                        <td><?php echo esc_html($item->detail_page_url); ?></td>
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
            'template' => 'default.php',
            'detail_template' => 'default_detail.php',
            'detail_page_url' => '',
            'detail_api_field' => 'employee_profile'
        ];
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $editing = true;
            $item = JsonifyWP_DB::get(intval($_GET['id']));
            if (!$item) {
                echo '<div class="notice notice-error"><p>' . __('No trobat.', 'jsonifywp') . '</p></div>';
                return;
            }
        }

        // Llegeix els templates disponibles
        $list_templates_dir = plugin_dir_path(__FILE__) . '../templates/list/';
        $list_templates = is_dir($list_templates_dir) ? array_diff(scandir($list_templates_dir), ['.', '..']) : [];

        $detail_templates_dir = plugin_dir_path(__FILE__) . '../templates/detail/';
        $detail_templates = is_dir($detail_templates_dir) ? array_diff(scandir($detail_templates_dir), ['.', '..']) : [];

        // Processa el formulari
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['jsonifywp_nonce']) && wp_verify_nonce($_POST['jsonifywp_nonce'], 'jsonifywp_save')) {
            $titol = sanitize_text_field($_POST['titol']);
            $idioma = sanitize_text_field($_POST['idioma']);
            $api_url = esc_url_raw($_POST['api_url']);
            $template = sanitize_file_name($_POST['template']);
            $detail_template = sanitize_file_name($_POST['detail_template']);
            $detail_page_url = sanitize_text_field($_POST['detail_page_url']);
            $detail_api_field = sanitize_text_field($_POST['detail_api_field']);
            if ($editing) {
                JsonifyWP_DB::update($item->id, $titol, $idioma, $api_url, $template, $detail_template, $detail_page_url, $detail_api_field);
                echo '<div class="notice notice-success"><p>' . __('Registre actualitzat.', 'jsonifywp') . '</p></div>';
            } else {
                JsonifyWP_DB::insert($titol, $idioma, $api_url, $template, $detail_template, $detail_page_url, $detail_api_field);
                echo '<div class="notice notice-success"><p>' . __('Registre creat.', 'jsonifywp') . '</p></div>';
            }
            // Redirigeix a la llista
            echo '<script>window.location="' . admin_url('admin.php?page=jsonifywp') . '";</script>';
            return;
        }

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
                        <th><label for="template"><?php _e('Llistat Template', 'jsonifywp'); ?></label></th>
                        <td>
                            <select name="template" id="template" aria-describedby="template-description" required>
                                <?php foreach ($list_templates as $tpl): ?>
                                    <option value="<?php echo esc_attr($tpl); ?>" <?php selected($item->template, $tpl); ?>>
                                        <?php echo esc_html($tpl); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <p class="description" id="template-description">
                                <?php _e('Escull el template per mostrar el llistat.', 'jsonifywp'); ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="detail_template"><?php _e('Detall Template', 'jsonifywp'); ?></label></th>
                        <td>
                            <select name="detail_template" id="detail_template" aria-describedby="detail_template-description" required>
                                <?php foreach ($detail_templates as $tpl): ?>
                                    <option value="<?php echo esc_attr($tpl); ?>" <?php selected($item->detail_template, $tpl); ?>>
                                        <?php echo esc_html($tpl); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <p class="description" id="detail_template-description">
                                <?php _e('Escull el template per mostrar el detall.', 'jsonifywp'); ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="detail_page_url"><?php _e('Detall Page URL', 'jsonifywp'); ?></label></th>
                        <td>
                            <input type="text" name="detail_page_url" id="detail_page_url" value="<?php echo esc_attr($item->detail_page_url); ?>" aria-describedby="detail_page_url-description" class="regular-text" required>
                            <p class="description" id="detail_page_url-description">
                                <?php _e('URL relativa de la pàgina de detall (ex: /detall/).', 'jsonifywp'); ?> <?php _e('A la pàgina de detall corresponent cal afegir aquest shortcode perquè funcioni:', 'jsonifywp'); ?> <code>[jsonifywp_detail]</code>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="detail_api_field"><?php _e('Detall API Field', 'jsonifywp'); ?></label></th>
                        <td>
                            <input type="text" name="detail_api_field" id="detail_api_field" value="<?php echo esc_attr($item->detail_api_field); ?>" aria-describedby="detail_api_field-description" class="regular-text" required>
                            <p class="description" id="detail_api_field-description">
                                <?php _e('Nom del camp del JSON del llistat que conté la URL de l\'API de detall (ex: employee_profile)', 'jsonifywp'); ?>							
                            </p>
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