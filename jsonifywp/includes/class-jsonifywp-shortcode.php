<?php
if (!defined('ABSPATH')) exit;

// List shortcode
add_shortcode('jsonifywp', function($atts) {
    $atts = shortcode_atts(['id' => 0], $atts);
    $id = intval($atts['id']);
    if (!$id) return '';
    $item = JsonifyWP_DB::get($id);
    if (!$item) return '';

    // Main API call
    $response = wp_remote_get($item->api_url);
    if (is_wp_error($response)) return '<p>Error retrieving data from the API.</p>';
    $body = wp_remote_retrieve_body($response);
    $json = json_decode($body, true);
    if (!$json || !is_array($json)) return '<p>Data format incorrect.</p>';

    // Load list template
    $template_file = plugin_dir_path(__FILE__) . '../templates/list/' . $item->list_template;
    if (!file_exists($template_file)) {
        return '<p>List template not found.</p>';
    }
    // Pass variables to template
    $json_data = $json;
    $json = $json;
    $type_id = $item->id;
    $item_obj = $item; // <-- This line makes the full record accessible to the template

    ob_start();
    include $template_file;
    return ob_get_clean();
});

// Detail shortcode
add_shortcode('jsonifywp_detail', function($atts) {
    // Read from shortcode or $_GET
    $id = isset($atts['id']) && $atts['id'] ? intval($atts['id']) : (isset($_GET['jsonifywp_id']) ? intval($_GET['jsonifywp_id']) : 0);
    $item_index = isset($atts['item']) && $atts['item'] ? intval($atts['item']) : (isset($_GET['item']) ? intval($_GET['item']) : 0);
    if (!$id) return '<p>ID not found.</p>';
    $type = JsonifyWP_DB::get($id);
    if (!$type) return '<p>Type not found.</p>';

    // Main API call
    $response = wp_remote_get($type->api_url);
    if (is_wp_error($response)) return '<p>Error retrieving data from the API.</p>';
    $body = wp_remote_retrieve_body($response);
    $json = json_decode($body, true);

    $field = isset($type->detail_api_field) && $type->detail_api_field ? $type->detail_api_field : 'employee_profile';
    if (!is_array($json) || !isset($json[$item_index][$field])) return '<p>Record not found.</p>';
    $profile_url = $json[$item_index][$field];

    // Detail API call
    $profile_response = wp_remote_get($profile_url);
    if (is_wp_error($profile_response)) return '<p>Error retrieving detail.</p>';
    $profile_body = wp_remote_retrieve_body($profile_response);
    $profile_json = json_decode($profile_body, true);
    if (!$profile_json) return '<p>Detail not available.</p>';

    // Load detail template
    $template_file = plugin_dir_path(__FILE__) . '../templates/detail/' . $type->detail_template;
    if (!file_exists($template_file)) {
        return '<p>Detail template not found.</p>';
    }
    $json = $profile_json;
    $type_id = $type->id;

    ob_start();
    include $template_file;
    return ob_get_clean();
});

// Allow [jsonifywp-1] as alias for [jsonifywp id="1"]
add_filter('the_content', function($content) {
    return preg_replace_callback(
        '/\[jsonifywp-(\d+)\]/',
        function($matches) {
            return '[jsonifywp id="' . $matches[1] . '"]';
        },
        $content
    );
});