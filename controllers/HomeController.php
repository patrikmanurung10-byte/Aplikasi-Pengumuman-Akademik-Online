<?php

use App\Core\Controller;
use App\Core\Session;

/**
 * Home Controller
 * Controller untuk halaman utama dan landing page
 */
class HomeController extends Controller
{
    public function index()
    {
        // If user is already logged in, redirect to appropriate dashboard
        if (Session::isLoggedIn()) {
            $user = Session::user();
            $redirectUrl = in_array($user['role'], ['admin', 'dosen']) ? '/admin/dashboard' : '/dashboard';
            $this->redirect($redirectUrl);
        }
        
        $this->view('home.index', [
            'page_title' => 'Aplikasi Pengumuman Akademik Online',
            'meta_description' => 'APAO Polibatam - Platform digital untuk akses informasi akademik dan pengumuman resmi Politeknik Negeri Batam',
            'body_class' => 'landing-page'
        ], 'landing');
    }
    
    public function about()
    {
        $this->view('home.about', [
            'page_title' => 'Tentang APAO Polibatam'
        ]);
    }
    
    public function contact()
    {
        $this->view('home.contact', [
            'page_title' => 'Kontak'
        ]);
    }
    
    public function privacy()
    {
        $this->view('home.privacy', [
            'page_title' => 'Kebijakan Privasi'
        ]);
    }
    
    public function terms()
    {
        $this->view('home.terms', [
            'page_title' => 'Syarat dan Ketentuan'
        ]);
    }
}