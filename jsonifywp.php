<?php
/*
Plugin Name: JsonifyWP
Description: Plugin to manage custom entries with its own table and shortcode, providing JSON integration.
Version: 1.0
Author: Oscar Periche
*/

if (!defined('ABSPATH')) exit;

require_once plugin_dir_path(__FILE__) . 'includes/class-jsonifywp-db.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-jsonifywp-admin.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-jsonifywp-shortcode.php';

add_action('plugins_loaded', function() {
    load_plugin_textdomain('jsonifywp', false, dirname(plugin_basename(__FILE__)) . '/languages');
});

register_activation_hook(__FILE__, ['JsonifyWP_DB', 'install']);