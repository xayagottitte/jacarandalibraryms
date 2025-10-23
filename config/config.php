<?php
// Include Composer autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// Set timezone to match your location (East Africa Time)
date_default_timezone_set('Africa/Nairobi');

// Application Configuration
define('APP_NAME', 'Jacaranda Libraries');
define('APP_VERSION', '1.0.0');
define('APP_URL', 'http://localhost');

// Auto-detect environment and set BASE_PATH accordingly
if (isset($_SERVER['SERVER_SOFTWARE']) && strpos($_SERVER['SERVER_SOFTWARE'], 'Apache') !== false) {
    // XAMPP or Apache server with .htaccess redirect
    define('BASE_PATH', '/jacarandalibraryms');
} elseif (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '8000') {
    // PHP development server
    define('BASE_PATH', '');
} else {
    // Default fallback for XAMPP
    define('BASE_PATH', '/jacarandalibraryms');
}

define('ENVIRONMENT', 'development'); // Change to 'production' for debugging

// Security Configuration
define('MAX_LOGIN_ATTEMPTS', 4);
define('LOCKOUT_TIME', 900); // 15 minutes
define('SESSION_TIMEOUT', 1800); // 30 minutes (session inactivity timeout)
define('SESSION_ABSOLUTE_TIMEOUT', 28800); // 8 hours (absolute session lifetime)
define('CSRF_TOKEN_LIFETIME', 3600); // 1 hour
define('SESSION_REGENERATE_INTERVAL', 300); // 5 minutes (regenerate session ID periodically)

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

// Session Security Configuration (must be set before session_start())
ini_set('session.cookie_httponly', 1);       // Prevent JavaScript access to session cookie
ini_set('session.use_strict_mode', 1);       // Reject uninitialized session IDs
ini_set('session.cookie_samesite', 'Strict'); // CSRF protection
ini_set('session.use_only_cookies', 1);      // Force cookies, reject URL session IDs
ini_set('session.cookie_lifetime', 0);       // Session cookie expires on browser close
ini_set('session.gc_maxlifetime', SESSION_TIMEOUT); // Server-side session data lifetime
ini_set('session.name', 'JACARANDA_SESSION'); // Custom session name

// Enable secure flag in production (requires HTTPS)
if (ENVIRONMENT === 'production') {
    ini_set('session.cookie_secure', 1);     // Only send cookie over HTTPS
}

// Security Headers
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('X-XSS-Protection: 1; mode=block');
if (ENVIRONMENT === 'production') {
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
}
?>