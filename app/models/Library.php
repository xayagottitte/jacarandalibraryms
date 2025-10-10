<?php
class Library extends Model {
    protected $table = 'libraries';

    public function __construct() {
        parent::__construct();
    }

    public function getAllWithStats() {
        $query = "SELECT l.*, 
                         COUNT(DISTINCT u.id) as total_librarians,
                         COUNT(DISTINCT b.id) as total_books,
                         COUNT(DISTINCT s.id) as total_students
                  FROM libraries l
                  LEFT JOIN users u ON l.id = u.library_id AND u.role = 'librarian' AND u.status = 'active'
                  LEFT JOIN books b ON l.id = b.library_id
                  LEFT JOIN students s ON l.id = s.library_id
                  GROUP BY l.id
                  ORDER BY l.created_at DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLibrariesByType($type) {
        $query = "SELECT * FROM libraries WHERE type = :type ORDER BY name";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':type', $type);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAvailableLibrarians() {
        $query = "SELECT u.* FROM users u 
                  WHERE u.role = 'librarian' 
                  AND u.status = 'active' 
                  AND u.library_id IS NULL
                  ORDER BY u.full_name";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function assignLibrarian($libraryId, $librarianId, $assignedBy) {
        // Update user's library assignment
        $query = "UPDATE users SET library_id = :library_id WHERE id = :librarian_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':library_id', $libraryId);
        $stmt->bindParam(':librarian_id', $librarianId);
        
        if ($stmt->execute()) {
            // Record assignment
            $assignmentQuery = "INSERT INTO library_assignments (user_id, library_id, assigned_by) 
                               VALUES (:user_id, :library_id, :assigned_by)";
            $assignmentStmt = $this->db->prepare($assignmentQuery);
            $assignmentStmt->bindParam(':user_id', $librarianId);
            $assignmentStmt->bindParam(':library_id', $libraryId);
            $assignmentStmt->bindParam(':assigned_by', $assignedBy);
            
            return $assignmentStmt->execute();
        }
        
        return false;
    }

    public function removeLibrarianAssignment($librarianId) {
        $query = "UPDATE users SET library_id = NULL WHERE id = :librarian_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':librarian_id', $librarianId);
        return $stmt->execute();
    }
}
?>