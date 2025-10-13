<?php
// Debug password reset tokens
require_once '../config/config.php';
require_once '../config/database.php';

echo "<h2>Password Reset Tokens Debug</h2>";

try {
    $database = new Database();
    $db = $database->getConnection();
    
    // Check if password_resets table exists
    $stmt = $db->query("SHOW TABLES LIKE 'password_resets'");
    if ($stmt->rowCount() > 0) {
        echo "✓ password_resets table exists<br><br>";
        
        // Show table structure
        echo "<strong>Table Structure:</strong><br>";
        $stmt = $db->query("DESCRIBE password_resets");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "- {$row['Field']}: {$row['Type']} ({$row['Null']}, {$row['Key']})<br>";
        }
        
        echo "<br><strong>Current Tokens:</strong><br>";
        $stmt = $db->query("SELECT id, email, LEFT(token, 10) as token_preview, expires_at, created_at, 
                           CASE WHEN expires_at > NOW() THEN 'Valid' ELSE 'Expired' END as status 
                           FROM password_resets ORDER BY created_at DESC LIMIT 10");
        
        if ($stmt->rowCount() > 0) {
            echo "<table border='1' cellpadding='5'>";
            echo "<tr><th>ID</th><th>Email</th><th>Token Preview</th><th>Expires At</th><th>Created At</th><th>Status</th></tr>";
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $statusColor = $row['status'] === 'Valid' ? 'green' : 'red';
                echo "<tr><td>{$row['id']}</td><td>{$row['email']}</td><td>{$row['token_preview']}...</td><td>{$row['expires_at']}</td><td>{$row['created_at']}</td><td style='color:$statusColor'>{$row['status']}</td></tr>";
            }
            echo "</table>";
        } else {
            echo "No password reset tokens found.";
        }
        
        echo "<br><br><strong>Current Server Time:</strong> " . date('Y-m-d H:i:s') . "<br>";
        echo "<strong>Database Time:</strong> ";
        $stmt = $db->query("SELECT NOW() as db_time");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo $result['db_time'] . "<br>";
        
    } else {
        echo "✗ password_resets table does not exist!<br>";
        echo "You may need to run the database schema to create it.";
    }
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "<br>";
}

echo "<br><a href='/'>← Back to Home</a>";
echo "<br><a href='/forgot-password'>Test Password Reset</a>";
?>