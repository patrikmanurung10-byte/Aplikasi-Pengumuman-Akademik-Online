# Integrasi WhatsApp API Fonnte - APAO Polibatam

## Status: ✅ COMPLETED

✅ **WhatsApp OTP System**: Fully implemented and ready for use  
✅ **Database Schema**: Created and configured  
✅ **API Integration**: Fonnte WhatsApp API integrated  
✅ **User Interface**: Responsive forms with countdown timer  
✅ **Security Features**: Rate limiting, validation, encryption  
✅ **Testing Tools**: Test pages available for verification  

## Next Steps for Production

1. **Configure Fonnte Token**: Add your real Fonnte API token to `.env`
2. **Update Phone Numbers**: Ensure all users have valid WhatsApp numbers
3. **Test with Real Numbers**: Use test pages to verify sending works
4. **Remove Test Files**: Delete test files in production:
   - `public/test-whatsapp.php`
   - `public/test-forgot-password-flow.php`
5. **Monitor Usage**: Check Fonnte dashboard for quota and delivery rates

## Fitur
- ✅ Kirim OTP via WhatsApp menggunakan Fonnte API
- ✅ Verifikasi OTP dengan countdown timer
- ✅ Rate limiting untuk mencegah spam
- ✅ Resend OTP functionality
- ✅ Konfirmasi reset password via WhatsApp
- ✅ Responsive design untuk mobile
- ✅ Auto-submit OTP ketika 6 digit dimasukkan

## Setup dan Konfigurasi

### 1. Daftar Akun Fonnte
1. Kunjungi [https://fonnte.com](https://fonnte.com)
2. Daftar akun baru atau login
3. Dapatkan API Token dari dashboard

### 2. Konfigurasi Database
Jalankan SQL berikut untuk membuat tabel yang diperlukan:

```sql
-- Jalankan file database/password_resets_whatsapp.sql
source database/password_resets_whatsapp.sql;
```

### 3. Konfigurasi Environment
1. Copy file `.env.example` menjadi `.env`
2. Update konfigurasi:

```env
# Fonnte WhatsApp API Configuration
FONNTE_TOKEN=your_actual_fonnte_token_here
```

### 4. Update Nomor WhatsApp User
Pastikan user memiliki nomor WhatsApp yang valid di profil mereka:

```sql
-- Update nomor WhatsApp user (contoh)
UPDATE users SET phone = '081234567890' WHERE username = 'test_user';
```

## Cara Penggunaan

### 1. Lupa Password
1. User mengakses `/forgot-password`
2. Masukkan username atau email
3. Sistem akan mencari user dan nomor WhatsApp
4. OTP dikirim ke WhatsApp user

### 2. Verifikasi OTP
1. User menerima pesan WhatsApp dengan kode OTP
2. Masukkan kode OTP di halaman verifikasi
3. Sistem memverifikasi kode dan validitas waktu
4. Jika valid, user diarahkan ke halaman reset password

### 3. Reset Password
1. User memasukkan password baru
2. Sistem mengupdate password di database
3. Konfirmasi dikirim via WhatsApp

## Format Pesan WhatsApp

### Pesan OTP
```
Halo [Nama User],

🔐 *Kode OTP Reset Password*
Sistem APAO Polibatam

Kode OTP Anda: *123456*

⚠️ *PENTING:*
• Kode berlaku selama 10 menit
• Jangan bagikan kode ini kepada siapapun
• Gunakan kode ini untuk reset password

Jika Anda tidak meminta reset password, abaikan pesan ini.

Terima kasih,
*Tim APAO Polibatam*
```

### Konfirmasi Reset Password
```
Halo [Nama User],

✅ *Password Berhasil Direset*
Sistem APAO Polibatam

Password Anda telah berhasil direset pada:
📅 30/12/2024 14:30:15

🔒 *Tips Keamanan:*
• Gunakan password yang kuat
• Jangan bagikan password kepada siapapun
• Logout dari perangkat yang tidak dikenal

Jika ini bukan Anda, segera hubungi administrator.

Terima kasih,
*Tim APAO Polibatam*
```

## Rate Limiting

### Batasan Penggunaan
- **Maksimal 3 request OTP per jam** per user
- **Maksimal 5 request OTP per hari** per user
- **Cooldown 2 menit** antar request
- **Maksimal 5 percobaan verifikasi** per OTP

### Error Messages
- `"Terlalu banyak percobaan. Coba lagi dalam 1 jam."`
- `"Anda sudah meminta OTP. Silakan tunggu 2 menit sebelum meminta lagi."`
- `"Kode OTP tidak valid atau sudah kadaluarsa"`

## Security Features

### 1. OTP Security
- **6 digit random number**
- **10 menit expiry time**
- **One-time use only**
- **Stored with hash in database**

### 2. Rate Limiting
- **IP-based limiting**
- **User-based limiting**
- **Time-based cooldown**

### 3. Phone Number Validation
- **Format validation** (Indonesian numbers)
- **Length validation** (10-15 digits)
- **International format conversion**

## Troubleshooting

### 1. OTP Tidak Terkirim
**Kemungkinan Penyebab:**
- Token Fonnte tidak valid
- Nomor WhatsApp tidak valid
- Quota Fonnte habis
- Koneksi internet bermasalah

**Solusi:**
1. Cek token Fonnte di dashboard
2. Validasi format nomor WhatsApp
3. Cek quota dan billing Fonnte
4. Test koneksi API

### 2. OTP Tidak Valid
**Kemungkinan Penyebab:**
- Kode sudah kadaluarsa (>10 menit)
- Kode sudah digunakan
- Salah memasukkan kode

**Solusi:**
1. Request OTP baru
2. Pastikan memasukkan kode dengan benar
3. Cek waktu sistem server

### 3. Rate Limit Exceeded
**Kemungkinan Penyebab:**
- Terlalu banyak request dalam waktu singkat
- User mencoba spam sistem

**Solusi:**
1. Tunggu cooldown period
2. Hubungi administrator jika urgent

## Testing

### 1. Test Database Setup
```sql
-- Check if password_resets table exists
DESCRIBE password_resets;

-- Check users with phone numbers
SELECT username, email, phone, full_name, role FROM users WHERE phone IS NOT NULL;
```

### 2. Test Pages Available
- **WhatsApp API Test**: `/test-whatsapp.php`
- **Forgot Password Flow Test**: `/test-forgot-password-flow.php`
- **Real Forgot Password**: `/forgot-password`

### 3. Test Connection Fonnte
```php
$whatsappService = new WhatsAppService();
$result = $whatsappService->testConnection();
var_dump($result);
```

### 4. Test Complete Flow
1. Go to `/test-forgot-password-flow.php`
2. Test each step:
   - Step 1: User lookup by username/email
   - Step 2: OTP generation and database storage
   - Step 3: OTP verification
3. Check database for stored OTP codes
4. Test real flow at `/forgot-password`

### 5. Test WhatsApp Sending
1. Configure FONNTE_TOKEN in .env file
2. Go to `/test-whatsapp.php`
3. Test connection to Fonnte API
4. Send test OTP message
5. Send test confirmation message

## Monitoring dan Logging

### 1. Log Files
- **WhatsApp API calls**: `logs/whatsapp.log`
- **Application errors**: `logs/app.log`
- **Database queries**: `logs/database.log`

### 2. Metrics to Monitor
- **OTP success rate**
- **API response time**
- **Failed delivery count**
- **Rate limit hits**

## Maintenance

### 1. Cleanup Database
Jalankan cleanup otomatis untuk menghapus OTP yang sudah kadaluarsa:

```php
$passwordResetModel = new PasswordReset();
$passwordResetModel->cleanExpired();
```

### 2. Monitor Quota Fonnte
- Cek quota harian di dashboard Fonnte
- Set up alert ketika quota hampir habis
- Renew subscription sebelum expired

## Support

Untuk bantuan teknis:
1. **Fonnte Support**: [https://fonnte.com/support](https://fonnte.com/support)
2. **APAO Admin**: Hubungi administrator sistem
3. **Documentation**: Baca dokumentasi lengkap di repository

---

**Catatan**: Pastikan untuk menjaga kerahasiaan token API dan tidak membagikannya kepada pihak yang tidak berwenang.