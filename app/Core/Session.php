<?php

namespace App\Core;


class Session
{
    public static function start()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public static function get($key, $default = null)
    {
        self::start();
        return $_SESSION[$key] ?? $default;
    }
    
    public static function set($key, $value)
    {
        self::start();
        $_SESSION[$key] = $value;
    }
    
    public static function has($key)
    {
        self::start();
        return isset($_SESSION[$key]);
    }
    
    public static function remove($key)
    {
        self::start();
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }
    
    public static function destroy()
    {
        self::start();
        session_destroy();
        $_SESSION = [];
    }
    
    public static function regenerate()
    {
        self::start();
        session_regenerate_id(true);
    }
    
    // Flash Messages
    public static function flash($key, $value)
    {
        self::set("flash_{$key}", $value);
    }
    
    public static function getFlash($key, $default = null)
    {
        $flashKey = "flash_{$key}";
        $value = self::get($flashKey, $default);
        self::remove($flashKey);
        return $value;
    }
    
    public static function hasFlash($key)
    {
        return self::has("flash_{$key}");
    }
    
    // Specific flash message types
    public static function success($message)
    {
        self::flash('success', $message);
    }
    
    public static function error($message)
    {
        self::flash('error', $message);
    }
    
    public static function warning($message)
    {
        self::flash('warning', $message);
    }
    
    public static function info($message)
    {
        self::flash('info', $message);
    }
    
    public static function getSuccess()
    {
        return self::getFlash('success');
    }
    
    public static function getError($key = null)
    {
        if ($key) {
            $errors = self::getFlash('errors', []);
            return $errors[$key] ?? null;
        }
        return self::getFlash('error');
    }
    
    public static function getWarning()
    {
        return self::getFlash('warning');
    }
    
    public static function getInfo()
    {
        return self::getFlash('info');
    }
    
    // CSRF Token
    public static function getCsrfToken()
    {
        if (!self::has('csrf_token')) {
            self::set('csrf_token', bin2hex(random_bytes(32)));
        }
        return self::get('csrf_token');
    }
    
    public static function verifyCsrfToken($token)
    {
        return hash_equals(self::getCsrfToken(), $token);
    }
    
    // Old Input (for form repopulation)
    public static function setOldInput($data)
    {
        self::set('old_input', $data);
    }
    
    public static function getOldInput($key = null, $default = '')
    {
        $oldInput = self::getFlash('old_input', []);
        
        if ($key === null) {
            return $oldInput;
        }
        
        return $oldInput[$key] ?? $default;
    }
    
    // User Authentication
    public static function login($user)
    {
        self::set('user_id', $user['id']);
        self::set('user_data', $user);
        self::set('login_time', time());
        self::regenerate();
    }
    
    public static function logout()
    {
        $keys = ['user_id', 'user_data', 'login_time', 'session_token'];
        foreach ($keys as $key) {
            self::remove($key);
        }
        self::regenerate();
    }
    
    public static function isLoggedIn()
    {
        return self::has('user_id') && self::has('user_data');
    }
    
    public static function user()
    {
        return self::get('user_data');
    }
    
    public static function userId()
    {
        return self::get('user_id');
    }
    
    public static function hasRole($roles)
    {
        $user = self::user();
        if (!$user) return false;
        
        if (is_array($roles)) {
            return in_array($user['role'], $roles);
        }
        
        return $user['role'] === $roles;
    }
    
    // Session timeout check
    public static function isExpired($timeout = 86400) // 24 hours default
    {
        $loginTime = self::get('login_time');
        if (!$loginTime) return true;
        
        return (time() - $loginTime) > $timeout;
    }
}