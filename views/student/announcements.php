<?php
// Student Announcements - Lihat Semua Pengumuman
?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-megaphone me-2"></i>Semua Pengumuman
            </h1>
            <p class="mb-0 text-muted">Informasi terbaru untuk mahasiswa</p>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="/student/announcements" class="row g-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" name="search" 
                               placeholder="Cari pengumuman..." 
                               value="<?php echo htmlspecialchars($current_search ?? ''); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <select class="form-select" name="category">
                        <option value="">Semua Kategori</option>
                        <?php if (!empty($categories)): ?>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['slug']; ?>" 
                                        <?php echo ($category['slug'] == ($current_category ?? '')) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-1"></i>Cari
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Announcements List -->
    <?php if (!empty($announcements)): ?>
        <div class="row">
            <?php foreach ($announcements as $announcement): ?>
                <div class="col-lg-6 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <?php if (!empty($announcement['category_name'])): ?>
                                    <span class="badge bg-primary me-2"><?php echo htmlspecialchars($announcement['category_name']); ?></span>
                                <?php endif; ?>
                                <?php if ($announcement['priority'] == 'urgent'): ?>
                                    <span class="badge bg-danger">Mendesak</span>
                                <?php elseif ($announcement['priority'] == 'high'): ?>
                                    <span class="badge bg-warning">Penting</span>
                                <?php endif; ?>
                            </div>
                            <small class="text-muted">
                                <?php echo date('d M Y', strtotime($announcement['publish_date'] ?? $announcement['created_at'])); ?>
                            </small>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="/student/announcements/<?php echo $announcement['id']; ?>" 
                                   class="text-decoration-none">
                                    <?php echo htmlspecialchars($announcement['title']); ?>
                                </a>
                            </h5>
                            <p class="card-text text-muted">
                                <?php echo htmlspecialchars(substr($announcement['excerpt'] ?? $announcement['content'], 0, 150)); ?>...
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="bi bi-person me-1"></i>
                                    <?php echo htmlspecialchars($announcement['author_name'] ?? 'Admin'); ?>
                                </small>
                                <div>
                                    <small class="text-muted me-3">
                                        <i class="bi bi-eye me-1"></i>
                                        <?php echo number_format($announcement['views'] ?? 0); ?>
                                    </small>
                                    <a href="/student/announcements/<?php echo $announcement['id']; ?>" 
                                       class="btn btn-sm btn-outline-primary">
                                        Baca Selengkapnya
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="card shadow">
            <div class="card-body text-center py-5">
                <i class="bi bi-megaphone text-muted" style="font-size: 4rem;"></i>
                <h5 class="mt-3 text-muted">Tidak ada pengumuman</h5>
                <?php if (!empty($current_search) || !empty($current_category)): ?>
                    <p class="text-muted">Tidak ditemukan pengumuman dengan kriteria pencarian Anda.</p>
                    <a href="/student/announcements" class="btn btn-primary">
                        <i class="bi bi-arrow-clockwise me-1"></i>Lihat Semua Pengumuman
                    </a>
                <?php else: ?>
                    <p class="text-muted">Belum ada pengumuman yang dipublikasikan.</p>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
}

.card-title a:hover {
    color: #0d6efd !important;
}
</style>