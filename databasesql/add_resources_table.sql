-- Create resources table for teacher-uploaded materials
CREATE TABLE IF NOT EXISTS resources (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    resource_type ENUM('video', 'document', 'link', 'image', 'other') NOT NULL,
    resource_url TEXT NOT NULL,
    source VARCHAR(100) DEFAULT 'teacher_upload' COMMENT 'teacher_upload, youtube, external_api',
    subject VARCHAR(100),
    grade_level VARCHAR(50),
    tags JSON COMMENT 'Array of tags for filtering',
    thumbnail_url TEXT,
    duration VARCHAR(50) COMMENT 'For videos: e.g., "10:30"',
    views INT DEFAULT 0,
    uploaded_by INT NOT NULL,
    library_id INT,
    status ENUM('active', 'archived') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (uploaded_by) REFERENCES users(id),
    FOREIGN KEY (library_id) REFERENCES libraries(id),
    INDEX idx_resource_type (resource_type),
    INDEX idx_subject (subject),
    INDEX idx_status (status)
);

-- Create resource views/access tracking table
CREATE TABLE IF NOT EXISTS resource_views (
    id INT PRIMARY KEY AUTO_INCREMENT,
    resource_id INT NOT NULL,
    viewed_by INT NOT NULL,
    viewed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (resource_id) REFERENCES resources(id) ON DELETE CASCADE,
    FOREIGN KEY (viewed_by) REFERENCES users(id),
    INDEX idx_resource_views (resource_id, viewed_at)
);
