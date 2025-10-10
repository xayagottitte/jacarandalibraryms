<?php
class ForgotPasswordController extends Controller {
    private $userModel;

    public function __construct() {
        session_start();
        $this->userModel = new User();
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
                
                // Store token in database
                $query = "INSERT INTO password_resets (email, token, expires_at) VALUES (:email, :token, :expires) 
                         ON DUPLICATE KEY UPDATE token = :token, expires_at = :expires, created_at = NOW()";
                $stmt = $this->userModel->db->prepare($query);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':token', $token);
                $stmt->bindParam(':expires', $expires);
                
                if ($stmt->execute()) {
                    // Send reset email (in a real application)
                    $resetLink = "http://" . $_SERVER['HTTP_HOST'] . "/reset-password?token=" . $token;
                    
                    // For now, we'll just show the link (in production, send email)
                    $_SESSION['reset_token'] = $token; // Temporary for demonstration
                    $_SESSION['success'] = "Password reset link has been generated. <br><br> 
                                           <strong>Demo Link:</strong> <a href='$resetLink'>$resetLink</a><br>
                                           <small>In production, this would be sent via email.</small>";
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