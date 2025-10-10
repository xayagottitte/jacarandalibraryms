<?php
class Auth extends Model {
    protected $table = 'users';

    public function login($email, $password) {
        $user = (new User())->findByEmail($email);
        
        if ($user && password_verify($password, $user['password'])) {
            if ($user['status'] === 'active') {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['library_id'] = $user['library_id'];
                $_SESSION['email'] = $user['email'];
                
                return true;
            } else {
                throw new Exception("Your account is pending approval.");
            }
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