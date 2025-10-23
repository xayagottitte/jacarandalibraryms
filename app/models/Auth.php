<?php
class Auth extends Model {
    protected $table = 'users';

    public function login($email, $password) {
        // Check if account is locked due to too many failed attempts
        $lockStatus = Security::isAccountLocked($email);
        
        if ($lockStatus['locked']) {
            $remainingTime = Security::formatLockoutTime($lockStatus['remaining_time']);
            throw new Exception("Account temporarily locked due to too many failed login attempts. Please try again in {$remainingTime}.");
        }
        
        $user = (new User())->findByEmail($email);
        
        // TIMING ATTACK PREVENTION:
        // Always perform password verification even if user doesn't exist
        // This prevents attackers from determining valid emails based on response time
        if (!$user) {
            // Use a dummy hash to ensure same computation time
            $dummyHash = '$2y$10$abcdefghijklmnopqrstuv1234567890123456789012345678';
            password_verify($password, $dummyHash);
            
            // Track failed attempt
            Security::trackLoginAttempt($email, false);
            Security::logAuth(
                null,
                'login_failed_credentials',
                "Failed login attempt for email: {$email}",
                'warning'
            );
            
            // Add small random delay (50-150ms) to further prevent timing analysis
            usleep(rand(50000, 150000));
            
            return false;
        }
        
        // Verify password
        $passwordValid = password_verify($password, $user['password']);
        
        if ($passwordValid) {
            // Check account status
            if ($user['status'] !== 'active') {
                // Track failed attempt (inactive account)
                Security::trackLoginAttempt($email, false);
                Security::logAuth(
                    $user['id'],
                    'login_failed_inactive',
                    "Login attempt with inactive account status: {$user['status']}",
                    'warning'
                );
                
                if ($user['status'] === 'pending') {
                    throw new Exception("Your account is pending approval. Please wait for an administrator to approve your registration.");
                } else {
                    throw new Exception("Your account has been deactivated. Please contact an administrator.");
                }
            }
            
            // For librarians, check if library is assigned
            if ($user['role'] === 'librarian' && empty($user['library_id'])) {
                // Track failed attempt (no library assigned)
                Security::trackLoginAttempt($email, false);
                Security::logAuth(
                    $user['id'],
                    'login_failed_no_library',
                    "Login attempt by librarian without library assignment",
                    'warning'
                );
                throw new Exception("Your account has been approved but no library has been assigned yet. Please contact an administrator.");
            }
            
            // Successful login - clear previous failed attempts
            Security::clearLoginAttempts($email);
            
            // Track successful login
            Security::trackLoginAttempt($email, true);
            
            // Log successful login
            Security::logAuth(
                $user['id'],
                'login_success',
                "User logged in successfully as {$user['role']}",
                'info'
            );
            
            // Regenerate session ID to prevent session fixation
            session_regenerate_id(true);
            
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['library_id'] = $user['library_id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['last_activity'] = time();
            
            return true;
        }
        
        // Invalid credentials (wrong password)
        // Track failed attempt
        Security::trackLoginAttempt($email, false);
        
        // Log failed login
        Security::logAuth(
            $user['id'],
            'login_failed_wrong_password',
            "Failed login attempt - incorrect password",
            'warning'
        );
        
        // Add small random delay to prevent timing analysis
        usleep(rand(50000, 150000));
        
        return false;
    }

    public function register($data) {
        // Hash password
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        
        // Set default role and status for librarians
        if (!isset($data['role'])) {
            $data['role'] = 'librarian';
            $data['status'] = 'pending';
        }
        
        $result = $this->create($data);
        
        // Log registration
        if ($result) {
            Security::logAuth(
                null,
                'user_registered',
                "New user registered: {$data['email']} ({$data['role']})",
                'info'
            );
        }
        
        return $result;
    }

    public function logout() {
        $userId = $_SESSION['user_id'] ?? null;
        $username = $_SESSION['username'] ?? 'unknown';
        
        // Log logout before destroying session
        if ($userId) {
            Security::logAuth(
                $userId,
                'logout',
                "User {$username} logged out",
                'info'
            );
        }
        
        Security::destroySession();
        Security::startSecureSession(); // Start fresh session for messages
        $_SESSION['success'] = "You have been logged out successfully.";
    }

    public function isAuthenticated() {
        return isset($_SESSION['user_id']);
    }
}
?>