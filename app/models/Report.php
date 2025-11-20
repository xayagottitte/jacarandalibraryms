<?php
class Report extends Model {
    protected $table = 'reports';

    public function __construct() {
        parent::__construct();
    }

    // Sanitize report data by removing sensitive fields and renaming columns
    private function sanitizeReportData($data, $type) {
        if (empty($data)) return $data;
        
        $sanitized = [];
        
        foreach ($data as $row) {
            $cleanRow = [];
            
            switch ($type) {
                case 'books':
                    $cleanRow = [
                        'Title' => $row['title'] ?? '',
                        'Author' => $row['author'] ?? '',
                        'ISBN' => $row['isbn'] ?? '',
                        'Publisher' => $row['publisher'] ?? '',
                        'Year' => $row['publication_year'] ?? '',
                        'Category' => $row['category_name'] ?? '',
                        'Class Level' => $row['class_level'] ?? '',
                        'Total Copies' => $row['total_copies'] ?? 0,
                        'Available Copies' => $row['available_copies'] ?? 0,
                        'Borrowed Copies' => $row['borrowed_copies'] ?? 0,
                        'Total Borrows' => $row['total_borrows'] ?? 0,
                        'Current Borrows' => $row['current_borrows'] ?? 0,
                        'Overdue Borrows' => $row['overdue_borrows'] ?? 0,
                        'Utilization Rate (%)' => $row['utilization_rate'] ?? 0
                    ];
                    break;
                    
                case 'students':
                    $cleanRow = [
                        'Student ID' => $row['student_id'] ?? '',
                        'Full Name' => $row['full_name'] ?? '',
                        'Email' => $row['email'] ?? '',
                        'Phone' => $row['phone'] ?? '',
                        'Class' => $row['class'] ?? '',
                        'Section' => $row['section'] ?? '',
                        'Status' => ucfirst($row['status'] ?? ''),
                        'Total Books Borrowed' => $row['total_borrows'] ?? 0,
                        'Currently Borrowed' => $row['active_borrows'] ?? 0,
                        'Overdue Books' => $row['overdue_books'] ?? 0,
                        'Total Fines (₦)' => number_format($row['total_fines'] ?? 0, 2),
                        'Paid Fines (₦)' => number_format($row['paid_fines'] ?? 0, 2),
                        'Activity Level (%)' => $row['activity_percentage'] ?? 0
                    ];
                    break;
                    
                case 'borrows':
                    $cleanRow = [
                        'Student ID' => $row['student_id'] ?? '',
                        'Student Name' => $row['student_name'] ?? '',
                        'Class' => $row['class'] ?? '',
                        'Book Title' => $row['title'] ?? '',
                        'Author' => $row['author'] ?? '',
                        'ISBN' => $row['isbn'] ?? '',
                        'Category' => $row['category'] ?? '',
                        'Borrowed Date' => $row['borrowed_date'] ?? '',
                        'Due Date' => $row['due_date'] ?? '',
                        'Returned Date' => $row['returned_date'] ?? 'Not Returned',
                        'Status' => ucfirst($row['status'] ?? ''),
                        'Days Borrowed' => $row['days_borrowed'] ?? 0,
                        'Days Overdue' => $row['days_overdue'] ?? 0,
                        'Fine Amount (₦)' => number_format($row['fine_amount'] ?? 0, 2),
                        'Paid Amount (₦)' => number_format($row['paid_amount'] ?? 0, 2),
                        'Librarian' => $row['librarian_name'] ?? ''
                    ];
                    break;
                    
                case 'financial':
                    $cleanRow = [
                        'Month' => $row['return_month'] ?? '',
                        'Total Returns' => $row['total_returns'] ?? 0,
                        'Total Fines (₦)' => number_format($row['total_fines'] ?? 0, 2),
                        'Total Paid (₦)' => number_format($row['total_paid'] ?? 0, 2),
                        'Outstanding Fines (₦)' => number_format($row['outstanding_fines'] ?? 0, 2),
                        'Collection Rate (%)' => $row['collection_rate'] ?? 0
                    ];
                    break;
            }
            
            $sanitized[] = $cleanRow;
        }
        
        return $sanitized;
    }

    public function generateBooksReport($libraryId, $filters = []) {
        $query = "SELECT 
                  b.*,
                  c.name as category_name,
                  COUNT(br.id) as total_borrows,
                  SUM(CASE WHEN br.status = 'borrowed' THEN 1 ELSE 0 END) as current_borrows,
                  SUM(CASE WHEN br.status = 'overdue' THEN 1 ELSE 0 END) as overdue_borrows,
                  (b.total_copies - b.available_copies) as borrowed_copies,
                  ROUND((b.total_copies - b.available_copies) * 100.0 / b.total_copies, 2) as utilization_rate
                  FROM books b
                  LEFT JOIN categories c ON b.category_id = c.id
                  LEFT JOIN borrows br ON b.id = br.book_id
                  WHERE b.library_id = :library_id";
        
        $params = ['library_id' => $libraryId];

        if (!empty($filters['category'])) {
            $query .= " AND b.category_id = :category";
            $params['category'] = $filters['category'];
        }

        if (!empty($filters['status'])) {
            if ($filters['status'] === 'available') {
                $query .= " AND b.available_copies > 0";
            } elseif ($filters['status'] === 'unavailable') {
                $query .= " AND b.available_copies = 0";
            }
        }

        $query .= " GROUP BY b.id ORDER BY b.title";

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $this->sanitizeReportData($data, 'books');
    }

    public function generateStudentsReport($libraryId, $filters = []) {
        $query = "SELECT 
                  s.*,
                  COUNT(br.id) as total_borrows,
                  COUNT(CASE WHEN br.status = 'borrowed' THEN 1 END) as active_borrows,
                  COUNT(CASE WHEN br.status = 'overdue' THEN 1 END) as overdue_books,
                  SUM(br.fine_amount) as total_fines,
                  SUM(br.paid_amount) as paid_fines,
                  ROUND(COUNT(br.id) * 100.0 / (SELECT COUNT(*) FROM students WHERE library_id = :inner_library_id), 2) as activity_percentage
                  FROM students s
                  LEFT JOIN borrows br ON s.id = br.student_id
                  WHERE s.library_id = :library_id";
        
        $params = ['library_id' => $libraryId, 'inner_library_id' => $libraryId];

        if (!empty($filters['class'])) {
            $query .= " AND s.class = :class";
            $params['class'] = $filters['class'];
        }

        if (!empty($filters['status'])) {
            $query .= " AND s.status = :status";
            $params['status'] = $filters['status'];
        }

        $query .= " GROUP BY s.id ORDER BY s.class, s.full_name";

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $this->sanitizeReportData($data, 'students');
    }

    public function generateBorrowingReport($libraryId, $dateRange = []) {
        $query = "SELECT 
                  br.*,
                  b.title, b.author, b.isbn, c.name as category,
                  s.full_name as student_name, s.student_id, s.class, s.section,
                  u.username as librarian_name,
                  DATEDIFF(COALESCE(br.returned_date, CURDATE()), br.borrowed_date) as days_borrowed,
                  CASE 
                    WHEN br.status = 'returned' AND br.returned_date > br.due_date 
                    THEN DATEDIFF(br.returned_date, br.due_date) 
                    WHEN br.status IN ('borrowed', 'overdue') AND CURDATE() > br.due_date 
                    THEN DATEDIFF(CURDATE(), br.due_date)
                    ELSE 0 
                  END as days_overdue,
                  CASE 
                    WHEN br.status = 'returned' AND br.returned_date > br.due_date 
                    THEN (DATEDIFF(br.returned_date, br.due_date) * 5)
                    WHEN br.status IN ('borrowed', 'overdue') AND CURDATE() > br.due_date 
                    THEN (DATEDIFF(CURDATE(), br.due_date) * 5)
                    ELSE 0 
                  END as calculated_fine
                  FROM borrows br
                  JOIN books b ON br.book_id = b.id
                  LEFT JOIN categories c ON b.category_id = c.id
                  JOIN students s ON br.student_id = s.id
                  JOIN users u ON br.created_by = u.id
                  WHERE b.library_id = :library_id";
        
        $params = ['library_id' => $libraryId];

        if (!empty($dateRange['start_date'])) {
            $query .= " AND br.borrowed_date >= :start_date";
            $params['start_date'] = $dateRange['start_date'];
        }

        if (!empty($dateRange['end_date'])) {
            $query .= " AND br.borrowed_date <= :end_date";
            $params['end_date'] = $dateRange['end_date'];
        }

        $query .= " ORDER BY br.borrowed_date DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $this->sanitizeReportData($data, 'borrows');
    }

    public function generateFinancialReport($libraryId, $dateRange = []) {
        $query = "SELECT 
                  DATE_FORMAT(br.returned_date, '%Y-%m') as return_month,
                  COUNT(*) as total_returns,
                  SUM(br.fine_amount) as total_fines,
                  SUM(br.paid_amount) as total_paid,
                  (SUM(br.fine_amount) - SUM(br.paid_amount)) as outstanding_fines,
                  ROUND(SUM(br.paid_amount) * 100.0 / NULLIF(SUM(br.fine_amount), 0), 2) as collection_rate
                  FROM borrows br
                  JOIN books b ON br.book_id = b.id
                  WHERE b.library_id = :library_id 
                  AND br.status = 'returned'
                  AND br.fine_amount > 0";
        
        $params = ['library_id' => $libraryId];

        if (!empty($dateRange['start_date'])) {
            $query .= " AND br.returned_date >= :start_date";
            $params['start_date'] = $dateRange['start_date'];
        }

        if (!empty($dateRange['end_date'])) {
            $query .= " AND br.returned_date <= :end_date";
            $params['end_date'] = $dateRange['end_date'];
        }

        $query .= " GROUP BY DATE_FORMAT(br.returned_date, '%Y-%m') 
                   ORDER BY return_month DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $this->sanitizeReportData($data, 'financial');
    }

    public function generateLibraryAnalytics($libraryId, $period = '30') {
        $query = "SELECT 
                  DATE(br.borrowed_date) as date,
                  COUNT(*) as daily_borrows,
                  COUNT(DISTINCT br.student_id) as unique_students,
                  COUNT(DISTINCT br.book_id) as unique_books
                  FROM borrows br
                  JOIN books b ON br.book_id = b.id
                  WHERE b.library_id = :library_id 
                  AND br.borrowed_date >= DATE_SUB(CURDATE(), INTERVAL :period DAY)
                  GROUP BY DATE(br.borrowed_date)
                  ORDER BY date DESC";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':library_id', $libraryId);
        $stmt->bindParam(':period', $period, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPopularBooks($libraryId, $limit = 10) {
        $query = "SELECT 
                  b.title, b.author, b.category,
                  COUNT(br.id) as borrow_count,
                  AVG(DATEDIFF(COALESCE(br.returned_date, CURDATE()), br.borrowed_date)) as avg_borrow_days
                  FROM books b
                  JOIN borrows br ON b.id = br.book_id
                  WHERE b.library_id = :library_id
                  GROUP BY b.id
                  ORDER BY borrow_count DESC
                  LIMIT :limit";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':library_id', $libraryId);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTopStudents($libraryId, $limit = 10) {
        $query = "SELECT 
                  s.full_name, s.class, s.section,
                  COUNT(br.id) as total_borrows,
                  COUNT(CASE WHEN br.status = 'borrowed' THEN 1 END) as active_borrows,
                  SUM(br.fine_amount) as total_fines
                  FROM students s
                  JOIN borrows br ON s.id = br.student_id
                  WHERE s.library_id = :library_id
                  GROUP BY s.id
                  ORDER BY total_borrows DESC
                  LIMIT :limit";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':library_id', $libraryId);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function saveReportWithData($data, $reportData = null) {
        if ($reportData) {
            $data['report_data'] = json_encode($reportData);
        }
        return $this->create($data);
    }

    public function getReportWithData($reportId) {
        $query = "SELECT * FROM reports WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $reportId);
        $stmt->execute();
        $report = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($report && $report['report_data']) {
            $report['report_data'] = json_decode($report['report_data'], true);
        }
        
        return $report;
    }

    public function cleanupOldReports($days = 30) {
        $query = "DELETE FROM reports WHERE created_at < DATE_SUB(NOW(), INTERVAL :days DAY)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':days', $days, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getUserReports($userId) {
        $query = "SELECT 
                    r.*,
                    l.name as library_name,
                    u.username
                  FROM reports r
                  LEFT JOIN libraries l ON r.library_id = l.id
                  LEFT JOIN users u ON r.generated_by = u.id
                  WHERE r.generated_by = :user_id 
                  ORDER BY r.created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllReports() {
        $query = "SELECT 
                    r.*,
                    l.name as library_name,
                    u.username
                  FROM reports r
                  LEFT JOIN libraries l ON r.library_id = l.id
                  LEFT JOIN users u ON r.generated_by = u.id
                  ORDER BY r.created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function saveReport($data) {
        return $this->create($data);
    }
}
?>