<?php
// Check current database structure
$host = 'localhost';
$dbname = 'multi_library_system';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== BOOKS TABLE STRUCTURE ===\n";
    $stmt = $pdo->query("DESCRIBE books");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo $row['Field'] . " - " . $row['Type'] . " - " . $row['Null'] . " - " . $row['Key'] . "\n";
    }
    
    echo "\n=== CATEGORIES TABLE ===\n";
    $stmt = $pdo->query("SELECT * FROM categories ORDER BY library_id, name");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Total categories: " . count($categories) . "\n\n";
    
    if (count($categories) > 0) {
        foreach ($categories as $cat) {
            echo "ID: {$cat['id']}, Name: {$cat['name']}, Library: {$cat['library_id']}\n";
        }
    }
    
    echo "\n=== BOOKS CATEGORY STATUS ===\n";
    $stmt = $pdo->query("SELECT COUNT(*) FROM books WHERE category_id IS NOT NULL");
    echo "Books with category_id: " . $stmt->fetchColumn() . "\n";
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM books WHERE category_id IS NULL");
    echo "Books without category_id: " . $stmt->fetchColumn() . "\n";
    
    echo "\n=== FOREIGN KEYS ===\n";
    $stmt = $pdo->query("
        SELECT 
            CONSTRAINT_NAME,
            TABLE_NAME,
            COLUMN_NAME,
            REFERENCED_TABLE_NAME,
            REFERENCED_COLUMN_NAME
        FROM information_schema.KEY_COLUMN_USAGE
        WHERE TABLE_SCHEMA = '$dbname' 
        AND TABLE_NAME = 'books' 
        AND REFERENCED_TABLE_NAME IS NOT NULL
    ");
    $fks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($fks as $fk) {
        echo "{$fk['CONSTRAINT_NAME']}: {$fk['COLUMN_NAME']} -> {$fk['REFERENCED_TABLE_NAME']}.{$fk['REFERENCED_COLUMN_NAME']}\n";
    }
    
} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
?>
