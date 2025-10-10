<?php
class SystemStatistics extends Model {
    protected $table = 'system_statistics';

    public function __construct() {
        parent::__construct();
    }

    public function recordDailyStats($libraryId = null) {
        $stats = [];
        
        // Record book statistics
        $bookQuery = "SELECT 
                     COUNT(*) as total_books,
                     SUM(total_copies) as total_copies,
                     SUM(available_copies) as available_copies
                     FROM books" . 
                     ($libraryId ? " WHERE library_id = :library_id" : "");
        
        $bookStmt = $this->db->prepare($bookQuery);
        if ($libraryId) $bookStmt->bindParam(':library_id', $libraryId);
        $bookStmt->execute();
        $bookStats = $bookStmt->fetch(PDO::FETCH_ASSOC);
        
        $stats[] = [
            'library_id' => $libraryId,
            'stat_date' => date('Y-m-d'),
            'stat_type' => 'total_books',
            'stat_value' => $bookStats['total_books']
        ];
        
        $stats[] = [
            'library_id' => $libraryId,
            'stat_date' => date('Y-m-d'),
            'stat_type' => 'available_books',
            'stat_value' => $bookStats['available_copies']
        ];

        // Record borrowing statistics
        $borrowQuery = "SELECT 
                       COUNT(*) as total_borrows,
                       COUNT(CASE WHEN status = 'borrowed' THEN 1 END) as active_borrows,
                       COUNT(CASE WHEN status = 'overdue' THEN 1 END) as overdue_books
                       FROM borrows br" . 
                       ($libraryId ? " JOIN books b ON br.book_id = b.id WHERE b.library_id = :library_id" : "");
        
        $borrowStmt = $this->db->prepare($borrowQuery);
        if ($libraryId) $borrowStmt->bindParam(':library_id', $libraryId);
        $borrowStmt->execute();
        $borrowStats = $borrowStmt->fetch(PDO::FETCH_ASSOC);
        
        $stats[] = [
            'library_id' => $libraryId,
            'stat_date' => date('Y-m-d'),
            'stat_type' => 'active_borrows',
            'stat_value' => $borrowStats['active_borrows']
        ];
        
        $stats[] = [
            'library_id' => $libraryId,
            'stat_date' => date('Y-m-d'),
            'stat_type' => 'overdue_books',
            'stat_value' => $borrowStats['overdue_books']
        ];

        // Insert all stats
        foreach ($stats as $stat) {
            $this->create($stat);
        }
        
        return true;
    }

    public function getStatisticsTrend($libraryId = null, $statType, $days = 30) {
        $query = "SELECT stat_date, stat_value 
                  FROM system_statistics 
                  WHERE stat_type = :stat_type 
                  AND stat_date >= DATE_SUB(CURDATE(), INTERVAL :days DAY)" .
                  ($libraryId ? " AND library_id = :library_id" : "") .
                  " ORDER BY stat_date ASC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':stat_type', $statType);
        $stmt->bindParam(':days', $days, PDO::PARAM_INT);
        if ($libraryId) $stmt->bindParam(':library_id', $libraryId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMonthlySummary($libraryId = null, $year = null) {
        $year = $year ?: date('Y');
        
        $query = "SELECT 
                  DATE_FORMAT(stat_date, '%Y-%m') as month,
                  MAX(CASE WHEN stat_type = 'total_books' THEN stat_value END) as total_books,
                  MAX(CASE WHEN stat_type = 'active_borrows' THEN stat_value END) as active_borrows,
                  MAX(CASE WHEN stat_type = 'overdue_books' THEN stat_value END) as overdue_books
                  FROM system_statistics 
                  WHERE YEAR(stat_date) = :year" .
                  ($libraryId ? " AND library_id = :library_id" : "") .
                  " GROUP BY DATE_FORMAT(stat_date, '%Y-%m')
                  ORDER BY month DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':year', $year);
        if ($libraryId) $stmt->bindParam(':library_id', $libraryId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>