<?php
// Student Dashboard
?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-speedometer2 me-2"></i>Dashboard Mahasiswa
            </h1>
            <p class="mb-0 text-muted">Selamat datang di portal mahasiswa APAO</p>
        </div>
        <div class="btn-toolbar">
            <a href="/student/announcements" class="btn btn-primary">
                <i class="bi bi-megaphone me-1"></i>Lihat Pengumuman
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-6 col-md-6 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Pengumuman Baru
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo $stats['new_announcements'] ?? 0; ?>
                            </div>
                        </div>
                        <div class="col-auto d-none d-md-block">
                            <i class="bi bi-bell fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-6 col-md-6 mb-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Pengumuman
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo $stats['total_announcements'] ?? 0; ?>
                            </div>
                        </div>
                        <div class="col-auto d-none d-md-block">
                            <i class="bi bi-megaphone fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Quick Access -->
        <div class="col-lg-4 col-md-6 mb-4 order-lg-1 order-2">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-lightning me-2"></i>Akses Cepat
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="/student/announcements" class="btn btn-primary">
                            <i class="bi bi-megaphone me-2"></i>Semua Pengumuman
                        </a>
                        <a href="/student/announcements?category=akademik" class="btn btn-success">
                            <i class="bi bi-mortarboard me-2"></i>Pengumuman Akademik
                        </a>
                    </div>
                </div>
            </div>

            <!-- Categories -->
            <div class="card shadow mt-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="bi bi-tags me-2"></i>Kategori Pengumuman
                    </h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="/student/announcements?category=akademik" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-mortarboard me-2 text-primary"></i>
                                Akademik
                            </div>
                            <span class="badge bg-primary rounded-pill"><?php echo $stats['categories']['akademik'] ?? 0; ?></span>
                        </a>
                        <a href="/student/announcements?category=beasiswa" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-award me-2 text-success"></i>
                                Beasiswa
                            </div>
                            <span class="badge bg-success rounded-pill"><?php echo $stats['categories']['beasiswa'] ?? 0; ?></span>
                        </a>
                        <a href="/student/announcements?category=acara" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-calendar-event me-2 text-info"></i>
                                Acara
                            </div>
                            <span class="badge bg-info rounded-pill"><?php echo $stats['categories']['acara'] ?? 0; ?></span>
                        </a>
                        <a href="/student/announcements?category=administrasi" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-file-text me-2 text-warning"></i>
                                Administrasi
                            </div>
                            <span class="badge bg-warning rounded-pill"><?php echo $stats['categories']['administrasi'] ?? 0; ?></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Announcements -->
        <div class="col-lg-8 col-md-6 mb-4 order-lg-2 order-1">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                    <h6 class="m-0 font-weight-bold text-primary mb-2 mb-md-0">
                        <i class="bi bi-clock-history me-2"></i>Pengumuman Terbaru
                    </h6>
                    <a href="/student/announcements" class="btn btn-sm btn-primary">
                        Lihat Semua
                    </a>
                </div>
                <div class="card-body">
                    <?php if (!empty($stats['recent_announcements'])): ?>
                        <div class="timeline">
                            <?php foreach ($stats['recent_announcements'] as $index => $announcement): ?>
                            <div class="timeline-item">
                                <div class="timeline-marker bg-<?php echo ['primary', 'success', 'info', 'warning'][$index % 4]; ?>">
                                    <i class="bi bi-megaphone"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start mb-2">
                                        <h6 class="mb-1 mb-md-0">
                                            <a href="/announcements/<?php echo $announcement['id']; ?>" class="text-decoration-none">
                                                <?php echo htmlspecialchars($announcement['title']); ?>
                                            </a>
                                        </h6>
                                        <small class="text-muted"><?php echo date('d/m/Y H:i', strtotime($announcement['publish_date'])); ?></small>
                                    </div>
                                    <p class="mb-2 text-muted">
                                        <?php echo htmlspecialchars(substr(strip_tags($announcement['content']), 0, 150)); ?>...
                                    </p>
                                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                                        <div class="mb-2 mb-md-0">
                                            <span class="badge bg-secondary me-2"><?php echo htmlspecialchars($announcement['category_name'] ?? 'Umum'); ?></span>
                                            <span class="badge bg-<?php echo $announcement['priority'] === 'urgent' ? 'danger' : ($announcement['priority'] === 'high' ? 'warning' : 'info'); ?>">
                                                <?php echo ucfirst($announcement['priority'] ?? 'medium'); ?>
                                            </span>
                                        </div>
                                        <div class="btn-group" role="group">
                                            <a href="/announcements/<?php echo $announcement['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye me-1"></i>Baca
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="bi bi-megaphone text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-2">Belum ada pengumuman terbaru</p>
                            <a href="/student/announcements" class="btn btn-primary">
                                <i class="bi bi-search me-2"></i>Cari Pengumuman
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>


</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 20px;
    width: 2px;
    background: #e3e6f0;
}

.timeline-item {
    position: relative;
    margin-bottom: 30px;
}

.timeline-marker {
    position: absolute;
    left: -23px;
    top: 0;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 8px;
    color: #fff;
}

.timeline-content {
    background: #fff;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    border-left: 3px solid #e3e6f0;
}

.border-left-primary {
    border-left: 0.25rem solid #198754 !important;
}

.border-left-success {
    border-left: 0.25rem solid #198754 !important;
}

.border-left-info {
    border-left: 0.25rem solid #0dcaf0 !important;
}

.border-left-warning {
    border-left: 0.25rem solid #ffc107 !important;
}

.border-left-danger {
    border-left: 0.25rem solid #dc3545 !important;
}

.list-group-item {
    border: none;
    padding: 0.75rem 0;
}

.list-group-item:hover {
    background-color: #f8f9fc;
}

.btn-group .btn {
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}
</style>