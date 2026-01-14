<?php
$page_title = 'Error';
$body_class = 'd-flex align-items-center justify-content-center min-vh-100';
$inline_css = 'body { background: linear-gradient(135deg, #e8f3ff, #f8faff); }';
include 'templates/header.php';
?>

<div class="text-center">
    <div class="card shadow-lg" style="max-width: 500px;">
        <div class="card-body p-5">
            <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 4rem;"></i>
            <h2 class="mt-3 mb-3">Oops! Terjadi Kesalahan</h2>
            <p class="text-muted mb-4">
                <?php echo isset($message) ? htmlspecialchars($message) : 'Terjadi kesalahan yang tidak terduga.'; ?>
            </p>
            <div class="d-grid gap-2">
                <a href="javascript:history.back()" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left me-2"></i>Kembali
                </a>
                <a href="index.php" class="btn btn-primary">
                    <i class="bi bi-house me-2"></i>Ke Halaman Utama
                </a>
            </div>
        </div>
    </div>
</div>

<?php include 'templates/footer.php'; ?>