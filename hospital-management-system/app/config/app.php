<?php
/**
 * Application Constants and Configuration
 * Yasmeen Maternity and Medical Center
 * Reference: R-85647
 * Developed_By_DCtechsolutions
 */

define('APP_NAME', 'Yasmeen Maternity and Medical Center');
define('APP_REG_NO', 'R-85647');
define('HOSPITAL_ADDRESS', 'Chishtia Colony, Raiwind Road Sundar, Lahore');
define('HOSPITAL_CONTACT', '03074246239');
define('DEVELOPER_BRAND', 'Developed_By_DCtechsolutions');

// Base URL calculation (supports direct access and reverse proxy)
$protocol = (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') 
    ? "https://" 
    : ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || ($_SERVER['SERVER_PORT'] ?? 80) == 443) ? "https://" : "http://");
$host = $_SERVER['HTTP_X_FORWARDED_HOST'] ?? ($_SERVER['HTTP_HOST'] ?? 'localhost');
$script = $_SERVER['SCRIPT_NAME'] ?? '/';
$dir = str_replace('\\', '/', dirname($script));
$base_url = rtrim($protocol . $host . $dir, '/') . '/';
define('BASE_URL', $base_url);

// Low stock threshold default
define('DEFAULT_LOW_STOCK_THRESHOLD', 15);
