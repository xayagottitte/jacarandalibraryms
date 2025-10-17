-- Profile Enhancement SQL
-- Add these columns to the users table if they don't exist

-- First, let's check what columns exist (run DESCRIBE users; to see current structure)

-- Add profile-related columns one by one to avoid conflicts
ALTER TABLE users ADD COLUMN IF NOT EXISTS employee_id VARCHAR(50) DEFAULT NULL;
ALTER TABLE users ADD COLUMN IF NOT EXISTS date_of_birth DATE DEFAULT NULL;
ALTER TABLE users ADD COLUMN IF NOT EXISTS gender ENUM('male', 'female', 'other') DEFAULT NULL;
ALTER TABLE users ADD COLUMN IF NOT EXISTS address TEXT DEFAULT NULL;
ALTER TABLE users ADD COLUMN IF NOT EXISTS profile_photo VARCHAR(255) DEFAULT NULL;
ALTER TABLE users ADD COLUMN IF NOT EXISTS supervisor VARCHAR(255) DEFAULT NULL;

-- Update the updated_at column if it doesn't exist
-- ALTER TABLE users ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- Add indexes for better performance
CREATE INDEX IF NOT EXISTS idx_users_employee_id ON users(employee_id);
CREATE INDEX IF NOT EXISTS idx_users_full_name ON users(full_name);

-- Update any existing records to have a basic full_name if missing
UPDATE users SET full_name = username WHERE full_name IS NULL OR full_name = '';

-- Show the final structure
-- DESCRIBE users;