<?php
class Auth extends Model {
    protected $table = 'users';

    public function login($email, $password) {
        $user = (new User())->findByEmail($email);
        
        if ($user && password_verify($password, $user['password'])) {
            // Check account status
            if ($user['status'] !== 'active') {
                if ($user['status'] === 'pending') {
                    throw new Exception("Your account is pending approval. Please wait for an administrator to approve your registration.");
                } else {
                    throw new Exception("Your account has been deactivated. Please contact an administrator.");
                }
            }
            
            // For librarians, check if library is assigned
            if ($user['role'] === 'librarian' && empty($user['library_id'])) {
                throw new Exception("Your account has been approved but no library has been assigned yet. Please contact an administrator.");
            }
            
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['library_id'] = $user['library_id'];
            $_SESSION['email'] = $user['email'];
            
            return true;
        }
        
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
        
        return $this->create($data);
    }

    public function logout() {
        session_destroy();
        session_start(); // Start fresh session for messages
        $_SESSION['success'] = "You have been logged out successfully.";
    }

    public function isAuthenticated() {
        return isset($_SESSION['user_id']);
    }
}
?>