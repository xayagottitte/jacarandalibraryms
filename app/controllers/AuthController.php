<?php
class AuthController extends Controller {
    private $authModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->authModel = new Auth();
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            try {
                if ($this->authModel->login($email, $password)) {
                    $_SESSION['success'] = "Login successful!";
                    
                    // Redirect based on role
                    if ($_SESSION['role'] === 'super_admin') {
                        $this->redirect('/admin/dashboard');
                    } else {
                        $this->redirect('/librarian/dashboard');
                    }
                    return;
                } else {
                    $_SESSION['error'] = "Invalid email or password.";
                }
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
            }
        }
        
        $this->view('auth/login');
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'username' => $_POST['username'],
                'full_name' => $_POST['full_name'],
                'email' => $_POST['email'],
                'phone' => $_POST['phone'],
                'password' => $_POST['password'],
                'role' => 'librarian', // Default role for registrations
                'status' => 'pending'
            ];
            
            // Check if user already exists
            $userModel = new User();
            if ($userModel->findByEmail($data['email'])) {
                $_SESSION['error'] = "Email already registered.";
            } elseif ($userModel->findByUsername($data['username'])) {
                $_SESSION['error'] = "Username already taken.";
            } else {
                // Hash password
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                
                if ($this->authModel->register($data)) {
                    $_SESSION['success'] = "Registration successful! Please wait for admin approval.";
                    $this->redirect('/login');
                    return;
                } else {
                    $_SESSION['error'] = "Registration failed. Please try again.";
                }
            }
        }
        
        $this->view('auth/register');
    }

    public function logout() {
        $this->authModel->logout();
        $this->redirect('/login');
    }
}
?>