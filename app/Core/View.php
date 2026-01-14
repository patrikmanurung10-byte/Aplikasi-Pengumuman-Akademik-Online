<?php

namespace App\Core;


class View
{
    private static $viewPath = '';
    private static $layoutPath = '';
    private static $data = [];
    
    public static function init()
    {
        self::$viewPath = __DIR__ . '/../../views/';
        self::$layoutPath = __DIR__ . '/../../views/layouts/';
    }
    
    public static function render($view, $data = [], $layout = 'app')
    {
        if (empty(self::$viewPath)) {
            self::init();
        }
        
        self::$data = array_merge(self::$data, $data);
        
        // Start output buffering
        ob_start();
        
        // Include the view file
        $viewFile = self::$viewPath . str_replace('.', '/', $view) . '.php';
        if (file_exists($viewFile)) {
            extract(self::$data);
            include $viewFile;
        } else {
            throw new \Exception("View file not found: {$viewFile}");
        }
        
        $content = ob_get_clean();
        
        // If layout is specified, wrap content in layout
        if ($layout) {
            self::renderLayout($layout, $content);
        } else {
            echo $content;
        }
    }
    
    private static function renderLayout($layout, $content)
    {
        $layoutFile = self::$layoutPath . $layout . '.php';
        if (file_exists($layoutFile)) {
            extract(self::$data);
            include $layoutFile;
        } else {
            echo $content; // Fallback to content only
        }
    }
    
    public static function component($component, $data = [])
    {
        $componentFile = self::$viewPath . 'components/' . $component . '.php';
        if (file_exists($componentFile)) {
            extract(array_merge(self::$data, $data));
            include $componentFile;
        }
    }
    
    public static function share($key, $value = null)
    {
        if (is_array($key)) {
            self::$data = array_merge(self::$data, $key);
        } else {
            self::$data[$key] = $value;
        }
    }
    
    public static function asset($path)
    {
        // Get base URL from server or config
        $baseUrl = self::getBaseUrl();
        return rtrim($baseUrl, '/') . '/assets/' . ltrim($path, '/');
    }
    
    public static function url($path = '')
    {
        // Get base URL from server or config
        $baseUrl = self::getBaseUrl();
        return rtrim($baseUrl, '/') . '/' . ltrim($path, '/');
    }
    
    private static function getBaseUrl()
    {
        // Try to get from Application if available
        try {
            $app = Application::getInstance();
            return $app->getConfig('app.base_url', self::getServerBaseUrl());
        } catch (Exception $e) {
            // Fallback to server-based detection
            return self::getServerBaseUrl();
        }
    }
    
    private static function getServerBaseUrl()
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        
        // For development server, just use protocol + host
        if (strpos($host, ':') !== false && strpos($host, 'localhost') !== false) {
            return $protocol . $host;
        }
        
        $path = dirname($_SERVER['SCRIPT_NAME'] ?? '');
        $path = ($path === '/' || $path === '\\') ? '' : $path;
        
        return $protocol . $host . $path;
    }
    
    public static function route($name, $params = [])
    {
        // Simple route helper - can be expanded
        return self::url($name);
    }
    
    public static function csrf()
    {
        return Session::getCsrfToken();
    }
    
    public static function old($key, $default = '')
    {
        return Session::getOldInput($key, $default);
    }
    
    public static function error($key)
    {
        return Session::getError($key);
    }
    
    public static function success()
    {
        return Session::getSuccess();
    }
}