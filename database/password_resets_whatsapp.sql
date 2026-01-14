-- Password Resets Table for WhatsApp OTP
-- Tabel untuk menyimpan data reset password dengan OTP WhatsApp

CREATE TABLE IF NOT EXISTS `password_resets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `identifier` varchar(255) NOT NULL COMMENT 'Username atau email user',
  `phone` varchar(20) NOT NULL COMMENT 'Nomor WhatsApp untuk OTP',
  `otp_code` varchar(6) NOT NULL COMMENT 'Kode OTP 6 digit',
  `expires_at` datetime NOT NULL COMMENT 'Waktu kadaluarsa OTP',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `used_at` datetime NULL DEFAULT NULL COMMENT 'Waktu OTP digunakan',
  `is_used` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Status OTP sudah digunakan',
  PRIMARY KEY (`id`),
  KEY `idx_identifier` (`identifier`),
  KEY `idx_otp_code` (`otp_code`),
  KEY `idx_expires_at` (`expires_at`),
  KEY `idx_is_used` (`is_used`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add phone column to users table if not exists
ALTER TABLE `users` 
ADD COLUMN IF NOT EXISTS `phone` varchar(20) NULL COMMENT 'Nomor WhatsApp user' 
AFTER `email`;

-- Create index for phone column
CREATE INDEX IF NOT EXISTS `idx_phone` ON `users` (`phone`);

-- Sample data untuk testing (opsional)
-- INSERT INTO `users` (`username`, `email`, `phone`, `password`, `full_name`, `role`, `is_active`) 
-- VALUES 
-- ('test_student', 'student@test.com', '081234567890', '$2y$10$example_hash', 'Test Student', 'mahasiswa', 1),
-- ('test_dosen', 'dosen@test.com', '081234567891', '$2y$10$example_hash', 'Test Dosen', 'dosen', 1);