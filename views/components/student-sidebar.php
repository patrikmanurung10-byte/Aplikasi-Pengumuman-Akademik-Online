<!-- Student Sidebar Component -->
<div class="col-md-3 col-lg-2 d-md-block bg-primary sidebar collapse show" id="sidebarMenu">
    <div class="position-sticky pt-3">
        <!-- Logo & Brand -->
        <div class="text-center mb-4 text-white">
            <img src="<?php echo \App\Core\View::asset('images/logo_poltek.png'); ?>" alt="Logo Polibatam" width="50" height="50" class="mb-2">
            <h6 class="mt-2 fw-bold">APAO Polibatam</h6>
            <small class="text-light">Portal Mahasiswa</small>
        </div>
        
        <!-- User Info -->
        <div class="text-center mb-3 pb-3 border-bottom border-light border-opacity-25">
            <div class="text-white">
                <i class="bi bi-person-circle fs-4"></i>
            </div>
            <small class="text-white d-block mt-1">
                <?php echo htmlspecialchars($_SESSION['full_name'] ?? 'Mahasiswa'); ?>
            </small>
            <small class="text-light">
                NIM: <?php echo htmlspecialchars($_SESSION['nim_nip'] ?? $_SESSION['username'] ?? ''); ?>
            </small>
        </div>
        
        <!-- Navigation Menu -->
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page ?? '') === 'dashboard' ? 'active text-primary bg-white' : 'text-white'; ?>" 
                   href="<?php echo \App\Core\View::url('student/dashboard'); ?>">
                    <i class="bi bi-house me-2"></i>Beranda
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page ?? '') === 'announcements' ? 'active text-primary bg-white' : 'text-white'; ?>" 
                   href="<?php echo \App\Core\View::url('student/announcements'); ?>">
                    <i class="bi bi-megaphone me-2"></i>Pengumuman
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white" href="<?php echo \App\Core\View::url('profile'); ?>">
                    <i class="bi bi-person-gear me-2"></i>Profil Saya
                </a>
            </li>
            
            <!-- Logout -->
            <li class="nav-item mt-auto pt-3">
                <a class="nav-link text-warning" href="<?php echo \App\Core\View::url('logout'); ?>" 
                   onclick="return confirm('Apakah Anda yakin ingin logout?')">
                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                </a>
            </li>
        </ul>
    </div>
</div>

<style>
.sidebar {
    min-height: calc(100vh - 56px);
    box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
    position: fixed;
    top: 56px;
    bottom: 0;
    left: 0;
    z-index: 100;
    padding: 0;
}

@media (min-width: 768px) {
    .sidebar {
        width: 25%;
    }
}

@media (min-width: 992px) {
    .sidebar {
        width: 16.66667%;
    }
}

.sidebar .nav-link {
    border-radius: 0.375rem;
    margin: 0.125rem 0.5rem;
    padding: 0.75rem 1rem;
    transition: all 0.2s ease;
}

.sidebar .nav-link:hover:not(.active) {
    background-color: rgba(255, 255, 255, 0.1);
    transform: translateX(5px);
}

.sidebar .nav-link.active {
    font-weight: 600 !important;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    border-left: 4px solid #fff !important;
}

.sidebar .nav-link i {
    width: 16px;
    text-align: center;
}

@media (max-width: 767.98px) {
    .sidebar {
        position: static !important;
        min-height: auto !important;
    }
}

/* Force sidebar positioning */
.sidebar {
    min-height: calc(100vh - 56px) !important;
    box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1) !important;
    position: fixed !important;
    top: 56px !important;
    bottom: 0 !important;
    left: 0 !important;
    z-index: 100 !important;
    padding: 0 !important;
}
</style>