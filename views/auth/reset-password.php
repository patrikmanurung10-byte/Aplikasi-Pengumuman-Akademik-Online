<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - APAO Polibatam</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-lg border-0 rounded-lg mt-5">
                    <div class="card-header bg-warning text-dark text-center py-4">
                        <h3 class="fw-light mb-0">
                            <i class="bi bi-lock-fill me-2"></i>Reset Password
                        </h3>
                        <p class="mb-0 mt-2 opacity-75">Masukkan password baru Anda</p>
                    </div>
                    
                    <div class="card-body p-4">
                        <?php if (isset($_SESSION['message'])): ?>
                            <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
                                <?php echo htmlspecialchars($_SESSION['message']); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                            <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
                        <?php endif; ?>
                        
                        <form method="POST" action="/forgot-password/update-password" id="resetForm">
                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="bi bi-lock me-1"></i>Password Baru
                                </label>
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control form-control-lg" 
                                           id="new_password" 
                                           name="new_password" 
                                           placeholder="Masukkan password baru"
                                           minlength="6"
                                           required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="bi bi-eye" id="eyeIcon"></i>
                                    </button>
                                </div>
                                <div class="form-text">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Password minimal 6 karakter
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="confirm_password" class="form-label">
                                    <i class="bi bi-lock-fill me-1"></i>Konfirmasi Password
                                </label>
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control form-control-lg" 
                                           id="confirm_password" 
                                           name="confirm_password" 
                                           placeholder="Ulangi password baru"
                                           minlength="6"
                                           required>
                                    <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                        <i class="bi bi-eye" id="eyeIconConfirm"></i>
                                    </button>
                                </div>
                                <div id="passwordMatch" class="form-text"></div>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-warning btn-lg text-dark" id="submitBtn">
                                    <i class="bi bi-check-circle me-2"></i>Reset Password
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
                            Setelah reset, Anda akan diarahkan ke halaman login
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordField = document.getElementById('new_password');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                eyeIcon.className = 'bi bi-eye-slash';
            } else {
                passwordField.type = 'password';
                eyeIcon.className = 'bi bi-eye';
            }
        });
        
        document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
            const confirmField = document.getElementById('confirm_password');
            const eyeIcon = document.getElementById('eyeIconConfirm');
            
            if (confirmField.type === 'password') {
                confirmField.type = 'text';
                eyeIcon.className = 'bi bi-eye-slash';
            } else {
                confirmField.type = 'password';
                eyeIcon.className = 'bi bi-eye';
            }
        });
        
        // Password match validation
        function checkPasswordMatch() {
            const password = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const matchDiv = document.getElementById('passwordMatch');
            const submitBtn = document.getElementById('submitBtn');
            
            if (confirmPassword === '') {
                matchDiv.innerHTML = '';
                submitBtn.disabled = false;
                return;
            }
            
            if (password === confirmPassword) {
                matchDiv.innerHTML = '<i class="bi bi-check-circle text-success me-1"></i>Password cocok';
                matchDiv.className = 'form-text text-success';
                submitBtn.disabled = false;
            } else {
                matchDiv.innerHTML = '<i class="bi bi-x-circle text-danger me-1"></i>Password tidak cocok';
                matchDiv.className = 'form-text text-danger';
                submitBtn.disabled = true;
            }
        }
        
        document.getElementById('new_password').addEventListener('input', checkPasswordMatch);
        document.getElementById('confirm_password').addEventListener('input', checkPasswordMatch);
        
        // Form validation
        document.getElementById('resetForm').addEventListener('submit', function(e) {
            const password = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (password.length < 6) {
                e.preventDefault();
                alert('Password minimal 6 karakter');
                return;
            }
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Konfirmasi password tidak cocok');
                return;
            }
        });
    </script>
</body>
</html>