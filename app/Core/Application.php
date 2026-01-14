<?php

namespace App\Core;

class Application
{
    private static $instance = null;
    private $config = [];
    private $routes = [];
    private $middleware = [];
    
    private function __construct()
    {
        $this->initializeApplication();
    }
    
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function initializeApplication()
    {
        // Start session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Set error reporting
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        // Set timezone
        date_default_timezone_set('Asia/Jakarta');
        
        // Load configuration
        $this->loadConfiguration();
        
        // Database will be initialized lazily when needed
    }
    
    private function loadConfiguration()
    {
        $configFiles = [
            'app' => __DIR__ . '/../Config/app.php',
            'database' => __DIR__ . '/../Config/database.php',
            'auth' => __DIR__ . '/../Config/auth.php'
        ];
        
        foreach ($configFiles as $key => $file) {
            if (file_exists($file)) {
                $this->config[$key] = require $file;
            }
        }
    }
    
    public function getConfig($key = null, $default = null)
    {
        if ($key === null) {
            return $this->config;
        }
        
        $keys = explode('.', $key);
        $value = $this->config;
        
        foreach ($keys as $k) {
            if (!isset($value[$k])) {
                return $default;
            }
            $value = $value[$k];
        }
        
        return $value;
    }
    
    public function addRoute($method, $path, $handler, $middleware = [])
    {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'handler' => $handler,
            'middleware' => $middleware
        ];
    }
    
    public function run()
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Remove base path if exists
        $basePath = $this->getConfig('app.base_path', '');
        if ($basePath && strpos($requestUri, $basePath) === 0) {
            $requestUri = substr($requestUri, strlen($basePath));
        }
        
        // Find matching route
        foreach ($this->routes as $route) {
            if ($this->matchRoute($route, $requestMethod, $requestUri)) {
                $this->executeRoute($route, $requestUri);
                return;
            }
        }
        
        // No route found, handle 404
        $this->handle404();
    }
    
    private function matchRoute($route, $method, $uri)
    {
        if ($route['method'] !== $method) {
            return false;
        }
        
        $pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $route['path']);
        $pattern = '#^' . $pattern . '$#';
        
        return preg_match($pattern, $uri);
    }
    
    private function executeRoute($route, $uri)
    {
        // Extract route parameters
        $this->extractRouteParameters($route, $uri);
        
        // Execute middleware
        foreach ($route['middleware'] as $middlewareClass) {
            $middleware = new $middlewareClass();
            if (!$middleware->handle()) {
                return;
            }
        }
        
        // Execute handler
        if (is_callable($route['handler'])) {
            call_user_func($route['handler']);
        } elseif (is_string($route['handler'])) {
            $this->executeControllerAction($route['handler']);
        }
    }
    
    private function extractRouteParameters($route, $uri)
    {
        // Get parameter names from route path
        preg_match_all('/\{([^}]+)\}/', $route['path'], $paramNames);
        $paramNames = $paramNames[1];
        
        // Get parameter values from URI
        $pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $route['path']);
        $pattern = '#^' . $pattern . '$#';
        
        if (preg_match($pattern, $uri, $matches)) {
            // Remove the full match
            array_shift($matches);
            
            // Combine parameter names with values
            $params = array_combine($paramNames, $matches);
            
            // Add parameters to $_GET so they're available via getInput()
            foreach ($params as $name => $value) {
                $_GET[$name] = $value;
            }
        }
    }
    
    private function executeControllerAction($handler)
    {
        list($controllerName, $action) = explode('@', $handler);
        
        // Try different controller class paths
        $possibleClasses = [
            $controllerName,  // Root namespace
            "Controllers\\{$controllerName}",  // Controllers namespace
            "App\\Controllers\\{$controllerName}"  // App\Controllers namespace
        ];
        
        foreach ($possibleClasses as $controllerClass) {
            if (class_exists($controllerClass)) {
                $controller = new $controllerClass();
                if (method_exists($controller, $action)) {
                    $controller->$action();
                    return;
                } else {
                    $this->handle404();
                    return;
                }
            }
        }
        
        // No controller found
        $this->handle404();
    }
    
    private function handle404()
    {
        http_response_code(404);
        View::render('errors/404');
    }
}