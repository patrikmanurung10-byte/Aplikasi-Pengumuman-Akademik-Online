<?php

use App\Core\Controller;
use App\Core\Session;

/**
 * Dashboard Controller
 * Controller untuk routing dashboard berdasarkan role
 */
class DashboardController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
    }
    
    public function index()
    {
        // Redirect based on user role
        $role = $_SESSION['role'] ?? '';
        
        switch ($role) {
            case 'admin':
                $this->redirect('/admin/dashboard');
                break;
                
            case 'dosen':
                $this->redirect('/dosen/dashboard');
                break;
                
            case 'mahasiswa':
                $this->redirect('/student/dashboard');
                break;
                
            default:
                $this->redirect('/login', 'Role tidak dikenali, silakan login ulang', 'warning');
                break;
        }
    }
    
    private function requireAuth()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login', 'Silakan login terlebih dahulu', 'warning');
        }
    }
}