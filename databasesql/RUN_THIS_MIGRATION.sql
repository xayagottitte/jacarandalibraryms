-- Quick Category Migration Script
-- Run this in phpMyAdmin or MySQL command line

USE multi_library_system;

-- Step 1: Add category_id column
ALTER TABLE books ADD COLUMN category_id INT NULL AFTER publication_year;

-- Step 2: Migrate existing categories to categories table
-- This will create categories for any unique book categories that don't exist
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

-- Step 3: Link books to categories
UPDATE books b
INNER JOIN categories c ON c.name = b.category AND c.library_id = b.library_id
SET b.category_id = c.id
WHERE b.category IS NOT NULL AND b.category != '';

-- Step 4: Verify before dropping
SELECT 'Books with categories:', COUNT(*) FROM books WHERE category_id IS NOT NULL;
SELECT 'Books without categories:', COUNT(*) FROM books WHERE category_id IS NULL;

-- Step 5: Drop old category column
ALTER TABLE books DROP COLUMN category;

-- Step 6: Add foreign key
ALTER TABLE books 
ADD CONSTRAINT fk_books_category 
FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL;

-- Step 7: Add index
CREATE INDEX idx_books_category_id ON books(category_id);

SELECT 'Migration Complete!' as status;
