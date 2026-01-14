<?php
// Base Layout Template - Konsisten untuk semua role
$role = $_SESSION['role'] ?? 'guest';
$user_name = $_SESSION['full_name'] ?? 'User';

// Role-specific configurations
$role_configs = [
    'admin' => [
        'title' => 'APAO Admin',
        'icon' => 'bi-shield-check',
        'color' => '#dc3545', // Red
        'bg_gradient' => 'linear-gradient(135deg, #dc3545 0%, #c82333 100%)',
        'menu_items' => [
            ['url' => '/admin/dashboard', 'icon' => 'bi-speedometer2', 'text' => 'Dashboard', 'key' => 'dashboard'],
            ['url' => '/admin/users', 'icon' => 'bi-people', 'text' => 'Kelola Pengguna', 'key' => 'users'],
            ['url' => '/admin/announcements', 'icon' => 'bi-megaphone', 'text' => 'Kelola Pengumuman', 'key' => 'announcements']
        ]
    ],
    'dosen' => [
        'title' => 'APAO Dosen',
        'icon' => 'bi-person-badge',
        'color' => '#0d6efd', // Blue
        'bg_gradient' => 'linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%)',
        'menu_items' => [
            ['url' => '/dosen/dashboard', 'icon' => 'bi-speedometer2', 'text' => 'Dashboard', 'key' => 'dashboard'],
            ['url' => '/dosen/announcements', 'icon' => 'bi-megaphone', 'text' => 'Pengumuman Saya', 'key' => 'announcements'],
            ['url' => '/dosen/create-announcement', 'icon' => 'bi-plus-circle', 'text' => 'Buat Pengumuman', 'key' => 'create-announcement'],
            ['divider' => true],
            ['url' => '/dosen/students', 'icon' => 'bi-mortarboard', 'text' => 'Data Mahasiswa', 'key' => 'students'],
            ['url' => '/dosen/analytics', 'icon' => 'bi-graph-up', 'text' => 'Analitik', 'key' => 'analytics']
        ]
    ],
    'mahasiswa' => [
        'title' => 'APAO Mahasiswa',
        'icon' => 'bi-mortarboard',
        'color' => '#198754', // Green
        'bg_gradient' => 'linear-gradient(135deg, #198754 0%, #157347 100%)',
        'menu_items' => [
            ['url' => '/student/dashboard', 'icon' => 'bi-speedometer2', 'text' => 'Dashboard', 'key' => 'dashboard'],
            ['url' => '/student/announcements', 'icon' => 'bi-megaphone', 'text' => 'Semua Pengumuman', 'key' => 'announcements']
        ]
    ]
];

$config = $role_configs[$role] ?? $role_configs['mahasiswa'];
$active_menu = $active_menu ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' . $config['title'] : $config['title']; ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo \App\Core\View::asset('images/logo_poltek.png'); ?>">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo \App\Core\View::asset('css/styles.css'); ?>">
    
    <style>
        :root {
            --primary-color: <?php echo $config['color']; ?>;
            --primary-gradient: <?php echo $config['bg_gradient']; ?>;
            --sidebar-width: 280px;
            --topbar-height: 70px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', sans-serif;
            background-color: #f8f9fc;
            color: #5a5c69;
            overflow-x: hidden;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--primary-gradient);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            transition: all 0.3s ease;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        
        .sidebar-brand {
            display: flex;
            align-items: center;
            padding: 1.5rem 1.25rem;
            color: #fff;
            text-decoration: none;
            font-weight: 700;
            font-size: 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-brand:hover {
            color: #fff;
            text-decoration: none;
        }
        
        .sidebar-brand i {
            font-size: 1.5rem;
            margin-right: 0.75rem;
        }
        
        .sidebar .nav {
            padding: 1rem 0;
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.875rem 1.25rem;
            margin: 0.125rem 0.75rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            font-weight: 500;
            display: flex;
            align-items: center;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.15);
            transform: translateX(5px);
        }
        
        .sidebar .nav-link i {
            margin-right: 0.75rem;
            width: 1.25rem;
            text-align: center;
            font-size: 1.1rem;
        }
        
        .sidebar-divider {
            border-color: rgba(255, 255, 255, 0.15);
            margin: 1rem 0.75rem;
        }
        
        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.3s ease;
        }
        
        /* Topbar */
        .topbar {
            background: #fff;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            padding: 1rem 1.5rem;
            position: sticky;
            top: 0;
            z-index: 999;
            height: var(--topbar-height);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .topbar-left {
            display: flex;
            align-items: center;
        }
        
        .topbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .sidebar-toggle {
            display: none;
            background: none;
            border: none;
            color: #5a5c69;
            font-size: 1.25rem;
            margin-right: 1rem;
        }
        
        /* Content Wrapper */
        .content-wrapper {
            padding: 1.5rem;
            min-height: calc(100vh - var(--topbar-height));
        }
        
        /* Cards */
        .card {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            border: none;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
        }
        
        .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
            padding: 1rem 1.25rem;
            font-weight: 600;
        }
        
        /* Border Colors */
        .border-left-primary { border-left: 0.25rem solid var(--primary-color) !important; }
        .border-left-success { border-left: 0.25rem solid #1cc88a !important; }
        .border-left-info { border-left: 0.25rem solid #36b9cc !important; }
        .border-left-warning { border-left: 0.25rem solid #f6c23e !important; }
        .border-left-danger { border-left: 0.25rem solid #e74a3b !important; }
        
        /* Text Colors */
        .text-primary { color: var(--primary-color) !important; }
        .text-gray-800 { color: #5a5c69 !important; }
        .text-gray-600 { color: #858796 !important; }
        .text-gray-300 { color: #dddfeb !important; }
        
        /* Buttons */
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            font-weight: 500;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            opacity: 0.9;
        }
        
        /* User Avatar */
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 600;
            font-size: 0.875rem;
        }
        
        /* Dropdown */
        .dropdown-menu {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            border: none;
            border-radius: 0.5rem;
        }
        
        .dropdown-item {
            padding: 0.75rem 1.25rem;
            font-weight: 500;
        }
        
        .dropdown-item:hover {
            background-color: #f8f9fc;
        }
        
        /* Mobile Responsive */
        @media (max-width: 768px) {
            .sidebar {
                margin-left: calc(-1 * var(--sidebar-width));
                transform: translateX(0);
            }
            
            .sidebar.show {
                margin-left: 0;
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .sidebar-toggle {
                display: block;
            }
            
            .content-wrapper {
                padding: 1rem;
            }
            
            .topbar {
                padding: 0.75rem 1rem;
            }
        }
        
        /* Animation */
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Loading */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }
        
        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>

<?php 
// Flash Messages dengan SweetAlert2
$success = \App\Core\Session::getSuccess();
$error = \App\Core\Session::getError();
$warning = \App\Core\Session::getWarning();
$info = \App\Core\Session::getInfo();
?>

<div class="d-flex">
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <a class="sidebar-brand" href="<?php echo '/' . $role . '/dashboard'; ?>">
            <i class="<?php echo $config['icon']; ?>"></i>
            <span><?php echo $config['title']; ?></span>
        </a>
        
        <ul class="nav flex-column">
            <?php foreach ($config['menu_items'] as $item): ?>
                <?php if (isset($item['divider'])): ?>
                    <hr class="sidebar-divider">
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $active_menu === $item['key'] ? 'active' : ''; ?>" 
                           href="<?php echo $item['url']; ?>">
                            <i class="<?php echo $item['icon']; ?>"></i>
                            <span><?php echo $item['text']; ?></span>
                        </a>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
            
            <hr class="sidebar-divider">
            
            <li class="nav-item">
                <a class="nav-link <?php echo $active_menu === 'profile' ? 'active' : ''; ?>" href="/profile">
                    <i class="bi bi-person"></i>
                    <span>Profil Saya</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link logout-btn" href="#" onclick="confirmLogout()">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar">
            <div class="topbar-left">
                <button class="sidebar-toggle" onclick="toggleSidebar()">
                    <i class="bi bi-list"></i>
                </button>
                <div>
                    <h6 class="mb-0 text-gray-800"><?php echo $page_title ?? 'Dashboard'; ?></h6>
                    <small class="text-muted">Selamat datang, <?php echo htmlspecialchars($user_name); ?></small>
                </div>
            </div>
            
            <div class="topbar-right">
                <!-- User Profile -->
                <div class="dropdown">
                    <button class="btn btn-link d-flex align-items-center" type="button" data-bs-toggle="dropdown">
                        <div class="user-avatar me-2">
                            <?php echo strtoupper(substr($user_name, 0, 2)); ?>
                        </div>
                        <span class="d-none d-md-inline text-gray-800"><?php echo htmlspecialchars($user_name); ?></span>
                        <i class="bi bi-chevron-down ms-2 text-gray-600"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="/profile">
                            <i class="bi bi-person me-2"></i>Profil Saya
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#" onclick="confirmLogout()">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Content Wrapper -->
        <div class="content-wrapper fade-in">
            <?php echo $content; ?>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay" style="display: none;">
    <div class="spinner"></div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom JavaScript -->
<script>
// Global functions
document.addEventListener('DOMContentLoaded', function() {
    // Show flash messages
    <?php if ($success): ?>
        window.showSuccess('<?php echo addslashes($success); ?>');
    <?php endif; ?>
    
    <?php if ($error): ?>
        window.showError('<?php echo addslashes($error); ?>');
    <?php endif; ?>
    
    <?php if ($warning): ?>
        window.showWarning('<?php echo addslashes($warning); ?>');
    <?php endif; ?>
    
    <?php if ($info): ?>
        window.showInfo('<?php echo addslashes($info); ?>');
    <?php endif; ?>
});

// Sidebar toggle for mobile
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('show');
}

// Close sidebar when clicking outside on mobile
document.addEventListener('click', function(event) {
    const sidebar = document.getElementById('sidebar');
    const toggle = document.querySelector('.sidebar-toggle');
    
    if (window.innerWidth <= 768 && 
        !sidebar.contains(event.target) && 
        !toggle.contains(event.target) && 
        sidebar.classList.contains('show')) {
        sidebar.classList.remove('show');
    }
});

// Logout confirmation
function confirmLogout() {
    Swal.fire({
        title: 'Konfirmasi Logout',
        text: 'Apakah Anda yakin ingin keluar dari sistem?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: 'var(--primary-color)',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Logout',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            showLoading();
            window.location.href = '/logout';
        }
    });
}

// Loading functions
function showLoading() {
    document.getElementById('loadingOverlay').style.display = 'flex';
}

function hideLoading() {
    document.getElementById('loadingOverlay').style.display = 'none';
}

// SweetAlert2 wrapper functions
window.showSuccess = function(message) {
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: message,
        timer: 3000,
        showConfirmButton: false,
        toast: true,
        position: 'top-end'
    });
};

window.showError = function(message) {
    Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: message,
        confirmButtonColor: 'var(--primary-color)'
    });
};

window.showWarning = function(message) {
    Swal.fire({
        icon: 'warning',
        title: 'Peringatan!',
        text: message,
        confirmButtonColor: 'var(--primary-color)'
    });
};

window.showInfo = function(message) {
    Swal.fire({
        icon: 'info',
        title: 'Informasi',
        text: message,
        confirmButtonColor: 'var(--primary-color)'
    });
};

// CSRF Token for AJAX requests
window.csrfToken = '<?php echo \App\Core\Session::getCsrfToken(); ?>';
window.baseUrl = '<?php echo \App\Core\View::url(); ?>';

// Auto-hide alerts after 5 seconds
setTimeout(function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        if (alert.classList.contains('alert-dismissible')) {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }
    });
}, 5000);

// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Initialize tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
});
</script>

</body>
</html>