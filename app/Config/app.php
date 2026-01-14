<?php

return [
    'name' => 'APAO Polibatam',
    'version' => '2.0.0',
    'description' => 'Aplikasi Pengumuman Akademik Online - Politeknik Negeri Batam',
    
    'base_url' => 'http://localhost:8000',
    'base_path' => '',
    
    'timezone' => 'Asia/Jakarta',
    'locale' => 'id_ID',
    
    'debug' => true,
    'log_errors' => true,
    
    'session' => [
        'timeout' => 86400, // 24 hours
        'name' => 'APAO_SESSION',
        'secure' => false, // Set to true in production with HTTPS
        'httponly' => true,
        'samesite' => 'Lax'
    ],
    
    'security' => [
        'csrf_protection' => true,
        'xss_protection' => true,
        'sql_injection_protection' => true
    ],
    
    'pagination' => [
        'per_page' => 15,
        'max_per_page' => 100
    ],
    
    'upload' => [
        'max_size' => 5242880, // 5MB
        'allowed_types' => ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx'],
        'path' => 'public/uploads/'
    ]
];