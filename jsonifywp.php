<?php
/*
Plugin Name: JsonifyWP
Description: Plugin per gestionar entrades personalitzades amb taula pròpia i shortcode.
Version: 1.0
Author: Oscar Periche
*/

if (!defined('ABSPATH')) exit;

require_once plugin_dir_path(__FILE__) . 'includes/class-jsonifywp-db.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-jsonifywp-admin.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-jsonifywp-shortcode.php';

register_activation_hook(__FILE__, ['JsonifyWP_DB', 'install']);