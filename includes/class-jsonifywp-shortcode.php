<?php
if (!defined('ABSPATH')) exit;

// Shortcode de llistat
add_shortcode('jsonifywp', function($atts) {
    $atts = shortcode_atts(['id' => 0], $atts);
    $id = intval($atts['id']);
    if (!$id) return '';
    $item = JsonifyWP_DB::get($id);
    if (!$item) return '';

    // Crida a l'API principal
    $response = wp_remote_get($item->api_url);
    if (is_wp_error($response)) return '<p>Error obtenint dades de l\'API.</p>';
    $body = wp_remote_retrieve_body($response);
    $json = json_decode($body, true);
    if (!$json || !is_array($json)) return '<p>Format de dades incorrecte.</p>';

    // Carrega el template de llistat
    $template_file = plugin_dir_path(__FILE__) . '../templates/list/' . $item->template;
    if (!file_exists($template_file)) {
        return '<p>Template de llistat no trobat.</p>';
    }
    // Passa variables al template
    $json_data = $json;
    $json = $json;
    $type_id = $item->id;
    $item_obj = $item; // <-- Aquesta línia fa accessible el registre complet al template

    ob_start();
    include $template_file;
    return ob_get_clean();
});

// Shortcode de detall
add_shortcode('jsonifywp_detail', function($atts) {
    // Llegeix de l'shortcode o de $_GET
    $id = isset($atts['id']) && $atts['id'] ? intval($atts['id']) : (isset($_GET['jsonifywp_id']) ? intval($_GET['jsonifywp_id']) : 0);
    $item_index = isset($atts['item']) && $atts['item'] ? intval($atts['item']) : (isset($_GET['item']) ? intval($_GET['item']) : 0);
    if (!$id) return '<p>No s\'ha trobat l\'ID.</p>';
    $type = JsonifyWP_DB::get($id);
    if (!$type) return '<p>No s\'ha trobat el tipus.</p>';

    // Crida a l'API principal
    $response = wp_remote_get($type->api_url);
    if (is_wp_error($response)) return '<p>Error obtenint dades de l\'API.</p>';
    $body = wp_remote_retrieve_body($response);
    $json = json_decode($body, true);

    $field = isset($type->detail_api_field) && $type->detail_api_field ? $type->detail_api_field : 'employee_profile';
    if (!is_array($json) || !isset($json[$item_index][$field])) return '<p>Registre no trobat.</p>';
    $profile_url = $json[$item_index][$field];

    // Crida a l'API de detall
    $profile_response = wp_remote_get($profile_url);
    if (is_wp_error($profile_response)) return '<p>Error obtenint detall.</p>';
    $profile_body = wp_remote_retrieve_body($profile_response);
    $profile_json = json_decode($profile_body, true);
    if (!$profile_json) return '<p>Detall no disponible.</p>';

    // Carrega el template de detall
    $template_file = plugin_dir_path(__FILE__) . '../templates/detail/' . $type->detail_template;
    if (!file_exists($template_file)) {
        return '<p>Template de detall no trobat.</p>';
    }
    $json = $profile_json;
    $type_id = $type->id;

    ob_start();
    include $template_file;
    return ob_get_clean();
});

// Permet usar [jsonifywp-1] com a alias de [jsonifywp id="1"]
add_filter('the_content', function($content) {
    return preg_replace_callback(
        '/\[jsonifywp-(\d+)\]/',
        function($matches) {
            return '[jsonifywp id="' . $matches[1] . '"]';
        },
        $content
    );
});