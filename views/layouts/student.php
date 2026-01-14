<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - Mahasiswa APAO' : 'Mahasiswa APAO'; ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Responsive CSS -->
    <link href="/assets/css/responsive.css" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fc;
            overflow-x: hidden;
        }
        
        .student-layout {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar */
        .sidebar {
            width: 280px;
            background: linear-gradient(135deg, #198754 0%, #157347 100%);
            color: white;
            position: fixed;
            height: 100vh;
            left: 0;
            top: 0;
            z-index: 1000;
            overflow-y: auto;
            transition: all 0.3s ease;
        }
        
        .sidebar-brand {
            padding: 1.5rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            color: white;
            text-decoration: none;
            font-weight: 700;
            font-size: 1.25rem;
        }
        
        .sidebar-brand:hover {
            color: white;
        }
        
        .sidebar-brand i {
            margin-right: 0.75rem;
            font-size: 1.5rem;
        }
        
        .sidebar-menu {
            padding: 1rem 0;
        }
        
        .menu-item {
            margin: 0.25rem 0.75rem;
        }
        
        .menu-link {
            display: flex;
            align-items: center;
            padding: 0.875rem 1rem;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .menu-link:hover,
        .menu-link.active {
            color: white;
            background-color: rgba(255,255,255,0.15);
            transform: translateX(5px);
        }
        
        .menu-link i {
            margin-right: 0.75rem;
            width: 1.25rem;
            text-align: center;
            font-size: 1.1rem;
        }
        
        .menu-divider {
            border-color: rgba(255,255,255,0.15);
            margin: 1rem 0.75rem;
        }
        
        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 280px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        /* Header */
        .main-header {
            background: white;
            padding: 1rem 1.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 999;
        }
        
        .header-left h4 {
            color: #5a5c69;
            margin: 0;
            font-weight: 600;
        }
        
        .header-left p {
            color: #858796;
            margin: 0;
            font-size: 0.875rem;
        }
        
        .header-right {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-left: auto;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #198754, #157347);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.875rem;
        }
        
        .user-details h6 {
            margin: 0;
            color: #5a5c69;
            font-weight: 600;
        }
        
        .user-details small {
            color: #858796;
        }
        
        /* Content Area */
        .content-area {
            flex: 1;
            padding: 1.5rem;
            background-color: #f8f9fc;
        }
        
        /* Cards */
        .stat-card {
            background: white;
            border-radius: 0.5rem;
            padding: 1.5rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            border-left: 4px solid;
            margin-bottom: 1.5rem;
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-2px);
        }
        
        .stat-card.primary { border-left-color: #198754; }
        .stat-card.success { border-left-color: #1cc88a; }
        .stat-card.info { border-left-color: #36b9cc; }
        .stat-card.warning { border-left-color: #f6c23e; }
        
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: #5a5c69;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            color: #858796;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.875rem;
            letter-spacing: 0.5px;
        }
        
        .stat-icon {
            font-size: 2rem;
            color: #dddfeb;
        }
        
        /* Action Cards */
        .action-card {
            background: white;
            border-radius: 0.5rem;
            padding: 1.5rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            margin-bottom: 1.5rem;
        }
        
        .action-card h6 {
            color: #198754;
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
        }
        
        .action-card h6 i {
            margin-right: 0.5rem;
        }
        
        .action-btn {
            display: block;
            width: 100%;
            padding: 0.75rem 1rem;
            margin-bottom: 0.5rem;
            background: white;
            border: 2px solid #e3e6f0;
            border-radius: 0.5rem;
            color: #5a5c69;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .action-btn:hover {
            border-color: #198754;
            color: #198754;
            background-color: #f8f9fc;
        }
        
        .action-btn i {
            margin-right: 0.5rem;
        }
        
        /* Timeline */
        .timeline {
            position: relative;
            padding-left: 30px;
        }
        
        .timeline::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 20px;
            width: 2px;
            background: #e3e6f0;
        }
        
        .timeline-item {
            position: relative;
            margin-bottom: 30px;
        }
        
        .timeline-marker {
            position: absolute;
            left: -23px;
            top: 0;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 2px solid #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8px;
            color: #fff;
        }
        
        .timeline-content {
            background: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-left: 3px solid #e3e6f0;
        }
        
        /* Buttons */
        .btn-student {
            background-color: #198754;
            border-color: #198754;
            color: white;
            font-weight: 500;
        }
        
        .btn-student:hover {
            background-color: #157347;
            border-color: #146c43;
            color: white;
        }
        
        /* Mobile Responsive */
        @media (max-width: 768px) {
            .sidebar {
                margin-left: -280px;
                z-index: 1050;
            }
            
            .sidebar.show {
                margin-left: 0;
                box-shadow: 0 0 0 100vw rgba(0,0,0,0.5);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .content-area {
                padding: 0.75rem;
            }
            
            .main-header {
                padding: 0.75rem 1rem;
            }
            
            .main-header .header-left h4 {
                font-size: 1.1rem;
            }
            
            .main-header .header-left p {
                display: none;
            }
            
            .user-details {
                display: none !important;
            }
            
            .stat-card {
                margin-bottom: 1rem;
            }
            
            .stat-number {
                font-size: 1.5rem;
            }
            
            .table-responsive {
                font-size: 0.875rem;
            }
            
            .btn-group .btn {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }
            
            .card {
                margin-bottom: 1rem;
            }
            
            .d-flex.justify-content-between {
                flex-direction: column;
                gap: 1rem;
            }
            
            .btn-toolbar {
                margin-bottom: 1rem;
            }
            
            .timeline-item {
                margin-bottom: 15px;
            }
            
            .timeline-content {
                padding: 10px;
            }
        }
        
        @media (max-width: 576px) {
            .content-area {
                padding: 0.5rem;
            }
            
            .main-header {
                padding: 0.5rem;
            }
            
            .stat-card {
                padding: 1rem;
            }
            
            .card-body {
                padding: 1rem;
            }
            
            .table th, .table td {
                padding: 0.5rem 0.25rem;
                font-size: 0.75rem;
            }
            
            .btn {
                font-size: 0.75rem;
                padding: 0.375rem 0.75rem;
            }
            
            .btn-sm {
                font-size: 0.7rem;
                padding: 0.25rem 0.5rem;
            }
            
            .list-group-item {
                padding: 0.5rem;
                font-size: 0.875rem;
            }
        }
        
        /* Other styles same as admin but with green theme */
        .table th {
            border-top: none;
            border-bottom: 2px solid #e3e6f0;
            color: #858796;
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 0.75rem;
        }
        
        .table td {
            border-top: 1px solid #e3e6f0;
            padding: 1rem 0.75rem;
            vertical-align: middle;
        }
        
        .table tbody tr:hover {
            background-color: #f8f9fc;
        }
        
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
        
        .list-group-item {
            border: none;
            padding: 0.75rem 0;
        }
        
        .list-group-item:hover {
            background-color: #f8f9fc;
        }
    </style>
</head>
<body>

<?php 
// Flash Messages
$success = \App\Core\Session::getSuccess();
$error = \App\Core\Session::getError();
$warning = \App\Core\Session::getWarning();
$info = \App\Core\Session::getInfo();
?>

<div class="student-layout">
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <a href="/student/dashboard" class="sidebar-brand">
            <i class="bi bi-mortarboard"></i>
            <span>APAO Mahasiswa</span>
        </a>
        
        <div class="sidebar-menu">
            <div class="menu-item">
                <a href="/student/dashboard" class="menu-link <?php echo ($active_menu ?? '') === 'dashboard' ? 'active' : ''; ?>">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </div>
            
            <div class="menu-item">
                <a href="/student/announcements" class="menu-link <?php echo ($active_menu ?? '') === 'announcements' ? 'active' : ''; ?>">
                    <i class="bi bi-megaphone"></i>
                    <span>Semua Pengumuman</span>
                </a>
            </div>
            
            <div class="menu-item">
                <a href="/student/profile" class="menu-link <?php echo ($active_menu ?? '') === 'profile' ? 'active' : ''; ?>">
                    <i class="bi bi-person-circle"></i>
                    <span>Profil Saya</span>
                </a>
            </div>
            
            <hr class="menu-divider">
            
            <div class="menu-item">
                <a href="#" class="menu-link" onclick="confirmLogout()">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="main-header">
            <div class="header-left">
                <button class="btn btn-link d-md-none me-2" onclick="toggleSidebar()" style="color: #858796;">
                    <i class="bi bi-list" style="font-size: 1.25rem;"></i>
                </button>
                <h4><?php echo $page_title ?? 'Dashboard Mahasiswa'; ?></h4>
                <p>Portal mahasiswa APAO Polibatam</p>
            </div>
            
            <div class="header-right">
                <!-- User Profile -->
                <div class="dropdown">
                    <button class="btn btn-link d-flex align-items-center" type="button" data-bs-toggle="dropdown">
                        <div class="user-info">
                            <div class="user-avatar">
                                <?php echo strtoupper(substr($_SESSION['full_name'] ?? 'MH', 0, 2)); ?>
                            </div>
                            <div class="user-details d-none d-md-block">
                                <h6><?php echo htmlspecialchars($_SESSION['full_name'] ?? 'Mahasiswa'); ?></h6>
                                <small>Mahasiswa</small>
                            </div>
                        </div>
                        <i class="bi bi-chevron-down ms-2"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="/student/profile">
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
        
        <!-- Content Area -->
        <div class="content-area">
            <?php echo $content; ?>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Mobile Enhancements -->
<script src="/assets/js/mobile-enhancements.js"></script>

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

// Logout confirmation
function confirmLogout() {
    Swal.fire({
        title: 'Konfirmasi Logout',
        text: 'Apakah Anda yakin ingin keluar dari sistem?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#198754',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Logout',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '/logout';
        }
    });
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
        confirmButtonColor: '#198754'
    });
};

window.showWarning = function(message) {
    Swal.fire({
        icon: 'warning',
        title: 'Peringatan!',
        text: message,
        confirmButtonColor: '#198754'
    });
};

window.showInfo = function(message) {
    Swal.fire({
        icon: 'info',
        title: 'Informasi',
        text: message,
        confirmButtonColor: '#198754'
    });
};

// Mobile sidebar toggle
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const isOpen = sidebar.classList.contains('show');
    
    if (isOpen) {
        sidebar.classList.remove('show');
        document.body.style.overflow = '';
    } else {
        sidebar.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
}

// Close sidebar when clicking outside on mobile
document.addEventListener('click', function(e) {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.querySelector('[onclick="toggleSidebar()"]');
    
    if (window.innerWidth <= 768 && sidebar.classList.contains('show')) {
        if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
            sidebar.classList.remove('show');
            document.body.style.overflow = '';
        }
    }
});

// Handle window resize
window.addEventListener('resize', function() {
    const sidebar = document.getElementById('sidebar');
    if (window.innerWidth > 768) {
        sidebar.classList.remove('show');
        document.body.style.overflow = '';
    }
});

// CSRF Token
window.csrfToken = '<?php echo \App\Core\Session::getCsrfToken(); ?>';
window.baseUrl = '<?php echo \App\Core\View::url(); ?>';
</script>

</body>
</html>