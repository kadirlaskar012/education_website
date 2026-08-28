<?php
/**
 * Global Configuration Settings
 * Supports both Local Development (SQLite) and cPanel Production (MySQL)
 */

return [
    'app_name' => 'EduGov News',
    'app_url'  => getenv('APP_URL') ?: 'http://127.0.0.1:8000',
    'env'      => getenv('APP_ENV') ?: 'production', // 'local' or 'production'
    'debug'    => (bool)(getenv('APP_DEBUG') ?: false),

    // Database Configuration (Dual-mode: SQLite locally, MySQL on cPanel)
    'database' => [
        'driver'   => getenv('DB_DRIVER') ?: 'sqlite', // 'mysql' or 'sqlite'
        
        // MySQL settings (for cPanel hosting)
        'mysql' => [
            'host'     => getenv('DB_HOST') ?: 'localhost',
            'port'     => getenv('DB_PORT') ?: '3306',
            'database' => getenv('DB_DATABASE') ?: 'edugov_db',
            'username' => getenv('DB_USERNAME') ?: 'root',
            'password' => getenv('DB_PASSWORD') ?: '',
            'charset'  => 'utf8mb4',
        ],

        // SQLite settings (for zero-config local testing)
        'sqlite' => [
            'path' => __DIR__ . '/../database/database.sqlite',
        ],
    ],

    // Admin Credentials (default)
    'admin' => [
        'username' => getenv('ADMIN_USER') ?: 'admin',
        'password' => getenv('ADMIN_PASS') ?: 'admin123', // Will be hashed via password_hash()
    ],

    // Scraper Pipeline Configuration
    'pipeline' => [
        'fetch_timeout_seconds' => 15,
        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/128.0.0.0 Safari/537.36 EduGovBot/2.0',
        'cron_interval_minutes' => 15,
    ]
];
