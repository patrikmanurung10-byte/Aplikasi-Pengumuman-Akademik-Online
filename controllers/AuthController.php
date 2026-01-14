<?php

use App\Core\Controller;
use App\Core\Session;

/**
 * Authentication Controller
 * Controller untuk autentikasi dengan database
 */
class AuthController extends Controller
{
    private $userModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();
    }
    
    public function showLogin()
    {
        // Check if user is already logged in
        if (isset($_SESSION['user_id'])) {
            $user_role = $_SESSION['role'] ?? 'mahasiswa';
            $redirectUrl = $this->getRedirectUrl($user_role);
            $this->redirect($redirectUrl);
        }
        
        $role = $this->getInput('role', 'mahasiswa');
        $isDosen = ($role === 'dosen');
        
        $this->view('auth.login', [
            'page_title' => $isDosen ? 'Login Dosen' : 'Login Mahasiswa',
            'role' => $role,
            'is_dosen' => $isDosen
        ], 'guest');
    }
    
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/login');
        }
        
        $username = $this->sanitize($this->getInput('username'));
        $password = $this->getInput('password');
        $role = $this->getInput('role', 'mahasiswa');
        
        // Validation
        if (empty($username) || empty($password)) {
            $this->back('Username dan password harus diisi', 'warning');
        }
        
        try {
            // Find user in database
            $user = $this->userModel->findByUsernameOrEmail($username);
            
            if (!$user) {
                $this->back('Username/email tidak ditemukan', 'error');
            }
            
            // Verify password
            if (!$this->userModel->verifyPassword($password, $user['password'])) {
                $this->back('Password salah', 'error');
            }
            
            // Check if user is active
            if (!$user['is_active']) {
                $this->back('Akun Anda tidak aktif. Hubungi administrator', 'error');
            }
            
            // Check role compatibility (allow all roles)
            if (!in_array($user['role'], ['admin', 'dosen', 'mahasiswa'])) {
                $this->back('Role tidak valid untuk sistem ini', 'error');
            }
            
            // Set session data
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['login_time'] = time();
            
            // Redirect based on role
            $redirectUrl = $this->getRedirectUrl($user['role']);
            $this->redirect($redirectUrl, "Login berhasil! Selamat datang, {$user['full_name']}", 'success');
            
        } catch (Exception $e) {
            $this->back('Terjadi kesalahan sistem: ' . $e->getMessage(), 'error');
        }
    }
    

    
    private function getRedirectUrl($role)
    {
        switch ($role) {
            case 'admin':
                return '/admin/dashboard';
            case 'dosen':
                return '/dosen/dashboard';
            case 'mahasiswa':
            default:
                return '/student/dashboard';
        }
    }
    
    public function showRegister()
    {
        // Simple guest check
        if (isset($_SESSION['user_id'])) {
            $this->redirect('/dashboard');
        }
        
        $this->view('auth.register', [
            'page_title' => 'Registrasi Mahasiswa'
        ], 'guest');
    }
    
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/register');
        }
        
        $input = $this->only([
            'username', 'email', 'password', 'confirm_password', 
            'full_name', 'nim_nip', 'program_studi'
        ]);
        
        // Sanitize input
        foreach ($input as $key => $value) {
            if (!in_array($key, ['password', 'confirm_password'])) {
                $input[$key] = $this->sanitize($value);
            }
        }
        
        // Validation
        $errors = $this->validateRegistration($input);
        
        if (!empty($errors)) {
            foreach ($errors as $error) {
                $this->back($error, 'warning');
                break; // Show first error only
            }
        }
        
        try {
            // Create user
            $userData = [
                'username' => $input['username'],
                'email' => $input['email'],
                'password' => $input['password'],
                'full_name' => $input['full_name'],
                'role' => 'mahasiswa', // Always register as mahasiswa
                'nim_nip' => $input['nim_nip'] ?? '',
                'program_studi' => $input['program_studi'] ?? ''
            ];
            
            $userId = $this->userModel->createUser($userData);
            
            if ($userId) {
                $this->redirect('/login', 'Registrasi berhasil! Silakan login dengan akun Anda', 'success');
            } else {
                $this->back('Gagal melakukan registrasi. Silakan coba lagi', 'error');
            }
            
        } catch (Exception $e) {
            $this->back('Terjadi kesalahan: ' . $e->getMessage(), 'error');
        }
    }
    
    private function validateRegistration($input)
    {
        $errors = [];
        
        // Required fields
        $required = ['username', 'email', 'password', 'confirm_password', 'full_name'];
        foreach ($required as $field) {
            if (empty($input[$field])) {
                $errors[] = ucfirst(str_replace('_', ' ', $field)) . ' harus diisi';
            }
        }
        
        // Username validation
        if (!empty($input['username'])) {
            if (strlen($input['username']) < 3) {
                $errors[] = 'Username minimal 3 karakter';
            } elseif ($this->userModel->isUsernameExists($input['username'])) {
                $errors[] = 'Username sudah digunakan';
            }
        }
        
        // Email validation
        if (!empty($input['email'])) {
            if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Format email tidak valid';
            } elseif ($this->userModel->isEmailExists($input['email'])) {
                $errors[] = 'Email sudah terdaftar';
            }
        }
        
        // Password validation
        if (!empty($input['password'])) {
            if (strlen($input['password']) < 6) {
                $errors[] = 'Password minimal 6 karakter';
            } elseif ($input['password'] !== $input['confirm_password']) {
                $errors[] = 'Konfirmasi password tidak sama';
            }
        }
        
        return $errors;
    }
    
    public function showForgotPassword()
    {
        // Clear any existing OTP session if resend is requested
        if (isset($_GET['resend']) && $_GET['resend'] == '1') {
            unset($_SESSION['otp_sent']);
            unset($_SESSION['otp_code']);
            unset($_SESSION['otp_expires']);
            unset($_SESSION['reset_whatsapp']);
        }
        
        $this->view('auth.forgot-password', [
            'page_title' => 'Lupa Password'
        ], 'guest');
    }
    
    public function forgotPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/forgot-password');
        }
        
        $whatsapp = trim($this->getInput('whatsapp') ?? '');
        
        // Validation
        if (empty($whatsapp)) {
            $this->redirect('/forgot-password', 'Nomor WhatsApp harus diisi', 'error');
        }
        
        // Format WhatsApp number
        $whatsapp = preg_replace('/\D/', '', $whatsapp); // Remove non-digits
        if (substr($whatsapp, 0, 1) === '0') {
            $whatsapp = substr($whatsapp, 1); // Remove leading 0
        }
        
        if (strlen($whatsapp) < 10 || strlen($whatsapp) > 13) {
            $this->redirect('/forgot-password', 'Format nomor WhatsApp tidak valid', 'error');
        }
        
        try {
            $userModel = new User();
            
            // Check if user exists with this WhatsApp number
            $user = $userModel->findByWhatsApp($whatsapp);
            
            if (!$user) {
                // Don't reveal if user exists or not for security
                $this->redirect('/forgot-password', 'Jika nomor WhatsApp terdaftar, kode OTP akan dikirim', 'info');
            }
            
            // Generate 4-digit OTP
            $otpCode = sprintf('%04d', rand(0, 9999));
            
            // Store OTP in session (expires in 5 minutes)
            $_SESSION['otp_code'] = $otpCode;
            $_SESSION['otp_expires'] = time() + (5 * 60); // 5 minutes
            $_SESSION['reset_whatsapp'] = $whatsapp;
            $_SESSION['reset_user_id'] = $user['id'];
            $_SESSION['otp_sent'] = true;
            
            // Send WhatsApp message (simulate for now)
            $this->sendWhatsAppOTP($whatsapp, $otpCode);
            
            $this->redirect('/forgot-password', 'Kode OTP telah dikirim ke WhatsApp Anda', 'success');
            
        } catch (Exception $e) {
            $this->redirect('/forgot-password', 'Terjadi kesalahan sistem. Silakan coba lagi.', 'error');
        }
    }
    
    public function verifyOTP()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/forgot-password');
        }
        
        $otpCode = trim($this->getInput('otp_code') ?? '');
        $newPassword = $this->getInput('new_password');
        $confirmPassword = $this->getInput('confirm_password');
        
        // Validation
        if (empty($otpCode) || strlen($otpCode) !== 4) {
            $this->redirect('/forgot-password', 'Kode OTP harus 4 digit', 'error');
        }
        
        if (empty($newPassword) || strlen($newPassword) < 6) {
            $this->redirect('/forgot-password', 'Password baru minimal 6 karakter', 'error');
        }
        
        if ($newPassword !== $confirmPassword) {
            $this->redirect('/forgot-password', 'Konfirmasi password tidak cocok', 'error');
        }
        
        // Check if OTP session exists
        if (!isset($_SESSION['otp_code']) || !isset($_SESSION['otp_expires']) || !isset($_SESSION['reset_user_id'])) {
            $this->redirect('/forgot-password', 'Sesi OTP tidak valid. Silakan kirim ulang kode OTP.', 'error');
        }
        
        // Check if OTP expired
        if (time() > $_SESSION['otp_expires']) {
            unset($_SESSION['otp_code']);
            unset($_SESSION['otp_expires']);
            unset($_SESSION['reset_whatsapp']);
            unset($_SESSION['reset_user_id']);
            unset($_SESSION['otp_sent']);
            $this->redirect('/forgot-password', 'Kode OTP telah expired. Silakan kirim ulang.', 'error');
        }
        
        // Verify OTP
        if ($otpCode !== $_SESSION['otp_code']) {
            $this->redirect('/forgot-password', 'Kode OTP salah. Silakan coba lagi.', 'error');
        }
        
        try {
            $userModel = new User();
            $userId = $_SESSION['reset_user_id'];
            
            // Update password
            $updated = $userModel->updatePassword($userId, $newPassword);
            
            if ($updated) {
                // Clear OTP session
                unset($_SESSION['otp_code']);
                unset($_SESSION['otp_expires']);
                unset($_SESSION['reset_whatsapp']);
                unset($_SESSION['reset_user_id']);
                unset($_SESSION['otp_sent']);
                
                $this->redirect('/login', 'Password berhasil direset! Silakan login dengan password baru.', 'success');
            } else {
                $this->redirect('/forgot-password', 'Gagal mereset password. Silakan coba lagi.', 'error');
            }
            
        } catch (Exception $e) {
            $this->redirect('/forgot-password', 'Terjadi kesalahan sistem. Silakan coba lagi.', 'error');
        }
    }
    
    private function sendWhatsAppOTP($whatsapp, $otpCode)
    {
        // Use WhatsApp Helper to send OTP
        require_once __DIR__ . '/../app/Helpers/WhatsAppHelper.php';
        
        return WhatsAppHelper::sendOTP($whatsapp, $otpCode, 'APAO Polibatam');
    }
    
    public function logout()
    {
        // Start session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Store user info for logout message
        $userName = $_SESSION['full_name'] ?? $_SESSION['username'] ?? 'User';
        
        // Clear all session variables
        $_SESSION = array();
        
        // Delete session cookie if it exists
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        // Destroy the session
        session_destroy();
        
        // Start a new session for flash message
        session_start();
        session_regenerate_id(true);
        
        // Set flash message
        $_SESSION['flash_success'] = "Logout berhasil! Sampai jumpa, {$userName}";
        
        // Redirect to home
        header('Location: /');
        exit;
    }
}