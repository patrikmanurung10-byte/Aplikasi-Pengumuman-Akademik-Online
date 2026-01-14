<?php

namespace App\Middleware;

use App\Core\Controller;

/**
 * Role Middleware
 * Middleware untuk mengatur akses berdasarkan role
 */
class RoleMiddleware
{
    /**
     * Check if user has required role
     */
    public static function requireRole($allowedRoles, $redirectUrl = '/login')
    {
        if (!isset($_SESSION['user_id'])) {
            self::redirect($redirectUrl, 'Silakan login terlebih dahulu', 'warning');
        }
        
        $userRole = $_SESSION['role'] ?? '';
        
        // Convert single role to array
        if (!is_array($allowedRoles)) {
            $allowedRoles = [$allowedRoles];
        }
        
        if (!in_array($userRole, $allowedRoles)) {
            self::handleUnauthorizedAccess($userRole);
        }
        
        return true;
    }
    
    /**
     * Require admin access only
     */
    public static function requireAdminOnly()
    {
        return self::requireRole(['admin'], '/login?role=admin');
    }
    
    /**
     * Require admin or dosen access
     */
    public static function requireAdmin()
    {
        return self::requireRole(['admin', 'dosen'], '/login?role=admin');
    }
    
    /**
     * Require dosen access only
     */
    public static function requireDosen()
    {
        return self::requireRole(['dosen'], '/login?role=dosen');
    }
    
    /**
     * Require student access
     */
    public static function requireStudent()
    {
        return self::requireRole(['mahasiswa'], '/login?role=mahasiswa');
    }
    
    /**
     * Handle unauthorized access based on user role
     */
    private static function handleUnauthorizedAccess($userRole)
    {
        switch ($userRole) {
            case 'admin':
            case 'dosen':
                self::redirect('/admin/dashboard', 'Akses ditolak. Anda akan dialihkan ke dashboard admin.', 'warning');
                break;
                
            case 'mahasiswa':
                self::redirect('/student/dashboard', 'Akses ditolak. Anda akan dialihkan ke dashboard mahasiswa.', 'warning');
                break;
                
            default:
                self::redirect('/login', 'Akses ditolak. Silakan login dengan role yang sesuai.', 'error');
                break;
        }
    }
    
    /**
     * Redirect with message
     */
    private static function redirect($url, $message = '', $type = 'info')
    {
        if ($message) {
            $_SESSION['flash_message'] = $message;
            $_SESSION['flash_type'] = $type;
        }
        
        header('Location: ' . $url);
        exit;
    }
    
    /**
     * Check if user is logged in
     */
    public static function requireAuth()
    {
        if (!isset($_SESSION['user_id'])) {
            self::redirect('/login', 'Silakan login terlebih dahulu', 'warning');
        }
        
        return true;
    }
    
    /**
     * Get user role
     */
    public static function getUserRole()
    {
        return $_SESSION['role'] ?? null;
    }
    
    /**
     * Check if user has specific role
     */
    public static function hasRole($role)
    {
        $userRole = $_SESSION['role'] ?? '';
        
        if (is_array($role)) {
            return in_array($userRole, $role);
        }
        
        return $userRole === $role;
    }
    
    /**
     * Check if user is admin
     */
    public static function isAdmin()
    {
        return self::hasRole(['admin', 'dosen']);
    }
    
    /**
     * Check if user is student
     */
    public static function isStudent()
    {
        return self::hasRole('mahasiswa');
    }
}