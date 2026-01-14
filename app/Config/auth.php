<?php

return [
    'password' => [
        'min_length' => 6,
        'require_uppercase' => false,
        'require_lowercase' => false,
        'require_numbers' => false,
        'require_symbols' => false,
        'hash_algorithm' => PASSWORD_DEFAULT
    ],
    
    'session' => [
        'timeout' => 86400, // 24 hours
        'remember_me_timeout' => 2592000, // 30 days
        'max_login_attempts' => 5,
        'lockout_duration' => 900 // 15 minutes
    ],
    
    'roles' => [
        'admin' => [
            'name' => 'Administrator',
            'permissions' => ['*'] // All permissions
        ],
        'dosen' => [
            'name' => 'Dosen',
            'permissions' => [
                'announcements.create',
                'announcements.read',
                'announcements.update',
                'announcements.delete',
                'dashboard.admin'
            ]
        ],
        'mahasiswa' => [
            'name' => 'Mahasiswa',
            'permissions' => [
                'announcements.read',
                'dashboard.student',
                'profile.update'
            ]
        ]
    ],
    
    'registration' => [
        'enabled' => true,
        'default_role' => 'mahasiswa',
        'email_verification' => false,
        'admin_approval' => false
    ]
];