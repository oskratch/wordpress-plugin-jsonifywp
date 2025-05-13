<?php
if (!defined('ABSPATH')) exit;

add_shortcode('jsonifywp', function($atts) {
    $atts = shortcode_atts(['id' => 0], $atts);
    $id = intval($atts['id']);
    if (!$id) return '';
    $item = JsonifyWP_DB::get($id);
    if (!$item) return '';

    // Crida a l'API
    $response = wp_remote_get($item->api_url);
    if (is_wp_error($response)) return '<p>Error obtenint dades de l\'API.</p>';
    $body = wp_remote_retrieve_body($response);
    $json = json_decode($body, true);
    if (!$json || !is_array($json)) return '<p>Format de dades incorrecte.</p>';

    // Carrega el template seleccionat
    $template_file = plugin_dir_path(__FILE__) . '../templates/' . $item->template;
    if (!file_exists($template_file)) {
        return '<p>Template no trobat.</p>';
    }
    $json_data = $json; // Per compatibilitat amb templates antics
    $json = $json; // Per compatibilitat

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