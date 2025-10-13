<?php
class ForgotPasswordController extends Controller {
    private $userModel;
    private $mailer;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->userModel = new User();
        $this->mailer = new Mailer();
    }

    public function show() {
        $this->view('auth/forgot-password');
    }

    public function sendResetLink() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            
            // Find user by email
            $user = $this->userModel->findByEmail($email);
            
            if ($user && $user['status'] === 'active') {
                // Generate reset token
                $token = bin2hex(random_bytes(32));
                $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
                
                // Store token in database using the proper method
                if ($this->userModel->storePasswordResetToken($email, $token, $expires)) {
                    // Send reset email
                    if ($this->mailer->sendPasswordResetEmail($email, $user['full_name'], $token)) {
                        $_SESSION['success'] = "Password reset instructions have been sent to your email address.";
                    } else {
                        // Fallback: show link if email fails
                        $resetLink = "http://" . $_SERVER['HTTP_HOST'] . BASE_PATH . "/reset-password?token=" . $token;
                        $_SESSION['success'] = "Email delivery failed, but here's your reset link: <br><br> 
                                               <strong>Reset Link:</strong> <a href='$resetLink'>$resetLink</a><br>
                                               <small>Please save this link to reset your password.</small>";
                    }
                } else {
                    $_SESSION['error'] = "Failed to generate reset token. Please try again.";
                }
            } else {
                $_SESSION['error'] = "No active account found with that email address.";
            }
            
            $this->redirect('/forgot-password');
        }
    }
}
?>