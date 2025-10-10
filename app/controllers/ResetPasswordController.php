<?php
class ResetPasswordController extends Controller {
    private $userModel;

    public function __construct() {
        session_start();
        $this->userModel = new User();
    }

    public function show() {
        $token = $_GET['token'] ?? '';
        
        // Validate token
        if (!$this->isValidToken($token)) {
            $_SESSION['error'] = "Invalid or expired reset token.";
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
            
            // Update password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $query = "UPDATE users SET password = :password WHERE email = :email";
            $stmt = $this->userModel->db->prepare($query);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':email', $email);
            
            if ($stmt->execute()) {
                // Delete used token
                $this->deleteToken($token);
                
                $_SESSION['success'] = "Password has been reset successfully. You can now login with your new password.";
                $this->redirect('/login');
            } else {
                $_SESSION['error'] = "Failed to reset password. Please try again.";
                $this->redirect("/reset-password?token=$token");
            }
        }
    }
    
    private function isValidToken($token) {
        $query = "SELECT * FROM password_resets WHERE token = :token AND expires_at > NOW()";
        $stmt = $this->userModel->db->prepare($query);
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }
    
    private function getEmailFromToken($token) {
        $query = "SELECT email FROM password_resets WHERE token = :token";
        $stmt = $this->userModel->db->prepare($query);
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['email'] : null;
    }
    
    private function deleteToken($token) {
        $query = "DELETE FROM password_resets WHERE token = :token";
        $stmt = $this->userModel->db->prepare($query);
        $stmt->bindParam(':token', $token);
        $stmt->execute();
    }
}
?>