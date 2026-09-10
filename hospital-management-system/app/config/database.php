<?php
/**
 * Yasmeen Maternity and Medical Center - Hospital Management System
 * Database Configuration (PDO)
 * Developed_By_DCtechsolutions
 */

class Database {
    private static ?PDO $instance = null;

    // Database connection parameters (default for XAMPP)
    private static string $host = 'localhost';
    private static string $db_name = 'yasmeen_hms';
    private static string $username = 'root';
    private static string $password = '';
    private static string $charset = 'utf8mb4';
    private static int $port = 3306;

    public static function getConnection(): PDO {
        if (self::$instance === null) {
            try {
                $dsn = "mysql:host=" . self::$host . ";port=" . self::$port . ";dbname=" . self::$db_name . ";charset=" . self::$charset;
                $options = [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ];
                self::$instance = new PDO($dsn, self::$username, self::$password, $options);
            } catch (PDOException $e) {
                // Log error locally and provide friendly message
                error_log("Database Connection Error: " . $e->getMessage(), 3, __DIR__ . '/../../storage/logs/app.log');
                die("Unable to connect to the database. Please verify that MySQL is running in XAMPP and configuration in app/config/database.php is correct.");
            }
        }
        return self::$instance;
    }

    public static function getConfig(): array {
        return [
            'host' => self::$host,
            'dbname' => self::$db_name,
            'username' => self::$username,
            'password' => self::$password,
            'port' => self::$port
        ];
    }
}
