<?php

use App\Core\Controller;
use App\Core\Session;

/**
 * Help Controller
 * Controller untuk bantuan dan FAQ
 */
class HelpController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
    }
    
    public function index()
    {
        $faqs = $this->getFAQs();
        
        $this->view('help.index', [
            'page_title' => 'Bantuan',
            'faqs' => $faqs
        ]);
    }
    
    public function contact()
    {
        $this->view('help.contact', [
            'page_title' => 'Hubungi Kami'
        ]);
    }
    
    public function submitContact()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/help/contact');
        }
        
        $name = $this->sanitize($this->getInput('name'));
        $email = $this->sanitize($this->getInput('email'));
        $subject = $this->sanitize($this->getInput('subject'));
        $message = $this->sanitize($this->getInput('message'));
        
        if (empty($name) || empty($email) || empty($subject) || empty($message)) {
            $this->back('Semua field harus diisi', 'warning');
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->back('Format email tidak valid', 'warning');
        }
        
        try {
            // In a real application, you would send email or save to database
            // For now, we'll just show a success message
            $this->redirect('/help/contact', 'Pesan Anda telah dikirim. Tim support akan menghubungi Anda segera.', 'success');
        } catch (Exception $e) {
            $this->back('Gagal mengirim pesan: ' . $e->getMessage(), 'error');
        }
    }
    
    public function userGuide()
    {
        $userRole = $_SESSION['role'] ?? 'mahasiswa';
        
        $this->view('help.user-guide', [
            'page_title' => 'Panduan Pengguna',
            'user_role' => $userRole
        ]);
    }
    
    public function systemInfo()
    {
        // Only allow admin to view system info
        if (!in_array($_SESSION['role'] ?? '', ['admin', 'dosen'])) {
            $this->redirect('/help', 'Akses ditolak', 'error');
        }
        
        $systemInfo = [
            'php_version' => PHP_VERSION,
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'document_root' => $_SERVER['DOCUMENT_ROOT'] ?? 'Unknown',
            'server_name' => $_SERVER['SERVER_NAME'] ?? 'Unknown',
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size')
        ];
        
        $this->view('help.system-info', [
            'page_title' => 'Informasi Sistem',
            'system_info' => $systemInfo
        ]);
    }
    
    private function getFAQs()
    {
        return [
            [
                'question' => 'Bagaimana cara mengubah password?',
                'answer' => 'Anda dapat mengubah password melalui menu Profil Saya > Ubah Password. Masukkan password lama dan password baru, lalu klik Simpan.'
            ],
            [
                'question' => 'Bagaimana cara melihat pengumuman terbaru?',
                'answer' => 'Pengumuman terbaru dapat dilihat di Dashboard atau melalui menu Pengumuman. Anda juga akan mendapat notifikasi untuk pengumuman baru.'
            ],
            [
                'question' => 'Apa yang harus dilakukan jika lupa password?',
                'answer' => 'Klik "Lupa Password" di halaman login, masukkan email Anda, dan ikuti instruksi yang dikirim ke email untuk reset password.'
            ],
            [
                'question' => 'Bagaimana cara mengubah informasi profil?',
                'answer' => 'Masuk ke menu Profil Saya, edit informasi yang ingin diubah seperti nama lengkap, email, atau program studi, lalu klik Simpan Perubahan.'
            ],
            [
                'question' => 'Mengapa saya tidak menerima notifikasi?',
                'answer' => 'Pastikan pengaturan notifikasi Anda aktif di menu Pengaturan. Periksa juga folder spam di email Anda untuk notifikasi email.'
            ],
            [
                'question' => 'Bagaimana cara menghubungi admin?',
                'answer' => 'Anda dapat menghubungi admin melalui menu Bantuan > Hubungi Kami atau langsung mengirim email ke admin@polibatam.ac.id.'
            ],
            [
                'question' => 'Apakah data saya aman?',
                'answer' => 'Ya, semua data Anda dienkripsi dan disimpan dengan aman. Kami mengikuti standar keamanan yang ketat untuk melindungi informasi pribadi Anda.'
            ],
            [
                'question' => 'Bagaimana cara logout dari sistem?',
                'answer' => 'Klik menu dropdown di pojok kanan atas (nama Anda), lalu pilih Logout. Pastikan untuk selalu logout setelah selesai menggunakan sistem.'
            ]
        ];
    }
}