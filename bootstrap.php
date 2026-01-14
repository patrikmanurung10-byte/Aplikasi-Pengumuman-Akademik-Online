<?php

/**
 * Bootstrap File for APAO Polibatam
 * File bootstrap untuk inisialisasi aplikasi
 */

// Clean any previous output
if (ob_get_level()) {
    ob_end_clean();
}

// Start clean output buffering only for web requests
if (isset($_SERVER['REQUEST_METHOD'])) {
    ob_start();
    
    // Set proper headers for web requests
    if (!headers_sent()) {
        header('Content-Type: text/html; charset=UTF-8');
    }
}

// Define constants
define('ROOT_PATH', __DIR__);
define('APP_PATH', ROOT_PATH . '/app');
define('VIEWS_PATH', ROOT_PATH . '/views');
define('CONTROLLERS_PATH', ROOT_PATH . '/controllers');
define('MODELS_PATH', ROOT_PATH . '/models');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('ASSETS_PATH', ROOT_PATH . '/assets');

// Load environment variables
if (file_exists(ROOT_PATH . '/.env')) {
    $envFile = file_get_contents(ROOT_PATH . '/.env');
    $lines = explode("\n", $envFile);
    
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || strpos($line, '#') === 0) {
            continue;
        }
        
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            
            // Remove quotes if present
            if ((substr($value, 0, 1) === '"' && substr($value, -1) === '"') ||
                (substr($value, 0, 1) === "'" && substr($value, -1) === "'")) {
                $value = substr($value, 1, -1);
            }
            
            $_ENV[$key] = $value;
            putenv("$key=$value");
        }
    }
}

// Define application constants from environment
if (!defined('APP_ENV')) {
    define('APP_ENV', $_ENV['APP_ENV'] ?? 'production');
}
if (!defined('APP_DEBUG')) {
    define('APP_DEBUG', ($_ENV['APP_DEBUG'] ?? 'false') === 'true');
}
if (!defined('DEBUG')) {
    define('DEBUG', APP_DEBUG);
}

// Autoloader
spl_autoload_register(function ($class) {
    // Handle namespaced classes (App\Core\*, App\Config\*, etc.)
    if (strpos($class, 'App\\') === 0) {
        $file = APP_PATH . '/' . str_replace(['App\\', '\\'], ['', '/'], $class) . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
    
    // Handle controllers
    if (strpos($class, 'Controller') !== false) {
        $file = CONTROLLERS_PATH . '/' . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
    
    // Handle models
    $modelFile = MODELS_PATH . '/' . $class . '.php';
    if (file_exists($modelFile)) {
        require_once $modelFile;
        return;
    }
    
    // Handle other classes in root
    $rootFile = ROOT_PATH . '/' . $class . '.php';
    if (file_exists($rootFile)) {
        require_once $rootFile;
        return;
    }
});

// Error handling
set_error_handler(function($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) {
        return false;
    }
    
    throw new ErrorException($message, 0, $severity, $file, $line);
});

// Exception handling
set_exception_handler(function($exception) {
    http_response_code(500);
    
    if (defined('DEBUG') && DEBUG) {
        echo "<h1>Uncaught Exception</h1>";
        echo "<p><strong>Message:</strong> " . $exception->getMessage() . "</p>";
        echo "<p><strong>File:</strong> " . $exception->getFile() . "</p>";
        echo "<p><strong>Line:</strong> " . $exception->getLine() . "</p>";
        echo "<pre>" . $exception->getTraceAsString() . "</pre>";
    } else {
        echo "<h1>Internal Server Error</h1>";
        echo "<p>Something went wrong. Please try again later.</p>";
    }
});

// Initialize application
use App\Core\Application;
use App\Core\Session;
use App\Core\View;

try {
    // Start session
    Session::start();
    
    // Initialize view system
    View::init();
    
    // Get application instance
    $app = Application::getInstance();
    
    // Define routes
    require_once ROOT_PATH . '/routes.php';
    
    return $app;
    
} catch (Exception $e) {
    http_response_code(500);
    die("Application initialization failed: " . $e->getMessage());
}