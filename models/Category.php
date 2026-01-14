<?php

use App\Core\Model;

/**
 * Category Model
 * Model untuk mengelola kategori pengumuman
 */
class Category extends Model
{
    protected $table = 'categories';
    
    protected $fillable = [
        'name', 'slug', 'description', 'color', 'icon', 'is_active', 'sort_order', 'created_by'
    ];
    
    protected $guarded = ['id'];
    
    public function getActiveCategories()
    {
        return $this->whereOrderBy('is_active', 1, 'ORDER BY sort_order ASC, name ASC');
    }
    
    public function createCategory($data)
    {
        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = $this->generateSlug($data['name']);
        }
        
        return $this->create($data);
    }
    
    private function generateSlug($name)
    {
        $slug = strtolower(trim($name));
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
}