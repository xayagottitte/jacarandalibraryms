<?php
// Make categories universal by consolidating duplicates and removing library_id constraint
$host = 'localhost';
$dbname = 'multi_library_system';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== MAKING CATEGORIES UNIVERSAL ===\n\n";
    
    // Step 1: Find and consolidate duplicate categories
    echo "Step 1: Finding duplicate categories...\n";
    $stmt = $pdo->query("
        SELECT LOWER(name) as normalized_name, MIN(id) as keep_id, GROUP_CONCAT(id) as all_ids, COUNT(*) as count
        FROM categories
        GROUP BY normalized_name
        HAVING count > 1
    ");
    $duplicates = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($duplicates as $dup) {
        $keepId = $dup['keep_id'];
        $allIds = explode(',', $dup['all_ids']);
        $deleteIds = array_filter($allIds, fn($id) => $id != $keepId);
        
        echo "  Consolidating '{$dup['normalized_name']}': keeping ID {$keepId}, merging " . implode(', ', $deleteIds) . "\n";
        
        // Update books to use the kept category
        foreach ($deleteIds as $deleteId) {
            $stmt = $pdo->prepare("UPDATE books SET category_id = ? WHERE category_id = ?");
            $stmt->execute([$keepId, $deleteId]);
        }
        
        // Delete duplicate categories
        $placeholders = implode(',', array_fill(0, count($deleteIds), '?'));
        $stmt = $pdo->prepare("DELETE FROM categories WHERE id IN ($placeholders)");
        $stmt->execute($deleteIds);
    }
    
    // Step 2: Make library_id nullable (already is, but ensure it)
    echo "\nStep 2: Ensuring library_id is nullable...\n";
    try {
        $pdo->exec("ALTER TABLE categories MODIFY library_id INT NULL");
        echo "✓ library_id is now nullable\n";
    } catch (PDOException $e) {
        echo "✓ library_id already nullable\n";
    }
    
    // Step 3: Set all library_id to NULL to make categories universal
    echo "\nStep 3: Making all categories universal (removing library associations)...\n";
    $stmt = $pdo->exec("UPDATE categories SET library_id = NULL");
    echo "✓ Updated $stmt categories to be universal\n";
    
    // Step 4: Show final state
    echo "\n=== FINAL STATE ===\n\n";
    $stmt = $pdo->query("SELECT id, name, library_id, COUNT(*) OVER() as total FROM categories ORDER BY name");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Total universal categories: " . ($categories[0]['total'] ?? 0) . "\n\n";
    foreach ($categories as $cat) {
        $libStatus = $cat['library_id'] === null ? 'Universal' : 'Library ' . $cat['library_id'];
        echo "ID: {$cat['id']}, Name: {$cat['name']}, Status: {$libStatus}\n";
    }
    
    // Show book assignments
    echo "\n=== BOOKS BY CATEGORY ===\n\n";
    $stmt = $pdo->query("
        SELECT c.name, COUNT(b.id) as book_count
        FROM categories c
        LEFT JOIN books b ON c.id = b.category_id
        GROUP BY c.id
        ORDER BY c.name
    ");
    $stats = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($stats as $stat) {
        echo "{$stat['name']}: {$stat['book_count']} books\n";
    }
    
    echo "\n========================================\n";
    echo "✓✓✓ CATEGORIES ARE NOW UNIVERSAL! ✓✓✓\n";
    echo "========================================\n";
    
} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
?>
