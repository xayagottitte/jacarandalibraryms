<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mailer {
    private $mailer;
    
    public function __construct() {
        $this->mailer = new PHPMailer(true);
        
        try {
            // Server settings
            $this->mailer->isSMTP();
            $this->mailer->Host       = SMTP_HOST;
            $this->mailer->SMTPAuth   = true;
            $this->mailer->Username   = SMTP_USERNAME;
            $this->mailer->Password   = SMTP_PASSWORD;
            $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mailer->Port       = SMTP_PORT;
            
            // Default sender
            $this->mailer->setFrom(SMTP_USERNAME, APP_NAME);
            
        } catch (Exception $e) {
            error_log("Mailer Error: {$this->mailer->ErrorInfo}");
        }
    }
    
    public function sendPasswordResetEmail($toEmail, $toName, $resetToken) {
        try {
            // Recipients
            $this->mailer->addAddress($toEmail, $toName);
            
            // Content
            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Password Reset Request - ' . APP_NAME;
            
            // Generate reset link
            $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
            $resetLink = $protocol . "://" . $host . BASE_PATH . "/reset-password?token=" . urlencode($resetToken);
            
            $this->mailer->Body = $this->getPasswordResetTemplate($toName, $resetLink);
            $this->mailer->AltBody = $this->getPasswordResetTextTemplate($toName, $resetLink);
            
            $result = $this->mailer->send();
            
            // Clear addresses for next use
            $this->mailer->clearAddresses();
            
            return $result;
            
        } catch (Exception $e) {
            error_log("Email send failed: {$this->mailer->ErrorInfo}");
            return false;
        }
    }
    
    private function getPasswordResetTemplate($name, $resetLink) {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='utf-8'>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #663399; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background: #f9f9f9; }
                .button { 
                    display: inline-block; 
                    padding: 12px 24px; 
                    background: #663399; 
                    color: white; 
                    text-decoration: none; 
                    border-radius: 5px; 
                    margin: 20px 0;
                }
                .footer { padding: 20px; text-align: center; font-size: 12px; color: #666; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>" . APP_NAME . "</h1>
                    <p>Password Reset Request</p>
                </div>
                <div class='content'>
                    <p>Hello " . htmlspecialchars($name) . ",</p>
                    
                    <p>We received a request to reset your password.</p>
                    
                    <p>Click the button below to reset your password:</p>
                    
                    <a href='" . htmlspecialchars($resetLink) . "' class='button'>Reset Password</a>
                    
                    <p>Or copy this link:</p>
                    <p style='word-break: break-all;'><a href='" . htmlspecialchars($resetLink) . "'>" . htmlspecialchars($resetLink) . "</a></p>
                    
                    <p><strong>This link expires in 1 hour.</strong></p>
                    
                    <p>If you didn't request this, ignore this email.</p>
                </div>
                <div class='footer'>
                    <p>This is an automated message from " . APP_NAME . "</p>
                </div>
            </div>
        </body>
        </html>";
    }
    
    private function getPasswordResetTextTemplate($name, $resetLink) {
        return "
Hello " . $name . ",

We received a request to reset your password.

To reset your password, visit:
" . $resetLink . "

This link expires in 1 hour.

If you didn't request this, ignore this email.

---
" . APP_NAME . "
        ";
    }
}
?>
