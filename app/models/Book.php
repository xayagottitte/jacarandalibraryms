<?php
class Book extends Model {
    protected $table = 'books';

    public function __construct() {
        parent::__construct();
    }

    public function getBooksByLibrary($libraryId, $filters = []) {
        $query = "SELECT b.*, c.name as category_name 
                  FROM books b 
                  LEFT JOIN categories c ON b.category = c.name 
                  WHERE b.library_id = :library_id";
        
        $params = ['library_id' => $libraryId];

        // Add filters
        if (!empty($filters['search'])) {
            $query .= " AND (b.title LIKE :search OR b.author LIKE :search OR b.isbn LIKE :search)";
            $params['search'] = '%' . $filters['search'] . '%';
        }

        if (!empty($filters['category'])) {
            $query .= " AND b.category = :category";
            $params['category'] = $filters['category'];
        }

        if (!empty($filters['status'])) {
            if ($filters['status'] === 'available') {
                $query .= " AND b.available_copies > 0";
            } elseif ($filters['status'] === 'unavailable') {
                $query .= " AND b.available_copies = 0";
            }
        }

        $query .= " ORDER BY b.title";

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBookWithLibrary($bookId) {
        $query = "SELECT b.*, l.name as library_name 
                  FROM books b 
                  JOIN libraries l ON b.library_id = l.id 
                  WHERE b.id = :book_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':book_id', $bookId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateCopies($bookId, $change) {
        $query = "UPDATE books SET 
                  available_copies = available_copies + :change 
                  WHERE id = :book_id AND available_copies + :change >= 0";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':change', $change);
        $stmt->bindParam(':book_id', $bookId);
        return $stmt->execute();
    }

    public function getCategoriesByLibrary($libraryId) {
        $query = "SELECT DISTINCT category FROM books 
                  WHERE library_id = :library_id AND category IS NOT NULL 
                  ORDER BY category";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':library_id', $libraryId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDashboardStats($libraryId) {
        $query = "SELECT 
                  COUNT(*) as total_books,
                  SUM(total_copies) as total_copies,
                  SUM(available_copies) as available_copies,
                  (SELECT COUNT(*) FROM borrows b 
                   JOIN books bk ON b.book_id = bk.id 
                   WHERE bk.library_id = :library_id AND b.status = 'borrowed') as borrowed_books,
                  (SELECT COUNT(*) FROM borrows b 
                   JOIN books bk ON b.book_id = bk.id 
                   WHERE bk.library_id = :library_id AND b.status = 'overdue') as overdue_books
                  FROM books 
                  WHERE library_id = :library_id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':library_id', $libraryId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>