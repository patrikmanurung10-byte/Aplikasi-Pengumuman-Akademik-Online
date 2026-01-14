<?php

use App\Core\Controller;
use App\Core\Session;
use App\Services\WhatsAppService;

/**
 * Forgot Password Controller
 * Controller untuk menangani lupa password dengan WhatsApp OTP
 */
class ForgotPasswordController extends Controller
{
    private $passwordResetModel;
    private $userModel;
    private $whatsappService;
    
    public function __construct()
    {
        parent::__construct();
        $this->passwordResetModel = new PasswordReset();
        $this->userModel = new User();
        $this->whatsappService = new WhatsAppService();
    }
    
    /**
     * Show forgot password form
     */
    public function index()
    {
        $this->view('auth.forgot-password', [
            'page_title' => 'Lupa Password'
        ]);
    }
    
    /**
     * Send OTP via WhatsApp
     */
    public function sendOtp()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/forgot-password');
        }
        
        $identifier = trim($this->getInput('identifier') ?? '');
        
        if (empty($identifier)) {
            Session::error('Username atau email harus diisi');
            $this->redirect('/forgot-password');
        }
        
        try {
            // Check rate limiting (max 3 attempts per hour)
            $recentAttempts = $this->passwordResetModel->getRecentAttempts($identifier);
            if ($recentAttempts >= 3) {
                Session::error('Terlalu banyak percobaan. Coba lagi dalam 1 jam.');
                $this->redirect('/forgot-password');
            }
            
            // Find user by username or email
            $user = $this->passwordResetModel->getUserByIdentifier($identifier);
            if (!$user) {
                Session::error('Username atau email tidak ditemukan');
                $this->redirect('/forgot-password');
            }
            
            // Check if user has phone number
            if (empty($user['phone'])) {
                Session::error('Nomor WhatsApp tidak terdaftar. Hubungi administrator untuk reset password.');
                $this->redirect('/forgot-password');
            }
            
            // Generate OTP
            $otp = $this->generateOTP();
            
            // Save reset request to database
            $resetCreated = $this->passwordResetModel->createResetRequest(
                $identifier,
                $user['phone'],
                $otp
            );
            
            if (!$resetCreated) {
                Session::error('Gagal membuat permintaan reset password');
                $this->redirect('/forgot-password');
            }
            
            // Send OTP via WhatsApp
            $whatsappResult = $this->whatsappService->sendOTP(
                $user['phone'],
                $otp,
                $user['full_name']
            );
            
            if (!$whatsappResult['success']) {
                Session::error('Gagal mengirim OTP ke WhatsApp: ' . $whatsappResult['message']);
                $this->redirect('/forgot-password');
            }
            
            // Store identifier in session for verification
            Session::set('reset_identifier', $identifier);
            Session::set('reset_phone', $this->maskPhoneNumber($user['phone']));
            
            Session::success('Kode OTP telah dikirim ke WhatsApp Anda');
            $this->redirect('/forgot-password/verify');
            
        } catch (Exception $e) {
            Session::error('Terjadi kesalahan: ' . $e->getMessage());
            $this->redirect('/forgot-password');
        }
    }
    
    /**
     * Show OTP verification form
     */
    public function verify()
    {
        $identifier = Session::get('reset_identifier');
        $phone = Session::get('reset_phone');
        
        if (!$identifier) {
            Session::error('Sesi tidak valid. Silakan mulai ulang proses reset password.');
            $this->redirect('/forgot-password');
        }
        
        $this->view('auth.verify-otp', [
            'page_title' => 'Verifikasi OTP',
            'identifier' => $identifier,
            'phone' => $phone
        ]);
    }
    
    /**
     * Verify OTP code
     */
    public function verifyOtp()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/forgot-password/verify');
        }
        
        $identifier = Session::get('reset_identifier');
        $otpCode = trim($this->getInput('otp_code') ?? '');
        
        if (!$identifier) {
            Session::error('Sesi tidak valid. Silakan mulai ulang proses reset password.');
            $this->redirect('/forgot-password');
        }
        
        if (empty($otpCode)) {
            Session::error('Kode OTP harus diisi');
            $this->redirect('/forgot-password/verify');
        }
        
        try {
            // Verify OTP
            $resetRequest = $this->passwordResetModel->verifyOTP($identifier, $otpCode);
            
            if (!$resetRequest) {
                Session::error('Kode OTP tidak valid atau sudah kadaluarsa');
                $this->redirect('/forgot-password/verify');
            }
            
            // Mark OTP as used
            $this->passwordResetModel->markAsUsed($resetRequest['id']);
            
            // Store reset token in session
            Session::set('reset_token', $resetRequest['id']);
            Session::success('Kode OTP berhasil diverifikasi');
            
            $this->redirect('/forgot-password/reset');
            
        } catch (Exception $e) {
            Session::error('Terjadi kesalahan: ' . $e->getMessage());
            $this->redirect('/forgot-password/verify');
        }
    }
    
    /**
     * Resend OTP
     */
    public function resendOtp()
    {
        $identifier = Session::get('reset_identifier');
        
        if (!$identifier) {
            Session::error('Sesi tidak valid. Silakan mulai ulang proses reset password.');
            $this->redirect('/forgot-password');
        }
        
        try {
            // Check rate limiting
            $recentAttempts = $this->passwordResetModel->getRecentAttempts($identifier);
            if ($recentAttempts >= 5) {
                Session::error('Terlalu banyak percobaan. Coba lagi dalam 1 jam.');
                $this->redirect('/forgot-password/verify');
            }
            
            // Get user data
            $user = $this->passwordResetModel->getUserByIdentifier($identifier);
            if (!$user || empty($user['phone'])) {
                Session::error('Data user tidak valid');
                $this->redirect('/forgot-password');
            }
            
            // Generate new OTP
            $otp = $this->generateOTP();
            
            // Create new reset request
            $resetCreated = $this->passwordResetModel->createResetRequest(
                $identifier,
                $user['phone'],
                $otp
            );
            
            if (!$resetCreated) {
                Session::error('Gagal membuat permintaan reset password');
                $this->redirect('/forgot-password/verify');
            }
            
            // Send new OTP
            $whatsappResult = $this->whatsappService->sendOTP(
                $user['phone'],
                $otp,
                $user['full_name']
            );
            
            if (!$whatsappResult['success']) {
                Session::error('Gagal mengirim OTP: ' . $whatsappResult['message']);
                $this->redirect('/forgot-password/verify');
            }
            
            Session::success('Kode OTP baru telah dikirim ke WhatsApp Anda');
            $this->redirect('/forgot-password/verify');
            
        } catch (Exception $e) {
            Session::error('Terjadi kesalahan: ' . $e->getMessage());
            $this->redirect('/forgot-password/verify');
        }
    }
    
    /**
     * Show reset password form
     */
    public function reset()
    {
        $resetToken = Session::get('reset_token');
        $identifier = Session::get('reset_identifier');
        
        if (!$resetToken || !$identifier) {
            Session::error('Sesi tidak valid. Silakan mulai ulang proses reset password.');
            $this->redirect('/forgot-password');
        }
        
        $this->view('auth.reset-password', [
            'page_title' => 'Reset Password',
            'identifier' => $identifier
        ]);
    }
    
    /**
     * Update password
     */
    public function updatePassword()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/forgot-password/reset');
        }
        
        $resetToken = Session::get('reset_token');
        $identifier = Session::get('reset_identifier');
        $newPassword = $this->getInput('new_password');
        $confirmPassword = $this->getInput('confirm_password');
        
        if (!$resetToken || !$identifier) {
            Session::error('Sesi tidak valid. Silakan mulai ulang proses reset password.');
            $this->redirect('/forgot-password');
        }
        
        // Validation
        if (empty($newPassword) || empty($confirmPassword)) {
            Session::error('Password baru dan konfirmasi password harus diisi');
            $this->redirect('/forgot-password/reset');
        }
        
        if ($newPassword !== $confirmPassword) {
            Session::error('Password baru dan konfirmasi password tidak cocok');
            $this->redirect('/forgot-password/reset');
        }
        
        if (strlen($newPassword) < 6) {
            Session::error('Password minimal 6 karakter');
            $this->redirect('/forgot-password/reset');
        }
        
        try {
            // Get user data
            $user = $this->passwordResetModel->getUserByIdentifier($identifier);
            if (!$user) {
                Session::error('User tidak ditemukan');
                $this->redirect('/forgot-password');
            }
            
            // Update password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $updated = $this->userModel->update($user['id'], [
                'password' => $hashedPassword,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            
            if (!$updated) {
                Session::error('Gagal mengupdate password');
                $this->redirect('/forgot-password/reset');
            }
            
            // Send confirmation via WhatsApp
            if (!empty($user['phone'])) {
                $this->whatsappService->sendPasswordResetConfirmation(
                    $user['phone'],
                    $user['full_name']
                );
            }
            
            // Clean up session
            Session::remove('reset_token');
            Session::remove('reset_identifier');
            Session::remove('reset_phone');
            
            // Clean expired reset requests
            $this->passwordResetModel->cleanExpired();
            
            Session::success('Password berhasil direset! Silakan login dengan password baru.');
            $this->redirect('/login');
            
        } catch (Exception $e) {
            Session::error('Terjadi kesalahan: ' . $e->getMessage());
            $this->redirect('/forgot-password/reset');
        }
    }
    
    /**
     * Generate 6-digit OTP
     * 
     * @return string
     */
    private function generateOTP()
    {
        return str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
    }
    
    /**
     * Mask phone number for display
     * 
     * @param string $phone
     * @return string
     */
    private function maskPhoneNumber($phone)
    {
        if (strlen($phone) < 4) {
            return $phone;
        }
        
        $start = substr($phone, 0, 4);
        $end = substr($phone, -2);
        $middle = str_repeat('*', strlen($phone) - 6);
        
        return $start . $middle . $end;
    }
}