<?php
// config/database.php — ScholarFlow Database Configuration

define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_NAME', 'scholarflow_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Application config
define('APP_NAME', 'ScholarFlow');
define('APP_URL', 'http://localhost/ScholarFlow/public');
define('APP_ENV', 'development'); // development | production
define('APP_VERSION', '1.0.0');

// Session config
define('SESSION_LIFETIME', 3600); // 1 hour

// Upload config
define('UPLOAD_PATH', realpath(__DIR__ . '/../public') . '/uploads/');
define('UPLOAD_URL', APP_URL . '/uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_EXTENSIONS', ['pdf', 'jpg', 'jpeg', 'png']);
