<?php

if (!defined('ABSPATH')) exit;

// Add settings submenu
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

// Settings form
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

// Register option and field
add_action('admin_init', function() {
    register_setting('jsonifywp_settings_group', 'jsonifywp_items_per_page', [
        'type' => 'integer',
        'sanitize_callback' => 'absint',
        'default' => 5,
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
        },
        'jsonifywp-settings',
        'jsonifywp_main_section'
    );
});