<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - APAO Polibatam</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-lg border-0 rounded-lg mt-5">
                    <div class="card-header bg-primary text-white text-center py-4">
                        <h3 class="fw-light mb-0">
                            <i class="bi bi-key me-2"></i>Lupa Password
                        </h3>
                        <p class="mb-0 mt-2 opacity-75">Masukkan username atau email Anda</p>
                    </div>
                    
                    <div class="card-body p-4">
                        <?php if (isset($_SESSION['message'])): ?>
                            <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
                                <?php echo htmlspecialchars($_SESSION['message']); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                            <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
                        <?php endif; ?>
                        
                        <form method="POST" action="/forgot-password/send-otp">
                            <div class="mb-3">
                                <label for="identifier" class="form-label">
                                    <i class="bi bi-person me-1"></i>Username atau Email
                                </label>
                                <input type="text" 
                                       class="form-control form-control-lg" 
                                       id="identifier" 
                                       name="identifier" 
                                       placeholder="Masukkan username atau email"
                                       required>
                                <div class="form-text">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Pastikan nomor WhatsApp Anda sudah terdaftar di sistem
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-send me-2"></i>Kirim OTP ke WhatsApp
                                </button>
                            </div>
                        </form>
                        
                        <div class="text-center mt-4">
                            <div class="small">
                                <a href="/login" class="text-decoration-none">
                                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Login
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-light text-center py-3">
                        <div class="small text-muted">
                            <i class="bi bi-shield-check me-1"></i>
                            Sistem akan mengirim kode OTP ke nomor WhatsApp yang terdaftar
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>