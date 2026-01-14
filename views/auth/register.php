<div class="container-fluid vh-100">
    <div class="row h-100">
        <!-- Left Side - Form -->
        <div class="col-lg-6 d-flex align-items-center justify-content-center">
            <div class="w-100" style="max-width: 400px;">
                <div class="text-center mb-4">
                    <img src="<?php echo \App\Core\View::asset('images/logo_poltek.png'); ?>" alt="Logo Polibatam" class="logo mb-3">
                    <h2 class="fw-bold text-primary">Registrasi Mahasiswa</h2>
                    <p class="text-muted">Daftar untuk mengakses APAO Polibatam</p>
                </div>

                <form method="POST" action="<?php echo \App\Core\View::url('register'); ?>" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                        <div class="invalid-feedback">Username harus diisi</div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                        <div class="invalid-feedback">Email harus diisi dengan format yang benar</div>
                    </div>

                    <div class="mb-3">
                        <label for="full_name" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="full_name" name="full_name" required>
                        <div class="invalid-feedback">Nama lengkap harus diisi</div>
                    </div>

                    <div class="mb-3">
                        <label for="nim_nip" class="form-label">NIM</label>
                        <input type="text" class="form-control" id="nim_nip" name="nim_nip">
                    </div>

                    <div class="mb-3">
                        <label for="program_studi" class="form-label">Program Studi</label>
                        <input type="text" class="form-control" id="program_studi" name="program_studi">
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <div class="invalid-feedback">Password harus diisi minimal 6 karakter</div>
                    </div>

                    <div class="mb-4">
                        <label for="confirm_password" class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        <div class="invalid-feedback">Konfirmasi password harus sama dengan password</div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mb-3">
                        <i class="bi bi-person-plus me-2"></i>Daftar
                    </button>

                    <div class="text-center">
                        <p class="mb-2">Sudah memiliki akun? 
                            <a href="<?php echo \App\Core\View::url('login'); ?>" class="text-decoration-none">Login di sini</a>
                        </p>
                        <a href="<?php echo \App\Core\View::url('/'); ?>" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Side - Info -->
        <div class="col-lg-6 bg-primary d-none d-lg-flex align-items-center justify-content-center text-white">
            <div class="text-center">
                <h3 class="fw-bold mb-4">Bergabung dengan APAO Polibatam</h3>
                <p class="lead mb-4">
                    Akses mudah ke informasi akademik dan pengumuman resmi 
                    Politeknik Negeri Batam
                </p>
                <div class="row text-center">
                    <div class="col-4">
                        <i class="bi bi-mortarboard display-4 mb-2"></i>
                        <p class="small">Akademik</p>
                    </div>
                    <div class="col-4">
                        <i class="bi bi-bell display-4 mb-2"></i>
                        <p class="small">Notifikasi</p>
                    </div>
                    <div class="col-4">
                        <i class="bi bi-people display-4 mb-2"></i>
                        <p class="small">Komunitas</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Form validation
(function() {
    'use strict';
    window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();

// Password confirmation validation
document.getElementById('confirm_password').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmPassword = this.value;
    
    if (password !== confirmPassword) {
        this.setCustomValidity('Password tidak sama');
    } else {
        this.setCustomValidity('');
    }
});
</script>