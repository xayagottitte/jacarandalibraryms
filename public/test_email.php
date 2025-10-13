<?php
// Test email functionality
require_once '../config/config.php';
require_once '../app/core/Mailer.php';

echo "<h2>Email Test Script</h2>";

try {
    $mailer = new Mailer();
    echo "✓ Mailer class instantiated successfully<br>";
    
    // Test password reset email (replace with a real email address)
    $testEmail = "test@example.com"; // Change this to your email
    $testName = "Test User";
    $testToken = "test_token_12345";
    
    echo "Testing password reset email...<br>";
    
    if ($mailer->sendPasswordResetEmail($testEmail, $testName, $testToken)) {
        echo "✓ Password reset email sent successfully!<br>";
    } else {
        echo "✗ Failed to send password reset email<br>";
    }
    
    echo "<br><strong>Configuration:</strong><br>";
    echo "SMTP Host: " . SMTP_HOST . "<br>";
    echo "SMTP Port: " . SMTP_PORT . "<br>";
    echo "SMTP Username: " . SMTP_USERNAME . "<br>";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "<br>";
}

echo "<br><a href='/'>← Back to Home</a>";
?>