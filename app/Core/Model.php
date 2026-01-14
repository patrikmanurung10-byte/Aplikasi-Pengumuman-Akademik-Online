<?php

namespace App\Core;


abstract class Model
{
    protected $table;
    protected $primaryKey = 'id';
    protected $fillable = [];
    protected $guarded = [];
    protected $timestamps = true;
    protected $dateFormat = 'Y-m-d H:i:s';
    
    protected $db;
    
    public function __construct()
    {
        $this->db = Database::getInstance();
        
        if (empty($this->table)) {
            $this->table = $this->getTableName();
        }
    }
    
    protected function getTableName()
    {
        $className = (new \ReflectionClass($this))->getShortName();
        return strtolower($className) . 's';
    }
    
    public function find($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id LIMIT 1";
        return $this->db->fetch($sql, ['id' => $id]);
    }
    
    public function findOrFail($id)
    {
        $result = $this->find($id);
        if (!$result) {
            throw new \Exception("Record not found with ID: {$id}");
        }
        return $result;
    }
    
    public function all($columns = ['*'])
    {
        $columns = is_array($columns) ? implode(',', $columns) : $columns;
        $sql = "SELECT {$columns} FROM {$this->table}";
        return $this->db->fetchAll($sql);
    }
    
    public function where($column, $operator = '=', $value = null, $orderBy = null)
    {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }
        
        $sql = "SELECT * FROM {$this->table} WHERE {$column} {$operator} :value";
        
        // Add ORDER BY if provided
        if ($orderBy) {
            $sql .= " {$orderBy}";
        }
        
        return $this->db->fetchAll($sql, ['value' => $value]);
    }
    
    public function whereFirst($column, $operator = '=', $value = null)
    {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }
        
        $sql = "SELECT * FROM {$this->table} WHERE {$column} {$operator} :value LIMIT 1";
        return $this->db->fetch($sql, ['value' => $value]);
    }
    
    public function whereOrderBy($column, $value, $orderBy)
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$column} = :value {$orderBy}";
        return $this->db->fetchAll($sql, ['value' => $value]);
    }
    
    public function create($data)
    {
        $data = $this->filterFillable($data);
        
        if ($this->timestamps) {
            $now = date($this->dateFormat);
            $data['created_at'] = $now;
            $data['updated_at'] = $now;
        }
        
        return $this->db->insert($this->table, $data);
    }
    
    public function update($id, $data)
    {
        $data = $this->filterFillable($data);
        
        if ($this->timestamps) {
            $data['updated_at'] = date($this->dateFormat);
        }
        
        $where = "{$this->primaryKey} = :id";
        return $this->db->update($this->table, $data, $where, ['id' => $id]);
    }
    
    public function delete($id)
    {
        $where = "{$this->primaryKey} = :id";
        return $this->db->delete($this->table, $where, ['id' => $id]);
    }
    
    public function paginate($page = 1, $perPage = 15, $conditions = [], $orderBy = null)
    {
        $offset = ($page - 1) * $perPage;
        
        // Build WHERE clause
        $whereClause = '';
        $params = [];
        if (!empty($conditions)) {
            $whereParts = [];
            foreach ($conditions as $key => $value) {
                $whereParts[] = "{$key} = :{$key}";
                $params[$key] = $value;
            }
            $whereClause = 'WHERE ' . implode(' AND ', $whereParts);
        }
        
        // Build ORDER BY clause
        $orderClause = $orderBy ? "ORDER BY {$orderBy}" : "ORDER BY {$this->primaryKey} DESC";
        
        // Get total count
        $countSql = "SELECT COUNT(*) as total FROM {$this->table} {$whereClause}";
        $totalResult = $this->db->fetch($countSql, $params);
        $total = $totalResult['total'];
        
        // Get data
        $sql = "SELECT * FROM {$this->table} {$whereClause} {$orderClause} LIMIT {$perPage} OFFSET {$offset}";
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
    
    public function search($query, $columns = [], $page = 1, $perPage = 15)
    {
        if (empty($columns)) {
            throw new \Exception("Search columns must be specified");
        }
        
        $offset = ($page - 1) * $perPage;
        
        // Build search conditions
        $searchParts = [];
        $params = [];
        foreach ($columns as $column) {
            $searchParts[] = "{$column} LIKE :query";
        }
        $searchClause = implode(' OR ', $searchParts);
        $params['query'] = "%{$query}%";
        
        // Get total count
        $countSql = "SELECT COUNT(*) as total FROM {$this->table} WHERE {$searchClause}";
        $totalResult = $this->db->fetch($countSql, $params);
        $total = $totalResult['total'];
        
        // Get data
        $sql = "SELECT * FROM {$this->table} WHERE {$searchClause} ORDER BY {$this->primaryKey} DESC LIMIT {$perPage} OFFSET {$offset}";
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
    
    protected function filterFillable($data)
    {
        if (empty($this->fillable)) {
            return $data;
        }
        
        return array_intersect_key($data, array_flip($this->fillable));
    }
    
    public function beginTransaction()
    {
        return $this->db->beginTransaction();
    }
    
    public function commit()
    {
        return $this->db->commit();
    }
    
    public function rollback()
    {
        return $this->db->rollback();
    }
}