<?php
// Temporary migration runner script
$host = 'localhost';
$dbname = 'multi_library_system';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to database successfully!\n\n";
    
    // Step 1: Add category_id column
    echo "Step 1: Adding category_id column...\n";
    try {
        $pdo->exec("ALTER TABLE books ADD COLUMN category_id INT NULL AFTER publication_year");
        echo "✓ category_id column added\n\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column') !== false) {
            echo "✓ category_id column already exists\n\n";
        } else {
            throw $e;
        }
    }
    
    // Step 2: Migrate existing categories to categories table
    echo "Step 2: Migrating existing categories...\n";
    $stmt = $pdo->exec("
        INSERT INTO categories (name, library_id, created_by, created_at)
        SELECT DISTINCT 
            b.category,
            b.library_id,
            b.created_by,
            NOW()
        FROM books b
        WHERE b.category IS NOT NULL 
            AND b.category != ''
            AND NOT EXISTS (
                SELECT 1 FROM categories c 
                WHERE c.name = b.category 
                AND c.library_id = b.library_id
            )
    ");
    echo "✓ Migrated $stmt new categories\n\n";
    
    // Step 3: Link books to categories
    echo "Step 3: Linking books to categories...\n";
    $stmt = $pdo->exec("
        UPDATE books b
        INNER JOIN categories c ON c.name = b.category AND c.library_id = b.library_id
        SET b.category_id = c.id
        WHERE b.category IS NOT NULL AND b.category != ''
    ");
    echo "✓ Updated $stmt books\n\n";
    
    // Step 4: Verify before dropping
    echo "Step 4: Verification...\n";
    $result = $pdo->query("SELECT COUNT(*) FROM books WHERE category_id IS NOT NULL")->fetchColumn();
    echo "Books with categories: $result\n";
    $result = $pdo->query("SELECT COUNT(*) FROM books WHERE category_id IS NULL")->fetchColumn();
    echo "Books without categories: $result\n\n";
    
    // Step 5: Drop old category column
    echo "Step 5: Dropping old category column...\n";
    try {
        $pdo->exec("ALTER TABLE books DROP COLUMN category");
        echo "✓ Old category column dropped\n\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), "check that it exists") !== false) {
            echo "✓ Old category column already dropped\n\n";
        } else {
            throw $e;
        }
    }
    
    // Step 6: Add foreign key
    echo "Step 6: Adding foreign key constraint...\n";
    try {
        $pdo->exec("
            ALTER TABLE books 
            ADD CONSTRAINT fk_books_category 
            FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
        ");
        echo "✓ Foreign key constraint added\n\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate') !== false || strpos($e->getMessage(), 'already exists') !== false) {
            echo "✓ Foreign key constraint already exists\n\n";
        } else {
            throw $e;
        }
    }
    
    // Step 7: Add index
    echo "Step 7: Adding index...\n";
    try {
        $pdo->exec("CREATE INDEX idx_books_category_id ON books(category_id)");
        echo "✓ Index created\n\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate') !== false) {
            echo "✓ Index already exists\n\n";
        } else {
            throw $e;
        }
    }
    
    echo "\n========================================\n";
    echo "✓✓✓ MIGRATION COMPLETE! ✓✓✓\n";
    echo "========================================\n\n";
    
    // Show final category counts
    echo "Final Statistics:\n";
    $result = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
    echo "Total categories: $result\n";
    $result = $pdo->query("SELECT COUNT(*) FROM books WHERE category_id IS NOT NULL")->fetchColumn();
    echo "Books with categories: $result\n";
    
} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
?>
