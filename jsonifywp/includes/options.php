<?php

if (!defined('ABSPATH')) exit;

add_action('admin_menu', function() {
    add_submenu_page(
        'jsonifywp',
        __('Settings', 'jsonifywp'),
        __('Settings', 'jsonifywp'),
        'manage_options',
        'jsonifywp-settings',
        'jsonifywp_settings_page'
    );
});

function jsonifywp_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php _e('JsonifyWP Settings', 'jsonifywp'); ?></h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('jsonifywp_settings_group');
            do_settings_sections('jsonifywp-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

add_action('admin_init', function() {
    register_setting('jsonifywp_settings_group', 'jsonifywp_items_per_page', [
        'type'              => 'integer',
        'sanitize_callback' => 'absint',
        'default'           => 5,
    ]);

    register_setting('jsonifywp_settings_group', 'jsonifywp_cache_ttl', [
        'type'              => 'integer',
        'sanitize_callback' => 'absint',
        'default'           => 0,
    ]);

    add_settings_section(
        'jsonifywp_main_section',
        __('Basic options', 'jsonifywp'),
        null,
        'jsonifywp-settings'
    );

    add_settings_field(
        'jsonifywp_items_per_page',
        __('Items per page', 'jsonifywp'),
        function() {
            $value = get_option('jsonifywp_items_per_page', 5);
            echo '<input type="number" min="1" name="jsonifywp_items_per_page" value="' . esc_attr($value) . '" />';
            echo '<p class="description">' . esc_html__('Number of items per page for list-only endpoints.', 'jsonifywp') . '</p>';
        },
        'jsonifywp-settings',
        'jsonifywp_main_section'
    );

    add_settings_field(
        'jsonifywp_cache_ttl',
        __('API cache duration (minutes)', 'jsonifywp'),
        function() {
            $value = get_option('jsonifywp_cache_ttl', 0);
            echo '<input type="number" min="0" name="jsonifywp_cache_ttl" value="' . esc_attr($value) . '" />';
            echo '<p class="description">' . esc_html__('Cache API responses for this many minutes. Set to 0 to disable caching.', 'jsonifywp') . '</p>';
        },
        'jsonifywp-settings',
        'jsonifywp_main_section'
    );
});
