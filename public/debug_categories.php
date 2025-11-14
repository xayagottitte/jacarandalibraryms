<?php
// Debug script to check categories
session_start();
require_once '../app/models/Database.php';
require_once '../app/core/Model.php';
require_once '../app/models/Category.php';

$categoryModel = new Category();

echo "=== Testing Category Model ===\n\n";

echo "getAllCategories():\n";
$categories = $categoryModel->getAllCategories();
echo "Count: " . count($categories) . "\n";
foreach ($categories as $cat) {
    echo "  ID: {$cat['id']}, Name: {$cat['name']}, Library: " . ($cat['library_id'] ?? 'NULL') . "\n";
}

echo "\n\ngetCategoriesByLibrary(1):\n";
$categories = $categoryModel->getCategoriesByLibrary(1);
echo "Count: " . count($categories) . "\n";
foreach ($categories as $cat) {
    echo "  ID: {$cat['id']}, Name: {$cat['name']}, Library: " . ($cat['library_id'] ?? 'NULL') . "\n";
}
?>
