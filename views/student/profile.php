<?php
// Student Profile Page
// Layout ditentukan di controller
?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Profil Mahasiswa</h1>
            <p class="mb-0 text-muted">Lengkapi dan perbarui data pribadi Anda</p>
        </div>
        <a href="/student/dashboard" class="btn btn-student">
            <i class="bi bi-arrow-left me-2"></i>Kembali ke Dashboard
        </a>
    </div>

    <!-- Info Alert -->
    <div class="alert alert-info mb-4" role="alert">
        <div class="d-flex align-items-center">
            <i class="bi bi-info-circle me-3" style="font-size: 1.5rem;"></i>
            <div>
                <h6 class="alert-heading mb-1">Informasi Penting</h6>
                <p class="mb-0">Silakan lengkapi data pribadi Anda dengan informasi yang akurat. Data ini akan digunakan untuk keperluan akademik dan komunikasi resmi.</p>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Profile Form -->
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="bi bi-person-circle me-2"></i>Informasi Profil
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="/student/profile/update" id="profileForm">
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
                                <label for="nim_nip" class="form-label">NIM</label>
                                <input type="text" class="form-control" id="nim_nip" 
                                       value="<?php echo htmlspecialchars($user['nim_nip'] ?? ''); ?>" disabled>
                                <div class="form-text">Hubungi admin untuk mengubah</div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">No. Telepon</label>
                                <input type="tel" class="form-control" id="phone" name="phone" 
                                       value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="program_studi" class="form-label">Program Studi</label>
                                <select class="form-select" id="program_studi" name="program_studi">
                                    <option value="">Pilih Program Studi</option>
                                    <option value="Teknik Informatika" <?php echo ($user['program_studi'] ?? '') === 'Teknik Informatika' ? 'selected' : ''; ?>>Teknik Informatika</option>
                                    <option value="Sistem Informasi" <?php echo ($user['program_studi'] ?? '') === 'Sistem Informasi' ? 'selected' : ''; ?>>Sistem Informasi</option>
                                    <option value="Teknik Elektro" <?php echo ($user['program_studi'] ?? '') === 'Teknik Elektro' ? 'selected' : ''; ?>>Teknik Elektro</option>
                                    <option value="Teknik Mesin" <?php echo ($user['program_studi'] ?? '') === 'Teknik Mesin' ? 'selected' : ''; ?>>Teknik Mesin</option>
                                    <option value="Teknik Sipil" <?php echo ($user['program_studi'] ?? '') === 'Teknik Sipil' ? 'selected' : ''; ?>>Teknik Sipil</option>
                                    <option value="Teknik Industri" <?php echo ($user['program_studi'] ?? '') === 'Teknik Industri' ? 'selected' : ''; ?>>Teknik Industri</option>
                                    <option value="Akuntansi" <?php echo ($user['program_studi'] ?? '') === 'Akuntansi' ? 'selected' : ''; ?>>Akuntansi</option>
                                    <option value="Manajemen" <?php echo ($user['program_studi'] ?? '') === 'Manajemen' ? 'selected' : ''; ?>>Manajemen</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="2"><?php echo htmlspecialchars($user['alamat'] ?? ''); ?></textarea>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-student">
                                <i class="bi bi-check-circle me-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Profile Info & Actions -->
        <div class="col-lg-4">
            <!-- Account Info -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="bi bi-info-circle me-2"></i>Informasi Akun
                    </h6>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div class="user-avatar mx-auto" style="width: 80px; height: 80px; font-size: 2rem;">
                            <?php echo strtoupper(substr($user['full_name'] ?? 'MH', 0, 2)); ?>
                        </div>
                    </div>
                    
                    <table class="table table-sm">
                        <tr>
                            <td class="text-muted">Username:</td>
                            <td><strong><?php echo htmlspecialchars($user['username'] ?? '-'); ?></strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">NIM:</td>
                            <td><?php echo htmlspecialchars($user['nim_nip'] ?? '-'); ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Role:</td>
                            <td><span class="badge" style="background-color: #198754;">Mahasiswa</span></td>
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
                    <p class="text-muted small mb-3">Untuk mengubah password, gunakan fitur lupa password atau hubungi administrator.</p>
                    
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
            
            <!-- Tips -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="bi bi-lightbulb me-2"></i>Panduan Pengisian Data
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2 mb-3">
                        <a href="/student/announcements" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-megaphone me-2"></i>Lihat Pengumuman
                        </a>
                        <a href="/student/dashboard" class="btn btn-outline-info btn-sm">
                            <i class="bi bi-speedometer2 me-2"></i>Dashboard
                        </a>
                    </div>
                    
                    <hr>
                    
                    <h6 class="text-primary mb-2">Mengapa Data Pribadi Penting?</h6>
                    <ul class="list-unstyled mb-3 small">
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            <strong>Komunikasi Resmi:</strong> Email dan telepon untuk pengumuman penting
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            <strong>Identifikasi:</strong> Data akurat untuk keperluan administrasi
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            <strong>Program Studi:</strong> Pengumuman sesuai jurusan Anda
                        </li>
                        <li class="mb-0">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            <strong>Keamanan:</strong> Verifikasi identitas saat diperlukan
                        </li>
                    </ul>
                    
                    <div class="alert alert-warning alert-sm p-2 mb-0">
                        <small><i class="bi bi-exclamation-triangle me-1"></i> <strong>Catatan:</strong> Pastikan semua data yang Anda masukkan adalah benar dan dapat dipertanggungjawabkan.</small>
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