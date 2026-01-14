<?php

use App\Core\Model;

/**
 * Announcement Model
 * Model untuk mengelola data pengumuman
 */
class Announcement extends Model
{
    protected $table = 'announcements';
    
    protected $fillable = [
        'title', 'slug', 'content', 'excerpt', 'category_id', 'author_id',
        'status', 'priority', 'is_active', 'is_featured', 'is_pinned',
        'publish_date', 'expire_date', 'views', 'target_audience'
    ];
    
    protected $guarded = ['id'];
    
    public function createAnnouncement($data)
    {
        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = $this->generateSlug($data['title']);
        }
        
        // Generate excerpt if not provided
        if (empty($data['excerpt'])) {
            $data['excerpt'] = $this->generateExcerpt($data['content']);
        }
        
        return $this->create($data);
    }
    
    public function getAllWithAuthor()
    {
        $sql = "SELECT a.*, u.full_name as author_name, u.role as author_role,
                       c.name as category_name, c.color as category_color
                FROM {$this->table} a
                LEFT JOIN users u ON a.author_id = u.id
                LEFT JOIN categories c ON a.category_id = c.id
                ORDER BY a.created_at DESC";
        
        return $this->db->fetchAll($sql);
    }
    
    public function getForUser($userRole = null, $userSemester = null, $userProdi = null)
    {
        $sql = "SELECT a.*, u.full_name as author_name, u.role as author_role,
                       c.name as category_name, c.color as category_color
                FROM {$this->table} a
                LEFT JOIN users u ON a.author_id = u.id
                LEFT JOIN categories c ON a.category_id = c.id
                WHERE a.is_active = 1 AND a.status = 'published'
                AND (a.publish_date IS NULL OR a.publish_date <= NOW())
                AND (a.expire_date IS NULL OR a.expire_date > NOW())
                AND (
                    a.target_audience = 'all' OR
                    a.target_audience = :user_role OR
                    a.target_audience = :user_semester OR
                    a.target_audience = :user_prodi
                )
                ORDER BY a.is_pinned DESC, a.publish_date DESC";
        
        return $this->db->fetchAll($sql, [
            'user_role' => $userRole,
            'user_semester' => $userSemester,
            'user_prodi' => $userProdi
        ]);
    }
    
    public function getAllActive()
    {
        $sql = "SELECT a.*, u.full_name as author_name, c.name as category_name
                FROM {$this->table} a
                LEFT JOIN users u ON a.author_id = u.id
                LEFT JOIN categories c ON a.category_id = c.id
                WHERE a.is_active = 1 AND a.status = 'published'
                AND (a.publish_date IS NULL OR a.publish_date <= NOW())
                AND (a.expire_date IS NULL OR a.expire_date > NOW())
                ORDER BY a.is_pinned DESC, a.publish_date DESC";
        
        return $this->db->fetchAll($sql);
    }
    
    public function getRecent($limit = 5)
    {
        $sql = "SELECT a.*, u.full_name as author_name, c.name as category_name
                FROM {$this->table} a
                LEFT JOIN users u ON a.author_id = u.id
                LEFT JOIN categories c ON a.category_id = c.id
                WHERE a.is_active = 1 AND a.status = 'published'
                ORDER BY a.created_at DESC
                LIMIT :limit";
        
        return $this->db->fetchAll($sql, ['limit' => $limit]);
    }
    
    public function getByAuthor($authorId)
    {
        $sql = "SELECT a.*, u.full_name as author_name, c.name as category_name
                FROM {$this->table} a
                LEFT JOIN users u ON a.author_id = u.id
                LEFT JOIN categories c ON a.category_id = c.id
                WHERE a.author_id = :author_id
                ORDER BY a.created_at DESC";
        
        return $this->db->fetchAll($sql, ['author_id' => $authorId]);
    }
    
    public function getByCategory($categoryId)
    {
        $sql = "SELECT a.*, u.full_name as author_name, c.name as category_name
                FROM {$this->table} a
                LEFT JOIN users u ON a.author_id = u.id
                LEFT JOIN categories c ON a.category_id = c.id
                WHERE a.category_id = :category_id AND a.is_active = 1
                ORDER BY a.created_at DESC";
        
        return $this->db->fetchAll($sql, ['category_id' => $categoryId]);
    }
    
    public function getByCategoryWithDetails($categoryId)
    {
        return $this->getByCategory($categoryId);
    }
    
    public function incrementViews($id)
    {
        $sql = "UPDATE {$this->table} SET views = views + 1 WHERE id = :id";
        return $this->db->execute($sql, ['id' => $id]);
    }
    
    public function search($query, $columns = ['title', 'content'], $page = 1, $perPage = 15, $conditions = [])
    {
        if (empty($columns)) {
            $columns = ['title', 'content'];
        }
        
        $offset = ($page - 1) * $perPage;
        
        // Build search conditions
        $searchParts = [];
        $params = [];
        foreach ($columns as $column) {
            $searchParts[] = "a.{$column} LIKE :query";
        }
        $searchClause = implode(' OR ', $searchParts);
        $params['query'] = "%{$query}%";
        
        // Add additional conditions
        $whereClause = "WHERE ({$searchClause})";
        if (!empty($conditions)) {
            foreach ($conditions as $key => $value) {
                $whereClause .= " AND a.{$key} = :{$key}";
                $params[$key] = $value;
            }
        }
        
        // Get total count
        $countSql = "SELECT COUNT(*) as total 
                     FROM {$this->table} a 
                     LEFT JOIN users u ON a.author_id = u.id
                     LEFT JOIN categories c ON a.category_id = c.id
                     {$whereClause}";
        $totalResult = $this->db->fetch($countSql, $params);
        $total = $totalResult['total'];
        
        // Get data
        $sql = "SELECT a.*, u.full_name as author_name, c.name as category_name
                FROM {$this->table} a
                LEFT JOIN users u ON a.author_id = u.id
                LEFT JOIN categories c ON a.category_id = c.id
                {$whereClause}
                ORDER BY a.created_at DESC
                LIMIT {$perPage} OFFSET {$offset}";
        
        $data = $this->db->fetchAll($sql, $params);
        
        return [
            'data' => $data,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'total_pages' => ceil($total / $perPage),
                'has_next' => $page < ceil($total / $perPage),
                'has_prev' => $page > 1
            ]
        ];
    }
    
    private function generateSlug($title)
    {
        $slug = strtolower(trim($title));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');
        
        // Make unique
        $originalSlug = $slug;
        $counter = 1;
        
        while ($this->slugExists($slug)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
    
    private function slugExists($slug)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE slug = :slug";
        $result = $this->db->fetch($sql, ['slug' => $slug]);
        return $result['count'] > 0;
    }
    
    private function generateExcerpt($content, $length = 200)
    {
        $text = strip_tags($content);
        if (strlen($text) <= $length) {
            return $text;
        }
        
        return substr($text, 0, $length) . '...';
    }
    
    public static function getTargetAudienceLabel($target)
    {
        $labels = [
            'all' => 'Semua Pengguna Website',
            'mahasiswa' => 'Semua Mahasiswa',
            'dosen' => 'Semua Dosen',
            'admin' => 'Admin',
            'semester_1' => 'Mahasiswa Semester 1',
            'semester_2' => 'Mahasiswa Semester 2',
            'semester_3' => 'Mahasiswa Semester 3',
            'semester_4' => 'Mahasiswa Semester 4',
            'semester_5' => 'Mahasiswa Semester 5',
            'semester_6' => 'Mahasiswa Semester 6',
            'teknik_informatika' => 'Teknik Informatika',
            'sistem_informasi' => 'Sistem Informasi',
            'teknik_komputer' => 'Teknik Komputer',
            'manajemen_informatika' => 'Manajemen Informatika'
        ];
        
        return $labels[$target] ?? 'Semua Pengguna Website';
    }
    
    public static function getTargetAudienceOptions()
    {
        return [
            'Umum' => [
                'all' => 'Semua Pengguna Website',
                'mahasiswa' => 'Semua Mahasiswa',
                'dosen' => 'Semua Dosen',
                'admin' => 'Admin'
            ],
            'Mahasiswa Per Semester' => [
                'semester_1' => 'Mahasiswa Semester 1',
                'semester_2' => 'Mahasiswa Semester 2',
                'semester_3' => 'Mahasiswa Semester 3',
                'semester_4' => 'Mahasiswa Semester 4',
                'semester_5' => 'Mahasiswa Semester 5',
                'semester_6' => 'Mahasiswa Semester 6'
            ],
            'Program Studi' => [
                'teknik_informatika' => 'Teknik Informatika',
                'sistem_informasi' => 'Sistem Informasi',
                'teknik_komputer' => 'Teknik Komputer',
                'manajemen_informatika' => 'Manajemen Informatika'
            ]
        ];
    }
}