-- Add Google OAuth support to users table
ALTER TABLE users 
ADD COLUMN google_id VARCHAR(100) NULL AFTER phone,
ADD COLUMN profile_photo VARCHAR(255) NULL AFTER google_id;

-- Add index for faster Google ID lookups
ALTER TABLE users ADD INDEX idx_google_id (google_id);