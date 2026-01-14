<?php

use App\Core\Model;

/**
 * Password Reset Model
 * Model untuk mengelola data reset password dan OTP
 */
class PasswordReset extends Model
{
    protected $table = 'password_resets';
    protected $timestamps = false; // Disable automatic timestamps
    
    /**
     * Create new password reset request
     * 
     * @param string $identifier Username atau email
     * @param string $phone Nomor WhatsApp
     * @param string $otp Kode OTP
     * @return bool
     */
    public function createResetRequest($identifier, $phone, $otp)
    {
        // Hapus request lama untuk identifier yang sama
        $this->deleteByIdentifier($identifier);
        
        $data = [
            'identifier' => $identifier,
            'phone' => $phone,
            'otp_code' => $otp,
            'expires_at' => date('Y-m-d H:i:s', strtotime('+10 minutes')),
            'created_at' => date('Y-m-d H:i:s'),
            'is_used' => 0
        ];
        
        return $this->create($data);
    }
    
    /**
     * Verify OTP code
     * 
     * @param string $identifier Username atau email
     * @param string $otp Kode OTP
     * @return array|false
     */
    public function verifyOTP($identifier, $otp)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE identifier = :identifier 
                AND otp_code = :otp 
                AND expires_at > NOW() 
                AND is_used = 0 
                ORDER BY created_at DESC 
                LIMIT 1";
        
        $params = [
            'identifier' => $identifier,
            'otp' => $otp
        ];
        
        return $this->db->fetch($sql, $params);
    }
    
    /**
     * Mark OTP as used
     * 
     * @param int $id ID reset request
     * @return bool
     */
    public function markAsUsed($id)
    {
        return $this->update($id, [
            'is_used' => 1,
            'used_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * Delete reset request by identifier
     * 
     * @param string $identifier Username atau email
     * @return bool
     */
    public function deleteByIdentifier($identifier)
    {
        $sql = "DELETE FROM {$this->table} WHERE identifier = :identifier";
        return $this->db->query($sql, ['identifier' => $identifier]);
    }
    
    /**
     * Get active reset request
     * 
     * @param string $identifier Username atau email
     * @return array|false
     */
    public function getActiveRequest($identifier)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE identifier = :identifier 
                AND expires_at > NOW() 
                AND is_used = 0 
                ORDER BY created_at DESC 
                LIMIT 1";
        
        return $this->db->fetch($sql, ['identifier' => $identifier]);
    }
    
    /**
     * Clean expired requests
     * 
     * @return bool
     */
    public function cleanExpired()
    {
        $sql = "DELETE FROM {$this->table} WHERE expires_at < NOW()";
        return $this->db->query($sql);
    }
    
    /**
     * Get reset attempts count in last hour
     * 
     * @param string $identifier Username atau email
     * @return int
     */
    public function getRecentAttempts($identifier)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} 
                WHERE identifier = :identifier 
                AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)";
        
        $result = $this->db->fetch($sql, ['identifier' => $identifier]);
        return $result ? (int)$result['count'] : 0;
    }
    
    /**
     * Check if phone number exists in users table
     * 
     * @param string $identifier Username atau email
     * @return array|false User data with phone
     */
    public function getUserByIdentifier($identifier)
    {
        $sql = "SELECT id, username, email, full_name, phone, role 
                FROM users 
                WHERE (username = :identifier1 OR email = :identifier2) 
                AND is_active = 1 
                LIMIT 1";
        
        return $this->db->fetch($sql, [
            'identifier1' => $identifier,
            'identifier2' => $identifier
        ]);
    }
}