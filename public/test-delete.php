<?php
// Simple delete test - place this in public/test-delete.php
session_start();

// Check if we're logged in
if (!isset($_SESSION['user_id'])) {
    die('Not logged in');
}

// Load necessary files
require_once '../config/config.php';
require_once '../app/models/Database.php';
require_once '../app/core/Model.php';
require_once '../app/models/Book.php';

// Check if we have a book ID
$bookId = $_GET['id'] ?? null;
if (!$bookId) {
    die('No book ID provided. Usage: test-delete.php?id=123');
}

$bookModel = new Book();
$libraryId = $_SESSION['library_id'];

// Get book info
$book = $bookModel->find($bookId);
if (!$book) {
    die("Book with ID $bookId not found");
}

echo "<h3>Book Information:</h3>";
echo "<pre>" . print_r($book, true) . "</pre>";

// Check active borrows
$borrowCheck = $bookModel->checkActiveBorrows($bookId);
echo "<h3>Active Borrows Check:</h3>";
echo "<pre>" . print_r($borrowCheck, true) . "</pre>";

// If no active borrows, offer to delete
if ($borrowCheck['count'] == 0) {
    if (isset($_GET['confirm']) && $_GET['confirm'] == 'yes') {
        $deleteResult = $bookModel->deleteBook($bookId, $libraryId);
        echo "<h3>Delete Result:</h3>";
        echo $deleteResult ? "SUCCESS - Book deleted!" : "FAILED - Could not delete book";
        
        // Check if book still exists
        $bookAfter = $bookModel->find($bookId);
        echo "<h3>Book After Delete:</h3>";
        echo $bookAfter ? "Still exists!" : "Successfully removed from database";
    } else {
        echo "<h3>Ready to Delete:</h3>";
        echo "Book has no active borrows. <a href='?id=$bookId&confirm=yes'>Click here to delete</a>";
    }
} else {
    echo "<h3>Cannot Delete:</h3>";
    echo "Book has {$borrowCheck['count']} active borrows.";
}
?>