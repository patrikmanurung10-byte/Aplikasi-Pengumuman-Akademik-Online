-- Tambah kolom academic_status ke tabel users
-- Jalankan jika belum ada kolom academic_status

-- Cek apakah kolom sudah ada
SET @col_exists = 0;
SELECT COUNT(*) INTO @col_exists 
FROM information_schema.columns 
WHERE table_schema = DATABASE() 
  AND table_name = 'users' 
  AND column_name = 'academic_status';

-- Tambah kolom jika belum ada
SET @sql = IF(@col_exists = 0, 
    'ALTER TABLE users ADD COLUMN academic_status ENUM(\'Aktif\', \'Nonaktif\', \'Cuti\', \'Lulus\', \'DO\') DEFAULT \'Aktif\' AFTER is_active',
    'SELECT "Column academic_status already exists" as message'
);

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;