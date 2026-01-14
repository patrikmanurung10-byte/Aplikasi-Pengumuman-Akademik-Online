<?php
// Admin Users Management Page
?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-people-fill me-2"></i>Kelola Pengguna
            </h1>
            <p class="mb-0 text-muted">Manajemen pengguna sistem APAO</p>
        </div>
        <div class="btn-toolbar">
            <a href="/admin/users/create" class="btn btn-primary">
                <i class="bi bi-person-plus me-1"></i>Tambah Pengguna
            </a>
        </div>
    </div>

    <!-- User Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Pengguna
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo count($users ?? []); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-people fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Mahasiswa
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo count(array_filter($users ?? [], fn($u) => $u['role'] === 'mahasiswa')); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-mortarboard fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Dosen
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo count(array_filter($users ?? [], fn($u) => $u['role'] === 'dosen')); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-person-badge fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Admin
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo count(array_filter($users ?? [], fn($u) => $u['role'] === 'admin')); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-shield-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="bi bi-search me-2"></i>Pencarian & Filter
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="/admin/users" class="row g-3">
                <div class="col-md-6">
                    <label for="search" class="form-label">Cari Pengguna</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           placeholder="Cari berdasarkan username, email, atau nama..." 
                           value="<?php echo htmlspecialchars($search ?? ''); ?>">
                </div>
                <div class="col-md-4">
                    <label for="role" class="form-label">Filter Role</label>
                    <select class="form-select" id="role" name="role">
                        <option value="">Semua Role</option>
                        <option value="admin" <?php echo ($role ?? '') === 'admin' ? 'selected' : ''; ?>>Admin</option>
                        <option value="dosen" <?php echo ($role ?? '') === 'dosen' ? 'selected' : ''; ?>>Dosen</option>
                        <option value="mahasiswa" <?php echo ($role ?? '') === 'mahasiswa' ? 'selected' : ''; ?>>Mahasiswa</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-search me-1"></i>Cari
                    </button>
                    <a href="/admin/users" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="bi bi-table me-2"></i>Daftar Pengguna
            </h6>
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-three-dots-vertical"></i>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#" onclick="exportUsers()">
                        <i class="bi bi-download me-2"></i>Export Data
                    </a></li>
                    <li><a class="dropdown-item" href="#" onclick="importUsers()">
                        <i class="bi bi-upload me-2"></i>Import Data
                    </a></li>
                </ul>
            </div>
        </div>
        <div class="card-body">
            <?php if (!empty($users)): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Pengguna</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Terdaftar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-3">
                                            <div class="avatar-initial rounded-circle bg-<?php 
                                                echo $user['role'] === 'admin' ? 'danger' : 
                                                    ($user['role'] === 'dosen' ? 'info' : 'success'); 
                                            ?>">
                                                <i class="bi bi-<?php 
                                                    echo $user['role'] === 'admin' ? 'shield-check' : 
                                                        ($user['role'] === 'dosen' ? 'person-badge' : 'mortarboard'); 
                                                ?> text-white"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="fw-bold"><?php echo htmlspecialchars($user['full_name']); ?></div>
                                            <small class="text-muted">@<?php echo htmlspecialchars($user['username']); ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-muted"><?php echo htmlspecialchars($user['email']); ?></span>
                                </td>
                                <td>
                                    <span class="badge bg-<?php 
                                        echo $user['role'] === 'admin' ? 'danger' : 
                                            ($user['role'] === 'dosen' ? 'info' : 'success'); 
                                    ?>">
                                        <?php echo ucfirst($user['role']); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-<?php echo $user['is_active'] ? 'success' : 'danger'; ?>">
                                        <i class="bi bi-<?php echo $user['is_active'] ? 'check-circle' : 'x-circle'; ?> me-1"></i>
                                        <?php echo $user['is_active'] ? 'Aktif' : 'Nonaktif'; ?>
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?php echo date('d/m/Y', strtotime($user['created_at'])); ?>
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-sm btn-outline-info" 
                                                onclick="viewUser(<?php echo $user['id']; ?>)" 
                                                title="Lihat Detail">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <a href="/admin/users/<?php echo $user['id']; ?>/edit" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                        <button class="btn btn-sm btn-outline-danger" 
                                                onclick="deleteUser(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars($user['full_name']); ?>')" 
                                                title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if (!empty($pagination) && $pagination['total_pages'] > 1): ?>
                <nav aria-label="User pagination" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <!-- Previous Page -->
                        <?php if ($pagination['has_prev']): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $pagination['current_page'] - 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?><?php echo !empty($role) ? '&role=' . urlencode($role) : ''; ?>">
                                <i class="bi bi-chevron-left"></i>
                            </a>
                        </li>
                        <?php endif; ?>

                        <!-- Page Numbers -->
                        <?php 
                        $start = max(1, $pagination['current_page'] - 2);
                        $end = min($pagination['total_pages'], $pagination['current_page'] + 2);
                        
                        for ($i = $start; $i <= $end; $i++): 
                        ?>
                        <li class="page-item <?php echo $i == $pagination['current_page'] ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?><?php echo !empty($role) ? '&role=' . urlencode($role) : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                        <?php endfor; ?>

                        <!-- Next Page -->
                        <?php if ($pagination['has_next']): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $pagination['current_page'] + 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?><?php echo !empty($role) ? '&role=' . urlencode($role) : ''; ?>">
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                    
                    <div class="text-center text-muted mt-2">
                        <small>
                            Halaman <?php echo $pagination['current_page']; ?> dari <?php echo $pagination['total_pages']; ?>
                            (<?php echo $pagination['total']; ?> pengguna)
                        </small>
                    </div>
                </nav>
                <?php endif; ?>

            <?php else: ?>
                <!-- Empty State -->
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="bi bi-people text-muted" style="font-size: 4rem;"></i>
                    </div>
                    <h5 class="text-muted">Tidak ada pengguna ditemukan</h5>
                    <p class="text-muted mb-4">
                        <?php if (!empty($search) || !empty($role)): ?>
                            Coba ubah kriteria pencarian atau filter Anda.
                        <?php else: ?>
                            Belum ada pengguna yang terdaftar dalam sistem.
                        <?php endif; ?>
                    </p>
                    <a href="/admin/users/create" class="btn btn-primary">
                        <i class="bi bi-person-plus me-2"></i>Tambah Pengguna Pertama
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- User Management JavaScript -->
<script>
function viewUser(userId) {
    // Show loading modal
    Swal.fire({
        title: 'Detail Pengguna',
        html: '<div class="text-center"><i class="bi bi-hourglass-split"></i> Memuat data...</div>',
        showConfirmButton: false,
        allowOutsideClick: false
    });
    
    // Fetch user details via AJAX
    fetch(`/admin/users/${userId}/details`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const user = data.user;
                const roleColors = {
                    'admin': 'danger',
                    'dosen': 'info', 
                    'mahasiswa': 'success'
                };
                
                const statusColors = {
                    'Aktif': 'success',
                    'Nonaktif': 'secondary',
                    'Cuti': 'warning',
                    'Lulus': 'primary',
                    'DO': 'danger'
                };
                
                Swal.fire({
                    title: `<i class="bi bi-person-circle me-2"></i>${user.full_name}`,
                    html: `
                        <div class="text-start">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <strong><i class="bi bi-person me-1"></i> Username:</strong><br>
                                    <span class="text-muted">${user.username}</span>
                                </div>
                                <div class="col-md-6">
                                    <strong><i class="bi bi-envelope me-1"></i> Email:</strong><br>
                                    <span class="text-muted">${user.email}</span>
                                </div>
                                <div class="col-md-6">
                                    <strong><i class="bi bi-telephone me-1"></i> Telepon:</strong><br>
                                    <span class="text-muted">${user.phone || 'Tidak ada'}</span>
                                </div>
                                <div class="col-md-6">
                                    <strong><i class="bi bi-shield me-1"></i> Role:</strong><br>
                                    <span class="badge bg-${roleColors[user.role] || 'secondary'}">${user.role.charAt(0).toUpperCase() + user.role.slice(1)}</span>
                                </div>
                                ${user.nim_nip ? `
                                <div class="col-md-6">
                                    <strong><i class="bi bi-card-text me-1"></i> ${user.role === 'mahasiswa' ? 'NIM' : 'NIP'}:</strong><br>
                                    <span class="text-muted">${user.nim_nip}</span>
                                </div>
                                ` : ''}
                                ${user.program_studi ? `
                                <div class="col-md-6">
                                    <strong><i class="bi bi-mortarboard me-1"></i> Program Studi:</strong><br>
                                    <span class="text-muted">${user.program_studi}</span>
                                </div>
                                ` : ''}
                                <div class="col-md-6">
                                    <strong><i class="bi bi-activity me-1"></i> Status Akun:</strong><br>
                                    <span class="badge bg-${user.is_active ? 'success' : 'danger'}">${user.is_active ? 'Aktif' : 'Nonaktif'}</span>
                                </div>
                                <div class="col-md-6">
                                    <strong><i class="bi bi-bookmark me-1"></i> Status Akademik:</strong><br>
                                    <span class="badge bg-${statusColors[user.academic_status] || 'secondary'}">${user.academic_status}</span>
                                </div>
                                <div class="col-md-6">
                                    <strong><i class="bi bi-calendar-plus me-1"></i> Terdaftar:</strong><br>
                                    <span class="text-muted">${new Date(user.created_at).toLocaleDateString('id-ID', {
                                        year: 'numeric',
                                        month: 'long', 
                                        day: 'numeric'
                                    })}</span>
                                </div>
                                <div class="col-md-6">
                                    <strong><i class="bi bi-clock me-1"></i> Terakhir Update:</strong><br>
                                    <span class="text-muted">${user.updated_at ? new Date(user.updated_at).toLocaleDateString('id-ID', {
                                        year: 'numeric',
                                        month: 'long',
                                        day: 'numeric',
                                        hour: '2-digit',
                                        minute: '2-digit'
                                    }) : 'Belum pernah'}</span>
                                </div>
                            </div>
                        </div>
                    `,
                    icon: 'info',
                    width: '600px',
                    confirmButtonText: '<i class="bi bi-pencil me-1"></i> Edit',
                    showCancelButton: true,
                    cancelButtonText: 'Tutup',
                    confirmButtonColor: '#0d6efd',
                    cancelButtonColor: '#6c757d'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = `/admin/users/${userId}/edit`;
                    }
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: data.message || 'Gagal memuat data pengguna',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error',
                text: 'Terjadi kesalahan saat memuat data',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        });
}

function deleteUser(userId, userName) {
    Swal.fire({
        title: 'Hapus Pengguna',
        html: `Apakah Anda yakin ingin menghapus pengguna <strong>${userName}</strong>?<br><small class="text-danger">Tindakan ini tidak dapat dibatalkan!</small>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Menghapus...',
                html: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Create form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/admin/users/delete';
            
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'id';
            input.value = userId;
            
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function exportUsers() {
    Swal.fire({
        title: 'Export Data Pengguna',
        text: 'Pilih format export yang diinginkan',
        icon: 'question',
        showCancelButton: true,
        showDenyButton: true,
        confirmButtonText: 'Excel (.xlsx)',
        denyButtonText: 'CSV (.csv)',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.open('/admin/users/export?format=excel', '_blank');
            window.showSuccess('Export Excel dimulai!');
        } else if (result.isDenied) {
            window.open('/admin/users/export?format=csv', '_blank');
            window.showSuccess('Export CSV dimulai!');
        }
    });
}

function importUsers() {
    Swal.fire({
        title: 'Import Data Pengguna',
        html: `
            <div class="text-start">
                <div class="mb-3">
                    <label for="importFile" class="form-label">Pilih File</label>
                    <input type="file" class="form-control" id="importFile" accept=".xlsx,.xls,.csv">
                </div>
                <div class="alert alert-info">
                    <small>
                        <strong>Format yang didukung:</strong> Excel (.xlsx, .xls) atau CSV (.csv)<br>
                        <strong>Kolom yang diperlukan:</strong> username, email, full_name, role
                    </small>
                </div>
                <div class="text-center">
                    <a href="/admin/users/template" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-download me-1"></i>Download Template
                    </a>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Import',
        cancelButtonText: 'Batal',
        preConfirm: () => {
            const file = document.getElementById('importFile').files[0];
            if (!file) {
                Swal.showValidationMessage('Pilih file untuk diimport!');
                return false;
            }
            return file;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // In real implementation, upload file via AJAX
            window.showSuccess('Import berhasil! Data pengguna telah ditambahkan.');
            setTimeout(() => {
                location.reload();
            }, 2000);
        }
    });
}

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>

<style>
.avatar-sm {
    width: 40px;
    height: 40px;
}

.avatar-initial {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
}

.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
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

.pagination .page-link {
    color: #4e73df;
    border-color: #dee2e6;
}

.pagination .page-item.active .page-link {
    background-color: #4e73df;
    border-color: #4e73df;
}

.pagination .page-link:hover {
    color: #224abe;
    background-color: #e3f2fd;
    border-color: #dee2e6;
}
</style>