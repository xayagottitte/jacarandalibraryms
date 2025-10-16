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
}
?>