<!-- Student Topbar Component -->
<nav class="navbar navbar-expand-lg navbar-primary bg-primary fixed-top">
    <div class="container-fluid">
        <!-- Brand -->
        <a class="navbar-brand d-flex align-items-center text-white" href="<?php echo \App\Core\View::url('student/dashboard'); ?>">
            <img src="<?php echo \App\Core\View::asset('images/logo_poltek.png'); ?>" alt="Logo" width="30" height="30" class="me-2">
            <span class="fw-bold">APAO Polibatam</span>
            <span class="badge bg-light text-primary ms-2 small">Mahasiswa</span>
        </a>
        
        <!-- Mobile Sidebar Toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Page Title -->
        <div class="d-none d-md-block">
            <h6 class="text-white mb-0">
                <i class="bi bi-<?php echo $page_icon ?? 'house'; ?> me-2"></i>
                <?php echo $page_title ?? 'Dashboard Mahasiswa'; ?>
            </h6>
        </div>
        
        <!-- Right Side Menu -->
        <div class="navbar-nav ms-auto d-flex flex-row">
            <!-- Quick Search -->
            <div class="nav-item me-3 d-none d-lg-block">
                <form class="d-flex" role="search">
                    <input class="form-control form-control-sm" type="search" placeholder="Cari pengumuman..." style="width: 200px;">
                    <button class="btn btn-outline-light btn-sm ms-1" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
            </div>
            
            <!-- User Menu -->
            <div class="nav-item dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center text-white" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle fs-5 me-2"></i>
                    <span class="d-none d-lg-inline">
                        <?php echo htmlspecialchars($_SESSION['full_name'] ?? 'Mahasiswa'); ?>
                    </span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li class="dropdown-header">
                        <strong><?php echo htmlspecialchars($_SESSION['full_name'] ?? 'Mahasiswa'); ?></strong><br>
                        <small class="text-muted">NIM: <?php echo htmlspecialchars($_SESSION['nim_nip'] ?? $_SESSION['username'] ?? ''); ?></small>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="<?php echo \App\Core\View::url('profile'); ?>">
                            <i class="bi bi-person me-2"></i>Profil Saya
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="<?php echo \App\Core\View::url('settings'); ?>">
                            <i class="bi bi-gear me-2"></i>Pengaturan
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="<?php echo \App\Core\View::url('help'); ?>">
                            <i class="bi bi-question-circle me-2"></i>Bantuan
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item text-danger" href="<?php echo \App\Core\View::url('logout'); ?>" 
                           onclick="return confirm('Apakah Anda yakin ingin logout?')">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<style>
/* Global Student Layout */
body {
    padding-top: 56px !important;
}

.navbar-brand:hover {
    transform: scale(1.02);
    transition: transform 0.2s ease;
}

.nav-link:hover {
    transform: translateY(-1px);
    transition: transform 0.2s ease;
}

.dropdown-menu {
    border: none;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    border-radius: 0.5rem;
}

.dropdown-item:hover {
    background-color: rgba(13, 110, 253, 0.1);
}

.form-control:focus {
    border-color: rgba(255, 255, 255, 0.5);
    box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.25);
}

/* Main Content Layout */
.main-content {
    padding-top: 1rem !important;
    min-height: calc(100vh - 56px) !important;
}

/* Consistent spacing for all student pages */
.container-fluid {
    padding-left: 0 !important;
    padding-right: 0 !important;
}

/* Ensure sidebar and main content alignment */
@media (min-width: 768px) {
    .main-content {
        margin-left: 25% !important; /* Account for sidebar width */
        padding-left: 1rem !important;
    }
    
    .sidebar {
        width: 25% !important;
        position: fixed !important;
        top: 56px !important;
        bottom: 0 !important;
        left: 0 !important;
        z-index: 100 !important;
    }
}

@media (min-width: 992px) {
    .main-content {
        margin-left: 16.66667% !important; /* Account for sidebar width */
        padding-left: 1rem !important;
    }
    
    .sidebar {
        width: 16.66667% !important;
    }
}

.row {
    margin: 0 !important;
}

/* Override Bootstrap grid for main content */
.main-content.col-md-9.ms-sm-auto.col-lg-10 {
    padding-left: 1rem;
    padding-right: 1rem;
}

@media (min-width: 768px) {
    .main-content.col-md-9.ms-sm-auto.col-lg-10 {
        flex: 0 0 auto;
        width: 75%;
        margin-left: 25%;
    }
}

@media (min-width: 992px) {
    .main-content.col-md-9.ms-sm-auto.col-lg-10 {
        flex: 0 0 auto;
        width: 83.33333%;
        margin-left: 16.66667%;
    }
}

/* Ensure consistent card styling */
.card {
    border: none;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    border-radius: 0.5rem;
}

.card-header {
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
    border-radius: 0.5rem 0.5rem 0 0 !important;
}

/* Consistent button styling */
.btn:hover {
    transform: translateY(-1px);
    transition: transform 0.2s ease;
}
</style>

</style>