<!-- Admin Sidebar Component -->
<div class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse show" id="sidebarMenu">
    <div class="position-sticky pt-3">
        <!-- Logo & Brand -->
        <div class="text-center mb-4 text-white">
            <img src="<?php echo \App\Core\View::asset('images/logo_poltek.png'); ?>" alt="Logo Polibatam" width="50" height="50" class="mb-2">
            <h6 class="mt-2 fw-bold">APAO Admin</h6>
            <small class="text-muted">
                <?php echo $_SESSION['role'] === 'admin' ? 'Administrator' : 'Dosen'; ?>
            </small>
        </div>
        
        <!-- User Info -->
        <div class="text-center mb-3 pb-3 border-bottom border-secondary">
            <div class="text-white">
                <i class="bi bi-person-circle fs-4"></i>
            </div>
            <small class="text-light d-block mt-1">
                <?php echo htmlspecialchars($_SESSION['full_name'] ?? 'User'); ?>
            </small>
            <small class="text-muted">
                <?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?>
            </small>
        </div>
        
        <!-- Navigation Menu -->
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page ?? '') === 'dashboard' ? 'active text-white' : 'text-light'; ?>" 
                   href="<?php echo \App\Core\View::url('admin/dashboard'); ?>">
                    <i class="bi bi-speedometer2 me-2"></i>Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo in_array(($current_page ?? ''), ['announcements', 'edit-announcement']) ? 'active text-white' : 'text-light'; ?>" 
                   href="<?php echo \App\Core\View::url('admin/announcements'); ?>">
                    <i class="bi bi-megaphone me-2"></i>Kelola Pengumuman
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page ?? '') === 'create-announcement' ? 'active text-white' : 'text-light'; ?>" 
                   href="<?php echo \App\Core\View::url('admin/announcements/create'); ?>">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Pengumuman
                </a>
            </li>
            
            <!-- Divider -->
            <li class="nav-item mt-2 mb-2">
                <hr class="text-secondary">
            </li>
            
            <li class="nav-item">
                <a class="nav-link text-light" href="#" onclick="alert('Fitur Kelola Kelas akan segera hadir!')">
                    <i class="bi bi-people me-2"></i>Kelola Kelas
                    <span class="badge bg-warning text-dark ms-2 small">Soon</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-light" href="#" onclick="alert('Fitur Laporan akan segera hadir!')">
                    <i class="bi bi-bar-chart me-2"></i>Laporan
                    <span class="badge bg-warning text-dark ms-2 small">Soon</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-light" href="<?php echo \App\Core\View::url('profile'); ?>">
                    <i class="bi bi-person-gear me-2"></i>Pengaturan
                </a>
            </li>
            
            <!-- Logout -->
            <li class="nav-item mt-auto pt-3">
                <a class="nav-link text-danger" href="<?php echo \App\Core\View::url('logout'); ?>" 
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
    font-weight: 500;
    color: #adb5bd;
}

.sidebar .nav-link:hover {
    background-color: rgba(255, 255, 255, 0.1);
    transform: translateX(5px);
    color: #fff;
}

.sidebar .nav-link.active {
    background-color: rgba(13, 110, 253, 0.3) !important;
    border-left: 4px solid #0d6efd !important;
    color: #fff !important;
    font-weight: 600 !important;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.sidebar .nav-link i {
    width: 16px;
    text-align: center;
}

@media (max-width: 767.98px) {
    .sidebar {
        position: static;
        min-height: auto;
    }
}
</style>