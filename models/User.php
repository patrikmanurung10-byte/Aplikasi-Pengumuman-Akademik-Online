<?php

use App\Core\Model;

/**
 * User Model
 * Model untuk mengelola data pengguna
 */
class User extends Model
{
    protected $table = 'users';
    
    protected $fillable = [
        'username', 'email', 'password', 'full_name', 
        'role', 'nim_nip', 'program_studi', 'phone', 'address', 'is_active', 'academic_status'
    ];
    
    protected $guarded = ['id'];
    
    public function findByUsername($username)
    {
        return $this->whereFirst('username', $username);
    }
    
    public function findByEmail($email)
    {
        return $this->whereFirst('email', $email);
    }
    
    public function findByUsernameOrEmail($identifier)
    {
        $sql = "SELECT * FROM {$this->table} WHERE username = :username OR email = :email LIMIT 1";
        return $this->db->fetch($sql, ['username' => $identifier, 'email' => $identifier]);
    }
    
    public function createUser($data)
    {
        // Hash password
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        // Set default values
        $data['role'] = $data['role'] ?? 'mahasiswa';
        $data['is_active'] = $data['is_active'] ?? 1;
        
        return $this->create($data);
    }
    
    public function updatePassword($userId, $newPassword)
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        return $this->update($userId, ['password' => $hashedPassword]);
    }
    
    public function verifyPassword($password, $hashedPassword)
    {
        return password_verify($password, $hashedPassword);
    }
    
    public function getActiveUsers()
    {
        return $this->where('is_active', 1);
    }
    
    public function getUsersByRole($role)
    {
        return $this->where('role', $role);
    }
    
    public function searchUsers($query, $page = 1, $perPage = 15)
    {
        return $this->search($query, ['username', 'email', 'full_name', 'nim_nip'], $page, $perPage);
    }
    
    public function getUserStats()
    {
        $sql = "SELECT 
                    COUNT(*) as total_users,
                    SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active_users,
                    SUM(CASE WHEN role = 'mahasiswa' THEN 1 ELSE 0 END) as total_mahasiswa,
                    SUM(CASE WHEN role = 'dosen' THEN 1 ELSE 0 END) as total_dosen,
                    SUM(CASE WHEN role = 'admin' THEN 1 ELSE 0 END) as total_admin
                FROM {$this->table}";
        
        return $this->db->fetch($sql);
    }
    
    public function getActiveUsersCount()
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE is_active = 1";
        $result = $this->db->fetch($sql);
        return $result['count'] ?? 0;
    }
    
    public function isUsernameExists($username, $excludeId = null)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE username = :username";
        $params = ['username' => $username];
        
        if ($excludeId) {
            $sql .= " AND id != :exclude_id";
            $params['exclude_id'] = $excludeId;
        }
        
        $result = $this->db->fetch($sql, $params);
        return $result['count'] > 0;
    }
    
    public function isEmailExists($email, $excludeId = null)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE email = :email";
        $params = ['email' => $email];
        
        if ($excludeId) {
            $sql .= " AND id != :exclude_id";
            $params['exclude_id'] = $excludeId;
        }
        
        $result = $this->db->fetch($sql, $params);
        return $result['count'] > 0;
    }
    
    public function isNimExists($nim, $excludeId = null)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE nim_nip = :nim";
        $params = ['nim' => $nim];
        
        if ($excludeId) {
            $sql .= " AND id != :exclude_id";
            $params['exclude_id'] = $excludeId;
        }
        
        $result = $this->db->fetch($sql, $params);
        return $result['count'] > 0;
    }
    
    public function getRecentUsers($limit = 10)
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT :limit";
        return $this->db->fetchAll($sql, ['limit' => $limit]);
    }
    
    public function getTotalCount()
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        $result = $this->db->fetch($sql);
        return $result['count'] ?? 0;
    }
    
    public function getActiveCount()
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE is_active = 1";
        $result = $this->db->fetch($sql);
        return $result['count'] ?? 0;
    }
}