<?php
class ResetPasswordController extends Controller {
    private $userModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->userModel = new User();
    }

    public function show() {
        $token = $_GET['token'] ?? '';
        
        // Debug: Log token validation attempt
        error_log("Reset password attempt with token: " . substr($token, 0, 10) . "...");
        
        // Validate token
        if (!$this->isValidToken($token)) {
            // Debug: Get more info about why validation failed
            $tokenData = $this->userModel->findByPasswordResetToken($token);
            if (!$tokenData) {
                error_log("Token validation failed: Token not found or expired");
                $_SESSION['error'] = "Invalid or expired reset token. Please request a new password reset.";
            } else {
                error_log("Token found but validation failed");
                $_SESSION['error'] = "Reset token has expired. Please request a new password reset.";
            }
            $this->redirect('/forgot-password');
            return;
        }
        
        $data = ['token' => $token];
        $this->view('auth/reset-password', $data);
    }

    public function reset() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['token'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            
            // Validate token
            if (!$this->isValidToken($token)) {
                $_SESSION['error'] = "Invalid or expired reset token.";
                $this->redirect('/forgot-password');
                return;
            }
            
            // Validate passwords
            if (empty($password) || empty($confirm_password)) {
                $_SESSION['error'] = "Please fill in all password fields.";
                $this->redirect("/reset-password?token=$token");
                return;
            }
            
            if ($password !== $confirm_password) {
                $_SESSION['error'] = "Passwords do not match.";
                $this->redirect("/reset-password?token=$token");
                return;
            }
            
            if (strlen($password) < 6) {
                $_SESSION['error'] = "Password must be at least 6 characters long.";
                $this->redirect("/reset-password?token=$token");
                return;
            }
            
            // Get email from token
            $email = $this->getEmailFromToken($token);
            if (!$email) {
                $_SESSION['error'] = "Invalid reset token.";
                $this->redirect('/forgot-password');
                return;
            }
            
            // Get user data from token
            $tokenData = $this->userModel->findByPasswordResetToken($token);
            if (!$tokenData) {
                $_SESSION['error'] = "Invalid or expired reset token.";
                $this->redirect('/forgot-password');
                return;
            }
            
            // Update password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            if ($this->userModel->updatePassword($tokenData['user_id'], $hashedPassword)) {
                // Delete used token
                $this->userModel->deletePasswordResetToken($token);
                
                $_SESSION['success'] = "Password has been reset successfully. You can now login with your new password.";
                $this->redirect('/login');
            } else {
                $_SESSION['error'] = "Failed to reset password. Please try again.";
                $this->redirect("/reset-password?token=$token");
            }
        }
    }
    
    private function isValidToken($token) {
        $tokenData = $this->userModel->findByPasswordResetToken($token);
        return $tokenData !== false;
    }
    
    private function getEmailFromToken($token) {
        $tokenData = $this->userModel->findByPasswordResetToken($token);
        return $tokenData ? $tokenData['email'] : null;
    }
}
?>