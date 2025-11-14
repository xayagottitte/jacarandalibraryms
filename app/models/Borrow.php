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
        $query = "SELECT br.*, bk.title, bk.author, bk.isbn, bk.cover_image,
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
        // Calculate fine per day from system settings for computed fine
        $settingsQuery = "SELECT setting_value FROM system_settings WHERE setting_key = 'fine_per_day'";
        $settingsStmt = $this->db->prepare($settingsQuery);
        $settingsStmt->execute();
        $finePerDay = $settingsStmt->fetchColumn() ?: 5; // Default to 5 if not set

        // Include borrows that are already marked overdue or those still marked borrowed but past due date
        $query = "SELECT br.*, 
                                         bk.title, bk.author,
                                         s.full_name as student_name, s.student_id, s.class,
                                         DATEDIFF(CURDATE(), br.due_date) as days_overdue,
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
                            WHERE bk.library_id = :library_id 
                            AND ((br.status = 'overdue') OR (br.status = 'borrowed' AND br.due_date < CURDATE()))
                            ORDER BY br.due_date ASC";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':library_id', $libraryId);
        $stmt->execute();
        $borrows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Ensure fine_amount field reflects calculated fine for display
        foreach ($borrows as &$b) {
                $b['fine_amount'] = $b['calculated_fine'] ?? ($b['fine_amount'] ?? 0);
        }

        return $borrows;
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
            // Books issued by this librarian (created_by)
            $query = "SELECT COUNT(*) as count FROM borrows WHERE created_by = :uid";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':uid', $userId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $stats['books_issued'] = (int)($result['count'] ?? 0);

            // Books returned (we don't have returned_by column; approximate by borrows created_by)
            $query = "SELECT COUNT(*) as count FROM borrows WHERE status = 'returned' AND created_by = :uid";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':uid', $userId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $stats['books_returned'] = (int)($result['count'] ?? 0);

            // Fines collected (sum of paid_amount on those borrows)
            $query = "SELECT SUM(paid_amount) as total FROM borrows WHERE created_by = :uid AND paid_amount > 0";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':uid', $userId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $stats['fines_collected'] = (float)($result['total'] ?? 0);

        } catch (Exception $e) {
            error_log("Error getting librarian stats: " . $e->getMessage());
        }

        return $stats;
    }

    public function getRecentActivities($userId, $limit = 50) {
        $activities = [];

        try {
            // Use created_by as the librarian responsible (schema does not have borrowed_by/returned_by)
            $query = "
                SELECT 
                    'Book Borrow' as activity,
                    CONCAT('Issued \"', b.title, '\" to ', s.full_name) as details,
                    'success' as status,
                    br.borrowed_date as created_at
                FROM borrows br
                JOIN books b ON br.book_id = b.id
                JOIN students s ON br.student_id = s.id
                WHERE br.created_by = :uid

                UNION ALL

                SELECT 
                    'Book Return' as activity,
                    CONCAT('Returned \"', b.title, '\" from ', s.full_name) as details,
                    'success' as status,
                    br.returned_date as created_at
                FROM borrows br
                JOIN books b ON br.book_id = b.id
                JOIN students s ON br.student_id = s.id
                WHERE br.status = 'returned' AND br.created_by = :uid

                ORDER BY created_at DESC
                LIMIT :lim
            ";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':uid', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':lim', $limit, PDO::PARAM_INT);
            $stmt->execute();
            $activities = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            error_log("Error getting recent activities: " . $e->getMessage());
        }

        return $activities;
    }

    public function getOverdueBookStats($libraryId) {
        $query = "SELECT 
                    b.title,
                    c.name as category_name,
                    COUNT(br.id) as overdue_count,
                    AVG(DATEDIFF(COALESCE(br.returned_date, CURDATE()), br.due_date)) as avg_days_overdue
                  FROM borrows br
                  JOIN books b ON br.book_id = b.id
                  LEFT JOIN categories c ON b.category_id = c.id
                  WHERE b.library_id = :library_id 
                  AND (
                    (br.status IN ('borrowed', 'overdue') AND br.due_date < CURDATE())
                    OR 
                    (br.status = 'returned' AND br.returned_date > br.due_date)
                  )
                  GROUP BY b.id, b.title, c.name
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

    public function getLostCounts($libraryId = null, $lostAfterDays = 30) {
        $this->ensureLostColumns();
        $where = " br.status IN ('borrowed','overdue') AND DATEDIFF(CURDATE(), br.due_date) > :days ";
        $where = " (br.is_lost = 1 OR (" . $where . ")) ";
        $params = [':days' => $lostAfterDays];
        if ($libraryId !== null) {
            $where .= " AND bk.library_id = :lib ";
            $params[':lib'] = $libraryId;
        }
        $sql = "SELECT COUNT(*) AS lost_count
                FROM borrows br
                JOIN books bk ON br.book_id = bk.id
                WHERE $where";
        $stmt = $this->db->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v, is_int($v) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($row['lost_count'] ?? 0);
    }

    public function getLostBooks($libraryId = null, $limit = 10, $lostAfterDays = 30) {
        $this->ensureLostColumns();
        $whereCore = " br.status IN ('borrowed','overdue') AND DATEDIFF(CURDATE(), br.due_date) > :days ";
        $where = " (br.is_lost = 1 OR (" . $whereCore . ")) ";
        $params = [':days' => $lostAfterDays, ':limit' => (int)$limit];
        if ($libraryId !== null) {
            $where .= " AND bk.library_id = :lib ";
            $params[':lib'] = $libraryId;
        }
        $sql = "SELECT br.id as borrow_id, br.student_id, br.book_id, br.due_date,
                        DATEDIFF(CURDATE(), br.due_date) AS days_overdue,
                        br.is_lost,
                        bk.title, bk.author, bk.isbn,
                        s.full_name as student_name, s.student_id as student_code,
                        l.id as library_id, l.name as library_name
                FROM borrows br
                JOIN books bk ON br.book_id = bk.id
                JOIN students s ON br.student_id = s.id
                JOIN libraries l ON bk.library_id = l.id
                WHERE $where
                ORDER BY days_overdue DESC, br.due_date ASC
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v, is_int($v) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function ensureLostColumns() {
        try {
            $check = $this->db->query("SHOW COLUMNS FROM borrows LIKE 'is_lost'");
            $exists = $check && $check->fetch(PDO::FETCH_ASSOC);
            if (!$exists) {
                $this->db->exec("ALTER TABLE borrows ADD COLUMN is_lost TINYINT(1) NOT NULL DEFAULT 0 AFTER status");
            }
            $check2 = $this->db->query("SHOW COLUMNS FROM borrows LIKE 'lost_marked_at'");
            if (!$check2 || !$check2->fetch(PDO::FETCH_ASSOC)) {
                $this->db->exec("ALTER TABLE borrows ADD COLUMN lost_marked_at DATETIME NULL AFTER is_lost");
            }
            $check3 = $this->db->query("SHOW COLUMNS FROM borrows LIKE 'lost_marked_by'");
            if (!$check3 || !$check3->fetch(PDO::FETCH_ASSOC)) {
                $this->db->exec("ALTER TABLE borrows ADD COLUMN lost_marked_by INT NULL AFTER lost_marked_at");
            }
        } catch (Exception $e) {
            // Silent fail; views will still use 30-day heuristic
        }
    }

    public function markLost($borrowId, $userId) {
        $this->ensureLostColumns();
        $this->db->beginTransaction();
        try {
            $q = $this->db->prepare("SELECT id, status FROM borrows WHERE id = :id FOR UPDATE");
            $q->bindParam(':id', $borrowId, PDO::PARAM_INT);
            $q->execute();
            $row = $q->fetch(PDO::FETCH_ASSOC);
            if (!$row) throw new Exception('Borrow record not found.');
            if ($row['status'] === 'returned') throw new Exception('Cannot mark lost: already returned.');

            $u = $this->db->prepare("UPDATE borrows SET is_lost = 1, lost_marked_at = NOW(), lost_marked_by = :uid WHERE id = :id");
            $u->bindParam(':uid', $userId, PDO::PARAM_INT);
            $u->bindParam(':id', $borrowId, PDO::PARAM_INT);
            $u->execute();

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function markFound($borrowId, $userId) {
        $this->ensureLostColumns();
        $u = $this->db->prepare("UPDATE borrows SET is_lost = 0 WHERE id = :id");
        $u->bindParam(':id', $borrowId, PDO::PARAM_INT);
        return $u->execute();
    }

    public function recordFinePayment($borrowId, $amount) {
        $this->db->beginTransaction();

        try {
            $stmt = $this->db->prepare("SELECT id, status, fine_amount, paid_amount FROM borrows WHERE id = :id FOR UPDATE");
            $stmt->bindParam(':id', $borrowId, PDO::PARAM_INT);
            $stmt->execute();
            $borrow = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$borrow) {
                throw new Exception('Borrow record not found.');
            }

            if ($borrow['status'] !== 'returned') {
                throw new Exception('Fine payment is only allowed after the book is returned.');
            }

            $fineAmount = (float)($borrow['fine_amount'] ?? 0);
            $paidAmount = (float)($borrow['paid_amount'] ?? 0);
            $remaining = max(0.0, $fineAmount - $paidAmount);

            if ($fineAmount <= 0) {
                throw new Exception('There is no fine to pay for this borrow.');
            }

            if ($remaining <= 0) {
                throw new Exception('This fine is already fully paid.');
            }

            $pay = (float)$amount;
            if ($pay <= 0) {
                throw new Exception('Payment amount must be greater than zero.');
            }

            if ($pay > $remaining) {
                $pay = $remaining;
            }

            $u = $this->db->prepare("UPDATE borrows SET paid_amount = paid_amount + :pay, updated_at = NOW() WHERE id = :id");
            $u->bindParam(':pay', $pay);
            $u->bindParam(':id', $borrowId, PDO::PARAM_INT);
            $u->execute();

            $this->db->commit();
            return [
                'success' => true,
                'paid_now' => $pay,
                'remaining' => $remaining - $pay
            ];
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function getOverdueCount($libraryId = null) {
        $query = "SELECT COUNT(*) as count FROM borrows b
                  INNER JOIN books bk ON b.book_id = bk.id
                  WHERE b.status = 'overdue'";
        
        if ($libraryId) {
            $query .= " AND bk.library_id = :library_id";
        }
        
        $stmt = $this->db->prepare($query);
        if ($libraryId) {
            $stmt->bindParam(':library_id', $libraryId);
        }
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }

    public function getTotalBorrowsCount($libraryId = null) {
        $query = "SELECT COUNT(*) as count FROM borrows b";
        
        if ($libraryId) {
            $query .= " INNER JOIN books bk ON b.book_id = bk.id
                       WHERE bk.library_id = :library_id";
        }
        
        $stmt = $this->db->prepare($query);
        if ($libraryId) {
            $stmt->bindParam(':library_id', $libraryId);
        }
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }

    public function getPeakBorrowDay($libraryId = null) {
        $query = "SELECT DAYNAME(b.borrow_date) as day_name, COUNT(*) as count
                  FROM borrows b";
        
        if ($libraryId) {
            $query .= " INNER JOIN books bk ON b.book_id = bk.id
                       WHERE bk.library_id = :library_id";
        }
        
        $query .= " GROUP BY DAYOFWEEK(b.borrow_date), DAYNAME(b.borrow_date)
                   ORDER BY count DESC LIMIT 1";
        
        $stmt = $this->db->prepare($query);
        if ($libraryId) {
            $stmt->bindParam(':library_id', $libraryId);
        }
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['day_name'] : null;
    }
}
?>