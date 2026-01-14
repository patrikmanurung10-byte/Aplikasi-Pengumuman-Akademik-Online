<?php
/**
 * Helper Functions for APAO Polibatam
 * Fungsi-fungsi bantuan untuk sistem
 */

// Dependencies are loaded via bootstrap.php

/**
 * Database connection instance
 */
function getDB() {
    static $db = null;
    if ($db === null) {
        $database = new Database();
        $db = $database->getConnection();
    }
    return $db;
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && isset($_SESSION['session_token']);
}

/**
 * Get current logged in user
 */
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    return [
        'id' => $_SESSION['user_id'],
        'username' => $_SESSION['username'],
        'email' => $_SESSION['email'],
        'full_name' => $_SESSION['full_name'],
        'role' => $_SESSION['role'],
        'nim_nip' => $_SESSION['nim_nip']
    ];
}

/**
 * Check if user has specific role
 */
function hasRole($roles) {
    $user = getCurrentUser();
    if (!$user) return false;
    
    if (is_array($roles)) {
        return in_array($user['role'], $roles);
    }
    
    return $user['role'] === $roles;
}

/**
 * Redirect to login if not authenticated
 */
function requireLogin() {
    if (!isLoggedIn()) {
        redirect('login.php');
    }
}

/**
 * Require specific role
 */
function requireRole($roles) {
    requireLogin();
    
    if (!hasRole($roles)) {
        showError('Akses ditolak. Anda tidak memiliki permission untuk mengakses halaman ini.');
    }
}

/**
 * Redirect function
 */
function redirect($url) {
    if (!headers_sent()) {
        header("Location: $url");
        exit();
    } else {
        echo "<script>window.location.href='$url';</script>";
        exit();
    }
}

/**
 * Sanitize input
 */
function sanitize($input) {
    return htmlspecialchars(strip_tags(trim($input)));
}

/**
 * Generate CSRF token
 */
function generateCSRFToken() {
    if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
    }
    return $_SESSION[CSRF_TOKEN_NAME];
}

/**
 * Verify CSRF token
 */
function verifyCSRFToken($token) {
    return isset($_SESSION[CSRF_TOKEN_NAME]) && hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
}

/**
 * Show error message and exit
 */
function showError($message, $code = 403) {
    http_response_code($code);
    include TEMPLATES_PATH . '/error.php';
    exit();
}

/**
 * Format date for display
 */
function formatDate($date, $format = 'd/m/Y') {
    return date($format, strtotime($date));
}

/**
 * Format datetime for display
 */
function formatDateTime($datetime, $format = 'd/m/Y H:i') {
    return date($format, strtotime($datetime));
}

/**
 * Get time ago format
 */
function timeAgo($datetime) {
    $time = time() - strtotime($datetime);
    
    if ($time < 60) return 'baru saja';
    if ($time < 3600) return floor($time/60) . ' menit yang lalu';
    if ($time < 86400) return floor($time/3600) . ' jam yang lalu';
    if ($time < 2592000) return floor($time/86400) . ' hari yang lalu';
    if ($time < 31536000) return floor($time/2592000) . ' bulan yang lalu';
    
    return floor($time/31536000) . ' tahun yang lalu';
}

/**
 * Get announcement priority badge class
 */
function getPriorityBadgeClass($priority) {
    switch ($priority) {
        case 'high': return 'bg-danger';
        case 'medium': return 'bg-warning';
        case 'low': return 'bg-secondary';
        default: return 'bg-secondary';
    }
}

/**
 * Get role display name
 */
function getRoleDisplayName($role) {
    switch ($role) {
        case 'admin': return 'Administrator';
        case 'dosen': return 'Dosen';
        case 'mahasiswa': return 'Mahasiswa';
        default: return ucfirst($role);
    }
}

/**
 * Flash message functions
 */
function setFlashMessage($type, $message) {
    $_SESSION['flash_message'] = [
        'type' => $type,
        'message' => $message
    ];
}

function getFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return $message;
    }
    return null;
}

/**
 * Pagination helper
 */
function generatePagination($current_page, $total_pages, $base_url) {
    $pagination = '';
    
    if ($total_pages <= 1) return $pagination;
    
    $pagination .= '<nav aria-label="Page navigation"><ul class="pagination justify-content-center">';
    
    // Previous button
    if ($current_page > 1) {
        $prev_page = $current_page - 1;
        $pagination .= "<li class='page-item'><a class='page-link' href='{$base_url}?page={$prev_page}'>Previous</a></li>";
    }
    
    // Page numbers
    $start = max(1, $current_page - 2);
    $end = min($total_pages, $current_page + 2);
    
    for ($i = $start; $i <= $end; $i++) {
        $active = ($i == $current_page) ? 'active' : '';
        $pagination .= "<li class='page-item {$active}'><a class='page-link' href='{$base_url}?page={$i}'>{$i}</a></li>";
    }
    
    // Next button
    if ($current_page < $total_pages) {
        $next_page = $current_page + 1;
        $pagination .= "<li class='page-item'><a class='page-link' href='{$base_url}?page={$next_page}'>Next</a></li>";
    }
    
    $pagination .= '</ul></nav>';
    
    return $pagination;
}

/**
 * Load template with variables
 */
function loadTemplate($template, $variables = []) {
    extract($variables);
    ob_start();
    include TEMPLATES_PATH . "/{$template}.php";
    return ob_get_clean();
}
?>