-- Migration: Convert books.category from VARCHAR to category_id foreign key
-- This script will preserve existing category data by creating categories if they don't exist

-- Step 1: Add temporary category_id column
ALTER TABLE books ADD COLUMN category_id INT NULL AFTER category;

-- Step 2: Migrate existing category data
-- For each unique category in books, create it in categories table if it doesn't exist
-- Then update the category_id in books

-- Get all unique categories and create them if they don't exist
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
    );

-- Step 3: Update category_id in books based on category name
UPDATE books b
INNER JOIN categories c ON c.name = b.category AND c.library_id = b.library_id
SET b.category_id = c.id
WHERE b.category IS NOT NULL AND b.category != '';

-- Step 4: Drop the old category column
ALTER TABLE books DROP COLUMN category;

-- Step 5: Add foreign key constraint
ALTER TABLE books 
ADD CONSTRAINT fk_books_category 
FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL;

-- Step 6: Add index for better performance
CREATE INDEX idx_books_category_id ON books(category_id);

-- Verification queries (uncomment to check):
-- SELECT COUNT(*) as books_with_category FROM books WHERE category_id IS NOT NULL;
-- SELECT c.name, COUNT(b.id) as book_count FROM categories c LEFT JOIN books b ON b.category_id = c.id GROUP BY c.id, c.name;
