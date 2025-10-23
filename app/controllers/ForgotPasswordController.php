<?php
class ForgotPasswordController extends Controller {
    private $userModel;
    private $mailer;

    public function __construct() {
        $this->userModel = new User();
        $this->mailer = new Mailer();
    }

    public function show() {
        $this->view('auth/forgot-password');
    }

    public function sendResetLink() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/forgot-password');
            return;
        }
        
        if (!isset($_POST['csrf_token']) || !Security::verifyCSRFToken($_POST['csrf_token'])) {
            $_SESSION['error'] = "Invalid security token.";
            $this->redirect('/forgot-password');
            return;
        }
        
        $email = trim($_POST['email'] ?? '');
        
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "Please enter a valid email address.";
            $this->redirect('/forgot-password');
            return;
        }
        
        $user = $this->userModel->findByEmail($email);
        
        if ($user && $user['status'] === 'active') {
            $token = bin2hex(random_bytes(32));
            
            // Let the database handle expiration time to avoid timezone issues
            if ($this->userModel->storePasswordResetToken($email, $token)) {
                $userName = $user['full_name'] ?? $user['username'];
                $this->mailer->sendPasswordResetEmail($email, $userName, $token);
            }
        }
        
        $_SESSION['success'] = "If an account exists with that email, you will receive password reset instructions shortly.";
        $this->redirect('/forgot-password');
    }
}
