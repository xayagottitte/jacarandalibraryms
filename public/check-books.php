<?php
require_once 'config/config.php';
require_once 'app/models/Database.php';

try {
    $db = (new Database())->connect();
    $stmt = $db->query('DESCRIBE books');
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Books table structure:\n";
    foreach($columns as $column) {
        echo $column['Field'] . " - " . $column['Type'] . "\n";
    }
    
    echo "\nSample book data:\n";
    $stmt = $db->query('SELECT * FROM books LIMIT 1');
    $book = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($book) {
        print_r(array_keys($book));
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>