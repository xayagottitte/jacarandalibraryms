<?php
class Student extends Model {
    protected $table = 'students';

    public function __construct() {
        parent::__construct();
    }

    public function getStudentsByLibrary($libraryId, $filters = []) {
        $query = "SELECT s.*, 
                         (SELECT COUNT(*) FROM borrows WHERE student_id = s.id AND status = 'borrowed') as active_borrows
                  FROM students s 
                  WHERE s.library_id = :library_id";
        
        $params = ['library_id' => $libraryId];

        if (!empty($filters['search'])) {
            $query .= " AND (s.full_name LIKE :search OR s.student_id LIKE :search OR s.email LIKE :search)";
            $params['search'] = '%' . $filters['search'] . '%';
        }

        if (!empty($filters['class'])) {
            $query .= " AND s.class = :class";
            $params['class'] = $filters['class'];
        }

        if (!empty($filters['status'])) {
            $query .= " AND s.status = :status";
            $params['status'] = $filters['status'];
        }

        $query .= " ORDER BY s.class, s.full_name";

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function generateStudentId($libraryId) {
        // Get the count of all students in this library to generate a unique sequence
        $query = "SELECT COUNT(*) as count FROM students WHERE library_id = :library_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':library_id', $libraryId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $sequence = $result['count'] + 1;
        
        // Generate 6-digit ID starting with 66 followed by 4-digit sequence
        return '66' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public function getStudentWithBorrows($studentId) {
        $query = "SELECT s.*, l.name as library_name 
                  FROM students s 
                  JOIN libraries l ON s.library_id = l.id 
                  WHERE s.id = :student_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':student_id', $studentId);
        $stmt->execute();
        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($student) {
            $borrowQuery = "SELECT br.*, bk.title, bk.author, bk.isbn,
                                   DATEDIFF(br.due_date, br.borrowed_date) as loan_days,
                                   CASE 
                                     WHEN br.status = 'borrowed' AND CURDATE() > br.due_date 
                                     THEN DATEDIFF(CURDATE(), br.due_date)
                                     WHEN br.status = 'returned' AND br.returned_date > br.due_date
                                     THEN DATEDIFF(br.returned_date, br.due_date)
                                     ELSE 0 
                                   END as days_overdue
                           FROM borrows br 
                           JOIN books bk ON br.book_id = bk.id 
                           WHERE br.student_id = :student_id 
                           ORDER BY br.borrowed_date DESC";
            $borrowStmt = $this->db->prepare($borrowQuery);
            $borrowStmt->bindParam(':student_id', $studentId);
            $borrowStmt->execute();
            $student['borrow_history'] = $borrowStmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return $student;
    }

    public function getClassesByLibrary($libraryId) {
        $query = "SELECT DISTINCT class FROM students 
                  WHERE library_id = :library_id 
                  ORDER BY class";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':library_id', $libraryId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function validateClassForLibrary($libraryId, $class) {
        $library = (new Library())->find($libraryId);
        if (!$library) return false;

        $class = (int)$class;
        if ($library['type'] === 'primary') {
            return $class >= 1 && $class <= 8;
        } else {
            return $class >= 1 && $class <= 4;
        }
    }

    public function getStudentByStudentId($studentId, $libraryId = null) {
        $query = "SELECT s.* FROM students s WHERE s.student_id = :student_id";
        if ($libraryId) {
            $query .= " AND s.library_id = :library_id";
        }
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':student_id', $studentId);
        if ($libraryId) {
            $stmt->bindParam(':library_id', $libraryId);
        }
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateStudent($id, $data, $libraryId) {
        $query = "UPDATE students SET 
                 full_name = :full_name, email = :email, phone = :phone,
                 class = :class, section = :section, status = :status
                 WHERE id = :id AND library_id = :library_id";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':full_name', $data['full_name']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':phone', $data['phone']);
        $stmt->bindParam(':class', $data['class']);
        $stmt->bindParam(':section', $data['section']);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':library_id', $libraryId);
        
        return $stmt->execute();
    }

    public function updateStatus($id, $status) {
        $query = "UPDATE students SET status = :status WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getClassBorrowStats($libraryId) {
        $query = "SELECT 
                    s.class,
                    COUNT(DISTINCT br.id) as borrow_count,
                    COUNT(DISTINCT s.id) as student_count
                  FROM students s
                  LEFT JOIN borrows br ON br.student_id = s.id
                  WHERE s.library_id = :library_id
                  GROUP BY s.class
                  ORDER BY CAST(s.class AS UNSIGNED)";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':library_id', $libraryId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAtRiskStudents($libraryId) {
                // Get fine per day from system settings
                $settingsQuery = "SELECT setting_value FROM system_settings WHERE setting_key = 'fine_per_day'";
                $settingsStmt = $this->db->prepare($settingsQuery);
                $settingsStmt->execute();
                $finePerDay = $settingsStmt->fetchColumn() ?: 5; // Default to 5 if not set

                $query = "SELECT 
                                        s.id,
                                        s.student_id,
                                        s.full_name,
                                        s.class,
                                        COUNT(br.id) as total_borrows,
                                        COUNT(CASE WHEN br.status = 'overdue' THEN 1 END) as overdue_count,
                                        SUM(
                                                CASE 
                                                        WHEN br.status IN ('overdue', 'borrowed') AND br.due_date < CURDATE() THEN (DATEDIFF(CURDATE(), br.due_date) * {$finePerDay}) - COALESCE(br.paid_amount, 0)
                                                        ELSE br.fine_amount - COALESCE(br.paid_amount, 0)
                                                END
                                        ) as unpaid_fines
                                    FROM students s
                                    LEFT JOIN borrows br ON br.student_id = s.id
                                    WHERE s.library_id = :library_id
                                    AND s.status = 'active'
                                    GROUP BY s.id, s.student_id, s.full_name, s.class
                                    HAVING total_borrows = 0 OR overdue_count > 0 OR unpaid_fines > 0
                                    ORDER BY overdue_count DESC, unpaid_fines DESC, total_borrows ASC
                                    LIMIT 10";

                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':library_id', $libraryId, PDO::PARAM_INT);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalCount($libraryId = null) {
        $query = "SELECT COUNT(*) as count FROM students";
        
        if ($libraryId) {
            $query .= " WHERE library_id = :library_id";
        }
        
        $stmt = $this->db->prepare($query);
        if ($libraryId) {
            $stmt->bindParam(':library_id', $libraryId);
        }
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }

    public function getActiveCount($libraryId = null) {
        $query = "SELECT COUNT(DISTINCT s.id) as count 
                  FROM students s
                  INNER JOIN borrows b ON s.id = b.student_id
                  WHERE b.status IN ('borrowed', 'overdue')";
        
        if ($libraryId) {
            $query .= " AND s.library_id = :library_id";
        }
        
        $stmt = $this->db->prepare($query);
        if ($libraryId) {
            $stmt->bindParam(':library_id', $libraryId);
        }
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }

    public function getUniqueClassLevels($libraryId = null) {
        $query = "SELECT DISTINCT class FROM students WHERE class IS NOT NULL AND class != ''";
        
        if ($libraryId) {
            $query .= " AND library_id = :library_id";
        }
        
        $query .= " ORDER BY class";
        
        $stmt = $this->db->prepare($query);
        if ($libraryId) {
            $stmt->bindParam(':library_id', $libraryId);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
?>