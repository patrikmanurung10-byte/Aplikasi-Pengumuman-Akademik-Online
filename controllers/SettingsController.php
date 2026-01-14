<?php

use App\Core\Controller;
use App\Core\Session;

/**
 * Settings Controller
 * Controller untuk pengaturan aplikasi
 */
class SettingsController extends Controller
{
    private $userModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        
        $this->userModel = new User();
    }
    
    public function index()
    {
        $user = $this->userModel->find($_SESSION['user_id']);
        
        $this->view('settings.index', [
            'page_title' => 'Pengaturan',
            'user' => $user
        ]);
    }
    
    public function updatePreferences()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/settings');
        }
        
        $userId = $_SESSION['user_id'];
        $preferences = [
            'theme' => $this->getInput('theme', 'light'),
            'language' => $this->getInput('language', 'id'),
            'timezone' => $this->getInput('timezone', 'Asia/Jakarta')
        ];
        
        try {
            // Update user preferences (assuming we have a preferences column or separate table)
            $this->userModel->update($userId, [
                'preferences' => json_encode($preferences),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            
            $this->redirect('/settings', 'Pengaturan berhasil disimpan', 'success');
        } catch (Exception $e) {
            $this->redirect('/settings', 'Gagal menyimpan pengaturan: ' . $e->getMessage(), 'error');
        }
    }
    
    public function privacy()
    {
        $this->view('settings.privacy', [
            'page_title' => 'Pengaturan Privasi'
        ]);
    }
    
    public function updatePrivacy()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/settings/privacy');
        }
        
        $userId = $_SESSION['user_id'];
        $privacy = [
            'profile_visibility' => $this->getInput('profile_visibility', 'public'),
            'show_email' => $this->getInput('show_email') ? 1 : 0,
            'show_phone' => $this->getInput('show_phone') ? 1 : 0,
            'allow_messages' => $this->getInput('allow_messages') ? 1 : 0
        ];
        
        try {
            $this->userModel->update($userId, [
                'privacy_settings' => json_encode($privacy),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            
            $this->redirect('/settings/privacy', 'Pengaturan privasi berhasil disimpan', 'success');
        } catch (Exception $e) {
            $this->redirect('/settings/privacy', 'Gagal menyimpan pengaturan privasi: ' . $e->getMessage(), 'error');
        }
    }
    
    public function security()
    {
        $user = $this->userModel->find($_SESSION['user_id']);
        
        $this->view('settings.security', [
            'page_title' => 'Pengaturan Keamanan',
            'user' => $user
        ]);
    }
    
    public function updateSecurity()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/settings/security');
        }
        
        $userId = $_SESSION['user_id'];
        $security = [
            'two_factor_enabled' => $this->getInput('two_factor_enabled') ? 1 : 0,
            'session_timeout' => (int)$this->getInput('session_timeout', 30)
        ];
        
        try {
            $this->userModel->update($userId, [
                'security_settings' => json_encode($security),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            
            $this->redirect('/settings/security', 'Pengaturan keamanan berhasil disimpan', 'success');
        } catch (Exception $e) {
            $this->redirect('/settings/security', 'Gagal menyimpan pengaturan keamanan: ' . $e->getMessage(), 'error');
        }
    }
}