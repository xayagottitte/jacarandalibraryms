<?php
class Library extends Model {
    protected $table = 'libraries';

    public function __construct() {
        parent::__construct();
    }

    public function getAllWithStats($filters = []) {
     $query = "SELECT l.*, 
                COUNT(DISTINCT u.id) as total_librarians,
                COUNT(DISTINCT b.id) as total_books,
                (SELECT COALESCE(SUM(b2.total_copies), 0) FROM books b2 WHERE b2.library_id = l.id) as total_copies,
                (SELECT COALESCE(SUM(b2.available_copies), 0) FROM books b2 WHERE b2.library_id = l.id) as available_copies,
                COUNT(DISTINCT s.id) as total_students,
                (SELECT COUNT(DISTINCT br2.id) FROM borrows br2 
                 INNER JOIN books b2 ON br2.book_id = b2.id 
                 WHERE b2.library_id = l.id AND br2.status IN ('borrowed', 'overdue')) as active_borrows
            FROM libraries l
            LEFT JOIN users u ON l.id = u.library_id AND u.role = 'librarian' AND u.status = 'active'
            LEFT JOIN books b ON l.id = b.library_id
            LEFT JOIN students s ON l.id = s.library_id
            WHERE 1=1";
        
        $params = [];

        // Add filters
        if (!empty($filters['search'])) {
            $query .= " AND (l.name LIKE ? OR l.address LIKE ?)";
            $params[] = '%' . $filters['search'] . '%';
            $params[] = '%' . $filters['search'] . '%';
        }

        if (!empty($filters['type'])) {
            $query .= " AND l.type = ?";
            $params[] = $filters['type'];
        }

        $query .= " GROUP BY l.id";

        // Handle librarian status filter after grouping
        if (!empty($filters['librarian_status'])) {
            if ($filters['librarian_status'] === 'assigned') {
                $query .= " HAVING COUNT(DISTINCT u.id) > 0";
            } elseif ($filters['librarian_status'] === 'unassigned') {
                $query .= " HAVING COUNT(DISTINCT u.id) = 0";
            }
        }

        // Add sorting
        $sortBy = $filters['sort_by'] ?? 'name';
        switch ($sortBy) {
            case 'type':
                $query .= " ORDER BY l.type, l.name";
                break;
            case 'created_at':
                $query .= " ORDER BY l.created_at DESC";
                break;
            case 'total_books':
                $query .= " ORDER BY total_books DESC, l.name";
                break;
            default:
                $query .= " ORDER BY l.name";
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
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

    public function updateLibrary($data, $id) {
        $query = "UPDATE libraries SET name = ?, type = ?, address = ?, loan_period_days = ? WHERE id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            $data['name'], 
            $data['type'], 
            $data['address'], 
            $data['loan_period_days'] ?? 5,
            $id
        ]);
    }

    public function canDelete($id) {
        // Always return true - we'll handle cleanup in deleteLibraryById
        return [true, ''];
    }

    public function deleteLibraryById($id) {
        try {
            error_log("Starting library deletion for ID: $id");
            
            // Disable foreign key checks temporarily
            $this->db->exec("SET FOREIGN_KEY_CHECKS = 0");
            
            $this->db->beginTransaction();
            
            // 1. Delete all borrows related to this library (must come before students/books)
            $stmt = $this->db->prepare("DELETE FROM borrows WHERE library_id = :id");
            $result = $stmt->execute([':id' => $id]);
            $deletedBorrows = $stmt->rowCount();
            error_log("Deleted $deletedBorrows borrows");
            
            // 2. Delete reservations related to this library
            $stmt = $this->db->prepare("DELETE FROM reservations WHERE library_id = :id");
            $result = $stmt->execute([':id' => $id]);
            $deletedReservations = $stmt->rowCount();
            error_log("Deleted $deletedReservations reservations");
            
            // 3. Delete all students in this library
            $stmt = $this->db->prepare("DELETE FROM students WHERE library_id = :id");
            $result = $stmt->execute([':id' => $id]);
            $deletedStudents = $stmt->rowCount();
            error_log("Deleted $deletedStudents students");
            
            // 4. Delete all books in this library
            $stmt = $this->db->prepare("DELETE FROM books WHERE library_id = :id");
            $result = $stmt->execute([':id' => $id]);
            $deletedBooks = $stmt->rowCount();
            error_log("Deleted $deletedBooks books");
            
            // 5. Delete reports for this library
            $stmt = $this->db->prepare("DELETE FROM reports WHERE library_id = :id");
            $result = $stmt->execute([':id' => $id]);
            error_log("Deleted reports");
            
            // 6. Delete all librarians assigned to this library
            $stmt = $this->db->prepare("DELETE FROM users WHERE library_id = :id");
            $result = $stmt->execute([':id' => $id]);
            $deletedUsers = $stmt->rowCount();
            error_log("Deleted $deletedUsers librarians");
            
            // 7. Finally, delete the library itself
            $stmt = $this->db->prepare("DELETE FROM libraries WHERE id = :id");
            $result = $stmt->execute([':id' => $id]);
            $deletedLibrary = $stmt->rowCount();
            error_log("Deleted library: " . ($deletedLibrary > 0 ? "YES" : "NO"));
            
            if ($deletedLibrary > 0) {
                $this->db->commit();
                // Re-enable foreign key checks
                $this->db->exec("SET FOREIGN_KEY_CHECKS = 1");
                error_log("Library deletion committed successfully");
                return true;
            } else {
                $this->db->rollBack();
                $this->db->exec("SET FOREIGN_KEY_CHECKS = 1");
                error_log("Library not found or already deleted");
                return false;
            }
        } catch (Exception $e) {
            $this->db->rollBack();
            // Re-enable foreign key checks even on error
            $this->db->exec("SET FOREIGN_KEY_CHECKS = 1");
            error_log("Error deleting library: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            return false;
        }
    }

    public function getLoanPeriod($libraryId) {
        $query = "SELECT loan_period_days FROM libraries WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$libraryId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? (int)$result['loan_period_days'] : 5;
    }
}
?>