<?php
/**
 * Dosen Announcements View
 * Halaman untuk melihat semua pengumuman dosen
 */

$page_title = $page_title ?? 'Semua Pengumuman';
$announcements = $announcements ?? [];
$active_menu = $active_menu ?? 'announcements';
?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">
            <i class="bi bi-megaphone me-2"></i>Semua Pengumuman
        </h4>
        <p class="text-muted mb-0">Kelola pengumuman yang telah Anda buat</p>
    </div>
    <a href="/dosen/announcements/create" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Buat Pengumuman Baru
    </a>
</div>

<!-- Filter & Search -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-search"></i></span>
            <input type="text" class="form-control" placeholder="Cari pengumuman..." id="searchInput">
        </div>
    </div>
    <div class="col-md-3">
        <select class="form-select" id="statusFilter">
            <option value="">Semua Status</option>
            <option value="published">Published</option>
            <option value="draft">Draft</option>
        </select>
    </div>
    <div class="col-md-3">
        <select class="form-select" id="sortBy">
            <option value="created_at">Terbaru</option>
            <option value="title">Judul A-Z</option>
            <option value="priority">Prioritas</option>
        </select>
    </div>
</div>

<!-- Announcements List -->
<div class="card shadow">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="bi bi-list-ul me-2"></i>Daftar Pengumuman
        </h6>
    </div>
    <div class="card-body">
        <?php if (!empty($announcements)): ?>
            <div class="table-responsive">
                <table class="table table-hover" id="announcementsTable">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Status</th>
                            <th class="d-none-mobile">Kategori</th>
                            <th>Prioritas</th>
                            <th class="d-none-mobile">Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($announcements as $announcement): ?>
                            <tr>
                                <td data-label="Judul">
                                    <div class="fw-bold"><?= htmlspecialchars($announcement['title']) ?></div>
                                    <small class="text-muted">
                                        <?= htmlspecialchars(substr(strip_tags($announcement['content']), 0, 100)) ?>...
                                    </small>
                                </td>
                                <td data-label="Status">
                                    <?php if ($announcement['status'] === 'published'): ?>
                                        <span class="badge bg-success">Published</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">Draft</span>
                                    <?php endif; ?>
                                </td>
                                <td data-label="Kategori" class="d-none-mobile">
                                    <?= htmlspecialchars($announcement['category_name'] ?? 'Umum') ?>
                                </td>
                                <td data-label="Prioritas">
                                    <?php
                                    $priority_class = '';
                                    $priority_text = '';
                                    switch($announcement['priority']) {
                                        case 'high':
                                            $priority_class = 'bg-danger';
                                            $priority_text = 'Tinggi';
                                            break;
                                        case 'medium':
                                            $priority_class = 'bg-warning';
                                            $priority_text = 'Sedang';
                                            break;
                                        case 'low':
                                            $priority_class = 'bg-info';
                                            $priority_text = 'Rendah';
                                            break;
                                        default:
                                            $priority_class = 'bg-secondary';
                                            $priority_text = 'Normal';
                                    }
                                    ?>
                                    <span class="badge <?= $priority_class ?>"><?= $priority_text ?></span>
                                </td>
                                <td data-label="Dibuat" class="d-none-mobile">
                                    <small>
                                        <?= date('d/m/Y H:i', strtotime($announcement['created_at'])) ?>
                                    </small>
                                </td>
                                <td data-label="Aksi">
                                    <div class="btn-group" role="group">
                                        <a href="/dosen/announcements/<?= $announcement['id'] ?>/edit" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="Edit">
                                            <i class="bi bi-pencil"></i>
                                            <span class="d-mobile-only ms-1">Edit</span>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-danger" 
                                                onclick="confirmDelete(<?= $announcement['id'] ?>, '<?= addslashes($announcement['title']) ?>')"
                                                title="Hapus">
                                            <i class="bi bi-trash"></i>
                                            <span class="d-mobile-only ms-1">Hapus</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="bi bi-megaphone" style="font-size: 4rem; color: #dee2e6;"></i>
                <h5 class="mt-3 text-muted">Belum Ada Pengumuman</h5>
                <p class="text-muted">Mulai buat pengumuman pertama Anda</p>
                <a href="/dosen/announcements/create" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Buat Pengumuman
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus pengumuman:</p>
                <p class="fw-bold" id="deleteTitle"></p>
                <p class="text-danger">Tindakan ini tidak dapat dibatalkan!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Search functionality
document.getElementById('searchInput').addEventListener('keyup', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('#announcementsTable tbody tr');
    
    rows.forEach(row => {
        const title = row.querySelector('td:first-child').textContent.toLowerCase();
        if (title.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Status filter
document.getElementById('statusFilter').addEventListener('change', function() {
    const filterValue = this.value.toLowerCase();
    const rows = document.querySelectorAll('#announcementsTable tbody tr');
    
    rows.forEach(row => {
        if (filterValue === '') {
            row.style.display = '';
        } else {
            const status = row.querySelector('td:nth-child(2) .badge').textContent.toLowerCase();
            if (status.includes(filterValue)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        }
    });
});

// Delete confirmation
function confirmDelete(id, title) {
    document.getElementById('deleteTitle').textContent = title;
    document.getElementById('deleteForm').action = '/dosen/announcements/' + id + '/delete';
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

// Sort functionality
document.getElementById('sortBy').addEventListener('change', function() {
    const sortBy = this.value;
    const tbody = document.querySelector('#announcementsTable tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    
    rows.sort((a, b) => {
        let aValue, bValue;
        
        switch(sortBy) {
            case 'title':
                aValue = a.querySelector('td:first-child .fw-bold').textContent;
                bValue = b.querySelector('td:first-child .fw-bold').textContent;
                return aValue.localeCompare(bValue);
                
            case 'priority':
                const priorityOrder = {'Tinggi': 4, 'Sedang': 3, 'Rendah': 2, 'Normal': 1};
                aValue = priorityOrder[a.querySelector('td:nth-child(4) .badge').textContent] || 0;
                bValue = priorityOrder[b.querySelector('td:nth-child(4) .badge').textContent] || 0;
                return bValue - aValue;
                
            case 'created_at':
            default:
                // Default sort by date (newest first)
                return 0; // Keep original order for now
        }
    });
    
    // Re-append sorted rows
    rows.forEach(row => tbody.appendChild(row));
});
</script>

<style>
.table th {
    border-top: none;
    border-bottom: 2px solid #e3e6f0;
    color: #858796;
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.table td {
    border-top: 1px solid #e3e6f0;
    vertical-align: middle;
}

.table tbody tr:hover {
    background-color: #f8f9fc;
}

.btn-group .btn {
    border-radius: 0.25rem;
    margin-right: 2px;
}

.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
}

.input-group-text {
    background-color: #f8f9fc;
    border-color: #d1d3e2;
}

.form-control, .form-select {
    border-color: #d1d3e2;
}

.form-control:focus, .form-select:focus {
    border-color: #bac8f3;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}
</style>