<?php
// Student Announcement Detail - Detail Pengumuman
?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="/student/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="/student/announcements">Pengumuman</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </nav>
        </div>
        <div class="btn-toolbar">
            <a href="/student/announcements" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Main Content -->
            <div class="card shadow">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <?php if (!empty($announcement['category_name'])): ?>
                                <span class="badge bg-primary me-2"><?php echo htmlspecialchars($announcement['category_name']); ?></span>
                            <?php endif; ?>
                            <?php if ($announcement['priority'] == 'urgent'): ?>
                                <span class="badge bg-danger">Mendesak</span>
                            <?php elseif ($announcement['priority'] == 'high'): ?>
                                <span class="badge bg-warning">Penting</span>
                            <?php elseif ($announcement['priority'] == 'medium'): ?>
                                <span class="badge bg-info">Sedang</span>
                            <?php endif; ?>
                        </div>
                        <small class="text-muted">
                            <i class="bi bi-eye me-1"></i>
                            <?php echo number_format($announcement['views'] ?? 0); ?> views
                        </small>
                    </div>
                </div>
                <div class="card-body">
                    <h1 class="h3 mb-3"><?php echo htmlspecialchars($announcement['title']); ?></h1>
                    
                    <div class="mb-4 pb-3 border-bottom">
                        <div class="row text-muted">
                            <div class="col-md-6">
                                <small>
                                    <i class="bi bi-person me-1"></i>
                                    <?php echo htmlspecialchars($announcement['author_name'] ?? 'Admin'); ?>
                                </small>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <small>
                                    <i class="bi bi-calendar me-1"></i>
                                    <?php echo date('d F Y, H:i', strtotime($announcement['publish_date'] ?? $announcement['created_at'])); ?>
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="content">
                        <?php 
                        // Simple markdown to HTML conversion
                        $content = $announcement['content'];
                        $content = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $content);
                        $content = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $content);
                        $content = preg_replace('/^# (.*$)/m', '<h4>$1</h4>', $content);
                        $content = preg_replace('/^## (.*$)/m', '<h5>$1</h5>', $content);
                        $content = preg_replace('/^- (.*$)/m', '• $1', $content);
                        $content = nl2br($content);
                        echo $content;
                        ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Related Announcements -->
            <?php if (!empty($related)): ?>
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="bi bi-collection me-2"></i>Pengumuman Terkait
                        </h6>
                    </div>
                    <div class="card-body">
                        <?php foreach ($related as $relatedAnnouncement): ?>
                            <div class="mb-3 pb-3 <?php echo ($relatedAnnouncement !== end($related)) ? 'border-bottom' : ''; ?>">
                                <h6 class="mb-1">
                                    <a href="/student/announcements/<?php echo $relatedAnnouncement['id']; ?>" 
                                       class="text-decoration-none">
                                        <?php echo htmlspecialchars($relatedAnnouncement['title']); ?>
                                    </a>
                                </h6>
                                <small class="text-muted">
                                    <?php echo date('d M Y', strtotime($relatedAnnouncement['created_at'])); ?>
                                </small>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Quick Actions -->
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-lightning me-2"></i>Aksi Cepat
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="/student/announcements" class="btn btn-outline-primary">
                            <i class="bi bi-list me-2"></i>Lihat Semua Pengumuman
                        </a>
                        <a href="/student/dashboard" class="btn btn-outline-secondary">
                            <i class="bi bi-house me-2"></i>Kembali ke Dashboard
                        </a>
                        <button class="btn btn-outline-info" onclick="shareAnnouncement()">
                            <i class="bi bi-share me-2"></i>Bagikan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function shareAnnouncement() {
    if (navigator.share) {
        navigator.share({
            title: '<?php echo htmlspecialchars($announcement['title']); ?>',
            text: '<?php echo htmlspecialchars(substr($announcement['excerpt'] ?? $announcement['content'], 0, 100)); ?>...',
            url: window.location.href
        });
    } else {
        // Fallback: copy to clipboard
        navigator.clipboard.writeText(window.location.href).then(function() {
            Swal.fire({
                title: 'Link Disalin!',
                text: 'Link pengumuman telah disalin ke clipboard',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
        });
    }
}
</script>

<style>
.content {
    line-height: 1.8;
    font-size: 1.1rem;
}

.content h4, .content h5 {
    margin-top: 2rem;
    margin-bottom: 1rem;
    color: #495057;
}

.content strong {
    color: #212529;
}

.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-1px);
}
</style>