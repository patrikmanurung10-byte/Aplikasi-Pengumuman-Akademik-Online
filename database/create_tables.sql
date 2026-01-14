-- =====================================================
-- APAO Polibatam - Complete Database Structure
-- Sistem Manajemen User dan Pengumuman
-- =====================================================

-- Drop tables if exists (in correct order to avoid foreign key constraints)
DROP TABLE IF EXISTS announcement_views;
DROP TABLE IF EXISTS announcements;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS password_resets;
DROP TABLE IF EXISTS user_sessions;
DROP TABLE IF EXISTS users;

-- =====================================================
-- 1. USERS TABLE
-- =====================================================
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('admin', 'dosen', 'mahasiswa') NOT NULL DEFAULT 'mahasiswa',
    nim_nip VARCHAR(20) NULL,
    program_studi VARCHAR(100) NULL,
    phone VARCHAR(20) NULL,
    address TEXT NULL,
    date_of_birth DATE NULL,
    gender ENUM('L', 'P') NULL,
    is_active BOOLEAN DEFAULT TRUE,
    academic_status ENUM('Aktif', 'Nonaktif', 'Cuti', 'Lulus', 'DO') DEFAULT 'Aktif',
    last_login TIMESTAMP NULL,
    email_verified_at TIMESTAMP NULL,
    remember_token VARCHAR(100) NULL,
    preferences JSON NULL,
    privacy_settings JSON NULL,
    security_settings JSON NULL,
    profile_picture VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_username (username),
    INDEX idx_email (email),
    INDEX idx_role (role),
    INDEX idx_nim_nip (nim_nip),
    INDEX idx_is_active (is_active),
    INDEX idx_academic_status (academic_status)
);

-- =====================================================
-- 2. CATEGORIES TABLE
-- =====================================================
CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    description TEXT NULL,
    color VARCHAR(7) DEFAULT '#007bff',
    icon VARCHAR(50) DEFAULT 'bi-tag',
    is_active BOOLEAN DEFAULT TRUE,
    sort_order INT DEFAULT 0,
    created_by INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_slug (slug),
    INDEX idx_is_active (is_active),
    INDEX idx_sort_order (sort_order)
);

-- =====================================================
-- 3. ANNOUNCEMENTS TABLE
-- =====================================================
CREATE TABLE announcements (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    content LONGTEXT NOT NULL,
    excerpt TEXT NULL,
    category_id INT NULL,
    author_id INT NOT NULL,
    status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    priority ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium',
    is_active BOOLEAN DEFAULT TRUE,
    is_featured BOOLEAN DEFAULT FALSE,
    is_pinned BOOLEAN DEFAULT FALSE,
    publish_date TIMESTAMP NULL,
    expire_date TIMESTAMP NULL,
    views INT DEFAULT 0,
    likes INT DEFAULT 0,
    featured_image VARCHAR(255) NULL,
    attachments JSON NULL,
    tags JSON NULL,
    target_audience JSON NULL,
    seo_title VARCHAR(255) NULL,
    seo_description TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_slug (slug),
    INDEX idx_category_id (category_id),
    INDEX idx_author_id (author_id),
    INDEX idx_status (status),
    INDEX idx_priority (priority),
    INDEX idx_is_active (is_active),
    INDEX idx_is_featured (is_featured),
    INDEX idx_is_pinned (is_pinned),
    INDEX idx_publish_date (publish_date),
    INDEX idx_expire_date (expire_date),
    INDEX idx_views (views),
    INDEX idx_created_at (created_at),
    FULLTEXT idx_search (title, content, excerpt)
);

-- =====================================================
-- 4. ANNOUNCEMENT VIEWS TABLE
-- =====================================================
CREATE TABLE announcement_views (
    id INT PRIMARY KEY AUTO_INCREMENT,
    announcement_id INT NOT NULL,
    user_id INT NULL,
    ip_address VARCHAR(45) NOT NULL,
    user_agent TEXT NULL,
    viewed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (announcement_id) REFERENCES announcements(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_announcement_id (announcement_id),
    INDEX idx_user_id (user_id),
    INDEX idx_ip_address (ip_address),
    INDEX idx_viewed_at (viewed_at),
    UNIQUE KEY unique_view (announcement_id, user_id, ip_address)
);

-- =====================================================
-- 5. PASSWORD RESETS TABLE (for forgot password)
-- =====================================================
CREATE TABLE password_resets (
    id INT PRIMARY KEY AUTO_INCREMENT,
    phone VARCHAR(20) NOT NULL,
    otp VARCHAR(6) NOT NULL,
    expires_at TIMESTAMP NOT NULL,
    is_used BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_phone (phone),
    INDEX idx_otp (otp),
    INDEX idx_expires_at (expires_at)
);

-- =====================================================
-- 6. USER SESSIONS TABLE
-- =====================================================
CREATE TABLE user_sessions (
    id VARCHAR(128) PRIMARY KEY,
    user_id INT NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    payload LONGTEXT NOT NULL,
    last_activity INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_last_activity (last_activity)
);

-- =====================================================
-- INSERT DEFAULT DATA
-- =====================================================

-- Default Admin User
INSERT INTO users (username, email, password, full_name, role, is_active, academic_status) VALUES
('admin', 'admin@polibatam.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin', TRUE, 'Aktif');

-- Default Categories
INSERT INTO categories (name, slug, description, color, icon, is_active, sort_order, created_by) VALUES
('Akademik', 'akademik', 'Pengumuman terkait kegiatan akademik', '#007bff', 'bi-mortarboard', TRUE, 1, 1),
('Beasiswa', 'beasiswa', 'Informasi beasiswa dan bantuan pendidikan', '#28a745', 'bi-award', TRUE, 2, 1),
('Acara', 'acara', 'Pengumuman acara dan kegiatan kampus', '#17a2b8', 'bi-calendar-event', TRUE, 3, 1),
('Administrasi', 'administrasi', 'Pengumuman administrasi dan tata usaha', '#ffc107', 'bi-file-text', TRUE, 4, 1),
('Kemahasiswaan', 'kemahasiswaan', 'Kegiatan kemahasiswaan dan organisasi', '#fd7e14', 'bi-people', TRUE, 5, 1),
('Penting', 'penting', 'Pengumuman penting dan mendesak', '#dc3545', 'bi-exclamation-triangle', TRUE, 0, 1);

-- Sample Announcements
INSERT INTO announcements (title, slug, content, excerpt, category_id, author_id, status, priority, is_active, is_featured, publish_date, target_audience) VALUES
('Selamat Datang di Sistem APAO', 'selamat-datang-di-sistem-apao', 
'Selamat datang di Aplikasi Pengumuman Akademik Online (APAO) Politeknik Negeri Batam.\n\nSistem ini dirancang untuk memudahkan penyampaian informasi akademik kepada seluruh civitas akademika.\n\n**Fitur Utama:**\n- Pengumuman real-time\n- Notifikasi WhatsApp\n- Manajemen user\n- Dashboard interaktif\n\nSilakan jelajahi sistem dan manfaatkan fitur-fitur yang tersedia.',
'Selamat datang di APAO - sistem pengumuman akademik online untuk Politeknik Negeri Batam.',
4, 1, 'published', 'high', TRUE, TRUE, NOW(), '["mahasiswa", "dosen"]'),

('Panduan Penggunaan Sistem', 'panduan-penggunaan-sistem',
'**Panduan Penggunaan APAO**\n\n**Untuk Mahasiswa:**\n1. Login dengan username dan password\n2. Lihat pengumuman terbaru di dashboard\n3. Update profil di menu Profil\n\n**Untuk Dosen:**\n1. Login dengan akun dosen\n2. Buat pengumuman baru\n3. Kelola pengumuman yang sudah dibuat\n\n**Untuk Admin:**\n1. Kelola semua pengguna\n2. Moderasi pengumuman\n3. Lihat statistik sistem\n\nJika ada pertanyaan, silakan hubungi administrator.',
'Panduan lengkap penggunaan sistem APAO untuk semua pengguna.',
4, 1, 'published', 'medium', TRUE, FALSE, NOW(), '["mahasiswa", "dosen", "admin"]');

-- =====================================================
-- CREATE INDEXES FOR PERFORMANCE
-- =====================================================

-- Additional indexes for better performance
CREATE INDEX idx_announcements_published ON announcements(status, is_active, publish_date);
CREATE INDEX idx_announcements_featured ON announcements(is_featured, is_active, publish_date);
CREATE INDEX idx_announcements_category_published ON announcements(category_id, status, is_active);
CREATE INDEX idx_users_role_active ON users(role, is_active);

-- =====================================================
-- CREATE VIEWS FOR COMMON QUERIES
-- =====================================================

-- View for published announcements with author and category info
CREATE VIEW v_published_announcements AS
SELECT 
    a.id,
    a.title,
    a.slug,
    a.content,
    a.excerpt,
    a.priority,
    a.is_featured,
    a.is_pinned,
    a.publish_date,
    a.views,
    a.likes,
    a.created_at,
    a.updated_at,
    u.full_name as author_name,
    u.role as author_role,
    c.name as category_name,
    c.color as category_color,
    c.icon as category_icon
FROM announcements a
LEFT JOIN users u ON a.author_id = u.id
LEFT JOIN categories c ON a.category_id = c.id
WHERE a.status = 'published' 
  AND a.is_active = TRUE 
  AND (a.publish_date IS NULL OR a.publish_date <= NOW())
  AND (a.expire_date IS NULL OR a.expire_date > NOW());

-- View for user statistics
CREATE VIEW v_user_stats AS
SELECT 
    role,
    COUNT(*) as total_users,
    SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active_users,
    SUM(CASE WHEN last_login >= DATE_SUB(NOW(), INTERVAL 30 DAY) THEN 1 ELSE 0 END) as active_last_30_days
FROM users 
GROUP BY role;

-- =====================================================
-- FINAL NOTES
-- =====================================================
/*
Default Login Credentials:
1. Admin: 
   - Username: admin
   - Password: password

The password is hashed using PHP's password_hash() function.
The hash shown is for the password "password".

Database Features:
- Complete announcement system
- User management with roles
- Password reset with WhatsApp OTP
- Category management
- View tracking
- Full-text search capabilities
- Comprehensive indexing for performance
*/