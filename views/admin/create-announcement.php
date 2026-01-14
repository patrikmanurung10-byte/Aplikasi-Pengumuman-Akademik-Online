<?php
// Admin Create Announcement - Form Tambah Pengumuman
?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-plus-circle me-2"></i>Tambah Pengumuman
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="/admin/announcements">Kelola Pengumuman</a></li>
                    <li class="breadcrumb-item active">Tambah Pengumuman</li>
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
                        <i class="bi bi-file-text me-2"></i>Form Pengumuman Baru
                    </h6>
                </div>
                <div class="card-body">
                    <form action="/admin/announcements/store" method="POST" id="createAnnouncementForm">
                        <div class="mb-3">
                            <label for="title" class="form-label">Judul Pengumuman <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="category_id" class="form-label">Kategori</label>
                                <select class="form-select" id="category_id" name="category_id">
                                    <option value="">Pilih Kategori</option>
                                    <?php if (!empty($categories)): ?>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?php echo $category['id']; ?>">
                                                <?php echo htmlspecialchars($category['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="priority" class="form-label">Prioritas</label>
                                <select class="form-select" id="priority" name="priority">
                                    <option value="low">Rendah</option>
                                    <option value="medium" selected>Sedang</option>
                                    <option value="high">Tinggi</option>
                                    <option value="urgent">Mendesak</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Konten Pengumuman <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="content" name="content" rows="10" required></textarea>
                            <div class="form-text">Gunakan Markdown untuk formatting teks.</div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg me-2"></i>Simpan Pengumuman
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
            <!-- Preview -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-eye me-2"></i>Preview
                    </h6>
                </div>
                <div class="card-body">
                    <div id="preview-content">
                        <p class="text-muted">Preview akan muncul saat Anda mengetik...</p>
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
    const form = document.getElementById('createAnnouncementForm');
    const titleInput = document.getElementById('title');
    const contentInput = document.getElementById('content');
    const previewContent = document.getElementById('preview-content');
    
    // Real-time preview
    function updatePreview() {
        const title = titleInput.value;
        const content = contentInput.value;
        
        if (title || content) {
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
            previewContent.innerHTML = preview;
        } else {
            previewContent.innerHTML = '<p class="text-muted">Preview akan muncul saat Anda mengetik...</p>';
        }
    }
    
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