<?php
/**
 * Create Announcement View
 * Halaman untuk membuat pengumuman baru
 */

$page_title = $page_title ?? 'Buat Pengumuman';
$categories = $categories ?? [];
$active_menu = $active_menu ?? 'announcements';
?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">
            <i class="bi bi-plus-circle me-2"></i>Buat Pengumuman Baru
        </h4>
        <p class="text-muted mb-0">Tambahkan pengumuman untuk mahasiswa</p>
    </div>
    <a href="/dosen/announcements" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar
    </a>
</div>

<!-- Create Form -->
<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="bi bi-pencil-square me-2"></i>Form Pengumuman
                </h6>
            </div>
            <div class="card-body">
                <form action="/dosen/announcements/store" method="POST" id="announcementForm">
                    <!-- CSRF Token -->
                    <input type="hidden" name="csrf_token" value="<?= \App\Core\Session::getCsrfToken() ?>">
                    
                    <!-- Title -->
                    <div class="mb-3">
                        <label for="title" class="form-label">
                            <i class="bi bi-type me-1"></i>Judul Pengumuman <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="title" 
                               name="title" 
                               placeholder="Masukkan judul pengumuman..."
                               value="<?= \App\Core\Session::getOldInput('title') ?>"
                               required>
                        <div class="form-text">Judul yang menarik dan informatif</div>
                    </div>

                    <!-- Row for Category and Priority -->
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Category -->
                            <div class="mb-3">
                                <label for="category_id" class="form-label">
                                    <i class="bi bi-folder me-1"></i>Kategori
                                </label>
                                <select class="form-select" id="category_id" name="category_id">
                                    <option value="">Pilih Kategori (Opsional)</option>
                                    <?php if (!empty($categories)): ?>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?= $category['id'] ?>" 
                                                    <?= \App\Core\Session::getOldInput('category_id') == $category['id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($category['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <div class="form-text">Pilih kategori yang sesuai</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Priority -->
                            <div class="mb-3">
                                <label for="priority" class="form-label">
                                    <i class="bi bi-exclamation-triangle me-1"></i>Prioritas
                                </label>
                                <select class="form-select" id="priority" name="priority">
                                    <option value="low" <?= \App\Core\Session::getOldInput('priority', 'medium') == 'low' ? 'selected' : '' ?>>
                                        Rendah
                                    </option>
                                    <option value="medium" <?= \App\Core\Session::getOldInput('priority', 'medium') == 'medium' ? 'selected' : '' ?>>
                                        Sedang
                                    </option>
                                    <option value="high" <?= \App\Core\Session::getOldInput('priority', 'medium') == 'high' ? 'selected' : '' ?>>
                                        Tinggi
                                    </option>
                                </select>
                                <div class="form-text">Tingkat prioritas pengumuman</div>
                            </div>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="mb-3">
                        <label for="content" class="form-label">
                            <i class="bi bi-text-paragraph me-1"></i>Isi Pengumuman <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control" 
                                  id="content" 
                                  name="content" 
                                  rows="6" 
                                  placeholder="Tulis isi pengumuman di sini..."
                                  required><?= \App\Core\Session::getOldInput('content') ?></textarea>
                        <div class="form-text">Jelaskan pengumuman dengan detail dan jelas</div>
                    </div>

                    <!-- Row for Dates -->
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Publish Date -->
                            <div class="mb-3">
                                <label for="publish_date" class="form-label">
                                    <i class="bi bi-calendar me-1"></i>Tanggal Publikasi
                                </label>
                                <input type="datetime-local" 
                                       class="form-control" 
                                       id="publish_date" 
                                       name="publish_date"
                                       value="<?= \App\Core\Session::getOldInput('publish_date', date('Y-m-d\TH:i')) ?>">
                                <div class="form-text">Kosongkan untuk publikasi sekarang</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Expire Date -->
                            <div class="mb-3">
                                <label for="expire_date" class="form-label">
                                    <i class="bi bi-calendar-x me-1"></i>Tanggal Kedaluwarsa
                                </label>
                                <input type="datetime-local" 
                                       class="form-control" 
                                       id="expire_date" 
                                       name="expire_date"
                                       value="<?= \App\Core\Session::getOldInput('expire_date') ?>">
                                <div class="form-text">Kosongkan jika tidak ada batas waktu</div>
                            </div>
                        </div>
                    </div>

                    <!-- Options -->
                    <div class="mb-4">
                        <div class="form-check mb-2">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="is_featured" 
                                   name="is_featured" 
                                   value="1"
                                   <?= \App\Core\Session::getOldInput('is_featured') ? 'checked' : '' ?>>
                            <label class="form-check-label" for="is_featured">
                                <i class="bi bi-star me-1"></i>Tampilkan sebagai pengumuman unggulan
                            </label>
                        </div>
                        
                        <div class="form-check mb-2">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="is_pinned" 
                                   name="is_pinned" 
                                   value="1"
                                   <?= \App\Core\Session::getOldInput('is_pinned') ? 'checked' : '' ?>>
                            <label class="form-check-label" for="is_pinned">
                                <i class="bi bi-pin me-1"></i>Pin pengumuman di bagian atas
                            </label>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="d-flex flex-column flex-md-row gap-2">
                        <button type="submit" name="status" value="published" class="btn btn-primary btn-mobile-full">
                            <i class="bi bi-check-circle me-2"></i>Publikasikan
                        </button>
                        <button type="submit" name="status" value="draft" class="btn btn-secondary btn-mobile-full">
                            <i class="bi bi-file-earmark me-2"></i>Simpan sebagai Draft
                        </button>
                        <a href="/dosen/announcements" class="btn btn-outline-secondary btn-mobile-full">
                            <i class="bi bi-x-circle me-2"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Preview Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-info">
                    <i class="bi bi-eye me-2"></i>Preview
                </h6>
            </div>
            <div class="card-body">
                <div id="preview-content">
                    <h6 id="preview-title" class="text-muted">Judul akan muncul di sini...</h6>
                    <div id="preview-category" class="mb-2"></div>
                    <div id="preview-priority" class="mb-2"></div>
                    <div id="preview-text" class="text-muted">Isi pengumuman akan muncul di sini...</div>
                </div>
            </div>
        </div>

        <!-- Tips Card -->
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-success">
                    <i class="bi bi-lightbulb me-2"></i>Tips Menulis Pengumuman
                </h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="bi bi-check text-success me-2"></i>
                        Gunakan judul yang jelas dan menarik
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check text-success me-2"></i>
                        Pilih kategori yang sesuai
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check text-success me-2"></i>
                        Tulis isi yang informatif dan mudah dipahami
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check text-success me-2"></i>
                        Tentukan prioritas dengan tepat
                    </li>
                    <li class="mb-0">
                        <i class="bi bi-check text-success me-2"></i>
                        Periksa kembali sebelum publikasi
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Preview functionality
    const titleInput = document.getElementById('title');
    const contentInput = document.getElementById('content');
    const categorySelect = document.getElementById('category_id');
    const prioritySelect = document.getElementById('priority');
    
    const previewTitle = document.getElementById('preview-title');
    const previewContent = document.getElementById('preview-text');
    const previewCategory = document.getElementById('preview-category');
    const previewPriority = document.getElementById('preview-priority');
    
    function updatePreview() {
        // Update title
        const title = titleInput.value.trim();
        previewTitle.textContent = title || 'Judul akan muncul di sini...';
        previewTitle.className = title ? 'fw-bold' : 'text-muted';
        
        // Update content
        const content = contentInput.value.trim();
        previewContent.textContent = content || 'Isi pengumuman akan muncul di sini...';
        previewContent.className = content ? '' : 'text-muted';
        
        // Update category
        const categoryText = categorySelect.options[categorySelect.selectedIndex].text;
        if (categorySelect.value) {
            previewCategory.innerHTML = `<span class="badge bg-info">${categoryText}</span>`;
        } else {
            previewCategory.innerHTML = '';
        }
        
        // Update priority
        const priorityText = prioritySelect.options[prioritySelect.selectedIndex].text;
        let priorityClass = 'bg-secondary';
        switch(prioritySelect.value) {
            case 'high': priorityClass = 'bg-danger'; break;
            case 'medium': priorityClass = 'bg-warning'; break;
            case 'low': priorityClass = 'bg-info'; break;
        }
        previewPriority.innerHTML = `<span class="badge ${priorityClass}">Prioritas: ${priorityText}</span>`;
    }
    
    // Add event listeners
    titleInput.addEventListener('input', updatePreview);
    contentInput.addEventListener('input', updatePreview);
    categorySelect.addEventListener('change', updatePreview);
    prioritySelect.addEventListener('change', updatePreview);
    
    // Initial preview update
    updatePreview();
    
    // Form validation
    const form = document.getElementById('announcementForm');
    form.addEventListener('submit', function(e) {
        const title = titleInput.value.trim();
        const content = contentInput.value.trim();
        
        if (!title) {
            e.preventDefault();
            alert('Judul pengumuman harus diisi!');
            titleInput.focus();
            return false;
        }
        
        if (!content) {
            e.preventDefault();
            alert('Isi pengumuman harus diisi!');
            contentInput.focus();
            return false;
        }
        
        // Show loading state
        const submitButtons = form.querySelectorAll('button[type="submit"]');
        submitButtons.forEach(btn => {
            btn.disabled = true;
            btn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Menyimpan...';
        });
    });
    
    // Character counter for content
    const maxLength = 5000;
    const counter = document.createElement('div');
    counter.className = 'form-text text-end';
    counter.id = 'content-counter';
    contentInput.parentNode.appendChild(counter);
    
    function updateCounter() {
        const length = contentInput.value.length;
        counter.textContent = `${length}/${maxLength} karakter`;
        counter.className = length > maxLength ? 'form-text text-end text-danger' : 'form-text text-end';
    }
    
    contentInput.addEventListener('input', updateCounter);
    updateCounter();
});
</script>

<style>
.form-label {
    font-weight: 600;
    color: #5a5c69;
}

.form-control:focus, .form-select:focus {
    border-color: #bac8f3;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

.card {
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-2px);
}

#preview-content {
    min-height: 150px;
}

.btn {
    font-weight: 500;
}

.form-check-input:checked {
    background-color: #4e73df;
    border-color: #4e73df;
}

.badge {
    font-size: 0.75rem;
}
</style>