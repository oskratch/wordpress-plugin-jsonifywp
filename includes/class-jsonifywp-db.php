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
            titol VARCHAR(255) NOT NULL,
            idioma VARCHAR(10) NOT NULL,
            api_url TEXT NOT NULL,
            template VARCHAR(100) NOT NULL DEFAULT 'default.php',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    public static function insert($titol, $idioma, $api_url, $template) {
        global $wpdb;
        $wpdb->insert(
            self::table_name(),
            [
                'titol' => $titol,
                'idioma' => $idioma,
                'api_url' => $api_url,
                'template' => $template
            ],
            ['%s', '%s', '%s', '%s']
        );
        return $wpdb->insert_id;
    }

    public static function update($id, $titol, $idioma, $api_url, $template) {
        global $wpdb;
        return $wpdb->update(
            self::table_name(),
            [
                'titol' => $titol,
                'idioma' => $idioma,
                'api_url' => $api_url,
                'template' => $template
            ],
            ['id' => $id],
            ['%s', '%s', '%s', '%s'],
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