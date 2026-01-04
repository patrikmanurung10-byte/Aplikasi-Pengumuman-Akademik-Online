<?php

/**
 * Fonnte WhatsApp API Configuration
 * Konfigurasi untuk integrasi WhatsApp API Fonnte
 */

return [
    // API Configuration
    'api_url' => 'https://api.fonnte.com/send',
    'validate_url' => 'https://api.fonnte.com/validate',
    
    // Token API Fonnte (dapatkan dari dashboard Fonnte)
    'token' => $_ENV['FONNTE_TOKEN'] ?? getenv('FONNTE_TOKEN') ?? 'YOUR_FONNTE_TOKEN_HERE',
    
    // Default country code
    'country_code' => '62',
    
    // Message Templates
    'templates' => [
        'otp' => [
            'greeting' => 'Halo {name},',
            'title' => '🔐 *Kode OTP Reset Password*',
            'subtitle' => 'Sistem APAO Polibatam',
            'otp_text' => 'Kode OTP Anda: *{otp}*',
            'warning_title' => '⚠️ *PENTING:*',
            'warnings' => [
                '• Kode berlaku selama 10 menit',
                '• Jangan bagikan kode ini kepada siapapun',
                '• Gunakan kode ini untuk reset password'
            ],
            'disclaimer' => 'Jika Anda tidak meminta reset password, abaikan pesan ini.',
            'signature' => 'Terima kasih,\n*Tim APAO Polibatam*'
        ],
        
        'password_reset_confirmation' => [
            'greeting' => 'Halo {name},',
            'title' => '✅ *Password Berhasil Direset*',
            'subtitle' => 'Sistem APAO Polibatam',
            'datetime_text' => 'Password Anda telah berhasil direset pada:\n📅 {datetime}',
            'security_title' => '🔒 *Tips Keamanan:*',
            'security_tips' => [
                '• Gunakan password yang kuat',
                '• Jangan bagikan password kepada siapapun',
                '• Logout dari perangkat yang tidak dikenal'
            ],
            'disclaimer' => 'Jika ini bukan Anda, segera hubungi administrator.',
            'signature' => 'Terima kasih,\n*Tim APAO Polibatam*'
        ]
    ],
    
    // Rate Limiting
    'rate_limit' => [
        'max_attempts_per_hour' => 5,
        'max_attempts_per_day' => 10,
        'cooldown_minutes' => 2
    ],
    
    // OTP Settings
    'otp' => [
        'length' => 6,
        'expiry_minutes' => 10,
        'max_verification_attempts' => 5
    ],
    
    // Phone Number Validation
    'phone_validation' => [
        'min_length' => 10,
        'max_length' => 15,
        'allowed_prefixes' => ['62', '0'],
        'format_to_international' => true
    ],
    
    // Logging
    'logging' => [
        'enabled' => true,
        'log_file' => 'logs/whatsapp.log',
        'log_level' => 'info' // debug, info, warning, error
    ],
    
    // Testing
    'testing' => [
        'enabled' => false,
        'test_numbers' => [
            '6281234567890',
            '6289876543210'
        ],
        'mock_responses' => true
    ]
];