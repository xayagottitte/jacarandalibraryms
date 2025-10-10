<?php
/**
 * Database Backup Script
 * Run this script to create a backup of the database
 */

class DatabaseBackup {
    private $db;
    private $backupPath;

    public function __construct() {
        $this->db = (new Database())->connect();
        $this->backupPath = '../backups/';
        
        if (!is_dir($this->backupPath)) {
            mkdir($this->backupPath, 0755, true);
        }
    }

    public function createBackup() {
        $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
        $filepath = $this->backupPath . $filename;
        
        $tables = $this->getTables();
        $sqlScript = "";
        
        foreach ($tables as $table) {
            $sqlScript .= $this->getTableSchema($table);
            $sqlScript .= $this->getTableData($table);
        }
        
        if (file_put_contents($filepath, $sqlScript)) {
            return ['success' => true, 'filename' => $filename, 'path' => $filepath];
        } else {
            return ['success' => false, 'error' => 'Failed to write backup file'];
        }
    }
    
    private function getTables() {
        $stmt = $this->db->query("SHOW TABLES");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    
    private function getTableSchema($table) {
        $stmt = $this->db->query("SHOW CREATE TABLE `$table`");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['Create Table'] . ";\n\n";
    }
    
    private function getTableData($table) {
        $stmt = $this->db->query("SELECT * FROM `$table`");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($rows)) {
            return "";
        }
        
        $sql = "INSERT INTO `$table` VALUES ";
        $values = [];
        
        foreach ($rows as $row) {
            $rowValues = array_map(function($value) {
                if ($value === null) return 'NULL';
                return "'" . addslashes($value) . "'";
            }, $row);
            
            $values[] = "(" . implode(", ", $rowValues) . ")";
        }
        
        $sql .= implode(", ", $values) . ";\n\n";
        return $sql;
    }
}

// Usage
if (php_sapi_name() === 'cli') {
    $backup = new DatabaseBackup();
    $result = $backup->createBackup();
    
    if ($result['success']) {
        echo "Backup created successfully: " . $result['filename'] . "\n";
    } else {
        echo "Backup failed: " . $result['error'] . "\n";
    }
}
?>