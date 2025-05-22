<?php
/*
 * Plugin Name: JsonifyWP
 * Plugin URI: https://github.com/oskratch/wordpress-plugin-jsonifywp
 * Description: Plugin to manage custom entries with its own table and shortcode, providing JSON integration.
 * Author: Oscar Periche, Metalinked
 * Author URI: https://metalinked.net/
 * Version: 1.0.0
 * Requires at least: 6.3
 * Requires PHP: 8
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * Copyright (c) 2025 Oscar Periche, Metalinked
 */

if (!defined('ABSPATH')) exit;

require_once plugin_dir_path(__FILE__) . 'includes/class-jsonifywp-db.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-jsonifywp-admin.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-jsonifywp-shortcode.php';
require_once plugin_dir_path(__FILE__) . 'includes/options.php';

add_action('plugins_loaded', function() {
    load_plugin_textdomain('jsonifywp', false, dirname(plugin_basename(__FILE__)) . '/languages');
});

register_activation_hook(__FILE__, ['JsonifyWP_DB', 'install']);