<?php
session_start();
require_once '../app/models/Database.php';
require_once '../app/core/Model.php';
require_once '../app/models/Category.php';
require_once '../app/models/Book.php';

$categoryModel = new Category();
$bookModel = new Book();

echo "=== Admin Create Book Data ===\n\n";
$categories = $categoryModel->getAllCategories();
echo "Categories count: " . count($categories) . "\n";
if (count($categories) > 0) {
    echo "First 3 categories:\n";
    for ($i = 0; $i < min(3, count($categories)); $i++) {
        echo "  - ID: {$categories[$i]['id']}, Name: {$categories[$i]['name']}\n";
    }
}

echo "\n=== Admin Edit Book Data ===\n\n";
// Get first book
$db = (new Database())->connect();
$stmt = $db->query("SELECT * FROM books LIMIT 1");
$book = $stmt->fetch(PDO::FETCH_ASSOC);

if ($book) {
    echo "Test Book ID: {$book['id']}\n";
    echo "Book category_id: " . ($book['category_id'] ?? 'NULL') . "\n";
    
    $categories = $categoryModel->getAllCategories();
    echo "Categories count: " . count($categories) . "\n";
    
    $bookWithLibrary = $bookModel->getBookWithLibrary($book['id']);
    echo "\nBook data from getBookWithLibrary:\n";
    echo "  - title: {$bookWithLibrary['title']}\n";
    echo "  - category_id: " . ($bookWithLibrary['category_id'] ?? 'NULL') . "\n";
    echo "  - category_name: " . ($bookWithLibrary['category_name'] ?? 'NULL') . "\n";
}
?>
