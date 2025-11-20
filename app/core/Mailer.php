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
            $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Use SMTPS for port 465
            $this->mailer->Port       = SMTP_PORT;
            $this->mailer->Hostname   = gethostname() ?: 'localhost'; // Use actual hostname for EHLO
            
            // Advanced connection settings to fix connection issues
            $this->mailer->Timeout    = 60; // Increase timeout
            $this->mailer->SMTPKeepAlive = false;
            $this->mailer->SMTPAutoTLS = true; // Auto enable TLS if available
            
            // SSL/TLS options with specific crypto method for Gmail
            $this->mailer->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                    'crypto_method' => STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT | STREAM_CRYPTO_METHOD_TLSv1_3_CLIENT
                )
            );
            
            // Disable debug output in production
            // $this->mailer->SMTPDebug = SMTP::DEBUG_SERVER;
            // $this->mailer->Debugoutput = function($str, $level) {
            //     error_log("SMTP Debug [$level]: $str");
            // };
            
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
    
    public function sendLibraryDeletionPin($toEmail, $toName, $libraryName, $pin) {
        try {
            // Recipients
            $this->mailer->addAddress($toEmail, $toName);
            
            // Content
            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Library Deletion Confirmation - ' . APP_NAME;
            
            $this->mailer->Body = $this->getLibraryDeletionPinTemplate($toName, $libraryName, $pin);
            $this->mailer->AltBody = $this->getLibraryDeletionPinTextTemplate($toName, $libraryName, $pin);
            
            $result = $this->mailer->send();
            
            // Clear addresses for next use
            $this->mailer->clearAddresses();
            
            return $result;
            
        } catch (Exception $e) {
            error_log("Library deletion PIN email send failed: {$this->mailer->ErrorInfo}");
            return false;
        }
    }
    
    private function getLibraryDeletionPinTemplate($name, $libraryName, $pin) {
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
                .pin-box { 
                    background: white; 
                    border: 2px solid #dc2626; 
                    padding: 20px; 
                    text-align: center; 
                    margin: 20px 0;
                    border-radius: 5px;
                }
                .pin { 
                    font-size: 32px; 
                    font-weight: bold; 
                    letter-spacing: 8px; 
                    color: #dc2626;
                    font-family: 'Courier New', monospace;
                }
                .warning { 
                    background: #fef3c7; 
                    border-left: 4px solid #f59e0b; 
                    padding: 15px; 
                    margin: 20px 0;
                }
                .footer { padding: 20px; text-align: center; font-size: 12px; color: #666; }
                .alert-icon { font-size: 48px; text-align: center; margin: 20px 0; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>" . APP_NAME . "</h1>
                    <p>⚠️ Critical Action Confirmation</p>
                </div>
                <div class='content'>
                    <div class='alert-icon'>🔒</div>
                    
                    <p>Hello " . htmlspecialchars($name) . ",</p>
                    
                    <p><strong>You have requested to delete the following library:</strong></p>
                    
                    <p style='font-size: 18px; text-align: center; background: white; padding: 15px; border-left: 4px solid #dc2626;'>
                        " . htmlspecialchars($libraryName) . "
                    </p>
                    
                    <p>To complete this critical action, please enter the confirmation PIN below:</p>
                    
                    <div class='pin-box'>
                        <p style='margin: 0; font-size: 14px; color: #666;'>Your Confirmation PIN</p>
                        <div class='pin'>" . htmlspecialchars($pin) . "</div>
                    </div>
                    
                    <div class='warning'>
                        <strong>⏰ Important:</strong>
                        <ul style='margin: 10px 0 0 0;'>
                            <li>This PIN expires in <strong>10 minutes</strong></li>
                            <li>Enter the PIN on the library deletion confirmation page</li>
                            <li>Do not share this PIN with anyone</li>
                            <li>This action cannot be undone</li>
                        </ul>
                    </div>
                    
                    <p><strong>What will be deleted:</strong></p>
                    <ul>
                        <li>Library information and settings</li>
                        <li>All associated books and records</li>
                        <li>Borrowing history</li>
                        <li>All related data (this action is permanent)</li>
                    </ul>
                    
                    <p style='color: #dc2626; font-weight: bold;'>⚠️ If you did not request this action, contact the system administrator immediately.</p>
                </div>
                <div class='footer'>
                    <p>This is an automated security message from " . APP_NAME . "</p>
                    <p>For your security, this PIN can only be used once.</p>
                </div>
            </div>
        </body>
        </html>";
    }
    
    private function getLibraryDeletionPinTextTemplate($name, $libraryName, $pin) {
        return "
========================================
" . APP_NAME . "
CRITICAL ACTION CONFIRMATION
========================================

Hello " . $name . ",

You have requested to delete the following library:
" . $libraryName . "

Your Confirmation PIN: " . $pin . "

IMPORTANT INFORMATION:
- This PIN expires in 10 minutes
- Enter the PIN on the library deletion confirmation page
- Do not share this PIN with anyone
- This action cannot be undone

WHAT WILL BE DELETED:
- Library information and settings
- All associated books and records
- Borrowing history
- All related data (this action is permanent)

⚠️ WARNING: If you did not request this action, contact the system administrator immediately.

For your security, this PIN can only be used once.

---
" . APP_NAME . "
        ";
    }
    
    public function sendUserDeletionPin($toEmail, $toName, $userEmail, $userRole, $pin) {
        try {
            // Recipients
            $this->mailer->addAddress($toEmail, $toName);
            
            // Content
            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'User Deletion Confirmation - ' . APP_NAME;
            
            $this->mailer->Body = $this->getUserDeletionPinTemplate($toName, $userEmail, $userRole, $pin);
            $this->mailer->AltBody = $this->getUserDeletionPinTextTemplate($toName, $userEmail, $userRole, $pin);
            
            $result = $this->mailer->send();
            
            // Clear addresses for next use
            $this->mailer->clearAddresses();
            
            return $result;
            
        } catch (Exception $e) {
            error_log("User deletion PIN email send failed: {$this->mailer->ErrorInfo}");
            return false;
        }
    }
    
    private function getUserDeletionPinTemplate($name, $userEmail, $userRole, $pin) {
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
                .pin-box { 
                    background: white; 
                    border: 2px solid #dc2626; 
                    padding: 20px; 
                    text-align: center; 
                    margin: 20px 0;
                    border-radius: 5px;
                }
                .pin { 
                    font-size: 32px; 
                    font-weight: bold; 
                    letter-spacing: 8px; 
                    color: #dc2626;
                    font-family: 'Courier New', monospace;
                }
                .warning { 
                    background: #fef3c7; 
                    border-left: 4px solid #f59e0b; 
                    padding: 15px; 
                    margin: 20px 0;
                }
                .danger { 
                    background: #fee2e2; 
                    border-left: 4px solid #dc2626; 
                    padding: 15px; 
                    margin: 20px 0;
                }
                .footer { padding: 20px; text-align: center; font-size: 12px; color: #666; }
                .alert-icon { font-size: 48px; text-align: center; margin: 20px 0; }
                .user-info { 
                    background: white; 
                    padding: 15px; 
                    border-left: 4px solid #dc2626;
                    margin: 15px 0;
                }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>" . APP_NAME . "</h1>
                    <p>⚠️ Critical Action Confirmation</p>
                </div>
                <div class='content'>
                    <div class='alert-icon'>🔒</div>
                    
                    <p>Hello " . htmlspecialchars($name) . ",</p>
                    
                    <p><strong>You have requested to delete the following user account:</strong></p>
                    
                    <div class='user-info'>
                        <p style='margin: 5px 0;'><strong>Email:</strong> " . htmlspecialchars($userEmail) . "</p>
                        <p style='margin: 5px 0;'><strong>Role:</strong> " . htmlspecialchars(ucfirst($userRole)) . "</p>
                    </div>
                    
                    <p>To complete this critical action, please enter the confirmation PIN below:</p>
                    
                    <div class='pin-box'>
                        <p style='margin: 0; font-size: 14px; color: #666;'>Your Confirmation PIN</p>
                        <div class='pin'>" . htmlspecialchars($pin) . "</div>
                    </div>
                    
                    <div class='warning'>
                        <strong>⏰ Important:</strong>
                        <ul style='margin: 10px 0 0 0;'>
                            <li>This PIN expires in <strong>10 minutes</strong></li>
                            <li>Enter the PIN on the user deletion confirmation page</li>
                            <li>Do not share this PIN with anyone</li>
                            <li>This action cannot be undone</li>
                        </ul>
                    </div>
                    
                    <div class='danger'>
                        <strong>⚠️ WARNING - Records Will Lose User Association:</strong>
                        <p style='margin: 10px 0 0 0;'>Deleting this user will affect the following records in the system:</p>
                        <ul style='margin: 10px 0 0 0;'>
                            <li><strong>Books:</strong> Records created by this user will lose creator information</li>
                            <li><strong>Students:</strong> Records created by this user will lose creator information</li>
                            <li><strong>Categories:</strong> Records created by this user will lose creator information</li>
                            <li><strong>Libraries:</strong> Records created by this user will lose creator information</li>
                            <li><strong>Borrowing Records:</strong> All borrow transactions (issued, returned, marked lost) associated with this user will lose user information</li>
                            <li><strong>Backup Logs:</strong> Backup records created by this user will lose creator information</li>
                        </ul>
                        <p style='margin: 10px 0 0 0; font-weight: bold; color: #dc2626;'>⚠️ The records themselves will NOT be deleted, but they will no longer show who created, issued, or processed them.</p>
                    </div>
                    
                    <p><strong>What will be deleted:</strong></p>
                    <ul>
                        <li>User account and login credentials</li>
                        <li>User profile information</li>
                        <li>User preferences and settings</li>
                        <li>Activity log entries for this user</li>
                    </ul>
                    
                    <p style='color: #dc2626; font-weight: bold;'>⚠️ If you did not request this action, contact the system administrator immediately.</p>
                </div>
                <div class='footer'>
                    <p>This is an automated security message from " . APP_NAME . "</p>
                    <p>For your security, this PIN can only be used once.</p>
                </div>
            </div>
        </body>
        </html>";
    }
    
    private function getUserDeletionPinTextTemplate($name, $userEmail, $userRole, $pin) {
        return "
========================================
" . APP_NAME . "
CRITICAL ACTION CONFIRMATION
========================================

Hello " . $name . ",

You have requested to delete the following user account:

Email: " . $userEmail . "
Role: " . ucfirst($userRole) . "

Your Confirmation PIN: " . $pin . "

IMPORTANT INFORMATION:
- This PIN expires in 10 minutes
- Enter the PIN on the user deletion confirmation page
- Do not share this PIN with anyone
- This action cannot be undone

⚠️ WARNING - RECORDS WILL LOSE USER ASSOCIATION:

Deleting this user will affect the following records:
- Books: Records created by this user will lose creator information
- Students: Records created by this user will lose creator information
- Categories: Records created by this user will lose creator information
- Libraries: Records created by this user will lose creator information
- Borrowing Records: All borrow transactions will lose user information
- Backup Logs: Backup records will lose creator information

⚠️ The records themselves will NOT be deleted, but they will no longer 
show who created, issued, or processed them.

WHAT WILL BE DELETED:
- User account and login credentials
- User profile information
- User preferences and settings
- Activity log entries for this user

⚠️ WARNING: If you did not request this action, contact the system 
administrator immediately.

For your security, this PIN can only be used once.

---
" . APP_NAME . "
        ";
    }
}
?>
