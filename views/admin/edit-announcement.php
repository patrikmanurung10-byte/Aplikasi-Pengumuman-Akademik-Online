<?php
// Admin Edit Announcement - Form Edit Pengumuman
?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-pencil me-2"></i>Edit Pengumuman
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="/admin/announcements">Kelola Pengumuman</a></li>
                    <li class="breadcrumb-item active">Edit: <?php echo htmlspecialchars(substr($announcement['title'], 0, 30)); ?>...</li>
                </ol>
            </nav>
        </div>
        <div class="btn-toolbar">
            <a href="/admin/announcements" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-file-text me-2"></i>Form Edit Pengumuman
                    </h6>
                </div>
                <div class="card-body">
                    <form action="/admin/announcements/<?php echo $announcement['id']; ?>/update" method="POST" id="editAnnouncementForm">
                        <div class="mb-3">
                            <label for="title" class="form-label">Judul Pengumuman <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" 
                                   value="<?php echo htmlspecialchars($announcement['title']); ?>" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="category_id" class="form-label">Kategori</label>
                                <select class="form-select" id="category_id" name="category_id">
                                    <option value="">Pilih Kategori</option>
                                    <?php if (!empty($categories)): ?>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?php echo $category['id']; ?>" 
                                                    <?php echo ($category['id'] == $announcement['category_id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($category['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="priority" class="form-label">Prioritas</label>
                                <select class="form-select" id="priority" name="priority">
                                    <option value="low" <?php echo ($announcement['priority'] == 'low') ? 'selected' : ''; ?>>Rendah</option>
                                    <option value="medium" <?php echo ($announcement['priority'] == 'medium') ? 'selected' : ''; ?>>Sedang</option>
                                    <option value="high" <?php echo ($announcement['priority'] == 'high') ? 'selected' : ''; ?>>Tinggi</option>
                                    <option value="urgent" <?php echo ($announcement['priority'] == 'urgent') ? 'selected' : ''; ?>>Mendesak</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Konten Pengumuman <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="content" name="content" rows="10" required><?php echo htmlspecialchars($announcement['content']); ?></textarea>
                            <div class="form-text">Gunakan Markdown untuk formatting teks.</div>
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
                            <a href="/admin/announcements" class="btn btn-outline-secondary">
                                <i class="bi bi-x-lg me-2"></i>Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Info -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-info-circle me-2"></i>Informasi Pengumuman
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <small class="text-muted">Dibuat Oleh</small>
                        <p class="mb-0"><?php echo htmlspecialchars($announcement['author_name'] ?? 'Unknown'); ?></p>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Tanggal Dibuat</small>
                        <p class="mb-0"><?php echo date('d F Y H:i', strtotime($announcement['created_at'])); ?></p>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Terakhir Diupdate</small>
                        <p class="mb-0"><?php echo date('d F Y H:i', strtotime($announcement['updated_at'])); ?></p>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Status</small>
                        <p class="mb-0">
                            <span class="badge bg-<?php echo $announcement['is_active'] ? 'success' : 'danger'; ?>">
                                <?php echo $announcement['is_active'] ? 'Aktif' : 'Nonaktif'; ?>
                            </span>
                        </p>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Views</small>
                        <p class="mb-0"><?php echo number_format($announcement['views'] ?? 0); ?> kali dilihat</p>
                    </div>
                </div>
            </div>
            
            <!-- Preview -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-eye me-2"></i>Preview
                    </h6>
                </div>
                <div class="card-body">
                    <div id="preview-content">
                        <!-- Initial preview will be loaded here -->
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
                    <h6>Format Markdown:</h6>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <code>**Bold**</code> → <strong>Bold</strong>
                        </li>
                        <li class="mb-2">
                            <code>*Italic*</code> → <em>Italic</em>
                        </li>
                        <li class="mb-2">
                            <code># Heading</code> → Judul
                        </li>
                        <li class="mb-2">
                            <code>- List item</code> → • List item
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Form validation and preview
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('editAnnouncementForm');
    const titleInput = document.getElementById('title');
    const contentInput = document.getElementById('content');
    const previewContent = document.getElementById('preview-content');
    
    // Real-time preview
    function updatePreview() {
        const title = titleInput.value;
        const content = contentInput.value;
        
        let preview = '';
        if (title) {
            preview += `<h5>${title}</h5>`;
        }
        if (content) {
            // Simple markdown to HTML conversion
            let htmlContent = content
                .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                .replace(/\*(.*?)\*/g, '<em>$1</em>')
                .replace(/^# (.*$)/gim, '<h6>$1</h6>')
                .replace(/^- (.*$)/gim, '• $1')
                .replace(/\n/g, '<br>');
            preview += `<div>${htmlContent}</div>`;
        }
        
        if (preview) {
            previewContent.innerHTML = preview;
        } else {
            previewContent.innerHTML = '<p class="text-muted">Preview akan muncul saat Anda mengetik...</p>';
        }
    }
    
    // Initial preview
    updatePreview();
    
    titleInput.addEventListener('input', updatePreview);
    contentInput.addEventListener('input', updatePreview);
    
    // Form submission
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