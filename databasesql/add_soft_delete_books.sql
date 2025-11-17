-- Add soft delete functionality to books table
ALTER TABLE books ADD COLUMN deleted_at TIMESTAMP NULL DEFAULT NULL AFTER updated_at;
ALTER TABLE books ADD COLUMN deleted_by INT(11) NULL DEFAULT NULL AFTER deleted_at;
ALTER TABLE books ADD CONSTRAINT fk_books_deleted_by FOREIGN KEY (deleted_by) REFERENCES users(id) ON DELETE SET NULL;

-- Create index for faster queries on non-deleted books
CREATE INDEX idx_deleted_at ON books(deleted_at);
