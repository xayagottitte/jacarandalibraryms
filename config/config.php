<?php
// Application Configuration
define('APP_NAME', 'Jacaranda Libraries');
define('APP_VERSION', '1.0.0');
define('APP_URL', 'http://your-domain.com');
define('ENVIRONMENT', 'development'); // Change to 'production' for debugging

// Security Configuration
define('MAX_LOGIN_ATTEMPTS', 4);
define('LOCKOUT_TIME', 900); // 15 minutes
define('SESSION_TIMEOUT', 3600); // 1 hour
define('CSRF_TOKEN_LIFETIME', 3600); // 1 hour

// File Upload Configuration
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_FILE_TYPES', ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'csv']);

// Email Configuration
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'lettherebecarnagex@gmail.com');
define('SMTP_PASSWORD', 'wiyc axrx gdvj poxv'); // Use environment variable or secure vault in production

// Backup Configuration
define('BACKUP_PATH', '../backups/');
define('BACKUP_RETENTION_DAYS', 30);

// Error Reporting
if (ENVIRONMENT === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', '../logs/php_errors.log');
}

// Timezone
date_default_timezone_set('UTC');

// Security Headers
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('X-XSS-Protection: 1; mode=block');
header('Strict-Transport-Security: max-age=31536000; includeSubDomains');

// Start session with security settings
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1); // Enable in production with HTTPS
ini_set('session.use_strict_mode', 1);
?>