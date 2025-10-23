<?php
class AuthController extends Controller {
    private $authModel;

    public function __construct() {
        // Session is already started in index.php
        $this->authModel = new Auth();
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verify CSRF token
            if (!isset($_POST['csrf_token']) || !Security::verifyCSRFToken($_POST['csrf_token'])) {
                $_SESSION['error'] = "Invalid security token. Please try again.";
                $this->redirect('/login');
                return;
            }
            
            // Sanitize and validate inputs
            $email = Security::sanitizeInput($_POST['email'] ?? '');
            $password = $_POST['password'] ?? ''; // Don't sanitize password (may have special chars)
            
            // Validate email format
            if (!Security::validateEmail($email)) {
                $_SESSION['error'] = "Please enter a valid email address.";
                $this->redirect('/login');
                return;
            }
            
            // Validate password not empty
            if (empty($password)) {
                $_SESSION['error'] = "Password is required.";
                $this->redirect('/login');
                return;
            }
            
            // Pre-check if account is locked
            $lockStatus = Security::isAccountLocked($email);
            
            if ($lockStatus['locked']) {
                $remainingTime = Security::formatLockoutTime($lockStatus['remaining_time']);
                $_SESSION['error'] = "Account temporarily locked due to too many failed login attempts. Please try again in {$remainingTime}.";
                $_SESSION['lockout_info'] = [
                    'locked' => true,
                    'remaining_time' => $lockStatus['remaining_time'],
                    'attempts' => $lockStatus['attempts']
                ];
                $this->redirect('/login');
                return;
            }
            
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
                    // Get updated attempt count after failed login
                    $lockStatus = Security::isAccountLocked($email);
                    
                    if ($lockStatus['locked']) {
                        $remainingTime = Security::formatLockoutTime($lockStatus['remaining_time']);
                        $_SESSION['error'] = "Too many failed attempts. Account locked for {$remainingTime}.";
                        $_SESSION['lockout_info'] = [
                            'locked' => true,
                            'remaining_time' => $lockStatus['remaining_time'],
                            'attempts' => $lockStatus['attempts']
                        ];
                    } else {
                        $_SESSION['error'] = "Invalid email or password. {$lockStatus['remaining_attempts']} attempt(s) remaining.";
                        $_SESSION['lockout_info'] = [
                            'locked' => false,
                            'attempts' => $lockStatus['attempts'],
                            'remaining_attempts' => $lockStatus['remaining_attempts']
                        ];
                    }
                }
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
                
                // Check if account is now locked after exception
                $lockStatus = Security::isAccountLocked($email);
                $_SESSION['lockout_info'] = [
                    'locked' => $lockStatus['locked'],
                    'attempts' => $lockStatus['attempts'],
                    'remaining_attempts' => $lockStatus['remaining_attempts'] ?? 0,
                    'remaining_time' => $lockStatus['remaining_time'] ?? 0
                ];
            }
        }
        
        $this->view('auth/login');
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verify CSRF token
            if (!isset($_POST['csrf_token']) || !Security::verifyCSRFToken($_POST['csrf_token'])) {
                $_SESSION['error'] = "Invalid security token. Please try again.";
                $this->redirect('/register');
                return;
            }
            
            // Sanitize inputs
            $username = Security::sanitizeInput($_POST['username'] ?? '');
            $full_name = Security::sanitizeInput($_POST['full_name'] ?? '');
            $email = Security::sanitizeInput($_POST['email'] ?? '');
            $phone = Security::sanitizeInput($_POST['phone'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            
            // Validate username
            if (!Security::validateUsername($username)) {
                $_SESSION['error'] = "Username must be 3-50 characters (letters, numbers, underscore, hyphen only).";
                $this->redirect('/register');
                return;
            }
            
            // Validate full name
            if (empty($full_name) || strlen($full_name) < 3) {
                $_SESSION['error'] = "Full name must be at least 3 characters long.";
                $this->redirect('/register');
                return;
            }
            
            // Validate email
            if (!Security::validateEmail($email)) {
                $_SESSION['error'] = "Please enter a valid email address.";
                $this->redirect('/register');
                return;
            }
            
            // Validate phone
            if (!empty($phone) && !Security::validatePhone($phone)) {
                $_SESSION['error'] = "Please enter a valid phone number.";
                $this->redirect('/register');
                return;
            }
            
            // Validate password
            $passwordValidation = Security::validatePassword($password);
            if (!$passwordValidation['valid']) {
                $_SESSION['error'] = implode(' ', $passwordValidation['errors']);
                $this->redirect('/register');
                return;
            }
            
            // Confirm password match
            if ($password !== $confirm_password) {
                $_SESSION['error'] = "Passwords do not match.";
                $this->redirect('/register');
                return;
            }
            
            $data = [
                'username' => $username,
                'full_name' => $full_name,
                'email' => $email,
                'phone' => $phone,
                'password' => $password,
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