<?php
if (!defined('WP_UNINSTALL_PLUGIN')) exit;

global $wpdb;
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}jsonifywp");
delete_option('jsonifywp_items_per_page');
delete_option('jsonifywp_cache_ttl');
