<?php
if (!defined('ABSPATH')) exit;

class JsonifyWP_DB {
    public static function table_name() {
        global $wpdb;
        return $wpdb->prefix . 'jsonifywp';
    }

    public static function install() {
        global $wpdb;
        $table = self::table_name();
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            title VARCHAR(255) NOT NULL,
            language VARCHAR(10) NOT NULL,
            api_url TEXT NOT NULL,
            list_template VARCHAR(100) NOT NULL DEFAULT 'default.php',
            detail_template VARCHAR(100) NOT NULL DEFAULT 'default_detail.php',
            detail_page_url VARCHAR(255) NOT NULL DEFAULT '',
            detail_api_field VARCHAR(100) NOT NULL DEFAULT 'employee_profile',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    public static function insert($title, $language, $api_url, $list_template, $detail_template, $detail_page_url, $detail_api_field) {
        global $wpdb;
        $wpdb->insert(
            self::table_name(),
            [
                'title' => $title,
                'language' => $language,
                'api_url' => $api_url,
                'list_template' => $list_template,
                'detail_template' => $detail_template,
                'detail_page_url' => $detail_page_url,
                'detail_api_field' => $detail_api_field
            ],
            ['%s', '%s', '%s', '%s', '%s', '%s', '%s']
        );
        return $wpdb->insert_id;
    }

    public static function update($id, $title, $language, $api_url, $list_template, $detail_template, $detail_page_url, $detail_api_field) {
        global $wpdb;
        return $wpdb->update(
            self::table_name(),
            [
                'title' => $title,
                'language' => $language,
                'api_url' => $api_url,
                'list_template' => $list_template,
                'detail_template' => $detail_template,
                'detail_page_url' => $detail_page_url,
                'detail_api_field' => $detail_api_field
            ],
            ['id' => $id],
            ['%s', '%s', '%s', '%s', '%s', '%s', '%s'],
            ['%d']
        );
    }

    public static function delete($id) {
        global $wpdb;
        return $wpdb->delete(self::table_name(), ['id' => $id], ['%d']);
    }

    public static function get($id) {
        global $wpdb;
        return $wpdb->get_row($wpdb->prepare("SELECT * FROM " . self::table_name() . " WHERE id = %d", $id));
    }

    public static function get_all() {
        global $wpdb;
        return $wpdb->get_results("SELECT * FROM " . self::table_name() . " ORDER BY created_at DESC");
    }
}