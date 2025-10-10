<?php
class Security {
    public static function sanitizeInput($input) {
        if (is_array($input)) {
            return array_map([self::class, 'sanitizeInput'], $input);
        }
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }

    public static function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function validateNumber($number, $min = null, $max = null) {
        if (!is_numeric($number)) return false;
        if ($min !== null && $number < $min) return false;
        if ($max !== null && $number > $max) return false;
        return true;
    }

    public static function generateCSRFToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function verifyCSRFToken($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    public static function checkAuthorization($requiredRole, $userLibraryId = null, $resourceLibraryId = null) {
        // Super admin can access everything
        if ($_SESSION['role'] === 'super_admin') {
            return true;
        }

        // Check role
        if ($_SESSION['role'] !== $requiredRole) {
            return false;
        }

        // Librarians can only access their own library resources
        if ($_SESSION['role'] === 'librarian' && $userLibraryId !== $resourceLibraryId) {
            return false;
        }

        return true;
    }

    public static function logSecurityEvent($userId, $event, $details = '') {
        $logMessage = date('Y-m-d H:i:s') . " - User $userId - $event - $details" . PHP_EOL;
        file_put_contents('../logs/security.log', $logMessage, FILE_APPEND | LOCK_EX);
    }
}
?>