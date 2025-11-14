# Quick Installation Guide - Logo & Book Covers

## Step 1: Apply Database Changes

Run the following SQL to add the book cover functionality:

```sql
USE multi_library_system;

-- Add cover_image column to books table
ALTER TABLE books 
ADD COLUMN cover_image VARCHAR(255) NULL AFTER category;

-- Add index for better performance
CREATE INDEX idx_cover_image ON books(cover_image);
```

## Step 2: Verify Directory Structure

The following directories should exist (already created):
- `public/assets/img/books/` - For book cover uploads
- `public/assets/img/books/default-cover.svg` - Default cover image

## Step 3: Set Permissions (if needed)

If running on Linux/Mac, ensure the books directory is writable:

```bash
chmod 755 public/assets/img/books/
```

On Windows (XAMPP), the default permissions should work fine.

## Step 4: Test the Implementation

### Test Logo:
1. ✅ Open any page in the system
2. ✅ Check that the purple/white logo appears in the navbar
3. ✅ Hover over the logo to see the animation effect

### Test Book Cover Upload:
1. ✅ Navigate to "Add New Book" (Admin or Librarian)
2. ✅ Fill in required book details
3. ✅ Click "Choose File" under Book Cover Image
4. ✅ Select a valid image (JPEG, PNG, GIF, or WebP)
5. ✅ Verify preview appears
6. ✅ Submit the form
7. ✅ Go to Books listing
8. ✅ Verify book cover thumbnail appears in the first column

### Test Book Cover Update:
1. ✅ Navigate to Books listing
2. ✅ Click Edit on any book
3. ✅ Current cover should be displayed
4. ✅ Upload a new cover
5. ✅ Preview should update
6. ✅ Save and verify in listing

### Test Default Cover:
1. ✅ Create a book without uploading a cover
2. ✅ Verify default purple gradient cover appears in listing

## What's Been Updated

### Files Modified:
- ✅ `app/views/shared/navbar.php` - Logo integration
- ✅ `app/models/Book.php` - Cover image support
- ✅ `app/controllers/AdminController.php` - File upload handling
- ✅ `app/controllers/LibrarianController.php` - File upload handling
- ✅ `app/views/admin/create-book.php` - Cover upload UI
- ✅ `app/views/admin/edit-book.php` - Cover upload UI
- ✅ `app/views/admin/books.php` - Cover display in listing
- ✅ `app/views/librarian/create-book.php` - Cover upload UI
- ✅ `app/views/librarian/edit-book.php` - Cover upload UI
- ✅ `app/views/librarian/books.php` - Cover display in listing

### Files Created:
- ✅ `databasesql/add_book_covers.sql` - Migration script
- ✅ `public/assets/img/books/default-cover.svg` - Default book cover
- ✅ `LOGO_AND_BOOK_COVERS.md` - Full documentation

## Troubleshooting

**Logo not showing?**
- Check that `public/assets/img/logo.png` exists
- Clear browser cache (Ctrl+Shift+R or Cmd+Shift+R)

**Can't upload covers?**
- Verify `public/assets/img/books/` directory exists and is writable
- Check PHP upload settings in php.ini:
  - `upload_max_filesize = 2M`
  - `post_max_size = 8M`
  
**Covers not displaying?**
- Run the database migration first
- Check file permissions
- Clear browser cache

**File upload fails?**
- Ensure file is under 2MB
- Use only JPEG, PNG, GIF, or WebP formats
- Check that the books directory is writable

## PHP Settings Check

To verify your PHP upload settings, create a file `info.php` in the `public` folder:

```php
<?php
phpinfo();
```

Then visit: `http://localhost/info.php` and search for:
- `upload_max_filesize`
- `post_max_size`
- `max_file_uploads`

Make sure these values are appropriate (2M, 8M, 20 respectively).

## All Done! 🎉

The system now has:
- ✅ Beautiful purple/white logo in the navbar
- ✅ Book cover upload functionality
- ✅ Live preview before upload
- ✅ Cover thumbnails in book listings
- ✅ Default cover for books without images
- ✅ Secure file validation and handling

For detailed information, see `LOGO_AND_BOOK_COVERS.md`
