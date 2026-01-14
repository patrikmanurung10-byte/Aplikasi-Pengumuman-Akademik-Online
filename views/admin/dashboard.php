<?php
// Admin Dashboard - Updated untuk konsistensi
?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-speedometer2 me-2"></i>Dashboard Admin
            </h1>
            <p class="mb-0 text-muted">Panel kontrol administrasi APAO</p>
        </div>
        <div class="btn-toolbar">
            <div class="btn-group me-2">
                <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="bi bi-calendar3 me-1"></i>Periode
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Hari ini</a></li>
                    <li><a class="dropdown-item" href="#">Minggu ini</a></li>
                    <li><a class="dropdown-item" href="#">Bulan ini</a></li>
                </ul>
            </div>
            <button type="button" class="btn btn-sm btn-primary" onclick="location.reload()">
                <i class="bi bi-arrow-clockwise me-1"></i>Refresh
            </button>
        </div>
    </div>
    <!-- User Management Stats -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Pengguna
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo $stats['total_users'] ?? 0; ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-people fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Mahasiswa
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo $stats['user_stats']['total_mahasiswa'] ?? 0; ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-mortarboard fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Dosen
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo $stats['user_stats']['total_dosen'] ?? 0; ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-person-badge fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Pengumuman
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo $stats['total_announcements'] ?? 0; ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-megaphone fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <!-- User Management Actions -->
        <div class="col-lg-12 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-people me-2"></i>Manajemen Pengguna
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <a href="/admin/users" class="btn btn-primary w-100">
                                <i class="bi bi-people me-2"></i>Kelola Pengguna
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="/admin/users/create" class="btn btn-success w-100">
                                <i class="bi bi-person-plus me-2"></i>Tambah Pengguna
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="/admin/announcements" class="btn btn-info w-100">
                                <i class="bi bi-megaphone me-2"></i>Kelola Pengumuman
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <button class="btn btn-warning w-100" onclick="showUserStats()">
                                <i class="bi bi-graph-up me-2"></i>Statistik Pengguna
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- User Management Table -->
    <div class="row">
        <!-- Recent Users -->
        <div class="col-lg-12 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-people me-2"></i>Pengguna Terbaru
                    </h6>
                    <a href="/admin/users" class="btn btn-sm btn-primary">
                        Lihat Semua
                    </a>
                </div>
                <div class="card-body">
                    <?php if (!empty($stats['recent_users'])): ?>
                        <?php foreach (array_slice($stats['recent_users'], 0, 5) as $user): ?>
                            <div class="d-flex align-items-start mb-3 pb-3 border-bottom">
                                <div class="flex-shrink-0 me-3">
                                    <div class="bg-<?php echo ($user['role'] === 'admin') ? 'danger' : (($user['role'] === 'dosen') ? 'info' : 'success'); ?> rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="bi bi-person text-white"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1"><?php echo htmlspecialchars($user['full_name']); ?></h6>
                                            <p class="mb-1 text-muted small"><?php echo htmlspecialchars($user['email']); ?></p>
                                            <small class="text-muted">
                                                <i class="bi bi-calendar me-1"></i><?php echo date('d/m/Y', strtotime($user['created_at'])); ?>
                                            </small>
                                        </div>
                                        <div class="d-flex flex-column align-items-end">
                                            <span class="badge bg-<?php echo ($user['role'] === 'admin') ? 'danger' : (($user['role'] === 'dosen') ? 'info' : 'success'); ?> mb-2">
                                                <?php echo ucfirst($user['role']); ?>
                                            </span>
                                            <div class="btn-group" role="group">
                                                <a href="/admin/users/<?php echo $user['id']; ?>" 
                                                   class="btn btn-sm btn-outline-info" title="Lihat Detail">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="/admin/users/<?php echo $user['id']; ?>/edit" 
                                                   class="btn btn-sm btn-outline-primary" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-danger" 
                                                        title="Hapus"
                                                        onclick="confirmDeleteUser(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars($user['full_name']); ?>')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="bi bi-people text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-2">Belum ada pengguna terdaftar</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Admin JavaScript - User Management Focus -->
<script>
// User Management Functions
function showUserStats() {
    Swal.fire({
        title: 'Statistik Pengguna',
        html: `
            <div class="text-start">
                <p><strong>Total Pengguna:</strong> <?php echo $stats['total_users'] ?? 0; ?></p>
                <p><strong>Mahasiswa:</strong> <?php echo $stats['user_stats']['total_mahasiswa'] ?? 0; ?></p>
                <p><strong>Dosen:</strong> <?php echo $stats['user_stats']['total_dosen'] ?? 0; ?></p>
                <p><strong>Admin:</strong> <?php echo $stats['user_stats']['total_admin'] ?? 0; ?></p>
                <p><strong>Pengguna Aktif:</strong> <?php echo $stats['user_stats']['active_users'] ?? 0; ?></p>
            </div>
        `,
        icon: 'info',
        confirmButtonText: 'OK'
    });
}

function toggleUserStatus(userId) {
    Swal.fire({
        title: 'Ubah Status User',
        text: 'Apakah Anda yakin ingin mengubah status user ini?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Ubah',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Implementation for toggling user status
            window.showSuccess('Status user berhasil diubah!');
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
    
    // Add CSRF token if available
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_method';
    csrfInput.value = 'DELETE';
    form.appendChild(csrfInput);
    
    document.body.appendChild(form);
    form.submit();
}

// Initialize admin dashboard
document.addEventListener('DOMContentLoaded', function() {
    console.log('Admin User Management Dashboard initialized');
});
</script>

<style>
.border-left-primary {
    border-left: 0.25rem solid #dc3545 !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.table th {
    border-top: none;
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.btn-group .btn {
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}
</style>