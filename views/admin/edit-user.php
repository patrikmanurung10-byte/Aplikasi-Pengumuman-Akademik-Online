<?php
// Admin Edit User - Form Edit Pengguna
?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-pencil me-2"></i>Edit Pengguna
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="/admin/users">Kelola Pengguna</a></li>
                    <li class="breadcrumb-item active">Edit <?php echo htmlspecialchars($user['full_name']); ?></li>
                </ol>
            </nav>
        </div>
        <div class="btn-toolbar">
            <a href="/admin/users/<?php echo $user['id']; ?>" class="btn btn-info me-2">
                <i class="bi bi-eye me-1"></i>Lihat Detail
            </a>
            <a href="/admin/users" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-person-gear me-2"></i>Form Edit Pengguna
                    </h6>
                </div>
                <div class="card-body">
                    <form action="/admin/users/<?php echo $user['id']; ?>/update" method="POST" id="editUserForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="username" name="username" 
                                       value="<?php echo htmlspecialchars($user['username']); ?>" required>
                                <div class="form-text">Username harus unik dan tidak boleh sama dengan pengguna lain</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="full_name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="full_name" name="full_name" 
                                       value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Nomor Telepon</label>
                                <input type="tel" class="form-control" id="phone" name="phone" 
                                       value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="">Pilih Role</option>
                                    <option value="admin" <?php echo ($user['role'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
                                    <option value="dosen" <?php echo ($user['role'] === 'dosen') ? 'selected' : ''; ?>>Dosen</option>
                                    <option value="mahasiswa" <?php echo ($user['role'] === 'mahasiswa') ? 'selected' : ''; ?>>Mahasiswa</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="is_active" class="form-label">Status</label>
                                <select class="form-select" id="is_active" name="is_active">
                                    <option value="1" <?php echo $user['is_active'] ? 'selected' : ''; ?>>Aktif</option>
                                    <option value="0" <?php echo !$user['is_active'] ? 'selected' : ''; ?>>Nonaktif</option>
                                </select>
                            </div>
                            
                            <!-- Role-specific fields -->
                            <div class="col-md-6 mb-3" id="nim_nip_field">
                                <label for="nim_nip" class="form-label">
                                    <span id="nim_nip_label">
                                        <?php echo ($user['role'] === 'mahasiswa') ? 'NIM' : (($user['role'] === 'dosen') ? 'NIP' : 'NIM/NIP'); ?>
                                    </span>
                                </label>
                                <input type="text" class="form-control" id="nim_nip" name="nim_nip" 
                                       value="<?php echo htmlspecialchars($user['nim_nip'] ?? ''); ?>">
                            </div>
                            <div class="col-md-6 mb-3" id="academic_status_field">
                                <label for="academic_status" class="form-label">Status Akademik</label>
                                <select class="form-select" id="academic_status" name="academic_status">
                                    <option value="Aktif" <?php echo ($user['academic_status'] ?? 'Aktif') === 'Aktif' ? 'selected' : ''; ?>>Aktif</option>
                                    <option value="Nonaktif" <?php echo ($user['academic_status'] ?? '') === 'Nonaktif' ? 'selected' : ''; ?>>Nonaktif</option>
                                    <option value="Cuti" <?php echo ($user['academic_status'] ?? '') === 'Cuti' ? 'selected' : ''; ?>>Cuti</option>
                                    <option value="Lulus" <?php echo ($user['academic_status'] ?? '') === 'Lulus' ? 'selected' : ''; ?>>Lulus</option>
                                    <option value="DO" <?php echo ($user['academic_status'] ?? '') === 'DO' ? 'selected' : ''; ?>>Drop Out (DO)</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3" id="program_studi_field" style="display: <?php echo (in_array($user['role'], ['mahasiswa', 'dosen'])) ? 'block' : 'none'; ?>;">
                                <label for="program_studi" class="form-label">Program Studi</label>
                                <input type="text" class="form-control" id="program_studi" name="program_studi" 
                                       value="<?php echo htmlspecialchars($user['program_studi'] ?? ''); ?>">
                            </div>
                            
                            <div class="col-12 mb-3">
                                <hr>
                                <h6 class="text-muted">Ubah Password (Opsional)</h6>
                                <p class="text-muted small">Kosongkan jika tidak ingin mengubah password</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password Baru</label>
                                <input type="password" class="form-control" id="password" name="password" 
                                       placeholder="Masukkan password baru (minimal 6 karakter)">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password_confirm" class="form-label">Konfirmasi Password</label>
                                <input type="password" class="form-control" id="password_confirm" name="password_confirm" 
                                       placeholder="Konfirmasi password baru">
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg me-2"></i>Simpan Perubahan
                                </button>
                                <button type="reset" class="btn btn-secondary">
                                    <i class="bi bi-arrow-clockwise me-2"></i>Reset Form
                                </button>
                            </div>
                            <a href="/admin/users" class="btn btn-outline-secondary">
                                <i class="bi bi-x-lg me-2"></i>Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- User Info -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-info-circle me-2"></i>Informasi Pengguna
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="bg-<?php echo ($user['role'] === 'admin') ? 'danger' : (($user['role'] === 'dosen') ? 'info' : 'success'); ?> rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-person text-white" style="font-size: 1.5rem;"></i>
                        </div>
                        <h5 class="mt-2 mb-0"><?php echo htmlspecialchars($user['full_name']); ?></h5>
                        <small class="text-muted"><?php echo htmlspecialchars($user['email']); ?></small>
                    </div>
                    <hr>
                    <div class="mb-2">
                        <small class="text-muted">Terdaftar Sejak</small>
                        <p class="mb-0"><?php echo date('d F Y', strtotime($user['created_at'])); ?></p>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Status Saat Ini</small>
                        <p class="mb-0">
                            <span class="badge bg-<?php echo $user['is_active'] ? 'success' : 'danger'; ?>">
                                <?php echo $user['is_active'] ? 'Aktif' : 'Nonaktif'; ?>
                            </span>
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Help -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-question-circle me-2"></i>Bantuan
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            <small>Username harus unik</small>
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            <small>Email harus valid dan unik</small>
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            <small>Password minimal 6 karakter</small>
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            <small>Role menentukan akses sistem</small>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Role-based field visibility and labels
document.getElementById('role').addEventListener('change', function() {
    const role = this.value;
    const nimNipLabel = document.getElementById('nim_nip_label');
    const nimNipField = document.getElementById('nim_nip_field');
    const programStudiField = document.getElementById('program_studi_field');
    
    // Update label based on role
    if (role === 'mahasiswa') {
        nimNipLabel.textContent = 'NIM';
        nimNipField.style.display = 'block';
        programStudiField.style.display = 'block';
    } else if (role === 'dosen') {
        nimNipLabel.textContent = 'NIP';
        nimNipField.style.display = 'block';
        programStudiField.style.display = 'block';
    } else if (role === 'admin') {
        nimNipLabel.textContent = 'NIM/NIP';
        nimNipField.style.display = 'block';
        programStudiField.style.display = 'none';
    } else {
        nimNipField.style.display = 'none';
        programStudiField.style.display = 'none';
    }
});

// Password confirmation validation
document.getElementById('editUserForm').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const passwordConfirm = document.getElementById('password_confirm').value;
    
    if (password && password !== passwordConfirm) {
        e.preventDefault();
        Swal.fire({
            title: 'Password Tidak Cocok',
            text: 'Konfirmasi password tidak sesuai dengan password baru',
            icon: 'error',
            confirmButtonText: 'OK'
        });
        return false;
    }
    
    if (password && password.length < 6) {
        e.preventDefault();
        Swal.fire({
            title: 'Password Terlalu Pendek',
            text: 'Password minimal harus 6 karakter',
            icon: 'error',
            confirmButtonText: 'OK'
        });
        return false;
    }
});

// Form validation feedback
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('editUserForm');
    const inputs = form.querySelectorAll('input[required], select[required]');
    
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value.trim() === '') {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        });
    });
    
    // Add loading state when form is submitted
    form.addEventListener('submit', function(e) {
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Menyimpan...';
        
        // Re-enable button after 5 seconds (in case of error)
        setTimeout(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }, 5000);
    });
});
</script>

<style>
.form-control.is-valid {
    border-color: #198754;
}

.form-control.is-invalid {
    border-color: #dc3545;
}

.card-header h6 {
    color: #5a5c69;
}

.btn-group .btn {
    margin-right: 5px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}
</style>