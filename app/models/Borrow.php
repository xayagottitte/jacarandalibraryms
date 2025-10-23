<?php
class Borrow extends Model {
    protected $table = 'borrows';

    public function __construct() {
        parent::__construct();
    }

    public function borrowBook($bookId, $studentId, $librarianId, $dueDays = null) {
        $this->db->beginTransaction();

        try {
            // Get book info and library_id
            $bookQuery = "SELECT available_copies, title, library_id FROM books WHERE id = :book_id FOR UPDATE";
            $bookStmt = $this->db->prepare($bookQuery);
            $bookStmt->bindParam(':book_id', $bookId);
            $bookStmt->execute();
            $book = $bookStmt->fetch(PDO::FETCH_ASSOC);

            // If dueDays not provided, get it from library settings
            if ($dueDays === null && isset($book['library_id'])) {
                $libraryModel = new Library();
                $dueDays = $libraryModel->getLoanPeriod($book['library_id']);
            }
            
            // Fallback to 5 days if still not set
            if ($dueDays === null) {
                $dueDays = 5;
            }

            if (!$book || $book['available_copies'] <= 0) {
                throw new Exception("Book '{$book['title']}' is not available for borrowing.");
            }

            // Check if student has reached borrow limit
            $borrowCountQuery = "SELECT COUNT(*) as count FROM borrows 
                                WHERE student_id = :student_id AND status IN ('borrowed', 'overdue')";
            $borrowCountStmt = $this->db->prepare($borrowCountQuery);
            $borrowCountStmt->bindParam(':student_id', $studentId);
            $borrowCountStmt->execute();
            $borrowCount = $borrowCountStmt->fetch(PDO::FETCH_ASSOC);

            if ($borrowCount['count'] >= 5) {
                throw new Exception("Student has reached the maximum borrowing limit (5 books).");
            }

            // Check if student already has this book
            $existingBorrowQuery = "SELECT id FROM borrows 
                                   WHERE student_id = :student_id AND book_id = :book_id AND status IN ('borrowed', 'overdue')";
            $existingBorrowStmt = $this->db->prepare($existingBorrowQuery);
            $existingBorrowStmt->bindParam(':student_id', $studentId);
            $existingBorrowStmt->bindParam(':book_id', $bookId);
            $existingBorrowStmt->execute();
            
            if ($existingBorrowStmt->fetch(PDO::FETCH_ASSOC)) {
                throw new Exception("Student already has this book borrowed.");
            }

            // Create borrow record
            $borrowData = [
                'book_id' => $bookId,
                'student_id' => $studentId,
                'borrowed_date' => date('Y-m-d'),
                'due_date' => date('Y-m-d', strtotime("+$dueDays days")),
                'status' => 'borrowed',
                'created_by' => $librarianId
            ];

            $borrowQuery = "INSERT INTO borrows (book_id, student_id, borrowed_date, due_date, status, created_by) 
                           VALUES (:book_id, :student_id, :borrowed_date, :due_date, :status, :created_by)";
            $borrowStmt = $this->db->prepare($borrowQuery);
            $borrowStmt->execute($borrowData);

            // Update book copies
            $updateBookQuery = "UPDATE books SET available_copies = available_copies - 1 WHERE id = :book_id";
            $updateBookStmt = $this->db->prepare($updateBookQuery);
            $updateBookStmt->bindParam(':book_id', $bookId);
            $updateBookStmt->execute();

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function returnBook($borrowId, $librarianId) {
        $this->db->beginTransaction();

        try {
            // Get fine per day from system settings
            $settingsQuery = "SELECT setting_value FROM system_settings WHERE setting_key = 'fine_per_day'";
            $settingsStmt = $this->db->prepare($settingsQuery);
            $settingsStmt->execute();
            $finePerDay = $settingsStmt->fetchColumn() ?: 5; // Default to 5 if not set
            
            // Get borrow record
            $borrowQuery = "SELECT br.*, bk.title, s.full_name as student_name 
                           FROM borrows br
                           JOIN books bk ON br.book_id = bk.id
                           JOIN students s ON br.student_id = s.id
                           WHERE br.id = :borrow_id";
            $borrowStmt = $this->db->prepare($borrowQuery);
            $borrowStmt->bindParam(':borrow_id', $borrowId);
            $borrowStmt->execute();
            $borrow = $borrowStmt->fetch(PDO::FETCH_ASSOC);

            if (!$borrow) {
                throw new Exception("Borrow record not found.");
            }

            if ($borrow['status'] === 'returned') {
                throw new Exception("Book '{$borrow['title']}' has already been returned by {$borrow['student_name']}.");
            }

            // Calculate fine if overdue
            $returnDate = date('Y-m-d');
            $dueDate = $borrow['due_date'];
            $fineAmount = 0;

            if (strtotime($returnDate) > strtotime($dueDate)) {
                $daysOverdue = floor((strtotime($returnDate) - strtotime($dueDate)) / (60 * 60 * 24));
                $fineAmount = $daysOverdue * $finePerDay;
            }

            // Update borrow record
            $updateBorrowQuery = "UPDATE borrows SET 
                                 returned_date = :returned_date,
                                 status = 'returned',
                                 fine_amount = :fine_amount,
                                 updated_at = NOW()
                                 WHERE id = :borrow_id";
            $updateBorrowStmt = $this->db->prepare($updateBorrowQuery);
            $updateBorrowStmt->bindParam(':returned_date', $returnDate);
            $updateBorrowStmt->bindParam(':fine_amount', $fineAmount);
            $updateBorrowStmt->bindParam(':borrow_id', $borrowId);
            $updateBorrowStmt->execute();

            // Update book copies
            $updateBookQuery = "UPDATE books SET available_copies = available_copies + 1 WHERE id = :book_id";
            $updateBookStmt = $this->db->prepare($updateBookQuery);
            $updateBookStmt->bindParam(':book_id', $borrow['book_id']);
            $updateBookStmt->execute();

            $this->db->commit();
            return [
                'success' => true, 
                'fine_amount' => $fineAmount,
                'book_title' => $borrow['title'],
                'student_name' => $borrow['student_name']
            ];

        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function getBorrowsByLibrary($libraryId, $filters = []) {
        // Get fine per day from system settings
        $settingsQuery = "SELECT setting_value FROM system_settings WHERE setting_key = 'fine_per_day'";
        $settingsStmt = $this->db->prepare($settingsQuery);
        $settingsStmt->execute();
        $finePerDay = $settingsStmt->fetchColumn() ?: 5; // Default to 5 if not set
        
        $query = "SELECT br.*, 
                         bk.title, bk.author, bk.isbn,
                         s.full_name as student_name, s.student_id, s.class,
                         u.username as librarian_name,
                         DATEDIFF(CURDATE(), br.due_date) as days_overdue_calc,
                         CASE 
                           WHEN br.status = 'returned' AND br.returned_date > br.due_date 
                           THEN DATEDIFF(br.returned_date, br.due_date) * {$finePerDay}
                           WHEN br.status IN ('borrowed', 'overdue') AND CURDATE() > br.due_date 
                           THEN DATEDIFF(CURDATE(), br.due_date) * {$finePerDay}
                           ELSE br.fine_amount
                         END as calculated_fine
                  FROM borrows br
                  JOIN books bk ON br.book_id = bk.id
                  JOIN students s ON br.student_id = s.id
                  JOIN users u ON br.created_by = u.id
                  WHERE bk.library_id = :library_id";
        
        $params = ['library_id' => $libraryId];

        if (!empty($filters['status'])) {
            $query .= " AND br.status = :status";
            $params['status'] = $filters['status'];
        }

        if (!empty($filters['student_id'])) {
            $query .= " AND s.student_id LIKE :student_id";
            $params['student_id'] = '%' . $filters['student_id'] . '%';
        }

        if (!empty($filters['book_title'])) {
            $query .= " AND bk.title LIKE :book_title";
            $params['book_title'] = '%' . $filters['book_title'] . '%';
        }

        $query .= " ORDER BY br.borrowed_date DESC, br.status";

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $borrows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Update fine_amount with calculated_fine for display
        foreach ($borrows as &$borrow) {
            $borrow['fine_amount'] = $borrow['calculated_fine'];
        }
        
        return $borrows;
    }

    public function getActiveBorrowsByStudent($studentId) {
        $query = "SELECT br.*, bk.title, bk.author, bk.isbn,
                         DATEDIFF(br.due_date, CURDATE()) as days_remaining,
                         CASE 
                           WHEN CURDATE() > br.due_date THEN DATEDIFF(CURDATE(), br.due_date)
                           ELSE 0 
                         END as days_overdue
                  FROM borrows br
                  JOIN books bk ON br.book_id = bk.id
                  WHERE br.student_id = :student_id 
                  AND br.status IN ('borrowed', 'overdue')
                  ORDER BY br.due_date ASC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':student_id', $studentId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOverdueBorrows($libraryId) {
        $query = "SELECT br.*, 
                         bk.title, bk.author,
                         s.full_name as student_name, s.student_id, s.class,
                         DATEDIFF(CURDATE(), br.due_date) as days_overdue
                  FROM borrows br
                  JOIN books bk ON br.book_id = bk.id
                  JOIN students s ON br.student_id = s.id
                  WHERE bk.library_id = :library_id 
                  AND br.status = 'borrowed' 
                  AND br.due_date < CURDATE()
                  ORDER BY br.due_date ASC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':library_id', $libraryId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateOverdueStatus() {
        $query = "UPDATE borrows SET status = 'overdue' 
                  WHERE status = 'borrowed' AND due_date < CURDATE()";
        $stmt = $this->db->prepare($query);
        return $stmt->execute();
    }

    public function getBorrowStatistics($libraryId) {
        $query = "SELECT 
                  COUNT(*) as total_borrows,
                  SUM(CASE WHEN status = 'borrowed' THEN 1 ELSE 0 END) as current_borrows,
                  SUM(CASE WHEN status = 'overdue' THEN 1 ELSE 0 END) as overdue_books,
                  SUM(fine_amount) as total_fines,
                  SUM(paid_amount) as paid_fines
                  FROM borrows br
                  JOIN books bk ON br.book_id = bk.id
                  WHERE bk.library_id = :library_id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':library_id', $libraryId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getBookBorrowHistory($bookId) {
        $query = "SELECT br.*, 
                         s.full_name as student_name, s.student_id, s.class
                  FROM borrows br
                  JOIN students s ON br.student_id = s.id
                  WHERE br.book_id = :book_id
                  ORDER BY br.borrowed_date DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':book_id', $bookId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLibrarianStats($userId) {
        $stats = [
            'books_issued' => 0,
            'books_returned' => 0,
            'fines_collected' => 0
        ];

        try {
            // Books issued by this librarian
            $query = "SELECT COUNT(*) as count FROM borrows WHERE borrowed_by = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $stats['books_issued'] = $result['count'] ?? 0;

            // Books returned (processed by this librarian)
            $query = "SELECT COUNT(*) as count FROM borrows WHERE returned_by = ? AND status = 'returned'";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $stats['books_returned'] = $result['count'] ?? 0;

            // Fines collected by this librarian
            $query = "SELECT SUM(paid_amount) as total FROM borrows WHERE returned_by = ? AND paid_amount > 0";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $stats['fines_collected'] = $result['total'] ?? 0;

        } catch (Exception $e) {
            error_log("Error getting librarian stats: " . $e->getMessage());
        }

        return $stats;
    }

    public function getRecentActivities($userId, $limit = 50) {
        $activities = [];

        try {
            $query = "SELECT 
                        'Book Borrow' as activity,
                        CONCAT('Issued \"', b.title, '\" to ', s.full_name) as details,
                        'success' as status,
                        br.borrowed_date as created_at
                      FROM borrows br
                      JOIN books b ON br.book_id = b.id
                      JOIN students s ON br.student_id = s.id
                      WHERE br.borrowed_by = ?
                      
                      UNION ALL
                      
                      SELECT 
                        'Book Return' as activity,
                        CONCAT('Returned \"', b.title, '\" from ', s.full_name) as details,
                        'success' as status,
                        br.returned_date as created_at
                      FROM borrows br
                      JOIN books b ON br.book_id = b.id
                      JOIN students s ON br.student_id = s.id
                      WHERE br.returned_by = ? AND br.status = 'returned'
                      
                      ORDER BY created_at DESC
                      LIMIT ?";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId, $userId, $limit]);
            $activities = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            error_log("Error getting recent activities: " . $e->getMessage());
        }

        return $activities;
    }

    public function getOverdueBookStats($libraryId) {
        $query = "SELECT 
                    b.title,
                    b.category,
                    COUNT(br.id) as overdue_count,
                    AVG(DATEDIFF(COALESCE(br.returned_date, CURDATE()), br.due_date)) as avg_days_overdue
                  FROM borrows br
                  JOIN books b ON br.book_id = b.id
                  WHERE b.library_id = :library_id 
                  AND (
                    (br.status IN ('borrowed', 'overdue') AND br.due_date < CURDATE())
                    OR 
                    (br.status = 'returned' AND br.returned_date > br.due_date)
                  )
                  GROUP BY b.id, b.title, b.category
                  ORDER BY overdue_count DESC
                  LIMIT 10";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':library_id', $libraryId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBorrowingTrends($libraryId, $days = 30) {
        $query = "SELECT 
                    DATE(br.borrowed_date) as borrow_date,
                    COUNT(br.id) as borrow_count
                  FROM borrows br
                  JOIN books b ON br.book_id = b.id
                  WHERE b.library_id = :library_id 
                  AND br.borrowed_date >= DATE_SUB(CURDATE(), INTERVAL :days DAY)
                  GROUP BY DATE(br.borrowed_date)
                  ORDER BY borrow_date ASC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':library_id', $libraryId, PDO::PARAM_INT);
        $stmt->bindParam(':days', $days, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFinancialStats($libraryId, $days = 30) {
        $query = "SELECT 
                    DATE(br.returned_date) as return_date,
                    SUM(br.fine_amount) as total_fines,
                    SUM(br.paid_amount) as total_paid,
                    COUNT(CASE WHEN br.fine_amount > 0 THEN 1 END) as fined_returns
                  FROM borrows br
                  JOIN books b ON br.book_id = b.id
                  WHERE b.library_id = :library_id 
                  AND br.returned_date IS NOT NULL
                  AND br.returned_date >= DATE_SUB(CURDATE(), INTERVAL :days DAY)
                  GROUP BY DATE(br.returned_date)
                  ORDER BY return_date ASC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':library_id', $libraryId, PDO::PARAM_INT);
        $stmt->bindParam(':days', $days, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>