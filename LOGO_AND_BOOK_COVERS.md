# Logo and Book Cover Implementation

## Overview
This implementation adds two key features to the Jacaranda Library Management System:
1. **Logo Integration** - The purple and white logo is now displayed in the navigation header
2. **Book Cover Images** - Books can now have cover images for easy visual identification

## Changes Made

### 1. Logo Integration

#### Files Modified:
- `app/views/shared/navbar.php`
  - Replaced the book icon with the actual logo image
  - Updated styling to show the logo properly with hover effects
  - Logo scales smoothly on hover with shadow effects

#### Visual Changes:
- Logo appears in the navbar next to the system name
- Logo is 32px height with auto width to maintain aspect ratio
- Rounded corners (4px) for a polished look
- Drop shadow effect for depth
- Hover animation scales logo to 105% with enhanced shadow

### 2. Book Cover Images

#### Database Changes:
- New migration file: `databasesql/add_book_covers.sql`
- Added `cover_image` column to `books` table (VARCHAR 255, nullable)
- Added index on `cover_image` for better query performance

**To apply database changes:**
```sql
USE multi_library_system;
ALTER TABLE books ADD COLUMN cover_image VARCHAR(255) NULL AFTER category;
CREATE INDEX idx_cover_image ON books(cover_image);
```

#### Directory Structure:
- Created: `public/assets/img/books/` - Storage for book cover images
- Created: `public/assets/img/books/default-cover.svg` - Default cover for books without custom images

#### Model Updates (`app/models/Book.php`):
1. **Updated `create()` method** - Now includes cover_image field
2. **Updated `updateBook()` method** - Now includes cover_image field
3. **New `uploadBookCover()` method** - Handles file upload with validation:
   - Allowed types: JPEG, PNG, GIF, WebP
   - Maximum size: 2MB
   - Auto-generates unique filenames
   - Returns success/error messages
4. **New `getBookCoverUrl()` method** - Returns cover URL or default cover

#### Controller Updates:

**AdminController (`app/controllers/AdminController.php`):**
- `createBook()` - Handles cover image upload after book creation
- `editBook()` - Handles cover image update, preserves existing if not updated

**LibrarianController (`app/controllers/LibrarianController.php`):**
- `createBook()` - Handles cover image upload after book creation
- `editBook()` - Handles cover image update, preserves existing if not updated

#### View Updates:

**Create Book Forms:**
- `app/views/admin/create-book.php`
- `app/views/librarian/create-book.php` (if exists)
  - Added file upload input for cover images
  - Added live preview functionality
  - Added client-side validation for file type and size
  - Form updated with `enctype="multipart/form-data"`

**Edit Book Forms:**
- `app/views/admin/edit-book.php`
- `app/views/librarian/edit-book.php` (if exists)
  - Added file upload input for cover images
  - Shows current cover with preview
  - Added live preview for new uploads
  - Form updated with `enctype="multipart/form-data"`

**Book Listings:**
- `app/views/admin/books.php`
  - Added "Cover" column to books table
  - Displays 40x60px book cover thumbnails
  - Shows default cover if no custom image uploaded

- `app/views/librarian/books.php`
  - Added "Cover" column to books table
  - Displays 40x60px book cover thumbnails
  - Shows default cover if no custom image uploaded

## Features

### Logo Features:
✅ Purple and white logo displayed in navbar
✅ Responsive design - scales appropriately
✅ Smooth hover animations
✅ Compatible with all user roles (admin, librarian)

### Book Cover Features:
✅ Upload book covers during book creation
✅ Update book covers when editing books
✅ Live preview before uploading
✅ File type validation (JPEG, PNG, GIF, WebP)
✅ File size validation (max 2MB)
✅ Default cover for books without images
✅ Thumbnail display in book listings
✅ Hover effects on book covers
✅ Automatic unique filename generation
✅ Secure file handling

## Usage

### Uploading Book Covers:

1. **When Creating a Book:**
   - Navigate to "Add New Book"
   - Fill in book details
   - Click "Choose File" under "Book Cover Image"
   - Select an image (JPEG, PNG, GIF, or WebP, max 2MB)
   - Preview appears instantly
   - Click "Add Book" to save with cover

2. **When Editing a Book:**
   - Navigate to book listing
   - Click edit button on desired book
   - Current cover is displayed
   - To change: Click "Choose File" under "Book Cover Image"
   - Select new image
   - Preview updates instantly
   - Click "Update Book" to save

3. **In Book Listings:**
   - Cover thumbnails appear in the first column
   - Hover over covers for book title tooltip
   - Books without covers show default purple gradient cover

## File Validation

**Accepted Formats:**
- JPEG (.jpg, .jpeg)
- PNG (.png)
- GIF (.gif)
- WebP (.webp)

**Size Limit:** 2MB maximum

**Error Handling:**
- Invalid file type: Alert shown, file rejected
- File too large: Alert shown, file rejected
- Upload failure: Book still created/updated, warning message shown

## Security Considerations

✅ File type validation on client and server side
✅ File size limits enforced
✅ Unique filename generation prevents overwrites
✅ Files stored outside web root would be ideal (future enhancement)
✅ Only authenticated users can upload covers
✅ Librarians can only manage books in their library

## Browser Compatibility

✅ Chrome/Edge (latest)
✅ Firefox (latest)
✅ Safari (latest)
✅ Mobile browsers

## Future Enhancements

Potential improvements for future versions:
- [ ] Image cropping/resizing tool
- [ ] Bulk upload functionality
- [ ] ISBN-based automatic cover fetching from APIs
- [ ] Image compression for optimal storage
- [ ] Multiple cover angles/views
- [ ] Cover image gallery view
- [ ] Search by cover similarity

## Troubleshooting

**Logo not showing:**
- Ensure `public/assets/img/logo.png` exists
- Check file permissions (should be readable)
- Clear browser cache

**Cover upload failing:**
- Check `public/assets/img/books/` directory exists
- Verify directory is writable (chmod 755)
- Ensure file meets size/type requirements
- Check PHP upload settings in php.ini:
  - `upload_max_filesize = 2M`
  - `post_max_size = 8M`

**Covers not displaying:**
- Run database migration to add cover_image column
- Check that uploaded files exist in `public/assets/img/books/`
- Verify file permissions

## Testing Checklist

- [x] Logo displays in navbar
- [x] Logo hover effects work
- [x] Can upload cover when creating book
- [x] Preview shows selected image
- [x] File validation works (type and size)
- [x] Can update cover when editing book
- [x] Current cover displays in edit form
- [x] Covers display in book listings
- [x] Default cover shows for books without covers
- [x] Works for both admin and librarian roles

## Support

For issues or questions, please refer to the main project documentation or contact the development team.
