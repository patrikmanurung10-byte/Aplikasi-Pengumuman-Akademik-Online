<?php
// Admin Profile Page
// Layout ditentukan di controller
?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Profil Administrator</h1>
            <p class="mb-0 text-muted">Kelola informasi profil administrator</p>
        </div>
        <a href="/admin/dashboard" class="btn btn-admin">
            <i class="bi bi-arrow-left me-2"></i>Kembali ke Dashboard
        </a>
    </div>

    <div class="row">
        <!-- Profile Form -->
        <div class="col-lg-8 col-md-12 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-danger">
                        <i class="bi bi-person-circle me-2"></i>Informasi Profil
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="/admin/profile/update" id="profileForm">
                        <input type="hidden" name="csrf_token" value="<?php echo \App\Core\Session::getCsrfToken(); ?>">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="full_name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="full_name" name="full_name" 
                                       value="<?php echo htmlspecialchars($user['full_name'] ?? ''); ?>" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" 
                                       value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>" disabled>
                                <div class="form-text">Username tidak dapat diubah</div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">No. Telepon</label>
                                <input type="tel" class="form-control" id="phone" name="phone" 
                                       value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nip" class="form-label">NIP</label>
                                <input type="text" class="form-control" id="nip" 
                                       value="<?php echo htmlspecialchars($user['nim_nip'] ?? '-'); ?>" disabled>
                                <div class="form-text">Hubungi super admin untuk mengubah</div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="role" class="form-label">Role</label>
                                <input type="text" class="form-control" 
                                       value="Administrator" disabled>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-admin">
                                <i class="bi bi-check-circle me-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Profile Info & Actions -->
        <div class="col-lg-4 col-md-12">
            <!-- Account Info -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-danger">
                        <i class="bi bi-info-circle me-2"></i>Informasi Akun
                    </h6>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div class="user-avatar mx-auto" style="width: 80px; height: 80px; font-size: 2rem;">
                            <?php echo strtoupper(substr($user['full_name'] ?? 'AD', 0, 2)); ?>
                        </div>
                    </div>
                    
                    <table class="table table-sm">
                        <tr>
                            <td class="text-muted">Username:</td>
                            <td><strong><?php echo htmlspecialchars($user['username'] ?? '-'); ?></strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Role:</td>
                            <td><span class="badge bg-admin">Administrator</span></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Status:</td>
                            <td>
                                <span class="badge bg-<?php echo ($user['is_active'] ?? 1) ? 'success' : 'danger'; ?>">
                                    <?php echo ($user['is_active'] ?? 1) ? 'Aktif' : 'Nonaktif'; ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Terdaftar:</td>
                            <td><?php echo isset($user['created_at']) ? date('d/m/Y', strtotime($user['created_at'])) : '-'; ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Update Terakhir:</td>
                            <td><?php echo isset($user['updated_at']) ? date('d/m/Y H:i', strtotime($user['updated_at'])) : '-'; ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Security -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="bi bi-shield-lock me-2"></i>Keamanan
                    </h6>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">Untuk mengubah password, gunakan fitur lupa password atau hubungi super admin.</p>
                    
                    <div class="d-grid gap-2">
                        <a href="/forgot-password" class="btn btn-outline-warning btn-sm">
                            <i class="bi bi-key me-2"></i>Ubah Password
                        </a>
                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="confirmLogout()">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </button>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-lightning me-2"></i>Aksi Cepat
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="/admin/users" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-people me-2"></i>Kelola Pengguna
                        </a>
                        <a href="/admin/announcements" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-megaphone me-2"></i>Kelola Pengumuman
                        </a>
                        <a href="/admin/users/create" class="btn btn-outline-success btn-sm">
                            <i class="bi bi-person-plus me-2"></i>Tambah Pengguna
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('profileForm');
    
    form.addEventListener('submit', function(e) {
        const fullName = document.getElementById('full_name').value.trim();
        const email = document.getElementById('email').value.trim();
        
        if (!fullName) {
            e.preventDefault();
            window.showError('Nama lengkap harus diisi');
            document.getElementById('full_name').focus();
            return false;
        }
        
        if (!email || !email.includes('@')) {
            e.preventDefault();
            window.showError('Email tidak valid');
            document.getElementById('email').focus();
            return false;
        }
    });
});
</script>