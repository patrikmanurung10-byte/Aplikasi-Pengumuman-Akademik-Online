<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP - APAO Polibatam</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .otp-input {
            text-align: center;
            font-size: 1.5rem;
            font-weight: bold;
            letter-spacing: 0.5rem;
        }
        .countdown {
            font-weight: bold;
            color: #dc3545;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-lg border-0 rounded-lg mt-5">
                    <div class="card-header bg-success text-white text-center py-4">
                        <h3 class="fw-light mb-0">
                            <i class="bi bi-whatsapp me-2"></i>Verifikasi OTP
                        </h3>
                        <p class="mb-0 mt-2 opacity-75">Masukkan kode yang dikirim ke WhatsApp</p>
                    </div>
                    
                    <div class="card-body p-4">
                        <?php if (isset($_SESSION['message'])): ?>
                            <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
                                <?php echo htmlspecialchars($_SESSION['message']); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                            <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
                        <?php endif; ?>
                        
                        <div class="alert alert-info mb-4">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-whatsapp me-3" style="font-size: 1.5rem;"></i>
                                <div>
                                    <strong>Kode OTP telah dikirim</strong><br>
                                    <small>ke nomor WhatsApp: <?php echo htmlspecialchars($phone ?? '****'); ?></small>
                                </div>
                            </div>
                        </div>
                        
                        <form method="POST" action="/forgot-password/verify-otp" id="otpForm">
                            <div class="mb-4">
                                <label for="otp_code" class="form-label text-center d-block">
                                    <i class="bi bi-key me-1"></i>Kode OTP (6 digit)
                                </label>
                                <input type="text" 
                                       class="form-control form-control-lg otp-input" 
                                       id="otp_code" 
                                       name="otp_code" 
                                       placeholder="000000"
                                       maxlength="6"
                                       pattern="[0-9]{6}"
                                       required>
                                <div class="form-text text-center">
                                    <i class="bi bi-clock me-1"></i>
                                    Kode berlaku selama <span class="countdown" id="countdown">10:00</span>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="bi bi-check-circle me-2"></i>Verifikasi OTP
                                </button>
                            </div>
                        </form>
                        
                        <div class="text-center mt-4">
                            <div class="mb-2">
                                <small class="text-muted">Tidak menerima kode?</small>
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm" id="resendBtn" onclick="resendOTP()">
                                <i class="bi bi-arrow-clockwise me-1"></i>Kirim Ulang OTP
                            </button>
                        </div>
                        
                        <div class="text-center mt-3">
                            <div class="small">
                                <a href="/forgot-password" class="text-decoration-none">
                                    <i class="bi bi-arrow-left me-1"></i>Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-light text-center py-3">
                        <div class="small text-muted">
                            <i class="bi bi-shield-lock me-1"></i>
                            Jangan bagikan kode OTP kepada siapapun
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto format OTP input
        document.getElementById('otp_code').addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^0-9]/g, '');
            e.target.value = value;
            
            // Auto submit when 6 digits entered
            if (value.length === 6) {
                document.getElementById('otpForm').submit();
            }
        });
        
        // Countdown timer
        let timeLeft = 600; // 10 minutes in seconds
        const countdownElement = document.getElementById('countdown');
        const resendBtn = document.getElementById('resendBtn');
        
        function updateCountdown() {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            countdownElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
            
            if (timeLeft <= 0) {
                countdownElement.textContent = 'Kadaluarsa';
                countdownElement.className = 'countdown text-danger';
                resendBtn.disabled = false;
                resendBtn.innerHTML = '<i class="bi bi-arrow-clockwise me-1"></i>Kirim Ulang OTP';
            } else {
                timeLeft--;
            }
        }
        
        // Update countdown every second
        setInterval(updateCountdown, 1000);
        updateCountdown();
        
        // Resend OTP function
        function resendOTP() {
            resendBtn.disabled = true;
            resendBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Mengirim...';
            
            // Redirect to resend endpoint
            window.location.href = '/forgot-password/resend-otp';
        }
        
        // Focus on OTP input when page loads
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('otp_code').focus();
        });
    </script>
</body>
</html>