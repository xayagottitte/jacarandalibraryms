-- Add loan_period_days column to libraries table
-- This allows each library to have its own configurable loan period

ALTER TABLE libraries 
ADD COLUMN loan_period_days INT DEFAULT 5 
COMMENT 'Default loan period in days for this library';

-- Update the system-wide default setting from 14 to 5 days
UPDATE system_settings 
SET setting_value = '5' 
WHERE setting_key = 'loan_period_days';

-- Set existing libraries to use 5 days default
UPDATE libraries 
SET loan_period_days = 5 
WHERE loan_period_days IS NULL;
