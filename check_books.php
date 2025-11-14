<?php
// Check books without categories
$host = 'localhost';
$dbname = 'multi_library_system';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== BOOKS WITHOUT CATEGORIES ===\n\n";
    $stmt = $pdo->query("
        SELECT b.id, b.title, b.library_id, l.name as library_name
        FROM books b
        LEFT JOIN libraries l ON b.library_id = l.id
        WHERE b.category_id IS NULL
        ORDER BY b.library_id, b.title
    ");
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($books as $book) {
        echo "Book ID: {$book['id']}, Title: {$book['title']}, Library: {$book['library_name']} (ID: {$book['library_id']})\n";
    }
    
    echo "\n=== BOOKS WITH CATEGORIES ===\n\n";
    $stmt = $pdo->query("
        SELECT b.id, b.title, b.category_id, c.name as category_name, l.name as library_name
        FROM books b
        LEFT JOIN categories c ON b.category_id = c.id
        LEFT JOIN libraries l ON b.library_id = l.id
        WHERE b.category_id IS NOT NULL
        ORDER BY b.library_id, b.title
    ");
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($books as $book) {
        echo "Book ID: {$book['id']}, Title: {$book['title']}, Category: {$book['category_name']}, Library: {$book['library_name']}\n";
    }
    
    echo "\n=== CATEGORIES BY LIBRARY ===\n\n";
    $stmt = $pdo->query("
        SELECT c.id, c.name, c.library_id, l.name as library_name, COUNT(b.id) as book_count
        FROM categories c
        LEFT JOIN libraries l ON c.library_id = l.id
        LEFT JOIN books b ON c.id = b.category_id
        GROUP BY c.id
        ORDER BY c.library_id, c.name
    ");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($categories as $cat) {
        echo "Category: {$cat['name']}, Library: {$cat['library_name']} (ID: {$cat['library_id']}), Books: {$cat['book_count']}\n";
    }
    
} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
?>
