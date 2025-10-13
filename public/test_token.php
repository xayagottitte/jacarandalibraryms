<?php
// Test token verification
if (!isset($_GET['token'])) {
    die("No token provided. Usage: /test_token.php?token=YOUR_TOKEN");
}

require_once '../config/config.php';
require_once '../app/models/Database.php';
require_once '../app/models/User.php';

$token = $_GET['token'];

echo "<h2>Token Verification Test</h2>";
echo "<p><strong>Token:</strong> " . htmlspecialchars(substr($token, 0, 20)) . "...</p>";

try {
    $userModel = new User();
    $tokenData = $userModel->findByPasswordResetToken($token);
    
    if ($tokenData) {
        echo "<div style='color: green;'>";
        echo "<h3>✓ Token is VALID</h3>";
        echo "<p><strong>Email:</strong> " . htmlspecialchars($tokenData['email']) . "</p>";
        echo "<p><strong>User ID:</strong> " . $tokenData['user_id'] . "</p>";
        echo "<p><strong>Expires At:</strong> " . $tokenData['expires_at'] . "</p>";
        echo "<p><strong>Created At:</strong> " . $tokenData['created_at'] . "</p>";
        echo "</div>";
        
        echo "<br><a href='/reset-password?token=" . urlencode($token) . "' style='background:#663399;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;'>Go to Reset Password Page</a>";
        
    } else {
        echo "<div style='color: red;'>";
        echo "<h3>✗ Token is INVALID or EXPIRED</h3>";
        echo "<p>This token was not found in the database or has expired.</p>";
        echo "</div>";
    }
    
} catch (Exception $e) {
    echo "<div style='color: red;'>";
    echo "<h3>✗ Error</h3>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "</div>";
}

echo "<br><br><a href='/debug_tokens.php'>View All Tokens</a>";
echo "<br><a href='/forgot-password'>Request New Reset</a>";
echo "<br><a href='/'>← Back to Home</a>";
?>