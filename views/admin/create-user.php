<?php
// Admin Create User Page
?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-person-plus me-2"></i>Tambah Pengguna Baru
            </h1>
            <p class="mb-0 text-muted">Buat akun pengguna baru untuk sistem APAO</p>
        </div>
        <div class="btn-toolbar">
            <a href="/admin/users" class="btn btn-outline-secondary me-2">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
            <button type="button" class="btn btn-outline-info" onclick="generateCredentials()">
                <i class="bi bi-magic me-1"></i>Generate
            </button>
        </div>
    </div>

    <div class="row">
        <!-- Main Form -->
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-person-fill-add me-2"></i>Informasi Pengguna Baru
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="/admin/users/store" id="createUserForm">
                        <input type="hidden" name="_token" value="<?php echo \App\Core\Session::getCsrfToken(); ?>">
                        
                        <!-- Basic Information -->
                        <div class="mb-4">
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-person me-2"></i>Informasi Dasar
                            </h6>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="full_name" class="form-label fw-bold">
                                        <i class="bi bi-person me-1"></i>Nama Lengkap *
                                    </label>
                                    <input type="text" class="form-control" id="full_name" name="full_name" 
                                           placeholder="Masukkan nama lengkap" required>
                                    <div class="form-text">Nama lengkap sesuai identitas resmi</div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="username" class="form-label fw-bold">
                                        <i class="bi bi-at me-1"></i>Username *
                                    </label>
                                    <input type="text" class="form-control" id="username" name="username" 
                                           placeholder="Masukkan username" required>
                                    <div class="form-text">Username untuk login (minimal 3 karakter)</div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label fw-bold">
                                        <i class="bi bi-envelope me-1"></i>Email *
                                    </label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           placeholder="Masukkan alamat email" required>
                                    <div class="form-text">Email aktif untuk notifikasi sistem</div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label fw-bold">
                                        <i class="bi bi-whatsapp me-1"></i>Nomor WhatsApp
                                    </label>
                                    <input type="tel" class="form-control" id="phone" name="phone" 
                                           placeholder="08xxxxxxxxxx">
                                    <div class="form-text">Nomor WhatsApp untuk notifikasi (opsional)</div>
                                </div>
                            </div>
                        </div>

                        <!-- Account Information -->
                        <div class="mb-4">
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-key me-2"></i>Informasi Akun
                            </h6>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="role" class="form-label fw-bold">
                                        <i class="bi bi-shield me-1"></i>Role *
                                    </label>
                                    <select class="form-select" id="role" name="role" required>
                                        <option value="">Pilih Role</option>
                                        <option value="admin">Administrator</option>
                                        <option value="dosen">Dosen</option>
                                        <option value="mahasiswa">Mahasiswa</option>
                                    </select>
                                    <div class="form-text">Tentukan hak akses pengguna</div>
                                </div>
                                
                                <div class="col-md-6 mb-3" id="nim-nip-field" style="display: none;">
                                    <label for="nim_nip" class="form-label fw-bold">
                                        <i class="bi bi-card-text me-1"></i><span id="nim-nip-label">NIM/NIP</span>
                                    </label>
                                    <input type="text" class="form-control" id="nim_nip" name="nim_nip" 
                                           placeholder="Masukkan NIM/NIP">
                                    <div class="form-text" id="nim-nip-help">Nomor identitas mahasiswa/dosen</div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label fw-bold">
                                        <i class="bi bi-lock me-1"></i>Password *
                                    </label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="password" name="password" 
                                               placeholder="Masukkan password" required>
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                            <i class="bi bi-eye" id="password-icon"></i>
                                        </button>
                                    </div>
                                    <div class="form-text">Password minimal 6 karakter</div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="confirm_password" class="form-label fw-bold">
                                        <i class="bi bi-lock-fill me-1"></i>Konfirmasi Password *
                                    </label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                                               placeholder="Ulangi password" required>
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('confirm_password')">
                                            <i class="bi bi-eye" id="confirm_password-icon"></i>
                                        </button>
                                    </div>
                                    <div class="form-text">Pastikan password sama</div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="mb-4" id="program-studi-field" style="display: none;">
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-mortarboard me-2"></i>Informasi Akademik
                            </h6>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="program_studi" class="form-label fw-bold">
                                        <i class="bi bi-book me-1"></i>Program Studi
                                    </label>
                                    <select class="form-select" id="program_studi" name="program_studi">
                                        <option value="">Pilih Program Studi</option>
                                        <option value="Teknik Informatika">Teknik Informatika</option>
                                        <option value="Sistem Informasi">Sistem Informasi</option>
                                        <option value="Teknik Elektro">Teknik Elektro</option>
                                        <option value="Teknik Mesin">Teknik Mesin</option>
                                        <option value="Teknik Sipil">Teknik Sipil</option>
                                        <option value="Manajemen">Manajemen</option>
                                        <option value="Akuntansi">Akuntansi</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="angkatan" class="form-label fw-bold">
                                        <i class="bi bi-calendar me-1"></i>Angkatan
                                    </label>
                                    <select class="form-select" id="angkatan" name="angkatan">
                                        <option value="">Pilih Angkatan</option>
                                        <?php for($year = date('Y'); $year >= 2020; $year--): ?>
                                        <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                                <label class="form-check-label fw-bold" for="is_active">
                                    <i class="bi bi-toggle-on me-1"></i>Aktifkan Pengguna
                                </label>
                                <div class="form-text">Pengguna dapat langsung login ke sistem</div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between">
                            <div>
                                <small class="text-muted">
                                    <span class="text-danger">*</span> Field wajib diisi
                                </small>
                            </div>
                            <div>
                                <button type="button" class="btn btn-outline-danger me-2" onclick="resetForm()">
                                    <i class="bi bi-arrow-clockwise me-1"></i>Reset
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-1"></i>Buat Pengguna
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Role Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="bi bi-info-circle me-2"></i>Informasi Role
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-12 mb-3">
                            <div class="role-info" id="admin-info">
                                <div class="user-avatar mx-auto mb-2" style="width: 50px; height: 50px; background: #dc3545;">
                                    <i class="bi bi-shield-check"></i>
                                </div>
                                <h6 class="text-danger">Administrator</h6>
                                <small class="text-muted">
                                    Akses penuh ke semua fitur sistem
                                </small>
                            </div>
                        </div>
                        
                        <div class="col-12 mb-3">
                            <div class="role-info" id="dosen-info">
                                <div class="user-avatar mx-auto mb-2" style="width: 50px; height: 50px; background: #0d6efd;">
                                    <i class="bi bi-person-badge"></i>
                                </div>
                                <h6 class="text-primary">Dosen</h6>
                                <small class="text-muted">
                                    Dapat membuat dan mengelola pengumuman
                                </small>
                            </div>
                        </div>
                        
                        <div class="col-12 mb-3">
                            <div class="role-info" id="mahasiswa-info">
                                <div class="user-avatar mx-auto mb-2" style="width: 50px; height: 50px; background: #198754;">
                                    <i class="bi bi-mortarboard"></i>
                                </div>
                                <h6 class="text-success">Mahasiswa</h6>
                                <small class="text-muted">
                                    Dapat melihat dan mencari pengumuman
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Password Guidelines -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="bi bi-shield-lock me-2"></i>Panduan Password
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            <strong>Minimal 6 karakter</strong>
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            <strong>Kombinasi huruf dan angka</strong>
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            <strong>Hindari informasi pribadi</strong>
                        </li>
                        <li>
                            <i class="bi bi-check-circle text-success me-2"></i>
                            <strong>Gunakan karakter khusus</strong>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Recent Users -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-secondary">
                        <i class="bi bi-clock-history me-2"></i>Pengguna Terbaru
                    </h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item border-0 px-0">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="mb-1">Ahmad Rizki</h6>
                                    <small class="text-muted">Mahasiswa - 2 hari lalu</small>
                                </div>
                                <span class="badge bg-success">Aktif</span>
                            </div>
                        </div>
                        <div class="list-group-item border-0 px-0">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="mb-1">Dr. Siti Aminah</h6>
                                    <small class="text-muted">Dosen - 5 hari lalu</small>
                                </div>
                                <span class="badge bg-primary">Aktif</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <a href="/admin/users" class="btn btn-sm btn-outline-secondary">
                            Lihat Semua
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Toggle password visibility
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '-icon');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        field.type = 'password';
        icon.className = 'bi bi-eye';
    }
}

// Show/hide additional fields based on role
document.getElementById('role').addEventListener('change', function() {
    const role = this.value;
    const nimNipField = document.getElementById('nim-nip-field');
    const programStudiField = document.getElementById('program-studi-field');
    const nimNipLabel = document.getElementById('nim-nip-label');
    const nimNipHelp = document.getElementById('nim-nip-help');
    const nimNipInput = document.getElementById('nim_nip');
    
    if (role === 'mahasiswa' || role === 'dosen') {
        nimNipField.style.display = 'block';
        
        if (role === 'mahasiswa') {
            nimNipLabel.textContent = 'NIM';
            nimNipHelp.textContent = 'Nomor Induk Mahasiswa';
            nimNipInput.placeholder = 'Masukkan NIM';
            programStudiField.style.display = 'block';
        } else if (role === 'dosen') {
            nimNipLabel.textContent = 'NIP';
            nimNipHelp.textContent = 'Nomor Induk Pegawai';
            nimNipInput.placeholder = 'Masukkan NIP';
            programStudiField.style.display = 'none';
        }
    } else {
        nimNipField.style.display = 'none';
        programStudiField.style.display = 'none';
    }
});

// Auto-generate username from full name
document.getElementById('full_name').addEventListener('input', function() {
    const fullName = this.value;
    const username = fullName.toLowerCase()
        .replace(/\s+/g, '')
        .replace(/[^a-z0-9]/g, '')
        .substring(0, 15);
    
    if (username && !document.getElementById('username').value) {
        document.getElementById('username').value = username;
    }
});

// Validate username availability (simulation)
document.getElementById('username').addEventListener('blur', function() {
    const username = this.value;
    if (username && username.length >= 3) {
        // Simulate AJAX check
        setTimeout(() => {
            // In real implementation, check via AJAX
            // 70% chance available
            const isAvailable = Math.random() > 0.3;
            
            if (!isAvailable) {
                this.classList.add('is-invalid');
                let feedback = this.nextElementSibling;
                if (!feedback || !feedback.classList.contains('invalid-feedback')) {
                    feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback';
                    this.parentNode.appendChild(feedback);
                }
                feedback.textContent = 'Username sudah digunakan';
            } else {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
                const feedback = this.parentNode.querySelector('.invalid-feedback');
                if (feedback) {
                    feedback.remove();
                }
            }
        }, 500);
    }
});

// Validate email format
document.getElementById('email').addEventListener('blur', function() {
    const email = this.value;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    if (email && !emailRegex.test(email)) {
        this.classList.add('is-invalid');
        let feedback = this.nextElementSibling;
        if (!feedback || !feedback.classList.contains('invalid-feedback')) {
            feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            this.parentNode.appendChild(feedback);
        }
        feedback.textContent = 'Format email tidak valid';
    } else {
        this.classList.remove('is-invalid');
        this.classList.add('is-valid');
        const feedback = this.parentNode.querySelector('.invalid-feedback');
        if (feedback) {
            feedback.remove();
        }
    }
});

// Generate random credentials
function generateCredentials() {
    const roles = ['admin', 'dosen', 'mahasiswa'];
    const names = ['Ahmad Rizki', 'Siti Aminah', 'Budi Santoso', 'Dewi Sari', 'Andi Wijaya'];
    const randomName = names[Math.floor(Math.random() * names.length)];
    const randomRole = roles[Math.floor(Math.random() * roles.length)];
    
    document.getElementById('full_name').value = randomName;
    document.getElementById('username').value = randomName.toLowerCase().replace(/\s+/g, '');
    document.getElementById('email').value = randomName.toLowerCase().replace(/\s+/g, '') + '@example.com';
    document.getElementById('role').value = randomRole;
    document.getElementById('password').value = 'password123';
    document.getElementById('confirm_password').value = 'password123';
    
    // Trigger role change event
    document.getElementById('role').dispatchEvent(new Event('change'));
    
    window.showSuccess('Kredensial berhasil digenerate!');
}

// Reset form
function resetForm() {
    Swal.fire({
        title: 'Reset Form',
        text: 'Apakah Anda yakin ingin mengosongkan semua field?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Reset',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('createUserForm').reset();
            // Hide additional fields
            document.getElementById('nim-nip-field').style.display = 'none';
            document.getElementById('program-studi-field').style.display = 'none';
            // Remove validation classes
            document.querySelectorAll('.is-valid, .is-invalid').forEach(el => {
                el.classList.remove('is-valid', 'is-invalid');
            });
            window.showSuccess('Form berhasil direset!');
        }
    });
}

// Form validation
document.getElementById('createUserForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    
    // Validate password match
    if (password !== confirmPassword) {
        window.showError('Password dan konfirmasi password tidak sama!');
        return false;
    }
    
    // Validate password length
    if (password.length < 6) {
        window.showError('Password minimal 6 karakter!');
        return false;
    }
    
    // Show confirmation
    Swal.fire({
        title: 'Konfirmasi',
        text: 'Apakah data yang dimasukkan sudah benar?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Tambah',
        cancelButtonText: 'Periksa Lagi'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Menyimpan...',
                html: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Submit form
            this.submit();
        }
    });
});
</script>