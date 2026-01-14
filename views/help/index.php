<?php 
$current_page = 'help';
$page_title = 'Bantuan';
$page_icon = 'question-circle';

// Determine which topbar and sidebar to use based on role
$userRole = $_SESSION['role'] ?? 'mahasiswa';
if (in_array($userRole, ['admin', 'dosen'])) {
    \App\Core\View::component('admin-topbar');
    $sidebarComponent = 'admin-sidebar';
    $dashboardUrl = 'admin/dashboard';
} else {
    \App\Core\View::component('student-topbar');
    $sidebarComponent = 'student-sidebar';
    $dashboardUrl = 'student/dashboard';
}
?>

<div class="container-fluid">
    <div class="row">
        <?php \App\Core\View::component($sidebarComponent); ?>

        <!-- Main content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">
                    <i class="bi bi-question-circle me-2 text-primary"></i>Bantuan
                </h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="<?php echo \App\Core\View::url($dashboardUrl); ?>" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Kembali
                    </a>
                </div>
            </div>

            <!-- Quick Help Cards -->
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <div class="card h-100 help-card">
                        <div class="card-body text-center">
                            <i class="bi bi-book display-4 text-primary mb-3"></i>
                            <h5 class="card-title">Panduan Pengguna</h5>
                            <p class="card-text">Pelajari cara menggunakan sistem APAO dengan panduan lengkap.</p>
                            <a href="<?php echo \App\Core\View::url('help/user-guide'); ?>" class="btn btn-primary">
                                Baca Panduan
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card h-100 help-card">
                        <div class="card-body text-center">
                            <i class="bi bi-envelope display-4 text-success mb-3"></i>
                            <h5 class="card-title">Hubungi Kami</h5>
                            <p class="card-text">Butuh bantuan lebih lanjut? Hubungi tim support kami.</p>
                            <a href="<?php echo \App\Core\View::url('help/contact'); ?>" class="btn btn-success">
                                Kirim Pesan
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card h-100 help-card">
                        <div class="card-body text-center">
                            <i class="bi bi-telephone display-4 text-info mb-3"></i>
                            <h5 class="card-title">Kontak Langsung</h5>
                            <p class="card-text">Hubungi kami langsung untuk bantuan segera.</p>
                            <div class="mt-3">
                                <p class="mb-1"><strong>Email:</strong> support@polibatam.ac.id</p>
                                <p class="mb-0"><strong>Telp:</strong> (0778) 469858</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ Section -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-patch-question me-2"></i>Frequently Asked Questions (FAQ)
                    </h5>
                </div>
                <div class="card-body">
                    <div class="accordion" id="faqAccordion">
                        <?php foreach ($faqs as $index => $faq): ?>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading<?php echo $index; ?>">
                                    <button class="accordion-button <?php echo $index === 0 ? '' : 'collapsed'; ?>" 
                                            type="button" data-bs-toggle="collapse" 
                                            data-bs-target="#collapse<?php echo $index; ?>" 
                                            aria-expanded="<?php echo $index === 0 ? 'true' : 'false'; ?>" 
                                            aria-controls="collapse<?php echo $index; ?>">
                                        <?php echo htmlspecialchars($faq['question']); ?>
                                    </button>
                                </h2>
                                <div id="collapse<?php echo $index; ?>" 
                                     class="accordion-collapse collapse <?php echo $index === 0 ? 'show' : ''; ?>" 
                                     aria-labelledby="heading<?php echo $index; ?>" 
                                     data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <?php echo htmlspecialchars($faq['answer']); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Additional Resources -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="bi bi-link-45deg me-2"></i>Link Berguna
                            </h6>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <a href="https://polibatam.ac.id" target="_blank" class="text-decoration-none">
                                        <i class="bi bi-globe me-2"></i>Website Polibatam
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="https://siakad.polibatam.ac.id" target="_blank" class="text-decoration-none">
                                        <i class="bi bi-mortarboard me-2"></i>SIAKAD Polibatam
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="https://elearning.polibatam.ac.id" target="_blank" class="text-decoration-none">
                                        <i class="bi bi-laptop me-2"></i>E-Learning
                                    </a>
                                </li>
                                <li class="mb-0">
                                    <a href="https://library.polibatam.ac.id" target="_blank" class="text-decoration-none">
                                        <i class="bi bi-book me-2"></i>Perpustakaan Digital
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="bi bi-clock me-2"></i>Jam Operasional Support
                            </h6>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <strong>Senin - Jumat:</strong><br>
                                    <span class="text-muted">08:00 - 16:00 WIB</span>
                                </li>
                                <li class="mb-2">
                                    <strong>Sabtu:</strong><br>
                                    <span class="text-muted">08:00 - 12:00 WIB</span>
                                </li>
                                <li class="mb-0">
                                    <strong>Minggu & Hari Libur:</strong><br>
                                    <span class="text-muted">Tutup</span>
                                </li>
                            </ul>
                            <div class="alert alert-info mt-3">
                                <small>
                                    <i class="bi bi-info-circle me-1"></i>
                                    Untuk bantuan darurat di luar jam operasional, silakan kirim email.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<style>
/* Force layout consistency */
body {
    padding-top: 56px !important;
}

.main-content {
    padding-top: 1rem !important;
    min-height: calc(100vh - 56px) !important;
}

@media (min-width: 768px) {
    .main-content {
        margin-left: 25% !important;
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
        margin-left: 16.66667% !important;
        padding-left: 1rem !important;
    }
    
    .sidebar {
        width: 16.66667% !important;
    }
}

.container-fluid {
    padding-left: 0 !important;
    padding-right: 0 !important;
}

.row {
    margin: 0 !important;
}

.card {
    border: none;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    border-radius: 0.5rem;
}

.help-card {
    transition: all 0.3s ease;
}

.help-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 2rem rgba(58, 59, 69, 0.25);
}

.accordion-button:not(.collapsed) {
    background-color: rgba(13, 110, 253, 0.1);
    border-color: rgba(13, 110, 253, 0.25);
}

.accordion-button:focus {
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}
</style>