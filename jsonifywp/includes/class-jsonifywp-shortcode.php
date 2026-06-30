<?php

if (!defined('ABSPATH')) exit;

/**
 * Prepend domain to URL if not absolute.
 */
function jsonifywp_prepend_domain_if_needed($url, $domain) {
    if (strpos($url, 'http') !== 0 && !empty($domain)) {
        return rtrim($domain, '/') . '/' . ltrim($url, '/');
    }
    return $url;
}

/**
 * Returns the configured detail API field, falling back to 'employee_profile'.
 */
function jsonifywp_get_detail_field($item_obj) {
    return !empty($item_obj->detail_api_field) ? $item_obj->detail_api_field : 'employee_profile';
}

/**
 * Fetch and decode a JSON API endpoint, with optional transient caching.
 * Returns an array on success or WP_Error on failure.
 */
function jsonifywp_get_api_data($url) {
    $ttl = intval(get_option('jsonifywp_cache_ttl', 0));

    if ($ttl > 0) {
        $key    = 'jsonifywp_' . md5($url);
        $cached = get_transient($key);
        if ($cached !== false) {
            return $cached;
        }
    }

    $response = wp_remote_get($url, ['timeout' => 10]);

    if (is_wp_error($response)) {
        return new WP_Error('request_failed', __('Error retrieving data from the API.', 'jsonifywp'));
    }

    $code = wp_remote_retrieve_response_code($response);
    if ($code !== 200) {
        return new WP_Error('http_error', sprintf(__('API returned status %d.', 'jsonifywp'), $code));
    }

    $data = json_decode(wp_remote_retrieve_body($response), true);
    if (!is_array($data)) {
        return new WP_Error('parse_error', __('Data format incorrect.', 'jsonifywp'));
    }

    if ($ttl > 0) {
        set_transient($key, $data, $ttl * MINUTE_IN_SECONDS);
    }

    return $data;
}

// List shortcode
add_shortcode('jsonifywp', function($atts) {
    $atts = shortcode_atts(['id' => 0], $atts);
    $id = intval($atts['id']);
    if (!$id) return '';

    $item = JsonifyWP_DB::get($id);
    if (!$item) return '';

    $api_url = jsonifywp_prepend_domain_if_needed($item->api_url, $item->api_domain);

    if ($item->detail_template === 'none') {
        $page    = isset($_GET['jsonifywp_page']) ? intval($_GET['jsonifywp_page']) : 1;
        $limit   = get_option('jsonifywp_items_per_page', 5);
        $api_url = add_query_arg(['page' => $page, 'limit' => $limit], $api_url);
    }

    $json = jsonifywp_get_api_data($api_url);
    if (is_wp_error($json)) {
        return '<p>' . esc_html($json->get_error_message()) . '</p>';
    }

    $template_file = JSONIFYWP_DIR . 'templates/list/' . $item->list_template;
    if (!file_exists($template_file)) {
        return '<p>' . esc_html__('List template not found.', 'jsonifywp') . '</p>';
    }

    $type_id  = $item->id;
    $item_obj = $item;

    ob_start();
    include $template_file;
    return ob_get_clean();
});

// Detail shortcode
add_shortcode('jsonifywp_detail', function($atts) {
    $id         = isset($atts['id']) && $atts['id'] ? intval($atts['id']) : (isset($_GET['jsonifywp_id']) ? intval($_GET['jsonifywp_id']) : 0);
    $item_index = isset($atts['item']) && $atts['item'] ? intval($atts['item']) : (isset($_GET['item']) ? intval($_GET['item']) : 0);

    if (!$id) return '<p>' . esc_html__('ID not found.', 'jsonifywp') . '</p>';

    $type = JsonifyWP_DB::get($id);
    if (!$type) return '<p>' . esc_html__('Type not found.', 'jsonifywp') . '</p>';

    $api_url   = jsonifywp_prepend_domain_if_needed($type->api_url, $type->api_domain);
    $list_json = jsonifywp_get_api_data($api_url);

    if (is_wp_error($list_json)) {
        return '<p>' . esc_html($list_json->get_error_message()) . '</p>';
    }

    $field = jsonifywp_get_detail_field($type);

    if (!isset($list_json[$item_index][$field])) {
        return '<p>' . esc_html__('Record not found.', 'jsonifywp') . '</p>';
    }

    $profile_url  = jsonifywp_prepend_domain_if_needed($list_json[$item_index][$field], $type->api_domain);
    $json         = jsonifywp_get_api_data($profile_url);

    if (is_wp_error($json)) {
        return '<p>' . esc_html($json->get_error_message()) . '</p>';
    }

    $template_file = JSONIFYWP_DIR . 'templates/detail/' . $type->detail_template;
    if (!file_exists($template_file)) {
        return '<p>' . esc_html__('Detail template not found.', 'jsonifywp') . '</p>';
    }

    $type_id = $type->id;

    ob_start();
    include $template_file;
    return ob_get_clean();
});

// Allow [jsonifywp-1] as alias for [jsonifywp id="1"]
add_filter('the_content', function($content) {
    if (strpos($content, '[jsonifywp-') === false) {
        return $content;
    }
    return preg_replace_callback(
        '/\[jsonifywp-(\d+)\]/',
        function($matches) {
            return '[jsonifywp id="' . $matches[1] . '"]';
        },
        $content
    );
});
