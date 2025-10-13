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
            error_log("Mailer configuration error: {$this->mailer->ErrorInfo}");
        }
    }
    
    public function sendPasswordResetEmail($toEmail, $toName, $resetToken) {
        try {
            // Recipients
            $this->mailer->addAddress($toEmail, $toName);
            
            // Content
            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Password Reset Request - ' . APP_NAME;
            
            // Generate reset link based on current request
            $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'] ?? 'localhost:8000';
            $resetLink = $protocol . "://" . $host . "/reset-password?token=" . urlencode($resetToken);
            
            $this->mailer->Body = $this->getPasswordResetTemplate($toName, $resetLink);
            $this->mailer->AltBody = $this->getPasswordResetTextTemplate($toName, $resetLink);
            
            $result = $this->mailer->send();
            
            // Clear addresses for next use
            $this->mailer->clearAddresses();
            
            return $result;
            
        } catch (Exception $e) {
            error_log("Message could not be sent. Mailer Error: {$this->mailer->ErrorInfo}");
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
                    
                    <p>We received a request to reset your password for your " . APP_NAME . " account.</p>
                    
                    <p>Click the button below to reset your password:</p>
                    
                    <a href='" . htmlspecialchars($resetLink) . "' class='button'>Reset Password</a>
                    
                    <p>Or copy and paste this link into your browser:</p>
                    <p><a href='" . htmlspecialchars($resetLink) . "'>" . htmlspecialchars($resetLink) . "</a></p>
                    
                    <p><strong>This link will expire in 1 hour for security reasons.</strong></p>
                    
                    <p>If you didn't request this password reset, you can safely ignore this email. Your password will remain unchanged.</p>
                    
                    <p>Best regards,<br>The " . APP_NAME . " Team</p>
                </div>
                <div class='footer'>
                    <p>This is an automated message. Please do not reply to this email.</p>
                </div>
            </div>
        </body>
        </html>";
    }
    
    private function getPasswordResetTextTemplate($name, $resetLink) {
        return "
Hello " . $name . ",

We received a request to reset your password for your " . APP_NAME . " account.

To reset your password, visit the following link:
" . $resetLink . "

This link will expire in 1 hour for security reasons.

If you didn't request this password reset, you can safely ignore this email. Your password will remain unchanged.

Best regards,
The " . APP_NAME . " Team

---
This is an automated message. Please do not reply to this email.
        ";
    }
    
    public function sendWelcomeEmail($toEmail, $toName, $role) {
        try {
            // Recipients
            $this->mailer->addAddress($toEmail, $toName);
            
            // Content
            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Welcome to ' . APP_NAME;
            
            // Generate login link based on current request
            $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'] ?? 'localhost:8000';
            $loginLink = $protocol . "://" . $host . "/login";
            
            $this->mailer->Body = $this->getWelcomeTemplate($toName, $role, $loginLink);
            $this->mailer->AltBody = $this->getWelcomeTextTemplate($toName, $role, $loginLink);
            
            $result = $this->mailer->send();
            
            // Clear addresses for next use
            $this->mailer->clearAddresses();
            
            return $result;
            
        } catch (Exception $e) {
            error_log("Welcome email could not be sent. Mailer Error: {$this->mailer->ErrorInfo}");
            return false;
        }
    }
    
    private function getWelcomeTemplate($name, $role, $loginLink) {
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
                    <h1>Welcome to " . APP_NAME . "</h1>
                </div>
                <div class='content'>
                    <p>Hello " . htmlspecialchars($name) . ",</p>
                    
                    <p>Your account has been approved and you now have access to " . APP_NAME . " as a " . ucfirst(htmlspecialchars($role)) . ".</p>
                    
                    <p>Click the button below to log in and start using the system:</p>
                    
                    <a href='" . htmlspecialchars($loginLink) . "' class='button'>Login Now</a>
                    
                    <p>You can log in using the credentials you used when registering.</p>
                    
                    <p>If you have any questions or need assistance, please contact your administrator.</p>
                    
                    <p>Best regards,<br>The " . APP_NAME . " Team</p>
                </div>
                <div class='footer'>
                    <p>This is an automated message. Please do not reply to this email.</p>
                </div>
            </div>
        </body>
        </html>";
    }
    
    private function getWelcomeTextTemplate($name, $role, $loginLink) {
        return "
Hello " . $name . ",

Your account has been approved and you now have access to " . APP_NAME . " as a " . ucfirst($role) . ".

You can log in at: " . $loginLink . "

You can log in using the credentials you used when registering.

If you have any questions or need assistance, please contact your administrator.

Best regards,
The " . APP_NAME . " Team

---
This is an automated message. Please do not reply to this email.
        ";
    }
}

?>