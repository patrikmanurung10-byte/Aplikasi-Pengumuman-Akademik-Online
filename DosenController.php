<?php

use App\Core\Controller;
use App\Core\Session;
use App\Middleware\RoleMiddleware;

/**
 * Dosen Controller
 * Controller khusus untuk dosen
 */
class DosenController extends Controller
{
    private $announcementModel;
    private $categoryModel;
    
    public function __construct()
    {
        parent::__construct();
        RoleMiddleware::requireDosen();
        
        $this->announcementModel = new Announcement();
        $this->categoryModel = new Category();
    }
    
    public function dashboard()
    {
        $user = [
            'id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'role' => $_SESSION['role'],
            'full_name' => $_SESSION['full_name']
        ];
        
        $this->view('dosen.dashboard', [
            'page_title' => 'Dashboard Dosen',
            'user' => $user,
            'stats' => $this->getDashboardStats(),
            'active_menu' => 'dashboard'
        ], 'dosen');
    }
    
    public function announcements()
    {
        $announcements = $this->announcementModel->getAllWithAuthor();
        
        $this->view('dosen.announcements', [
            'page_title' => 'Semua Pengumuman',
            'announcements' => $announcements ?? [],
            'active_menu' => 'announcements'
        ], 'dosen');
    }
    
    public function createAnnouncement()
    {
        $categories = $this->categoryModel->getActiveCategories();
        
        $this->view('dosen.create-announcement', [
            'page_title' => 'Tambah Pengumuman',
            'categories' => $categories ?? [],
            'active_menu' => 'announcements'
        ], 'dosen');
    }
    
    public function storeAnnouncement()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/dosen/announcements/create');
        }
        
        // Get form inputs
        $title = trim($this->getInput('title') ?? '');
        $content = trim($this->getInput('content') ?? '');
        $category_id = $this->getInput('category_id');
        $priority = $this->getInput('priority', 'medium');
        $status = $this->getInput('status', 'published');
        $publish_date = $this->getInput('publish_date');
        $expire_date = $this->getInput('expire_date');
        $is_featured = $this->getInput('is_featured') ? 1 : 0;
        $is_pinned = $this->getInput('is_pinned') ? 1 : 0;
        
        // Store old input for form repopulation
        Session::setOldInput($this->getInput());
        
        // Validation
        if (empty($title) || empty($content)) {
            Session::error('Judul dan konten harus diisi');
            $this->redirect('/dosen/announcements/create');
        }
        
        if (strlen($title) > 255) {
            Session::error('Judul terlalu panjang (maksimal 255 karakter)');
            $this->redirect('/dosen/announcements/create');
        }
        
        if (strlen($content) > 5000) {
            Session::error('Konten terlalu panjang (maksimal 5000 karakter)');
            $this->redirect('/dosen/announcements/create');
        }
        
        try {
            // Prepare data
            $data = [
                'title' => $title,
                'content' => $content,
                'category_id' => !empty($category_id) ? (int)$category_id : null,
                'author_id' => $_SESSION['user_id'],
                'status' => $status,
                'priority' => $priority,
                'target_audience' => 'all', // Default to all users
                'is_active' => 1,
                'is_featured' => $is_featured,
                'is_pinned' => $is_pinned
            ];
            
            // Handle publish date
            if (!empty($publish_date)) {
                $data['publish_date'] = date('Y-m-d H:i:s', strtotime($publish_date));
            } else {
                $data['publish_date'] = date('Y-m-d H:i:s');
            }
            
            // Handle expire date
            if (!empty($expire_date)) {
                $data['expire_date'] = date('Y-m-d H:i:s', strtotime($expire_date));
            }
            
            // Create announcement
            $id = $this->announcementModel->createAnnouncement($data);
            
            if ($id) {
                // Clear old input on success
                Session::setOldInput([]);
                
                $statusText = $status === 'published' ? 'dipublikasikan' : 'disimpan sebagai draft';
                Session::success("Pengumuman berhasil {$statusText}!");
                $this->redirect('/dosen/announcements');
            } else {
                Session::error('Gagal menambahkan pengumuman');
                $this->redirect('/dosen/announcements/create');
            }
            
        } catch (Exception $e) {
            Session::error('Terjadi kesalahan: ' . $e->getMessage());
            $this->redirect('/dosen/announcements/create');
        }
    }
    
    public function profile()
    {
        $userModel = new User();
        $user = $userModel->find($_SESSION['user_id']);
        
        $this->view('dosen.profile', [
            'page_title' => 'Profil Dosen',
            'user' => $user,
            'active_menu' => 'profile'
        ], 'dosen');
    }
    
    public function updateProfile()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/dosen/profile');
        }
        
        $full_name = trim($this->getInput('full_name') ?? '');
        $email = trim($this->getInput('email') ?? '');
        $phone = trim($this->getInput('phone') ?? '');
        $program_studi = trim($this->getInput('program_studi') ?? '');
        
        if (empty($full_name)) {
            Session::error('Nama lengkap harus diisi');
            $this->redirect('/dosen/profile');
        }
        
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Session::error('Email tidak valid');
            $this->redirect('/dosen/profile');
        }
        
        try {
            $userModel = new User();
            
            if ($userModel->isEmailExists($email, $_SESSION['user_id'])) {
                Session::error('Email sudah digunakan');
                $this->redirect('/dosen/profile');
            }
            
            $data = [
                'full_name' => $full_name,
                'email' => $email,
                'phone' => $phone ?: null,
                'program_studi' => $program_studi ?: null,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            $updated = $userModel->update($_SESSION['user_id'], $data);
            
            if ($updated) {
                $_SESSION['full_name'] = $full_name;
                Session::success('Profil berhasil diperbarui!');
            } else {
                Session::error('Gagal memperbarui profil');
            }
        } catch (Exception $e) {
            Session::error('Terjadi kesalahan: ' . $e->getMessage());
        }
        
        $this->redirect('/dosen/profile');
    }
    
    public function editAnnouncement($id)
    {
        $announcement = $this->announcementModel->find($id);
        
        if (!$announcement) {
            Session::error('Pengumuman tidak ditemukan');
            $this->redirect('/dosen/announcements');
        }
        
        // Check if user is the author
        if ($announcement['author_id'] != $_SESSION['user_id']) {
            Session::error('Anda tidak memiliki akses untuk mengedit pengumuman ini');
            $this->redirect('/dosen/announcements');
        }
        
        $categories = $this->categoryModel->getActiveCategories();
        
        $this->view('dosen.edit-announcement', [
            'page_title' => 'Edit Pengumuman',
            'announcement' => $announcement,
            'categories' => $categories ?? [],
            'active_menu' => 'announcements'
        ], 'dosen');
    }
    
    public function updateAnnouncement($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/dosen/announcements');
        }
        
        $announcement = $this->announcementModel->find($id);
        
        if (!$announcement) {
            Session::error('Pengumuman tidak ditemukan');
            $this->redirect('/dosen/announcements');
        }
        
        // Check if user is the author
        if ($announcement['author_id'] != $_SESSION['user_id']) {
            Session::error('Anda tidak memiliki akses untuk mengedit pengumuman ini');
            $this->redirect('/dosen/announcements');
        }
        
        $title = trim($this->getInput('title') ?? '');
        $content = trim($this->getInput('content') ?? '');
        $category_id = $this->getInput('category_id');
        $priority = $this->getInput('priority', 'medium');
        
        if (empty($title) || empty($content)) {
            Session::error('Judul dan konten harus diisi');
            $this->redirect('/dosen/announcements/' . $id . '/edit');
        }
        
        try {
            $data = [
                'title' => $title,
                'content' => $content,
                'category_id' => !empty($category_id) ? (int)$category_id : null,
                'priority' => $priority,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            $updated = $this->announcementModel->update($id, $data);
            if ($updated) {
                Session::success('Pengumuman berhasil diperbarui!');
                $this->redirect('/dosen/announcements');
            } else {
                Session::error('Gagal memperbarui pengumuman');
                $this->redirect('/dosen/announcements/' . $id . '/edit');
            }
        } catch (Exception $e) {
            Session::error('Terjadi kesalahan: ' . $e->getMessage());
            $this->redirect('/dosen/announcements/' . $id . '/edit');
        }
    }
    
    public function deleteAnnouncement($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/dosen/announcements');
        }
        
        $announcement = $this->announcementModel->find($id);
        
        if (!$announcement) {
            Session::error('Pengumuman tidak ditemukan');
            $this->redirect('/dosen/announcements');
        }
        
        // Check if user is the author
        if ($announcement['author_id'] != $_SESSION['user_id']) {
            Session::error('Anda tidak memiliki akses untuk menghapus pengumuman ini');
            $this->redirect('/dosen/announcements');
        }
        
        try {
            $deleted = $this->announcementModel->delete($id);
            if ($deleted) {
                Session::success('Pengumuman berhasil dihapus!');
            } else {
                Session::error('Gagal menghapus pengumuman');
            }
        } catch (Exception $e) {
            Session::error('Terjadi kesalahan: ' . $e->getMessage());
        }
        
        $this->redirect('/dosen/announcements');
    }
    
    private function getDashboardStats()
    {
        $userModel = new User();
        $user = $userModel->find($_SESSION['user_id']);
        
        $allAnnouncements = $this->announcementModel->getAllWithAuthor();
        $myAnnouncements = $this->announcementModel->getByAuthor($_SESSION['user_id']);
        
        return [
            'my_total_announcements' => count($myAnnouncements),
            'recent_announcements' => array_slice($allAnnouncements, 0, 5),
            'user_info' => $user,
            'academic_status' => $user['academic_status'] ?? 'Aktif'
        ];
    }
}