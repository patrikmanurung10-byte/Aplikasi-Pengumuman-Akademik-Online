<?php

namespace App\Core;

abstract class Controller
{
    protected $data = [];
    
    public function __construct()
    {
        // Share common data to all views
        try {
            $app = Application::getInstance();
            View::share([
                'app_name' => $app->getConfig('app.name', 'APAO Polibatam'),
                'app_version' => $app->getConfig('app.version', '2.0.0'),
                'current_user' => Session::user(),
                'is_logged_in' => Session::isLoggedIn()
            ]);
        } catch (Exception $e) {
            // Fallback if application not fully initialized
            View::share([
                'app_name' => 'APAO Polibatam',
                'app_version' => '2.0.0',
                'current_user' => null,
                'is_logged_in' => false
            ]);
        }
    }
    
    protected function view($view, $data = [], $layout = 'app')
    {
        View::render($view, array_merge($this->data, $data), $layout);
    }
    
    protected function json($data, $status = 200)
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    protected function redirect($url, $message = null, $type = 'info')
    {
        if ($message) {
            Session::flash($type, $message);
        }
        
        header("Location: {$url}");
        exit;
    }
    
    protected function back($message = null, $type = 'info')
    {
        if ($message) {
            Session::flash($type, $message);
        }
        
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        header("Location: {$referer}");
        exit;
    }
    
    protected function validate($data, $rules)
    {
        $validator = new Validator();
        return $validator->validate($data, $rules);
    }
    
    protected function sanitize($input)
    {
        if (is_array($input)) {
            return array_map([$this, 'sanitize'], $input);
        }
        
        return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
    }
    
    protected function requireAuth()
    {
        if (!Session::isLoggedIn()) {
            $this->redirect('/login', 'Silakan login terlebih dahulu', 'warning');
        }
    }
    
    protected function requireRole($roles)
    {
        $this->requireAuth();
        
        if (!Session::hasRole($roles)) {
            $this->redirect('/', 'Akses ditolak. Anda tidak memiliki permission untuk mengakses halaman ini.', 'error');
        }
    }
    
    protected function requireGuest()
    {
        if (Session::isLoggedIn()) {
            $user = Session::user();
            $redirectUrl = in_array($user['role'], ['admin', 'dosen']) ? '/admin/dashboard' : '/dashboard';
            $this->redirect($redirectUrl);
        }
    }
    
    protected function verifyCsrf()
    {
        $token = $_POST['csrf_token'] ?? $_GET['csrf_token'] ?? '';
        
        if (!Session::verifyCsrfToken($token)) {
            $this->json(['error' => 'Token keamanan tidak valid'], 403);
        }
    }
    
    protected function getInput($key = null, $default = null)
    {
        $input = array_merge($_GET, $_POST);
        
        if ($key === null) {
            return $input;
        }
        
        return $input[$key] ?? $default;
    }
    
    protected function hasInput($key)
    {
        $input = array_merge($_GET, $_POST);
        return isset($input[$key]);
    }
    
    protected function only($keys)
    {
        $input = $this->getInput();
        return array_intersect_key($input, array_flip($keys));
    }
    
    protected function except($keys)
    {
        $input = $this->getInput();
        return array_diff_key($input, array_flip($keys));
    }
    
    protected function paginate($data, $page, $perPage, $total)
    {
        return [
            'data' => $data,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'total_pages' => ceil($total / $perPage),
                'has_next' => $page < ceil($total / $perPage),
                'has_prev' => $page > 1,
                'from' => ($page - 1) * $perPage + 1,
                'to' => min($page * $perPage, $total)
            ]
        ];
    }
    
    protected function formatDate($date, $format = 'd/m/Y')
    {
        return date($format, strtotime($date));
    }
    
    protected function formatDateTime($datetime, $format = 'd/m/Y H:i')
    {
        return date($format, strtotime($datetime));
    }
    
    protected function timeAgo($datetime)
    {
        $time = time() - strtotime($datetime);
        
        if ($time < 60) return 'baru saja';
        if ($time < 3600) return floor($time/60) . ' menit yang lalu';
        if ($time < 86400) return floor($time/3600) . ' jam yang lalu';
        if ($time < 2592000) return floor($time/86400) . ' hari yang lalu';
        if ($time < 31536000) return floor($time/2592000) . ' bulan yang lalu';
        
        return floor($time/31536000) . ' tahun yang lalu';
    }
}