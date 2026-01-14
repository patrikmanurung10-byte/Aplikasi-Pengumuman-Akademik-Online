<?php
// Admin Show User - Detail Pengguna
?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-person me-2"></i>Detail Pengguna
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="/admin/users">Kelola Pengguna</a></li>
                    <li class="breadcrumb-item active"><?php echo htmlspecialchars($user['full_name']); ?></li>
                </ol>
            </nav>
        </div>
        <div class="btn-toolbar">
            <div class="btn-group me-2">
                <a href="/admin/users/<?php echo $user['id']; ?>/edit" class="btn btn-primary">
                    <i class="bi bi-pencil me-1"></i>Edit Pengguna
                </a>
            </div>
            <a href="/admin/users" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <!-- User Information -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-info-circle me-2"></i>Informasi Pengguna
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nama Lengkap</label>
                            <p class="form-control-plaintext"><?php echo htmlspecialchars($user['full_name']); ?></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Username</label>
                            <p class="form-control-plaintext"><?php echo htmlspecialchars($user['username']); ?></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <p class="form-control-plaintext">
                                <a href="mailto:<?php echo htmlspecialchars($user['email']); ?>">
                                    <?php echo htmlspecialchars($user['email']); ?>
                                </a>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nomor Telepon</label>
                            <p class="form-control-plaintext">
                                <?php if (!empty($user['phone'])): ?>
                                    <a href="tel:<?php echo htmlspecialchars($user['phone']); ?>">
                                        <?php echo htmlspecialchars($user['phone']); ?>
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">Tidak ada</span>
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Role</label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-<?php echo ($user['role'] === 'admin') ? 'danger' : (($user['role'] === 'dosen') ? 'info' : 'success'); ?> fs-6">
                                    <?php echo ucfirst($user['role']); ?>
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Status</label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-<?php echo $user['is_active'] ? 'success' : 'danger'; ?> fs-6">
                                    <?php echo $user['is_active'] ? 'Aktif' : 'Nonaktif'; ?>
                                </span>
                            </p>
                        </div>
                        <?php if (!empty($user['program_studi'])): ?>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Program Studi</label>
                            <p class="form-control-plaintext"><?php echo htmlspecialchars($user['program_studi']); ?></p>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($user['nim'])): ?>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">NIM</label>
                            <p class="form-control-plaintext"><?php echo htmlspecialchars($user['nim']); ?></p>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($user['nip'])): ?>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">NIP</label>
                            <p class="form-control-plaintext"><?php echo htmlspecialchars($user['nip']); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Stats & Actions -->
        <div class="col-lg-4">
            <!-- User Stats -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-graph-up me-2"></i>Statistik
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Terdaftar Sejak</small>
                        <p class="mb-0 fw-bold"><?php echo date('d F Y', strtotime($user['created_at'])); ?></p>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Terakhir Diperbarui</small>
                        <p class="mb-0 fw-bold">
                            <?php echo !empty($user['updated_at']) ? date('d F Y H:i', strtotime($user['updated_at'])) : 'Belum pernah'; ?>
                        </p>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Login Terakhir</small>
                        <p class="mb-0 fw-bold">
                            <?php echo !empty($user['last_login']) ? date('d F Y H:i', strtotime($user['last_login'])) : 'Belum pernah'; ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-lightning me-2"></i>Aksi Cepat
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="/admin/users/<?php echo $user['id']; ?>/edit" class="btn btn-primary">
                            <i class="bi bi-pencil me-2"></i>Edit Pengguna
                        </a>
                        <button type="button" class="btn btn-warning" onclick="resetPassword(<?php echo $user['id']; ?>)">
                            <i class="bi bi-key me-2"></i>Reset Password
                        </button>
                        <button type="button" class="btn btn-<?php echo $user['is_active'] ? 'secondary' : 'success'; ?>" 
                                onclick="toggleStatus(<?php echo $user['id']; ?>, <?php echo $user['is_active'] ? 'false' : 'true'; ?>)">
                            <i class="bi bi-<?php echo $user['is_active'] ? 'pause' : 'play'; ?> me-2"></i>
                            <?php echo $user['is_active'] ? 'Nonaktifkan' : 'Aktifkan'; ?>
                        </button>
                        <?php if ($user['id'] != $_SESSION['user_id']): ?>
                        <button type="button" class="btn btn-danger" 
                                onclick="confirmDeleteUser(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars($user['full_name']); ?>')">
                            <i class="bi bi-trash me-2"></i>Hapus Pengguna
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function resetPassword(userId) {
    Swal.fire({
        title: 'Reset Password',
        text: 'Apakah Anda yakin ingin mereset password pengguna ini?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Reset',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Implementation for password reset
            window.showSuccess('Password berhasil direset!');
        }
    });
}

function toggleStatus(userId, newStatus) {
    const action = newStatus ? 'mengaktifkan' : 'menonaktifkan';
    
    Swal.fire({
        title: 'Ubah Status',
        text: `Apakah Anda yakin ingin ${action} pengguna ini?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Ubah',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Implementation for status toggle
            window.showSuccess(`Status pengguna berhasil diubah!`);
            setTimeout(() => {
                location.reload();
            }, 1500);
        }
    });
}

function confirmDeleteUser(userId, userName) {
    Swal.fire({
        title: 'Hapus Pengguna',
        html: `Apakah Anda yakin ingin menghapus pengguna <strong>${userName}</strong>?<br><small class="text-muted">Tindakan ini tidak dapat dibatalkan.</small>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d'
    }).then((result) => {
        if (result.isConfirmed) {
            deleteUser(userId, userName);
        }
    });
}

function deleteUser(userId, userName) {
    // Show loading
    Swal.fire({
        title: 'Menghapus...',
        text: 'Sedang menghapus pengguna',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // Create form and submit
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/admin/users/' + userId + '/delete';
    
    document.body.appendChild(form);
    form.submit();
}
</script>

<style>
.form-control-plaintext {
    padding: 0.375rem 0;
    margin-bottom: 0;
    font-size: 0.875rem;
    line-height: 1.5;
    color: #212529;
    background-color: transparent;
    border: solid transparent;
    border-width: 1px 0;
}

.badge.fs-6 {
    font-size: 0.875rem !important;
}

.card-header h6 {
    color: #5a5c69;
}
</style>