<?php

/**
 * OTP Model
 * Model untuk mengelola OTP (One Time Password)
 */
class OTP extends \App\Core\Model
{
    protected $table = 'otp_codes';
    
    /**
     * Simpan OTP baru
     */
    public function createOTP($userId, $phoneNumber, $otpCode, $purpose = 'password_reset')
    {
        // Hapus OTP lama untuk user ini
        $this->deleteOTPByUser($userId, $purpose);
        
        $expiresAt = date('Y-m-d H:i:s', strtotime('+10 minutes'));
        
        return $this->create([
            'user_id' => $userId,
            'phone_number' => $phoneNumber,
            'otp_code' => $otpCode,
            'purpose' => $purpose,
            'expires_at' => $expiresAt,
            'is_used' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * Verifikasi OTP
     */
    public function verifyOTP($userId, $otpCode, $purpose = 'password_reset')
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE user_id = :user_id 
                AND otp_code = :otp_code 
                AND purpose = :purpose 
                AND is_used = 0 
                AND expires_at > NOW()
                ORDER BY created_at DESC 
                LIMIT 1";
        
        $otp = $this->db->fetch($sql, [
            'user_id' => $userId,
            'otp_code' => $otpCode,
            'purpose' => $purpose
        ]);
        
        if ($otp) {
            // Mark OTP sebagai sudah digunakan
            $this->markAsUsed($otp['id']);
            return true;
        }
        
        return false;
    }
    
    /**
     * Tandai OTP sebagai sudah digunakan
     */
    public function markAsUsed($otpId)
    {
        return $this->update($otpId, [
            'is_used' => 1,
            'used_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * Hapus OTP berdasarkan user dan purpose
     */
    public function deleteOTPByUser($userId, $purpose = 'password_reset')
    {
        $sql = "DELETE FROM {$this->table} 
                WHERE user_id = :user_id AND purpose = :purpose";
        
        return $this->db->query($sql, [
            'user_id' => $userId,
            'purpose' => $purpose
        ]);
    }
    
    /**
     * Cek apakah user sudah request OTP dalam waktu dekat
     */
    public function canRequestOTP($userId, $purpose = 'password_reset', $cooldownMinutes = 2)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} 
                WHERE user_id = :user_id 
                AND purpose = :purpose 
                AND created_at > DATE_SUB(NOW(), INTERVAL :cooldown MINUTE)";
        
        $result = $this->db->fetch($sql, [
            'user_id' => $userId,
            'purpose' => $purpose,
            'cooldown' => $cooldownMinutes
        ]);
        
        return $result['count'] == 0;
    }
    
    /**
     * Hapus OTP yang sudah expired
     */
    public function cleanupExpiredOTP()
    {
        $sql = "DELETE FROM {$this->table} WHERE expires_at < NOW()";
        return $this->db->query($sql);
    }
    
    /**
     * Get OTP terakhir untuk user
     */
    public function getLatestOTP($userId, $purpose = 'password_reset')
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE user_id = :user_id 
                AND purpose = :purpose 
                ORDER BY created_at DESC 
                LIMIT 1";
        
        return $this->db->fetch($sql, [
            'user_id' => $userId,
            'purpose' => $purpose
        ]);
    }
    
    /**
     * Hitung percobaan verifikasi OTP
     */
    public function getVerificationAttempts($userId, $purpose = 'password_reset')
    {
        $sql = "SELECT attempts FROM {$this->table} 
                WHERE user_id = :user_id 
                AND purpose = :purpose 
                AND expires_at > NOW()
                ORDER BY created_at DESC 
                LIMIT 1";
        
        $result = $this->db->fetch($sql, [
            'user_id' => $userId,
            'purpose' => $purpose
        ]);
        
        return $result ? (int)$result['attempts'] : 0;
    }
    
    /**
     * Increment percobaan verifikasi
     */
    public function incrementAttempts($userId, $purpose = 'password_reset')
    {
        $sql = "UPDATE {$this->table} 
                SET attempts = attempts + 1 
                WHERE user_id = :user_id 
                AND purpose = :purpose 
                AND expires_at > NOW()";
        
        return $this->db->query($sql, [
            'user_id' => $userId,
            'purpose' => $purpose
        ]);
    }
}