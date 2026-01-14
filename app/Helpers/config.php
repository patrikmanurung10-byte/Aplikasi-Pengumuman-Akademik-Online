<?php
/**
 * Main Configuration File for APAO Polibatam
 * Konfigurasi utama sistem
 */

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'apao_polibatam');
define('DB_USER', 'root');
define('DB_PASS', '');

// Application configuration
define('APP_NAME', 'APAO Polibatam');
define('APP_VERSION', '1.0.0');
define('BASE_URL', 'http://localhost/IFMALAM1C-8/');

// Security configuration
define('SESSION_TIMEOUT', 24 * 60 * 60); // 24 hours in seconds
define('CSRF_TOKEN_NAME', 'csrf_token');

// File paths
define('ASSETS_PATH', 'assets/');
define('IMAGES_PATH', 'assets/images/');
define('CSS_PATH', 'assets/css/');
define('JS_PATH', 'assets/js/');

// Error reporting (set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Timezone
date_default_timezone_set('Asia/Jakarta');
?>