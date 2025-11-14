
<?php
class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $conn;

    public function __construct() {
        // Load configuration based on environment
        // Check if it's local development (localhost, 127.0.0.1, or local IP)
        $serverName = $_SERVER['SERVER_NAME'] ?? 'localhost';
        $isLocal = in_array($serverName, ['localhost', '127.0.0.1']) || 
                   preg_match('/^192\.168\.\d+\.\d+$/', $serverName) ||
                   preg_match('/^10\.\d+\.\d+\.\d+$/', $serverName) ||
                   preg_match('/^172\.(1[6-9]|2[0-9]|3[0-1])\.\d+\.\d+$/', $serverName);
        
        if ($isLocal) {
            // Development/Local environment
            $this->host = 'localhost';
            $this->db_name = 'multi_library_system';
            $this->username = 'root';
            $this->password = '';
        } else {
            // Production environment
            $this->host = getenv('DB_HOST');
            $this->db_name = getenv('DB_NAME');
            $this->username = getenv('DB_USER');
            $this->password = getenv('DB_PASS');
        }
    }

    public function connect() {
        $this->conn = null;
        
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4",
                $this->username, 
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch(PDOException $e) {
            error_log("Database Connection Error: " . $e->getMessage());
            // Check if local environment
            $serverName = $_SERVER['SERVER_NAME'] ?? 'localhost';
            $isLocal = in_array($serverName, ['localhost', '127.0.0.1']) || 
                       preg_match('/^192\.168\.\d+\.\d+$/', $serverName) ||
                       preg_match('/^10\.\d+\.\d+\.\d+$/', $serverName) ||
                       preg_match('/^172\.(1[6-9]|2[0-9]|3[0-1])\.\d+\.\d+$/', $serverName);
            
            // Don't display detailed errors in production
            if ($isLocal) {
                die("Connection Error: " . $e->getMessage());
            } else {
                die("Database connection failed. Please try again later.");
            }
        }
        
        return $this->conn;
    }

    // Method to check if database exists and create if not
    public function initializeDatabase() {
        try {
            // Connect without selecting database first
            $temp_conn = new PDO(
                "mysql:host=" . $this->host,
                $this->username, 
                $this->password
            );
            $temp_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Create database if it doesn't exist
            $sql = "CREATE DATABASE IF NOT EXISTS " . $this->db_name . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
            $temp_conn->exec($sql);
            
            return true;
        } catch(PDOException $e) {
            error_log("Database Initialization Error: " . $e->getMessage());
            return false;
        }
    }
}
?>