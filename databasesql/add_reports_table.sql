-- Add reports table for saving report configurations and metadata
USE multi_library_system;

CREATE TABLE IF NOT EXISTS reports (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    type ENUM('books', 'students', 'borrows', 'financial', 'performance') NOT NULL,
    generated_by INT NOT NULL,
    library_id INT NULL,
    date_range_start DATE NULL,
    date_range_end DATE NULL,
    filters JSON NULL,
    report_data JSON NULL,
    status ENUM('generating', 'completed', 'failed') DEFAULT 'completed',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (generated_by) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (library_id) REFERENCES libraries(id) ON DELETE SET NULL
);

-- Add index for better performance
CREATE INDEX idx_reports_generated_by ON reports(generated_by);
CREATE INDEX idx_reports_library_id ON reports(library_id);
CREATE INDEX idx_reports_created_at ON reports(created_at);