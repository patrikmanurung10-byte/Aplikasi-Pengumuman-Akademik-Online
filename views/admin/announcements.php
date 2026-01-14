<?php
// Admin Announcements - Kelola Pengumuman
?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-megaphone me-2"></i>Kelola Pengumuman
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active">Kelola Pengumuman</li>
                </ol>
            </nav>
        </div>
        <div class="btn-toolbar">
            <a href="/admin/announcements/create" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>Tambah Pengumuman
            </a>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="/admin/announcements" class="row g-3">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" name="search" 
                               placeholder="Cari pengumuman..." 
                               value="<?php echo htmlspecialchars($search ?? ''); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-grid gap-2 d-md-flex">
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="bi bi-search me-1"></i>Cari
                        </button>
                        <a href="/admin/announcements" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise me-1"></i>Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Announcements Table -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="bi bi-list me-2"></i>Daftar Pengumuman
            </h6>
        </div>
        <div class="card-body">
            <?php if (!empty($announcements)): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="30%">Judul</th>
                                <th width="15%">Kategori</th>
                                <th width="15%">Penulis</th>
                                <th width="10%">Status</th>
                                <th width="10%">Tanggal</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($announcements as $index => $announcement): ?>
                                <tr>
                                    <td><?php echo $index + 1; ?></td>
                                    <td>
                                        <div class="fw-bold"><?php echo htmlspecialchars($announcement['title']); ?></div>
                                        <small class="text-muted">
                                            <?php echo htmlspecialchars(substr($announcement['excerpt'] ?? '', 0, 100)); ?>...
                                        </small>
                                    </td>
                                    <td>
                                        <?php if (!empty($announcement['category_name'])): ?>
                                            <span class="badge bg-info"><?php echo htmlspecialchars($announcement['category_name']); ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Tanpa Kategori</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <small><?php echo htmlspecialchars($announcement['author_name'] ?? 'Unknown'); ?></small>
                                    </td>
                                    <td>
                                        <?php if ($announcement['is_active']): ?>
                                            <span class="badge bg-success">Aktif</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Nonaktif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <small><?php echo date('d/m/Y', strtotime($announcement['created_at'])); ?></small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="/admin/announcements/<?php echo $announcement['id']; ?>/edit" 
                                               class="btn btn-sm btn-outline-primary" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    title="Hapus"
                                                    onclick="confirmDelete(<?php echo $announcement['id']; ?>, '<?php echo htmlspecialchars($announcement['title']); ?>')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if (!empty($pagination) && $pagination['total_pages'] > 1): ?>
                    <nav aria-label="Pagination">
                        <ul class="pagination justify-content-center mt-3">
                            <?php if ($pagination['has_prev']): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $pagination['current_page'] - 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>">
                                        <i class="bi bi-chevron-left"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                            
                            <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                                <li class="page-item <?php echo ($i == $pagination['current_page']) ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                            
                            <?php if ($pagination['has_next']): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $pagination['current_page'] + 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>">
                                        <i class="bi bi-chevron-right"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>

            <?php else: ?>
                <div class="text-center py-5">
                    <i class="bi bi-megaphone text-muted" style="font-size: 4rem;"></i>
                    <h5 class="mt-3 text-muted">Belum ada pengumuman</h5>
                    <p class="text-muted">Mulai dengan membuat pengumuman pertama Anda.</p>
                    <a href="/admin/announcements/create" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>Tambah Pengumuman
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function confirmDelete(id, title) {
    Swal.fire({
        title: 'Hapus Pengumuman',
        html: `Apakah Anda yakin ingin menghapus pengumuman <strong>"${title}"</strong>?<br><small class="text-muted">Tindakan ini tidak dapat dibatalkan.</small>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d'
    }).then((result) => {
        if (result.isConfirmed) {
            deleteAnnouncement(id);
        }
    });
}

function deleteAnnouncement(id) {
    // Show loading
    Swal.fire({
        title: 'Menghapus...',
        text: 'Sedang menghapus pengumuman',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // Create form and submit
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/admin/announcements/' + id + '/delete';
    
    document.body.appendChild(form);
    form.submit();
}
</script>