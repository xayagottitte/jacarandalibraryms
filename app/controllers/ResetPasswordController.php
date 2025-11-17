<?php
class ResetPasswordController extends Controller {
    private $userModel;

    public function __construct() {
        // Session is already started in index.php
        $this->userModel = new User();
    }

    public function show() {
        $token = $_GET['token'] ?? '';
        
        if (empty($token) || !$this->isValidToken($token)) {
            $_SESSION['error'] = "Invalid or expired reset token. Please request a new password reset.";
            $this->redirect('/forgot-password');
            return;
        }
        
        $this->view('auth/reset-password', ['token' => $token]);
    }

    public function reset() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verify CSRF token
            if (!isset($_POST['csrf_token']) || !Security::verifyCSRFToken($_POST['csrf_token'])) {
                $_SESSION['error'] = "Invalid security token. Please try again.";
                $this->redirect('/forgot-password');
                return;
            }
            
            // Sanitize inputs
            $token = Security::sanitizeInput($_POST['token'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            
            // Validate token
            if (!$this->isValidToken($token)) {
                $_SESSION['error'] = "Invalid or expired reset token.";
                $this->redirect('/forgot-password');
                return;
            }
            
            // Validate password
            $passwordValidation = Security::validatePassword($password);
            if (!$passwordValidation['valid']) {
                $_SESSION['error'] = implode(' ', $passwordValidation['errors']);
                $this->redirect("/reset-password?token=$token");
                return;
            }
            
            // Additional XSS protection for password field
            if (!Security::isPasswordSafeFromXSS($password)) {
                $_SESSION['error'] = "Password contains invalid characters.";
                Security::logSecurity(
                    null,
                    'password_reset_xss_attempt',
                    "Password reset attempt with XSS in password field for token: {$token}",
                    'critical'
                );
                $this->redirect("/reset-password?token=$token");
                return;
            }
            
            // Confirm password match
            if ($password !== $confirm_password) {
                $_SESSION['error'] = "Passwords do not match.";
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
                // Log password reset
                Security::logAuth(
                    $tokenData['user_id'],
                    'password_reset',
                    "User reset password via reset token",
                    'info'
                );
                
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