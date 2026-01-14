<?php

use App\Core\Controller;
use App\Core\Session;
use App\Middleware\RoleMiddleware;

/**
 * Student Controller
 * Controller untuk mahasiswa
 */
class StudentController extends Controller
{
    private $announcementModel;
    private $categoryModel;
    
    public function __construct()
    {
        parent::__construct();
        RoleMiddleware::requireStudent();
        
        $this->announcementModel = new Announcement();
        $this->categoryModel = new Category();
    }
    
    public function dashboard()
    {
        $user = [
            'id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'role' => $_SESSION['role'],
            'full_name' => $_SESSION['full_name'],
            'nim_nip' => $_SESSION['nim_nip'] ?? $_SESSION['username']
        ];
        
        $this->view('student.dashboard', [
            'page_title' => 'Dashboard Mahasiswa',
            'user' => $user,
            'stats' => $this->getDashboardStats(),
            'active_menu' => 'dashboard'
        ], 'student');
    }
    
    public function announcements()
    {
        $search = $this->getInput('search');
        $categorySlug = $this->getInput('category');
        
        // Get announcements from database
        if ($search) {
            $result = $this->announcementModel->search($search);
            $announcements = $result['data'] ?? [];
        } elseif ($categorySlug) {
            // Find category by slug first
            $category = $this->categoryModel->whereFirst('slug', $categorySlug);
            if ($category) {
                $announcements = $this->announcementModel->getByCategoryWithDetails($category['id']);
            } else {
                $announcements = [];
            }
        } else {
            $announcements = $this->announcementModel->getAllActive();
        }
        
        // Get categories for filter
        $categories = $this->categoryModel->getActiveCategories();
        
        $this->view('student.announcements', [
            'page_title' => 'Semua Pengumuman',
            'announcements' => $announcements ?? [],
            'categories' => $categories ?? [],
            'current_search' => $search,
            'current_category' => $categorySlug,
            'active_menu' => 'announcements'
        ], 'student');
    }
    
    public function announcementDetail()
    {
        $id = (int)$this->getInput('id');
        
        $announcement = $this->announcementModel->find($id);
        if (!$announcement) {
            Session::error('Pengumuman tidak ditemukan');
            $this->redirect('/student/announcements');
        }
        
        // Increment view count
        $this->announcementModel->incrementViews($id);
        
        // Get related announcements
        $related = [];
        if ($announcement['category_id'] ?? false) {
            $related = $this->announcementModel->getByCategory($announcement['category_id']);
            // Remove current announcement and limit to 3
            $related = array_filter($related, function($item) use ($id) {
                return $item['id'] != $id;
            });
            $related = array_slice($related, 0, 3);
        }
        
        $this->view('student.announcement-detail', [
            'page_title' => $announcement['title'],
            'announcement' => $announcement,
            'related' => $related
        ], 'student');
    }
    
    public function profile()
    {
        $userModel = new User();
        $user = $userModel->find($_SESSION['user_id']);
        
        $this->view('student.profile', [
            'page_title' => 'Profil Mahasiswa',
            'user' => $user,
            'active_menu' => 'profile'
        ], 'student');
    }
    
    public function updateProfile()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/student/profile');
        }
        
        $full_name = trim($this->getInput('full_name') ?? '');
        $email = trim($this->getInput('email') ?? '');
        $phone = trim($this->getInput('phone') ?? '');
        $program_studi = trim($this->getInput('program_studi') ?? '');
        $alamat = trim($this->getInput('alamat') ?? '');
        
        // Validation
        if (empty($full_name)) {
            Session::error('Nama lengkap harus diisi');
            $this->redirect('/student/profile');
        }
        
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Session::error('Email tidak valid');
            $this->redirect('/student/profile');
        }
        
        try {
            $userModel = new User();
            
            // Check if email exists (excluding current user)
            if ($userModel->isEmailExists($email, $_SESSION['user_id'])) {
                Session::error('Email sudah digunakan');
                $this->redirect('/student/profile');
            }
            
            $data = [
                'full_name' => $full_name,
                'email' => $email,
                'phone' => $phone ?: null,
                'program_studi' => $program_studi ?: null,
                'alamat' => $alamat ?: null,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            $updated = $userModel->update($_SESSION['user_id'], $data);
            if ($updated) {
                // Update session data
                $_SESSION['full_name'] = $full_name;
                Session::success('Profil berhasil diperbarui!');
                $this->redirect('/student/profile');
            } else {
                Session::error('Gagal memperbarui profil');
                $this->redirect('/student/profile');
            }
        } catch (Exception $e) {
            Session::error('Terjadi kesalahan: ' . $e->getMessage());
            $this->redirect('/student/profile');
        }
    }
    
    private function getDashboardStats()
    {
        // Get user academic status from database
        $userModel = new User();
        $user = $userModel->find($_SESSION['user_id']);
        
        // Determine academic status based on user data
        $academicStatus = 'Aktif'; // Default
        if ($user) {
            if (isset($user['academic_status'])) {
                $academicStatus = $user['academic_status'];
            } elseif (isset($user['is_active'])) {
                $academicStatus = $user['is_active'] ? 'Aktif' : 'Nonaktif';
            }
        }
        
        // Enhanced stats for student dashboard
        $allAnnouncements = $this->announcementModel->getAllActive() ?? [];
        $recentAnnouncements = $this->announcementModel->getRecent(3) ?? [];
        
        // Calculate new announcements (last 7 days)
        $newAnnouncements = array_filter($allAnnouncements, function($announcement) {
            $publishDate = strtotime($announcement['publish_date'] ?? '');
            $weekAgo = strtotime('-7 days');
            return $publishDate && $publishDate >= $weekAgo;
        });
        
        // Get category statistics
        $categories = $this->categoryModel->getActiveCategories() ?? [];
        $categoryCounts = [];
        $categoryNew = [];
        
        foreach ($categories as $category) {
            $categoryAnnouncements = $this->announcementModel->getByCategory($category['id']) ?? [];
            $categoryCounts[$category['slug']] = count($categoryAnnouncements);
            
            // Count new announcements in this category
            $categoryNewCount = array_filter($categoryAnnouncements, function($announcement) {
                $publishDate = strtotime($announcement['publish_date'] ?? '');
                $weekAgo = strtotime('-7 days');
                return $publishDate && $publishDate >= $weekAgo;
            });
            $categoryNew[$category['slug']] = count($categoryNewCount);
        }
        
        return [
            'new_announcements' => count($newAnnouncements),
            'total_announcements' => count($allAnnouncements),
            'academic_status' => $academicStatus,
            'categories' => $categoryCounts, // Fixed key name for consistency
            'category_counts' => $categoryCounts,
            'category_new' => $categoryNew,
            'recent_announcements' => $recentAnnouncements,
            'user_info' => $user
        ];
    }
}