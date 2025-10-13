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
                         COALESCE(SUM(b.total_copies), 0) as total_copies,
                         COUNT(DISTINCT s.id) as total_students
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

    public function getLoanPeriod($libraryId) {
        $query = "SELECT loan_period_days FROM libraries WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$libraryId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? (int)$result['loan_period_days'] : 5;
    }
}
?>