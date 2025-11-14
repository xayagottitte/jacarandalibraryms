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

    public function googleAuth() {
        require_once '../vendor/autoload.php';
        
        $client = new Google_Client();
        $client->setClientId(getenv('GOOGLE_CLIENT_ID') ?: 'your-google-client-id');
        $client->setClientSecret(getenv('GOOGLE_CLIENT_SECRET') ?: 'your-google-client-secret');
        $client->setRedirectUri(getenv('BASE_URL') ? getenv('BASE_URL') . '/google-callback' : 'http://localhost/jacarandalibraryms/google-callback');
        $client->addScope('email');
        $client->addScope('profile');
        
        $authUrl = $client->createAuthUrl();
        header('Location: ' . $authUrl);
        exit();
    }

    public function googleCallback() {
        require_once '../vendor/autoload.php';
        
        if (!isset($_GET['code'])) {
            $_SESSION['error'] = "Google authentication failed. No authorization code received.";
            $this->redirect('/login');
            return;
        }
        
        $client = new Google_Client();
        $client->setClientId(getenv('GOOGLE_CLIENT_ID') ?: 'your-google-client-id');
        $client->setClientSecret(getenv('GOOGLE_CLIENT_SECRET') ?: 'your-google-client-secret');
        $client->setRedirectUri(getenv('BASE_URL') ? getenv('BASE_URL') . '/google-callback' : 'http://localhost/jacarandalibraryms/google-callback');
        
        try {
            $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
            
            if (isset($token['error'])) {
                $_SESSION['error'] = "Google authentication failed: " . $token['error_description'];
                $this->redirect('/login');
                return;
            }
            
            $client->setAccessToken($token);
            
            // Get user info from Google
            $oauth2 = new Google_Service_Oauth2($client);
            $userInfo = $oauth2->userinfo->get();
            
            $email = $userInfo->email;
            $name = $userInfo->name;
            $googleId = $userInfo->id;
            $picture = $userInfo->picture;
            
            // Check if user exists
            $userModel = new User();
            $existingUser = $userModel->findByEmail($email);
            
            if ($existingUser) {
                // Update Google ID and profile photo if not set
                if (empty($existingUser['google_id'])) {
                    $this->updateUserGoogleInfo($existingUser['id'], $googleId, $picture);
                }
                
                // Log the user in
                $_SESSION['user_id'] = $existingUser['id'];
                $_SESSION['username'] = $existingUser['username'];
                $_SESSION['role'] = $existingUser['role'];
                $_SESSION['library_id'] = $existingUser['library_id'];
                $_SESSION['success'] = "Welcome back, " . $existingUser['full_name'] . "!";
                
                // Redirect based on role
                if ($existingUser['role'] === 'super_admin') {
                    $this->redirect('/admin/dashboard');
                } else {
                    $this->redirect('/librarian/dashboard');
                }
            } else {
                // Create new user account
                $username = $this->generateUniqueUsername($email, $name);
                
                $userData = [
                    'username' => $username,
                    'full_name' => $name,
                    'email' => $email,
                    'password' => password_hash(uniqid(), PASSWORD_DEFAULT), // Random password
                    'role' => 'librarian', // Default role
                    'status' => 'pending', // Requires admin approval
                    'google_id' => $googleId,
                    'profile_photo' => $this->downloadGoogleProfileImage($picture, $googleId)
                ];
                
                if ($this->authModel->register($userData)) {
                    $_SESSION['success'] = "Account created with Google! Please wait for admin approval before you can access the system.";
                } else {
                    $_SESSION['error'] = "Failed to create account. Please try again.";
                }
                
                $this->redirect('/login');
            }
            
        } catch (Exception $e) {
            $_SESSION['error'] = "Google authentication failed: " . $e->getMessage();
            $this->redirect('/login');
        }
    }

    private function updateUserGoogleInfo($userId, $googleId, $picture) {
        try {
            $db = new Database();
            $conn = $db->connect();
            
            $profilePhoto = $this->downloadGoogleProfileImage($picture, $googleId);
            
            $query = "UPDATE users SET google_id = ?, profile_photo = ?, updated_at = NOW() WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->execute([$googleId, $profilePhoto, $userId]);
        } catch (Exception $e) {
            // Silently fail - not critical
            error_log("Failed to update Google info: " . $e->getMessage());
        }
    }

    private function generateUniqueUsername($email, $name) {
        $userModel = new User();
        
        // Try email prefix first
        $baseUsername = strtolower(explode('@', $email)[0]);
        $baseUsername = preg_replace('/[^a-z0-9]/', '', $baseUsername);
        
        if (strlen($baseUsername) < 3) {
            // Use name if email prefix is too short
            $baseUsername = strtolower(preg_replace('/[^a-z0-9]/', '', $name));
        }
        
        $username = $baseUsername;
        $counter = 1;
        
        // Keep trying until we find a unique username
        while ($userModel->findByUsername($username)) {
            $username = $baseUsername . $counter;
            $counter++;
        }
        
        return $username;
    }

    private function downloadGoogleProfileImage($pictureUrl, $googleId) {
        if (empty($pictureUrl)) {
            return null;
        }
        
        try {
            // Create directory if it doesn't exist
            $uploadDir = '../public/assets/img/profiles/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            // Download image
            $imageContent = file_get_contents($pictureUrl);
            if ($imageContent === false) {
                return null;
            }
            
            // Save with unique filename
            $filename = 'google_' . $googleId . '_' . time() . '.jpg';
            $filePath = $uploadDir . $filename;
            
            if (file_put_contents($filePath, $imageContent)) {
                return '/assets/img/profiles/' . $filename;
            }
            
        } catch (Exception $e) {
            error_log("Failed to download Google profile image: " . $e->getMessage());
        }
        
        return null;
    }
}
?>