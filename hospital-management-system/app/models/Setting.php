<?php
/**
 * System Settings Model
 * Developed_By_DCtechsolutions
 */

require_once __DIR__ . '/../config/database.php';

class Setting {
    public static function get(string $key, string $default = ''): string {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT setting_value FROM system_settings WHERE setting_key = ? LIMIT 1");
        $stmt->execute([$key]);
        $val = $stmt->fetchColumn();
        return $val !== false ? $val : $default;
    }

    public static function set(string $key, string $value): bool {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            INSERT INTO system_settings (setting_key, setting_value) 
            VALUES (?, ?) 
            ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)
        ");
        return $stmt->execute([$key, $value]);
    }

    public static function getAll(): array {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM system_settings ORDER BY id ASC");
        return $stmt->fetchAll();
    }
}
