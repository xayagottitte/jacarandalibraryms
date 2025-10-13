-- Create database
CREATE DATABASE IF NOT EXISTS multi_library_system;
USE multi_library_system;

-- Users table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('super_admin', 'librarian') NOT NULL,
    status ENUM('active', 'pending', 'inactive') DEFAULT 'pending',
    library_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Libraries table
CREATE TABLE libraries (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    type ENUM('primary', 'secondary') NOT NULL,
    address TEXT,
    loan_period_days INT DEFAULT 5 COMMENT 'Default loan period in days for this library',
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Add foreign key constraint after libraries table is created
ALTER TABLE users ADD FOREIGN KEY (library_id) REFERENCES libraries(id);

-- Insert default Super Admin
INSERT INTO users (username, email, password, role, status) 
VALUES ('superadmin', 'admin@library.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'super_admin', 'active');
-- Password: password

-- Add new columns to users table for better management
ALTER TABLE users 
ADD COLUMN full_name VARCHAR(100) AFTER username,
ADD COLUMN phone VARCHAR(20) AFTER email,
ADD COLUMN approved_by INT AFTER status,
ADD COLUMN approved_at TIMESTAMP NULL AFTER approved_by,
ADD FOREIGN KEY (approved_by) REFERENCES users(id);

-- Create library assignments table for better tracking
CREATE TABLE library_assignments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    library_id INT NOT NULL,
    assigned_by INT NOT NULL,
    assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (library_id) REFERENCES libraries(id),
    FOREIGN KEY (assigned_by) REFERENCES users(id)
);

-- Insert sample libraries for testing
INSERT INTO libraries (name, type, address, created_by) VALUES 
('Primary School Library', 'primary', '123 Primary School Street', 1),
('Secondary School Library', 'secondary', '456 High School Road', 1),
('Community Library', 'primary', '789 Community Center', 1);


-- Books table
CREATE TABLE books (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    isbn VARCHAR(20) UNIQUE,
    publisher VARCHAR(255),
    publication_year INT,
    category VARCHAR(100),
    class_level VARCHAR(10),
    total_copies INT DEFAULT 1,
    available_copies INT DEFAULT 1,
    library_id INT NOT NULL,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (library_id) REFERENCES libraries(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Students table
CREATE TABLE students (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id VARCHAR(50) UNIQUE NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    phone VARCHAR(20),
    class VARCHAR(20) NOT NULL,
    section VARCHAR(10),
    library_id INT NOT NULL,
    created_by INT NOT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (library_id) REFERENCES libraries(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Borrows table
CREATE TABLE borrows (
    id INT PRIMARY KEY AUTO_INCREMENT,
    book_id INT NOT NULL,
    student_id INT NOT NULL,
    borrowed_date DATE NOT NULL,
    due_date DATE NOT NULL,
    returned_date DATE NULL,
    status ENUM('borrowed', 'returned', 'overdue') DEFAULT 'borrowed',
    fine_amount DECIMAL(10,2) DEFAULT 0.00,
    paid_amount DECIMAL(10,2) DEFAULT 0.00,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (book_id) REFERENCES books(id),
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Categories table for book categorization
CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    library_id INT NOT NULL,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (library_id) REFERENCES libraries(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Insert sample categories
INSERT INTO categories (name, description, library_id, created_by) VALUES 
('Fiction', 'Fictional books and novels', 1, 1),
('Science', 'Scientific books and textbooks', 1, 1),
('Mathematics', 'Math related books', 1, 1),
('History', 'Historical books', 1, 1),
('Literature', 'Literary works', 1, 1);


-- System settings table
CREATE TABLE system_settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    description TEXT,
    updated_by INT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (updated_by) REFERENCES users(id)
);

-- Insert default system settings
INSERT INTO system_settings (setting_key, setting_value, description) VALUES
('loan_period_days', '5', 'Default loan period in days'),
('max_books_per_student', '5', 'Maximum books a student can borrow at once'),
('fine_per_day', '5', 'Fine amount per day for overdue books'),
('library_name', 'Multi-Library System', 'System-wide library name'),
('system_email', 'admin@librarysystem.com', 'System email address'),
('reservation_period', '7', 'Book reservation period in days');

-- Reports table for storing generated reports
CREATE TABLE reports (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    type ENUM('books', 'students', 'borrows', 'overdue', 'financial') NOT NULL,
    generated_by INT NOT NULL,
    library_id INT NULL,
    date_range_start DATE,
    date_range_end DATE,
    filters JSON,
    file_path VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (generated_by) REFERENCES users(id),
    FOREIGN KEY (library_id) REFERENCES libraries(id)
);

-- Activity logs table
CREATE TABLE activity_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    action VARCHAR(100) NOT NULL,
    description TEXT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Add indexes for better performance
CREATE INDEX idx_books_library_id ON books(library_id);
CREATE INDEX idx_students_library_id ON students(library_id);
CREATE INDEX idx_borrows_status ON borrows(status);
CREATE INDEX idx_borrows_due_date ON borrows(due_date);
CREATE INDEX idx_activity_logs_user_id ON activity_logs(user_id);
CREATE INDEX idx_activity_logs_created_at ON activity_logs(created_at);


-- Students table (if not already created)
CREATE TABLE IF NOT EXISTS students (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id VARCHAR(50) UNIQUE NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    phone VARCHAR(20),
    class VARCHAR(20) NOT NULL,
    section VARCHAR(10),
    library_id INT NOT NULL,
    created_by INT NOT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (library_id) REFERENCES libraries(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Borrows table (if not already created)
CREATE TABLE IF NOT EXISTS borrows (
    id INT PRIMARY KEY AUTO_INCREMENT,
    book_id INT NOT NULL,
    student_id INT NOT NULL,
    borrowed_date DATE NOT NULL,
    due_date DATE NOT NULL,
    returned_date DATE NULL,
    status ENUM('borrowed', 'returned', 'overdue') DEFAULT 'borrowed',
    fine_amount DECIMAL(10,2) DEFAULT 0.00,
    paid_amount DECIMAL(10,2) DEFAULT 0.00,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (book_id) REFERENCES books(id),
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Add sample data for testing
INSERT INTO students (student_id, full_name, email, phone, class, section, library_id, created_by) VALUES 
('STU2024001001', 'John Smith', 'john.smith@school.com', '123-456-7890', '5', 'A', 1, 1),
('STU2024001002', 'Sarah Johnson', 'sarah.j@school.com', '123-456-7891', '6', 'B', 1, 1),
('STU2024001003', 'Mike Wilson', 'mike.w@school.com', '123-456-7892', '7', 'A', 1, 1),
('STU2024002001', 'Emma Davis', 'emma.d@school.com', '123-456-7893', '2', 'A', 2, 1),
('STU2024002002', 'James Brown', 'james.b@school.com', '123-456-7894', '3', 'B', 2, 1);

CREATE TABLE password_resets (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) NOT NULL,
    token VARCHAR(255) NOT NULL,
    expires_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- User preferences table
CREATE TABLE user_preferences (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    preference_key VARCHAR(100) NOT NULL,
    preference_value TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_preference (user_id, preference_key)
);

-- Add class_level column to existing books table
ALTER TABLE books ADD COLUMN class_level VARCHAR(10) AFTER category;