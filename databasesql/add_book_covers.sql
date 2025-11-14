-- Add book cover image functionality
-- This migration adds book cover image support to the books table

USE multi_library_system;

-- Add cover_image column to books table
ALTER TABLE books 
ADD COLUMN cover_image VARCHAR(255) NULL AFTER category;

-- Add index for cover_image column
CREATE INDEX idx_cover_image ON books(cover_image);

-- Create uploads directory structure comment (need to create manually)
-- public/assets/img/books/ - for book cover images

-- Example update for existing books (optional)
-- UPDATE books SET cover_image = 'default-book-cover.png' WHERE cover_image IS NULL;