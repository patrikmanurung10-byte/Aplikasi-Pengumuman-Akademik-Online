<?php

use App\Core\Controller;
use App\Core\Session;
use App\Middleware\RoleMiddleware;

/**
 * Admin Controller
 * Controller untuk admin - fokus pada manajemen user
 */
class AdminController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        RoleMiddleware::requireAdminOnly(); // Hanya admin yang bisa akses
    }
    
    public function dashboard()
    {
        $this->view('admin.dashboard', [
            'page_title' => 'Dashboard Admin',
            'stats' => $this->getDashboardStats(),
            'active_menu' => 'dashboard'
        ], 'admin');
    }
    
    // =====================================================
    // USER MANAGEMENT - SIMPLIFIED
    // =====================================================
    
    public function users()
    {
        $userModel = new User();
        $page = (int)($this->getInput('page') ?? 1);
        $search = trim($this->getInput('search') ?? '');
        $role = $this->getInput('role');
        $status = $this->getInput('status');
        $perPage = 15;
        
        try {
            $conditions = [];
            
            // Add role filter
            if (!empty($role) && in_array($role, ['admin', 'dosen', 'mahasiswa'])) {
                $conditions['role'] = $role;
            }
            
            // Add status filter
            if (!empty($status) && in_array($status, ['active', 'inactive'])) {
                $conditions['is_active'] = $status === 'active' ? 1 : 0;
            }
            
            if (!empty($search)) {
                $result = $userModel->search($search, ['username', 'email', 'full_name'], $page, $perPage, $conditions);
            } else {
                $result = $userModel->paginate($page, $perPage, $conditions, 'created_at DESC');
            }
            
            $this->view('admin.users', [
                'page_title' => 'Kelola Pengguna',
                'users' => $result['data'] ?? [],
                'pagination' => $result['pagination'] ?? [],
                'search' => $search,
                'role' => $role,
                'status' => $status,
                'active_menu' => 'users'
            ], 'admin');
            
        } catch (Exception $e) {
            Session::error('Terjadi kesalahan: ' . $e->getMessage());
            $this->redirect('/admin/dashboard');
        }
    }
    
    public function createUser()
    {
        $this->view('admin.create-user', [
            'page_title' => 'Tambah Pengguna',
            'active_menu' => 'users'
        ], 'admin');
    }
    
    public function storeUser()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/users/create');
        }
        
        $username = trim($this->getInput('username') ?? '');
        $email = trim($this->getInput('email') ?? '');
        $password = $this->getInput('password');
        $full_name = trim($this->getInput('full_name') ?? '');
        $role = $this->getInput('role');
        
        // Basic validation
        if (empty($username) || empty($email) || empty($password) || empty($full_name) || empty($role)) {
            Session::error('Semua field harus diisi');
            $this->redirect('/admin/users/create');
        }
        
        if (!in_array($role, ['admin', 'dosen', 'mahasiswa'])) {
            Session::error('Role tidak valid');
            $this->redirect('/admin/users/create');
        }
        
        try {
            $userModel = new User();
            $data = [
                'username' => $username,
                'email' => $email,
                'password' => $password, // Will be hashed in createUser method
                'full_name' => $full_name,
                'role' => $role,
                'is_active' => 1
            ];
            
            $userId = $userModel->createUser($data);
            if ($userId) {
                Session::success('Pengguna berhasil ditambahkan!');
                $this->redirect('/admin/users');
            } else {
                Session::error('Gagal menambahkan pengguna');
                $this->redirect('/admin/users/create');
            }
        } catch (Exception $e) {
            Session::error('Terjadi kesalahan: ' . $e->getMessage());
            $this->redirect('/admin/users/create');
        }
    }
    
    public function deleteUser()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/users');
        }
        
        $id = $this->getInput('id');
        
        // Prevent deleting current user
        if ($id == $_SESSION['user_id']) {
            Session::error('Tidak dapat menghapus akun sendiri');
            $this->redirect('/admin/users');
        }
        
        try {
            $userModel = new User();
            $deleted = $userModel->delete($id);
            
            if ($deleted) {
                Session::success('Pengguna berhasil dihapus!');
            } else {
                Session::error('Gagal menghapus pengguna');
            }
        } catch (Exception $e) {
            Session::error('Terjadi kesalahan: ' . $e->getMessage());
        }
        
        $this->redirect('/admin/users');
    }
    
    public function showUser()
    {
        $id = $this->getInput('id');
        
        if (empty($id)) {
            Session::error('ID pengguna tidak valid');
            $this->redirect('/admin/users');
        }
        
        try {
            $userModel = new User();
            $user = $userModel->find($id);
            
            if (!$user) {
                Session::error('Pengguna tidak ditemukan');
                $this->redirect('/admin/users');
            }
            
            $this->view('admin.show-user', [
                'page_title' => 'Detail Pengguna',
                'user' => $user,
                'active_menu' => 'users'
            ], 'admin');
        } catch (Exception $e) {
            Session::error('Terjadi kesalahan: ' . $e->getMessage());
            $this->redirect('/admin/users');
        }
    }
    
    /**
     * Get user details for AJAX request
     */
    public function getUserDetails()
    {
        header('Content-Type: application/json');
        
        $id = $this->getInput('id');
        
        if (empty($id)) {
            echo json_encode([
                'success' => false,
                'message' => 'ID pengguna tidak valid'
            ]);
            return;
        }
        
        try {
            $userModel = new User();
            $user = $userModel->find($id);
            
            if (!$user) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Pengguna tidak ditemukan'
                ]);
                return;
            }
            
            // Convert boolean to proper format
            $user['is_active'] = (bool)$user['is_active'];
            
            echo json_encode([
                'success' => true,
                'user' => $user
            ]);
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }
    
    public function editUser()
    {
        $id = $this->getInput('id');
        
        if (empty($id)) {
            Session::error('ID pengguna tidak valid');
            $this->redirect('/admin/users');
        }
        
        try {
            $userModel = new User();
            $user = $userModel->find($id);
            
            if (!$user) {
                Session::error('Pengguna tidak ditemukan');
                $this->redirect('/admin/users');
            }
            
            $this->view('admin.edit-user', [
                'page_title' => 'Edit Pengguna',
                'user' => $user,
                'active_menu' => 'users'
            ], 'admin');
        } catch (Exception $e) {
            Session::error('Terjadi kesalahan: ' . $e->getMessage());
            $this->redirect('/admin/users');
        }
    }
    
    public function updateUser()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/users');
        }
        
        $id = $this->getInput('id'); // Get ID from route parameter
        $username = trim($this->getInput('username') ?? '');
        $full_name = trim($this->getInput('full_name') ?? '');
        $email = trim($this->getInput('email') ?? '');
        $phone = trim($this->getInput('phone') ?? '');
        $role = $this->getInput('role');
        $is_active = $this->getInput('is_active', 0);
        $password = trim($this->getInput('password') ?? '');
        $nim_nip = trim($this->getInput('nim_nip') ?? ''); // Use single field
        $program_studi = trim($this->getInput('program_studi') ?? '');
        $academic_status = $this->getInput('academic_status', 'Aktif');
        
        // Validation
        if (empty($username) || empty($full_name) || empty($email) || empty($role)) {
            Session::error('Semua field wajib harus diisi');
            $this->redirect('/admin/users/' . $id . '/edit');
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Session::error('Format email tidak valid');
            $this->redirect('/admin/users/' . $id . '/edit');
        }
        
        if (!in_array($role, ['admin', 'dosen', 'mahasiswa'])) {
            Session::error('Role tidak valid');
            $this->redirect('/admin/users/' . $id . '/edit');
        }
        
        try {
            $userModel = new User();
            
            // Check if username exists (excluding current user)
            if ($userModel->isUsernameExists($username, $id)) {
                Session::error('Username sudah digunakan');
                $this->redirect('/admin/users/' . $id . '/edit');
            }
            
            // Check if email exists (excluding current user)
            if ($userModel->isEmailExists($email, $id)) {
                Session::error('Email sudah digunakan');
                $this->redirect('/admin/users/' . $id . '/edit');
            }
            
            $data = [
                'username' => $username,
                'full_name' => $full_name,
                'email' => $email,
                'phone' => $phone,
                'role' => $role,
                'is_active' => (int)$is_active,
                'academic_status' => $academic_status,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            // Add NIM/NIP based on role
            if ($role === 'mahasiswa') {
                $data['nim_nip'] = $nim_nip;
                $data['program_studi'] = $program_studi;
            } else {
                // Admin/Dosen role - clear program_studi for admin
                $data['nim_nip'] = $nim_nip; // NIP untuk dosen
                $data['program_studi'] = ($role === 'dosen') ? $program_studi : null;
            }
            
            // Update password if provided
            if (!empty($password)) {
                if (strlen($password) < 6) {
                    Session::error('Password minimal 6 karakter');
                    $this->redirect('/admin/users/' . $id . '/edit');
                    return;
                }
                $data['password'] = password_hash($password, PASSWORD_DEFAULT);
            }
            
            $updated = $userModel->update($id, $data);
            
            if ($updated) {
                Session::success('Data pengguna berhasil diperbarui!');
                $this->redirect('/admin/users');
            } else {
                Session::error('Gagal memperbarui data pengguna. Tidak ada perubahan yang disimpan.');
                $this->redirect('/admin/users/' . $id . '/edit');
            }
        } catch (Exception $e) {
            Session::error('Terjadi kesalahan: ' . $e->getMessage());
            $this->redirect('/admin/users/' . $id . '/edit');
        }
    }
    
    // =====================================================
    // PROFILE MANAGEMENT
    // =====================================================
    
    public function profile()
    {
        $userModel = new User();
        $user = $userModel->find($_SESSION['user_id']);
        
        $this->view('admin.profile', [
            'page_title' => 'Profil Administrator',
            'user' => $user,
            'active_menu' => 'profile'
        ], 'admin');
    }
    
    public function updateProfile()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/profile');
        }
        
        $full_name = trim($this->getInput('full_name') ?? '');
        $email = trim($this->getInput('email') ?? '');
        $phone = trim($this->getInput('phone') ?? '');
        
        // Validation
        if (empty($full_name)) {
            Session::error('Nama lengkap harus diisi');
            $this->redirect('/admin/profile');
        }
        
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Session::error('Email tidak valid');
            $this->redirect('/admin/profile');
        }
        
        try {
            $userModel = new User();
            
            // Check if email exists (excluding current user)
            if ($userModel->isEmailExists($email, $_SESSION['user_id'])) {
                Session::error('Email sudah digunakan');
                $this->redirect('/admin/profile');
            }
            
            $updated = $userModel->update($_SESSION['user_id'], [
                'full_name' => $full_name,
                'email' => $email,
                'phone' => $phone,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            
            if ($updated) {
                // Update session data
                $_SESSION['full_name'] = $full_name;
                Session::success('Profil berhasil diperbarui!');
            } else {
                Session::error('Gagal memperbarui profil');
            }
        } catch (Exception $e) {
            Session::error('Terjadi kesalahan: ' . $e->getMessage());
        }
        
        $this->redirect('/admin/profile');
    }
    
    // =====================================================
    // DASHBOARD STATS - SIMPLIFIED
    // =====================================================
    
    private function getDashboardStats()
    {
        try {
            $announcementModel = new Announcement();
            $userModel = new User();
            
            // Get basic counts
            $allUsers = $userModel->all();
            $allAnnouncements = $announcementModel->all();
            
            $totalUsers = count($allUsers);
            $totalAnnouncements = count($allAnnouncements);
            
            // Count by role
            $mahasiswa = count(array_filter($allUsers, fn($user) => $user['role'] === 'mahasiswa'));
            $dosen = count(array_filter($allUsers, fn($user) => $user['role'] === 'dosen'));
            $admin = count(array_filter($allUsers, fn($user) => $user['role'] === 'admin'));
            
            // Recent data
            $recentUsers = array_slice(array_reverse($allUsers), 0, 5);
            $recentAnnouncements = array_slice(array_reverse($allAnnouncements), 0, 5);
            
            return [
                'total_users' => $totalUsers,
                'total_announcements' => $totalAnnouncements,
                'user_stats' => [
                    'total_mahasiswa' => $mahasiswa,
                    'total_dosen' => $dosen,
                    'total_admin' => $admin,
                    'active_users' => count(array_filter($allUsers, fn($user) => $user['is_active'] == 1))
                ],
                'recent_users' => $recentUsers,
                'recent_announcements' => $recentAnnouncements
            ];
        } catch (Exception $e) {
            return [
                'total_users' => 0,
                'total_announcements' => 0,
                'user_stats' => [
                    'total_mahasiswa' => 0,
                    'total_dosen' => 0,
                    'total_admin' => 0,
                    'active_users' => 0
                ],
                'recent_users' => [],
                'recent_announcements' => []
            ];
        }
    }
    
    // =====================================================
    // ANNOUNCEMENT MANAGEMENT
    // =====================================================
    
    public function announcements()
    {
        $announcementModel = new Announcement();
        $page = (int)($this->getInput('page') ?? 1);
        $search = $this->getInput('search');
        $perPage = 15;
        
        try {
            if (!empty($search)) {
                $result = $announcementModel->search($search, ['title', 'content'], $page, $perPage);
            } else {
                $result = $announcementModel->paginate($page, $perPage, [], 'created_at DESC');
            }
            
            $this->view('admin.announcements', [
                'page_title' => 'Kelola Pengumuman',
                'announcements' => $result['data'] ?? [],
                'pagination' => $result['pagination'] ?? [],
                'search' => $search,
                'active_menu' => 'announcements'
            ], 'admin');
            
        } catch (Exception $e) {
            Session::error('Terjadi kesalahan: ' . $e->getMessage());
            $this->redirect('/admin/dashboard');
        }
    }
    
    public function createAnnouncement()
    {
        $categoryModel = new Category();
        $categories = $categoryModel->all();
        
        $this->view('admin.create-announcement', [
            'page_title' => 'Tambah Pengumuman',
            'categories' => $categories ?? [],
            'active_menu' => 'announcements'
        ], 'admin');
    }
    
    public function storeAnnouncement()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/announcements/create');
        }
        
        $title = trim($this->getInput('title') ?? '');
        $content = trim($this->getInput('content') ?? '');
        $category_id = $this->getInput('category_id');
        $priority = $this->getInput('priority', 'medium');
        
        // Validation
        if (empty($title) || empty($content)) {
            Session::error('Judul dan konten harus diisi');
            $this->redirect('/admin/announcements/create');
        }
        
        try {
            $announcementModel = new Announcement();
            $data = [
                'title' => $title,
                'content' => $content,
                'category_id' => !empty($category_id) ? (int)$category_id : null,
                'author_id' => $_SESSION['user_id'],
                'priority' => $priority,
                'status' => 'published',
                'is_active' => 1,
                'publish_date' => date('Y-m-d H:i:s')
            ];
            
            $id = $announcementModel->createAnnouncement($data);
            if ($id) {
                Session::success('Pengumuman berhasil ditambahkan!');
                $this->redirect('/admin/announcements');
            } else {
                Session::error('Gagal menambahkan pengumuman');
                $this->redirect('/admin/announcements/create');
            }
        } catch (Exception $e) {
            Session::error('Terjadi kesalahan: ' . $e->getMessage());
            $this->redirect('/admin/announcements/create');
        }
    }
    
    public function editAnnouncement()
    {
        $id = $this->getInput('id');
        $announcementModel = new Announcement();
        $categoryModel = new Category();
        
        $announcement = $announcementModel->find($id);
        if (!$announcement) {
            Session::error('Pengumuman tidak ditemukan');
            $this->redirect('/admin/announcements');
        }
        
        $categories = $categoryModel->all();
        
        $this->view('admin.edit-announcement', [
            'page_title' => 'Edit Pengumuman',
            'announcement' => $announcement,
            'categories' => $categories ?? [],
            'active_menu' => 'announcements'
        ], 'admin');
    }
    
    public function updateAnnouncement()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/announcements');
        }
        
        $id = $this->getInput('id');
        $title = trim($this->getInput('title') ?? '');
        $content = trim($this->getInput('content') ?? '');
        $category_id = $this->getInput('category_id');
        $priority = $this->getInput('priority', 'medium');
        
        if (empty($title) || empty($content)) {
            Session::error('Judul dan konten harus diisi');
            $this->redirect('/admin/announcements/' . $id . '/edit');
        }
        
        try {
            $announcementModel = new Announcement();
            
            $data = [
                'title' => $title,
                'content' => $content,
                'category_id' => !empty($category_id) ? (int)$category_id : null,
                'priority' => $priority,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            $updated = $announcementModel->update($id, $data);
            if ($updated) {
                Session::success('Pengumuman berhasil diperbarui!');
                $this->redirect('/admin/announcements');
            } else {
                Session::error('Gagal memperbarui pengumuman');
                $this->redirect('/admin/announcements/' . $id . '/edit');
            }
        } catch (Exception $e) {
            Session::error('Terjadi kesalahan: ' . $e->getMessage());
            $this->redirect('/admin/announcements/' . $id . '/edit');
        }
    }
    
    public function deleteAnnouncement()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/announcements');
        }
        
        $id = $this->getInput('id');
        
        try {
            $announcementModel = new Announcement();
            $deleted = $announcementModel->delete($id);
            
            if ($deleted) {
                Session::success('Pengumuman berhasil dihapus!');
            } else {
                Session::error('Gagal menghapus pengumuman');
            }
        } catch (Exception $e) {
            Session::error('Terjadi kesalahan: ' . $e->getMessage());
        }
        
        $this->redirect('/admin/announcements');
    }
}
