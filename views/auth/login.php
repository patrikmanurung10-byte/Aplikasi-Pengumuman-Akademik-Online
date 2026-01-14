<div class="card p-4 text-center" style="width: 100%; max-width: 400px;">
    <img src="<?php echo \App\Core\View::asset('images/logo_poltek.png'); ?>" alt="Logo Polibatam" class="logo">
    
    <?php if (isset($is_dosen) && $is_dosen): ?>
        <h4 class="dosen-title">APAO Login Dosen</h4>
        <p class="subtitle">Politeknik Negeri Batam</p>
    <?php else: ?>
        <h5 class="mb-1">Aplikasi Pengumuman Akademik Online</h5>
        <p class="text-muted mb-4">Politeknik Negeri Batam</p>
    <?php endif; ?>

    <form method="POST" action="<?php echo \App\Core\View::url('login'); ?>">
        <input type="hidden" name="csrf_token" value="<?php echo \App\Core\View::csrf(); ?>">
        <input type="hidden" name="role" value="<?php echo htmlspecialchars($role ?? 'mahasiswa'); ?>">
        
        <div class="mb-3 text-start">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" placeholder="Masukkan username" required 
                   value="<?php echo htmlspecialchars(\App\Core\View::old('username')); ?>">
        </div>
        
        <div class="mb-4 text-start">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
        </div>
        
        <button type="submit" class="btn btn-primary w-100">
            <?php echo (isset($is_dosen) && $is_dosen) ? 'Login Dosen' : 'Masuk'; ?>
        </button>
    </form>
    
    <?php if (!isset($is_dosen) || !$is_dosen): ?>
        <p class="mt-3 mb-0">Belum punya akun? 
            <a href="<?php echo \App\Core\View::url('register'); ?>" class="text-decoration-none">Daftar di sini</a>
        </p>
    <?php endif; ?>
    
    <div class="additional-link" style="margin-top: 25px; margin-bottom: 20px; font-size: 0.95rem;">
        <a href="<?php echo \App\Core\View::url('forgot-password'); ?>" class="text-decoration-none">
            Lupa Password?
        </a>
    </div>
    
    <div class="mt-3">
        <a href="<?php echo \App\Core\View::url('/'); ?>" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>
    
    <small class="text-muted d-block mt-3">© 2024 Politeknik Negeri Batam. All rights reserved.</small>
</div>

<style>
.dosen-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 5px;
}
.subtitle {
    color: #6c757d;
    margin-bottom: 25px;
    font-size: 0.95rem;
}
.form-label {
    font-weight: 500;
    margin-bottom: 5px;
}
.additional-link a {
    color: #0d6efd !important;
}

/* Responsive Styles for Login */
@media (max-width: 768px) {
    .card {
        margin: 1rem;
        padding: 2rem 1.5rem !important;
        max-width: 90% !important;
    }
    
    .logo {
        width: 60px !important;
        height: 60px !important;
    }
    
    .dosen-title {
        font-size: 1.25rem !important;
    }
    
    h5 {
        font-size: 1.1rem !important;
    }
    
    .form-control {
        padding: 0.75rem !important;
        font-size: 1rem !important;
    }
    
    .btn {
        padding: 0.75rem 1rem !important;
        font-size: 1rem !important;
    }
}

@media (max-width: 576px) {
    .card {
        margin: 0.5rem;
        padding: 1.5rem 1rem !important;
        max-width: 95% !important;
    }
    
    .logo {
        width: 50px !important;
        height: 50px !important;
    }
    
    .dosen-title {
        font-size: 1.1rem !important;
    }
    
    h5 {
        font-size: 1rem !important;
    }
    
    .subtitle, p {
        font-size: 0.875rem !important;
    }
    
    .form-control {
        padding: 0.625rem !important;
        font-size: 0.9rem !important;
    }
    
    .btn {
        padding: 0.625rem 0.875rem !important;
        font-size: 0.9rem !important;
    }
    
    .btn-sm {
        padding: 0.375rem 0.75rem !important;
        font-size: 0.8rem !important;
    }
    
    small {
        font-size: 0.75rem !important;
    }
}
</style>