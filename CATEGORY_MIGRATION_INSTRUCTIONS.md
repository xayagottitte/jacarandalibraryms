# Category Foreign Key Migration Instructions

## Problem
The books table was using `category VARCHAR(100)` which stored category names as strings, while there was also a separate `categories` table with proper IDs. This caused inconsistencies where:
- New categories added through forms went into the `categories` table
- But books stored category names as strings in the `category` column
- Analytics and filtering didn't work properly between the two systems

## Solution
Migrate to using `category_id INT` as a foreign key reference to the `categories` table.

## Steps to Apply

### 1. Backup Your Database First!
```sql
mysqldump -u root multi_library_system > backup_before_category_migration.sql
```

### 2. Run the Migration Script
Open MySQL command line or phpMyAdmin and run the following commands:

```sql
USE multi_library_system;

-- Step 1: Add temporary category_id column
ALTER TABLE books ADD COLUMN category_id INT NULL AFTER category;

-- Step 2: Create categories for all unique book categories that don't exist yet
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

-- Step 4: Verify the data before dropping the old column
SELECT 
    b.id, 
    b.title, 
    b.category AS old_category, 
    c.name AS new_category_name,
    b.category_id
FROM books b
LEFT JOIN categories c ON b.category_id = c.id
LIMIT 20;

-- Step 5: Drop the old category column
ALTER TABLE books DROP COLUMN category;

-- Step 6: Add foreign key constraint
ALTER TABLE books 
ADD CONSTRAINT fk_books_category 
FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL;

-- Step 7: Add index for better performance
CREATE INDEX idx_books_category_id ON books(category_id);
```

### 3. Verify the Migration
```sql
-- Check how many books have categories
SELECT COUNT(*) as books_with_category FROM books WHERE category_id IS NOT NULL;

-- Check category distribution
SELECT c.name, COUNT(b.id) as book_count 
FROM categories c 
LEFT JOIN books b ON b.category_id = c.id 
GROUP BY c.id, c.name;
```

## Files Modified

### Backend (PHP)
1. **app/models/Book.php**
   - `create()` - now uses `category_id` instead of `category`
   - `updateBook()` - now uses `category_id`
   - `getBooksByLibrary()` - now joins with categories table and returns `category_name`
   - `getCategoriesByLibrary()` - now properly queries categories table with book count

2. **app/controllers/AdminController.php**
   - `createBook()` - accepts `category_id` from POST
   - `editBook()` - accepts `category_id` from POST

3. **app/controllers/LibrarianController.php**
   - `createBook()` - accepts `category_id` from POST
   - `editBook()` - accepts `category_id` from POST

### Frontend (Views)
**You will need to update these view files to use a dropdown with category IDs instead of text input:**

1. `app/views/admin/create-book.php` - Change category input to:
   ```html
   <select name="category_id" class="form-select">
       <option value="">Select Category</option>
       <?php foreach ($categories as $cat): ?>
           <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
       <?php endforeach; ?>
   </select>
   ```

2. `app/views/admin/edit-book.php` - Same as above but with selected value

3. `app/views/librarian/create-book.php` - Update category dropdown to use IDs

4. `app/views/librarian/edit-book.php` - Update category dropdown to use IDs

5. `app/views/librarian/books.php` - Filter should use category_id

6. `app/views/admin/books.php` - Filter should use category_id

## Benefits After Migration

1. **Data Integrity**: Foreign key constraint ensures only valid categories are assigned
2. **Consistency**: Categories are managed in one place (`categories` table)
3. **Better Analytics**: Can properly join and count books by category
4. **Easier Management**: Add/edit/delete categories affects all books properly
5. **Performance**: Indexed foreign key improves query performance
