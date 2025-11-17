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
    
    /**
     * Validate password strength
     * @return array ['valid' => bool, 'errors' => array]
     */
    public static function validatePassword($password, $minLength = 8) {
        $errors = [];
        
        if (strlen($password) < $minLength) {
            $errors[] = "Password must be at least {$minLength} characters long.";
        }
        
        // Enforce strong password requirements
        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = "Password must contain at least one uppercase letter.";
        }
        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = "Password must contain at least one lowercase letter.";
        }
        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = "Password must contain at least one number.";
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }
    
    /**
     * Validate username format
     */
    public static function validateUsername($username) {
        // Username: 3-50 chars, alphanumeric, underscore, hyphen
        return preg_match('/^[a-zA-Z0-9_-]{3,50}$/', $username) === 1;
    }
    
    /**
     * Validate phone number
     */
    public static function validatePhone($phone) {
        // Remove spaces, dashes, parentheses
        $cleaned = preg_replace('/[\s\-\(\)]/', '', $phone);
        // Check if it's 10-15 digits
        return preg_match('/^\+?[0-9]{10,15}$/', $cleaned) === 1;
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

    /**
     * Log activity to database
     * @param int|null $userId User ID (null for anonymous events)
     * @param string $eventType Type of event (e.g., 'login', 'logout', 'password_change')
     * @param string $eventCategory Category: authentication, profile, security, data, system
     * @param string $description Human-readable description
     * @param array $metadata Additional structured data
     * @param string $severity Severity level: info, warning, critical
     */
    public static function logActivity($userId, $eventType, $eventCategory, $description, $metadata = [], $severity = 'info') {
        try {
            $database = new Database();
            $db = $database->connect();
            
            $sql = "INSERT INTO activity_logs 
                    (user_id, event_type, event_category, description, ip_address, user_agent, metadata, severity) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $db->prepare($sql);
            $stmt->execute([
                $userId,
                $eventType,
                $eventCategory,
                $description,
                self::getClientIP(),
                $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown',
                json_encode($metadata),
                $severity
            ]);
            
            return true;
        } catch (Exception $e) {
            // Fail silently - don't break application if logging fails
            error_log("Activity logging failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Quick log for authentication events
     */
    public static function logAuth($userId, $eventType, $description, $severity = 'info') {
        return self::logActivity($userId, $eventType, 'authentication', $description, [], $severity);
    }

    /**
     * Quick log for profile events
     */
    public static function logProfile($userId, $eventType, $description, $metadata = []) {
        return self::logActivity($userId, $eventType, 'profile', $description, $metadata, 'info');
    }

    /**
     * Quick log for security events
     */
    public static function logSecurity($userId, $eventType, $description, $severity = 'warning') {
        return self::logActivity($userId, $eventType, 'security', $description, [], $severity);
    }

    public static function logSecurityEvent($userId, $event, $details = '') {
        // Legacy method - redirect to new logging system
        self::logSecurity($userId, $event, $details, 'warning');
    }

    /**
     * Track login attempt (both failed and successful)
     */
    public static function trackLoginAttempt($email, $success = false) {
        try {
            $database = new Database();
            $db = $database->connect();
            $ip = self::getClientIP();
            
            $stmt = $db->prepare("INSERT INTO login_attempts (email, ip_address, success) VALUES (?, ?, ?)");
            $stmt->execute([$email, $ip, $success ? 1 : 0]);
            
            return true;
        } catch (PDOException $e) {
            error_log("Failed to track login attempt: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if account is locked due to too many failed attempts
     */
    public static function isAccountLocked($email) {
        try {
            $database = new Database();
            $db = $database->connect();
            $ip = self::getClientIP();
            $lockoutTime = LOCKOUT_TIME; // seconds
            $maxAttempts = MAX_LOGIN_ATTEMPTS;
            
            // Check failed attempts within lockout window
            $stmt = $db->prepare("
                SELECT COUNT(*) as attempt_count,
                       MAX(attempt_time) as last_attempt,
                       TIMESTAMPDIFF(SECOND, MAX(attempt_time), NOW()) as seconds_since_last
                FROM login_attempts 
                WHERE email = ? 
                AND success = 0
                AND attempt_time > DATE_SUB(NOW(), INTERVAL ? SECOND)
            ");
            $stmt->execute([$email, $lockoutTime]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result['attempt_count'] >= $maxAttempts) {
                // Calculate remaining time using database timestamps
                $remainingTime = max(0, $lockoutTime - ($result['seconds_since_last'] ?? 0));
                
                return [
                    'locked' => true,
                    'attempts' => $result['attempt_count'],
                    'last_attempt' => $result['last_attempt'],
                    'remaining_time' => $remainingTime
                ];
            }
            
            return [
                'locked' => false,
                'attempts' => $result['attempt_count'],
                'remaining_attempts' => $maxAttempts - $result['attempt_count']
            ];
        } catch (PDOException $e) {
            error_log("Failed to check account lock status: " . $e->getMessage());
            // Fail open - don't lock account on error
            return ['locked' => false, 'attempts' => 0, 'remaining_attempts' => MAX_LOGIN_ATTEMPTS];
        }
    }

    /**
     * Clear login attempts for an email (after successful login)
     */
    public static function clearLoginAttempts($email) {
        try {
            $database = new Database();
            $db = $database->connect();
            
            // Delete old failed attempts
            $stmt = $db->prepare("DELETE FROM login_attempts WHERE email = ? AND success = 0");
            $stmt->execute([$email]);
            
            return true;
        } catch (PDOException $e) {
            error_log("Failed to clear login attempts: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get remaining lockout time in seconds
     */
    public static function getRemainingLockoutTime($lastAttemptTime) {
        if (!$lastAttemptTime) return 0;
        
        $lastAttempt = strtotime($lastAttemptTime);
        $lockoutEnd = $lastAttempt + LOCKOUT_TIME;
        $remaining = $lockoutEnd - time();
        
        return max(0, $remaining);
    }

    /**
     * Get client IP address (handles proxies)
     */
    public static function getClientIP() {
        $ipAddress = '';
        
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ipAddress = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        }
        
        return $ipAddress;
    }

    /**
     * Format remaining time for display
     */
    public static function formatLockoutTime($seconds) {
        $minutes = floor($seconds / 60);
        $secs = $seconds % 60;
        
        if ($minutes > 0) {
            return sprintf("%d minute%s %d second%s", 
                $minutes, $minutes != 1 ? 's' : '',
                $secs, $secs != 1 ? 's' : ''
            );
        }
        
        return sprintf("%d second%s", $secs, $secs != 1 ? 's' : '');
    }

    /**
     * Check if password reset is rate limited for an email
     * @param string $email
     * @return array ['allowed' => bool, 'attempts' => int, 'remaining_time' => int]
     */
    public static function checkPasswordResetRateLimit($email) {
        try {
            $database = new Database();
            $db = $database->connect();
            
            $window = PASSWORD_RESET_RATE_LIMIT_WINDOW;
            $maxAttempts = MAX_PASSWORD_RESET_ATTEMPTS;
            
            // Count reset requests within the time window
            $stmt = $db->prepare("
                SELECT COUNT(*) as attempt_count,
                       MAX(created_at) as last_attempt,
                       TIMESTAMPDIFF(SECOND, MAX(created_at), NOW()) as seconds_since_last
                FROM password_reset_tokens 
                WHERE email = ? 
                AND created_at > DATE_SUB(NOW(), INTERVAL ? SECOND)
            ");
            $stmt->execute([$email, $window]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result['attempt_count'] >= $maxAttempts) {
                $remainingTime = max(0, $window - ($result['seconds_since_last'] ?? 0));
                
                return [
                    'allowed' => false,
                    'attempts' => $result['attempt_count'],
                    'remaining_time' => $remainingTime
                ];
            }
            
            return [
                'allowed' => true,
                'attempts' => $result['attempt_count'],
                'remaining_attempts' => $maxAttempts - $result['attempt_count']
            ];
        } catch (PDOException $e) {
            error_log("Failed to check password reset rate limit: " . $e->getMessage());
            // Fail secure - deny on error
            return ['allowed' => false, 'attempts' => 0, 'remaining_time' => PASSWORD_RESET_RATE_LIMIT_WINDOW];
        }
    }

    /**
     * Validate password complexity for XSS prevention in password fields
     * @param string $password
     * @return bool
     */
    public static function isPasswordSafeFromXSS($password) {
        // Check for HTML tags or script injections in password
        if (preg_match('/<[^>]*>|javascript:|onerror=|onload=/i', $password)) {
            return false;
        }
        return true;
    }

    /**
     * Start a secure session with proper configuration
     */
    public static function startSecureSession() {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return; // Session already started
        }

        // Start the session
        session_start();

        // Validate and manage session security
        self::validateSession();
    }

    /**
     * Validate session security (timeout, regeneration, hijacking prevention)
     */
    private static function validateSession() {
        // Initialize session tracking variables if not set
        if (!isset($_SESSION['created'])) {
            $_SESSION['created'] = time();
        }
        if (!isset($_SESSION['last_activity'])) {
            $_SESSION['last_activity'] = time();
        }
        if (!isset($_SESSION['last_regeneration'])) {
            $_SESSION['last_regeneration'] = time();
        }

        // Check for session hijacking (IP and User Agent validation)
        if (isset($_SESSION['user_id'])) {
            self::validateSessionFingerprint();
        }

        // Check absolute session timeout (maximum session lifetime)
        if (time() - $_SESSION['created'] > SESSION_ABSOLUTE_TIMEOUT) {
            $userId = $_SESSION['user_id'] ?? null;
            
            // Log session timeout
            if ($userId) {
                self::logAuth($userId, 'session_timeout_absolute', 'Session expired due to absolute timeout', 'info');
            }
            
            self::destroySession();
            header('Location: ' . BASE_PATH . '/login?timeout=1');
            exit;
        }

        // Check inactivity timeout
        if (isset($_SESSION['user_id']) && (time() - $_SESSION['last_activity']) > SESSION_TIMEOUT) {
            $userId = $_SESSION['user_id'];
            
            // Log inactivity timeout
            self::logAuth($userId, 'session_timeout_inactivity', 'Session expired due to inactivity', 'info');
            
            self::destroySession();
            header('Location: ' . BASE_PATH . '/login?timeout=1');
            exit;
        }

        // Update last activity time
        $_SESSION['last_activity'] = time();

        // Regenerate session ID periodically to prevent session fixation
        if (time() - $_SESSION['last_regeneration'] > SESSION_REGENERATE_INTERVAL) {
            self::regenerateSession();
        }
    }

    /**
     * Validate session fingerprint to prevent session hijacking
     */
    private static function validateSessionFingerprint() {
        $currentFingerprint = self::generateSessionFingerprint();

        if (!isset($_SESSION['fingerprint'])) {
            $_SESSION['fingerprint'] = $currentFingerprint;
        } elseif ($_SESSION['fingerprint'] !== $currentFingerprint) {
            // Possible session hijacking detected
            self::logSecurityEvent(
                $_SESSION['user_id'] ?? 'unknown',
                'SESSION_HIJACK_ATTEMPT',
                'Session fingerprint mismatch. IP: ' . self::getClientIP()
            );
            self::destroySession();
            header('Location: ' . BASE_PATH . '/login?security=1');
            exit;
        }
    }

    /**
     * Generate session fingerprint based on IP and User Agent
     */
    private static function generateSessionFingerprint() {
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $ip = self::getClientIP();
        
        // Use IP subnet (first 3 octets) to allow for dynamic IPs from same provider
        $ipParts = explode('.', $ip);
        $ipSubnet = implode('.', array_slice($ipParts, 0, 3));
        
        return hash('sha256', $ipSubnet . $userAgent);
    }

    /**
     * Regenerate session ID
     */
    public static function regenerateSession() {
        session_regenerate_id(true);
        $_SESSION['last_regeneration'] = time();
    }

    /**
     * Destroy session completely
     */
    public static function destroySession() {
        $_SESSION = [];
        
        // Delete session cookie
        if (isset($_COOKIE[session_name()])) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }
        
        session_destroy();
    }
}
?>