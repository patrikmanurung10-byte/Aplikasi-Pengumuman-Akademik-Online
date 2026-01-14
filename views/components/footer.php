<!-- Footer Component -->
<footer class="footer bg-dark text-light py-4 mt-5">
    <div class="container">
        <div class="row">
            <!-- Brand & Description -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="d-flex align-items-center mb-3">
                    <img src="<?php echo \App\Core\View::asset('images/logo_poltek.png'); ?>" alt="Logo Polibatam" width="40" height="40" class="me-3">
                    <div>
                        <h6 class="mb-0 fw-bold">APAO Polibatam</h6>
                        <small class="text-muted">Aplikasi Pengumuman Akademik Online</small>
                    </div>
                </div>
                <p class="text-muted small">
                    Platform digital terintegrasi untuk mengakses informasi akademik, pengumuman resmi, 
                    dan layanan digital kampus Politeknik Negeri Batam.
                </p>
                <div class="social-links">
                    <a href="#" class="text-light me-3" title="Website Resmi">
                        <i class="bi bi-globe fs-5"></i>
                    </a>
                    <a href="#" class="text-light me-3" title="Facebook">
                        <i class="bi bi-facebook fs-5"></i>
                    </a>
                    <a href="#" class="text-light me-3" title="Instagram">
                        <i class="bi bi-instagram fs-5"></i>
                    </a>
                    <a href="#" class="text-light" title="YouTube">
                        <i class="bi bi-youtube fs-5"></i>
                    </a>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6 mb-4">
                <h6 class="fw-bold mb-3">Menu Utama</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="<?php echo \App\Core\View::url('/'); ?>" class="text-muted text-decoration-none small">
                            <i class="bi bi-house me-2"></i>Beranda
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="<?php echo \App\Core\View::url('announcements'); ?>" class="text-muted text-decoration-none small">
                            <i class="bi bi-megaphone me-2"></i>Pengumuman
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="<?php echo \App\Core\View::url('about'); ?>" class="text-muted text-decoration-none small">
                            <i class="bi bi-info-circle me-2"></i>Tentang
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="<?php echo \App\Core\View::url('contact'); ?>" class="text-muted text-decoration-none small">
                            <i class="bi bi-envelope me-2"></i>Kontak
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Services -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h6 class="fw-bold mb-3">Layanan</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="<?php echo \App\Core\View::url('register'); ?>" class="text-muted text-decoration-none small">
                            <i class="bi bi-person-plus me-2"></i>Registrasi
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-muted text-decoration-none small" onclick="alert('Fitur akan segera hadir!')">
                            <i class="bi bi-question-circle me-2"></i>Bantuan
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Contact Info -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h6 class="fw-bold mb-3">Kontak Kami</h6>
                <div class="contact-info">
                    <div class="mb-2 small">
                        <i class="bi bi-geo-alt me-2 text-primary"></i>
                        <span class="text-muted">
                            Jl. Ahmad Yani, Batam Centre<br>
                            Batam 29461, Kepulauan Riau
                        </span>
                    </div>
                    <div class="mb-2 small">
                        <i class="bi bi-telephone me-2 text-primary"></i>
                        <a href="tel:+62778469858" class="text-muted text-decoration-none">
                            (0778) 469858
                        </a>
                    </div>
                    <div class="mb-2 small">
                        <i class="bi bi-envelope me-2 text-primary"></i>
                        <a href="mailto:info@polibatam.ac.id" class="text-muted text-decoration-none">
                            info@polibatam.ac.id
                        </a>
                    </div>
                    <div class="small">
                        <i class="bi bi-globe me-2 text-primary"></i>
                        <a href="https://www.polibatam.ac.id" class="text-muted text-decoration-none" target="_blank">
                            www.polibatam.ac.id
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Divider -->
        <hr class="my-4 border-secondary">
        
        <!-- Bottom Footer -->
        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="mb-0 small text-muted">
                    © <?php echo date('Y'); ?> Politeknik Negeri Batam. All rights reserved.
                </p>
            </div>
            <div class="col-md-6 text-md-end">
                <div class="footer-links">
                    <a href="<?php echo \App\Core\View::url('privacy'); ?>" class="text-muted text-decoration-none small me-3">
                        Kebijakan Privasi
                    </a>
                    <a href="<?php echo \App\Core\View::url('terms'); ?>" class="text-muted text-decoration-none small me-3">
                        Syarat & Ketentuan
                    </a>
                    <span class="text-muted small">
                        <i class="bi bi-code-slash me-1"></i>
                        Made with ❤️ by APAO Team
                    </span>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
.footer {
    margin-top: auto;
}

.footer .social-links a:hover {
    color: #0d6efd !important;
    transform: translateY(-2px);
    transition: all 0.2s ease;
}

.footer .contact-info a:hover {
    color: #0d6efd !important;
    transition: color 0.2s ease;
}

.footer .list-unstyled a:hover {
    color: #0d6efd !important;
    transform: translateX(5px);
    transition: all 0.2s ease;
}

.footer-links a:hover {
    color: #0d6efd !important;
    transition: color 0.2s ease;
}
</style>