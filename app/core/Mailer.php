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
    
    public function sendWelcomeEmail($toEmail, $toName, $role) {
        try {
            // Recipients
            $this->mailer->addAddress($toEmail, $toName);
            
            // Content
            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Welcome to ' . APP_NAME . ' - Account Approved';
            
            // Generate login link
            $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
            $loginLink = $protocol . "://" . $host . BASE_PATH . "/login";
            
            $this->mailer->Body = $this->getWelcomeEmailTemplate($toName, $role, $loginLink);
            $this->mailer->AltBody = $this->getWelcomeEmailTextTemplate($toName, $role, $loginLink);
            
            $result = $this->mailer->send();
            
            // Clear addresses for next use
            $this->mailer->clearAddresses();
            
            return $result;
            
        } catch (Exception $e) {
            error_log("Welcome email send failed: {$this->mailer->ErrorInfo}");
            return false;
        }
    }
    
    private function getWelcomeEmailTemplate($name, $role, $loginLink) {
        $roleDisplay = ucfirst($role);
        
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
                .success-icon { font-size: 48px; text-align: center; margin: 20px 0; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>" . APP_NAME . "</h1>
                    <p>Account Approved</p>
                </div>
                <div class='content'>
                    <div class='success-icon'>✓</div>
                    
                    <p>Hello " . htmlspecialchars($name) . ",</p>
                    
                    <p><strong>Great news! Your account has been approved.</strong></p>
                    
                    <p>You can now access the library system as a <strong>" . htmlspecialchars($roleDisplay) . "</strong>.</p>
                    
                    <p>Click the button below to log in:</p>
                    
                    <a href='" . htmlspecialchars($loginLink) . "' class='button'>Log In Now</a>
                    
                    <p>Or visit: <a href='" . htmlspecialchars($loginLink) . "'>" . htmlspecialchars($loginLink) . "</a></p>
                    
                    <p>If you have any questions, please contact the system administrator.</p>
                </div>
                <div class='footer'>
                    <p>This is an automated message from " . APP_NAME . "</p>
                </div>
            </div>
        </body>
        </html>";
    }
    
    private function getWelcomeEmailTextTemplate($name, $role, $loginLink) {
        $roleDisplay = ucfirst($role);
        
        return "
Hello " . $name . ",

Great news! Your account has been approved.

You can now access the library system as a " . $roleDisplay . ".

To log in, visit:
" . $loginLink . "

If you have any questions, please contact the system administrator.

---
" . APP_NAME . "
        ";
    }
    
    public function sendRejectionEmail($toEmail, $toName) {
        try {
            // Recipients
            $this->mailer->addAddress($toEmail, $toName);
            
            // Content
            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Account Registration Update - ' . APP_NAME;
            
            $this->mailer->Body = $this->getRejectionEmailTemplate($toName);
            $this->mailer->AltBody = $this->getRejectionEmailTextTemplate($toName);
            
            $result = $this->mailer->send();
            
            // Clear addresses for next use
            $this->mailer->clearAddresses();
            
            return $result;
            
        } catch (Exception $e) {
            error_log("Rejection email send failed: {$this->mailer->ErrorInfo}");
            return false;
        }
    }
    
    private function getRejectionEmailTemplate($name) {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='utf-8'>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #dc2626; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background: #f9f9f9; }
                .footer { padding: 20px; text-align: center; font-size: 12px; color: #666; }
                .info-icon { font-size: 48px; text-align: center; margin: 20px 0; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>" . APP_NAME . "</h1>
                    <p>Account Registration Update</p>
                </div>
                <div class='content'>
                    <div class='info-icon'>ℹ</div>
                    
                    <p>Hello " . htmlspecialchars($name) . ",</p>
                    
                    <p>We regret to inform you that your registration request for " . APP_NAME . " has not been approved at this time.</p>
                    
                    <p>This may be due to:</p>
                    <ul>
                        <li>Incomplete or incorrect information provided</li>
                        <li>Ineligibility based on current criteria</li>
                        <li>Other administrative reasons</li>
                    </ul>
                    
                    <p>If you believe this decision was made in error or would like more information, please contact the system administrator.</p>
                    
                    <p>Thank you for your interest in our library system.</p>
                </div>
                <div class='footer'>
                    <p>This is an automated message from " . APP_NAME . "</p>
                </div>
            </div>
        </body>
        </html>";
    }
    
    private function getRejectionEmailTextTemplate($name) {
        return "
Hello " . $name . ",

We regret to inform you that your registration request for " . APP_NAME . " has not been approved at this time.

This may be due to:
- Incomplete or incorrect information provided
- Ineligibility based on current criteria
- Other administrative reasons

If you believe this decision was made in error or would like more information, please contact the system administrator.

Thank you for your interest in our library system.

---
" . APP_NAME . "
        ";
    }
}
?>
