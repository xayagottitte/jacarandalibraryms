<?php
class Book extends Model {
    protected $table = 'books';

    public function __construct() {
        parent::__construct();
    }

    public function getBooksByLibrary($libraryId, $filters = []) {
        $query = "SELECT b.*, l.name as library_name, l.type as library_type 
                  FROM books b 
                  LEFT JOIN libraries l ON b.library_id = l.id 
                  WHERE b.library_id = ?";
        
        $params = [$libraryId];

        // Add filters
        if (!empty($filters['search'])) {
            $query .= " AND (b.title LIKE ? OR b.author LIKE ? OR b.isbn LIKE ?)";
            $params[] = '%' . $filters['search'] . '%';
            $params[] = '%' . $filters['search'] . '%';
            $params[] = '%' . $filters['search'] . '%';
        }

        if (!empty($filters['category'])) {
            $query .= " AND b.category = ?";
            $params[] = $filters['category'];
        }

        if (!empty($filters['class_level'])) {
            $query .= " AND b.class_level = ?";
            $params[] = $filters['class_level'];
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
                  available_copies = available_copies + ? 
                  WHERE id = ? AND available_copies + ? >= 0";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$change, $bookId, $change]);
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
                   WHERE bk.library_id = ? AND b.status = 'borrowed') as borrowed_books,
                  (SELECT COUNT(*) FROM borrows b 
                   JOIN books bk ON b.book_id = bk.id 
                   WHERE bk.library_id = ? AND b.status = 'overdue') as overdue_books
                  FROM books 
                  WHERE library_id = ?";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute([$libraryId, $libraryId, $libraryId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getBookByISBN($isbn, $libraryId) {
        $query = "SELECT id, available_copies, title FROM books WHERE isbn = ? AND library_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$isbn, $libraryId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function checkISBNExists($isbn, $libraryId) {
        $query = "SELECT id FROM books WHERE isbn = ? AND library_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$isbn, $libraryId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getBorrowedCount($bookId) {
        $borrowQuery = "SELECT COUNT(*) as borrowed_count FROM borrows 
                       WHERE book_id = ? AND status IN ('borrowed', 'overdue')";
        $stmt = $this->db->prepare($borrowQuery);
        $stmt->execute([$bookId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateBook($data, $id, $libraryId) {
        $query = "UPDATE books SET 
                 title = ?, author = ?, isbn = ?, 
                 publisher = ?, publication_year = ?,
                 category = ?, class_level = ?, total_copies = ?, 
                 available_copies = ? 
                 WHERE id = ? AND library_id = ?";

        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            $data['title'], $data['author'], $data['isbn'],
            $data['publisher'], $data['publication_year'],
            $data['category'], $data['class_level'], $data['total_copies'],
            $data['available_copies'], $id, $libraryId
        ]);
    }

    public function checkActiveBorrows($bookId) {
        $checkQuery = "SELECT COUNT(*) as count FROM borrows 
                      WHERE book_id = ? AND status IN ('borrowed', 'overdue')";
        $stmt = $this->db->prepare($checkQuery);
        $stmt->execute([$bookId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteBook($id, $libraryId) {
        try {
            $deleteQuery = "DELETE FROM books WHERE id = ? AND library_id = ?";
            $stmt = $this->db->prepare($deleteQuery);
            $result = $stmt->execute([$id, $libraryId]);
            return $result && $stmt->rowCount() > 0;
        } catch (Exception $e) {
            return false;
        }
    }

    public function getClassLevelsForLibrary($libraryId) {
        // Get library type to determine available class levels
        $query = "SELECT type FROM libraries WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$libraryId]);
        $library = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$library) return [];
        
        if ($library['type'] === 'primary') {
            return ['1', '2', '3', '4', '5', '6', '7', '8'];
        } else { // secondary
            return ['1', '2', '3', '4'];
        }
    }

    public function getBooksByClass($libraryId, $classLevel) {
        $query = "SELECT * FROM books WHERE library_id = ? AND class_level = ? ORDER BY title";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$libraryId, $classLevel]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBooksByLibraryWithClass($libraryId, $filters = []) {
        $query = "SELECT b.*, l.name as library_name, l.type as library_type 
                  FROM books b 
                  LEFT JOIN libraries l ON b.library_id = l.id 
                  WHERE b.library_id = ?";
        
        $params = [$libraryId];

        // Add filters
        if (!empty($filters['search'])) {
            $query .= " AND (b.title LIKE ? OR b.author LIKE ? OR b.isbn LIKE ?)";
            $params[] = '%' . $filters['search'] . '%';
            $params[] = '%' . $filters['search'] . '%';
            $params[] = '%' . $filters['search'] . '%';
        }

        if (!empty($filters['category'])) {
            $query .= " AND b.category = ?";
            $params[] = $filters['category'];
        }

        if (!empty($filters['class_level'])) {
            $query .= " AND b.class_level = ?";
            $params[] = $filters['class_level'];
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

    public function getAllBooksWithLibrary($filters = []) {
        $query = "SELECT b.*, l.name as library_name, l.type as library_type 
                  FROM books b 
                  LEFT JOIN libraries l ON b.library_id = l.id 
                  WHERE 1=1";
        
        $params = [];

        // Add filters
        if (!empty($filters['search'])) {
            $query .= " AND (b.title LIKE ? OR b.author LIKE ? OR b.isbn LIKE ?)";
            $params[] = '%' . $filters['search'] . '%';
            $params[] = '%' . $filters['search'] . '%';
            $params[] = '%' . $filters['search'] . '%';
        }

        if (!empty($filters['category'])) {
            $query .= " AND b.category = ?";
            $params[] = $filters['category'];
        }

        if (!empty($filters['library_filter'])) {
            $query .= " AND b.library_id = ?";
            $params[] = $filters['library_filter'];
        }

        if (!empty($filters['status'])) {
            if ($filters['status'] === 'available') {
                $query .= " AND b.available_copies > 0";
            } elseif ($filters['status'] === 'unavailable') {
                $query .= " AND b.available_copies = 0";
            }
        }

        $query .= " ORDER BY l.name, b.title";

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBooksStatistics($libraryId = null) {
        $query = "SELECT 
                    COUNT(*) as total_books,
                    SUM(total_copies) as total_copies,
                    SUM(available_copies) as available_copies,
                    SUM(total_copies - available_copies) as borrowed_copies,
                    COUNT(DISTINCT library_id) as libraries_with_books
                  FROM books";
        
        $params = [];
        if ($libraryId) {
            $query .= " WHERE library_id = ?";
            $params[] = $libraryId;
        }
        
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getCategoryStatistics($libraryId = null) {
        $query = "SELECT 
                    b.category,
                    COUNT(*) as book_count,
                    SUM(b.total_copies) as total_copies,
                    SUM(b.available_copies) as available_copies,
                    l.name as library_name
                  FROM books b 
                  LEFT JOIN libraries l ON b.library_id = l.id 
                  WHERE b.category IS NOT NULL AND b.category != ''";
        
        $params = [];
        if ($libraryId) {
            $query .= " AND b.library_id = ?";
            $params[] = $libraryId;
        }
        
        $query .= " GROUP BY b.category, b.library_id, l.name
                  ORDER BY b.category, l.name";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPopularBooks($libraryId, $limit = 5) {
        $query = "SELECT 
                    b.title, 
                    b.class_level,
                    COUNT(br.id) as borrow_count
                  FROM borrows br
                  JOIN books b ON br.book_id = b.id
                  WHERE b.library_id = :library_id
                  GROUP BY b.id, b.title, b.class_level
                  ORDER BY borrow_count DESC
                  LIMIT :limit";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':library_id', $libraryId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUnderutilizedBooks($libraryId, $limit = 5) {
        $query = "SELECT 
                    b.title, 
                    b.class_level,
                    COALESCE(COUNT(br.id), 0) as borrow_count
                  FROM books b
                  LEFT JOIN borrows br ON br.book_id = b.id
                  WHERE b.library_id = :library_id 
                  AND (b.category = 'Educational' OR b.category = 'education')
                  GROUP BY b.id, b.title, b.class_level
                  ORDER BY borrow_count ASC, b.created_at DESC
                  LIMIT :limit";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':library_id', $libraryId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCategoryBorrowStats($libraryId) {
        $query = "SELECT 
                    b.category,
                    COUNT(DISTINCT br.id) as borrow_count
                  FROM books b
                  LEFT JOIN borrows br ON br.book_id = b.id
                  WHERE b.library_id = :library_id 
                  AND b.category IS NOT NULL 
                  AND b.category != ''
                  GROUP BY b.category
                  ORDER BY borrow_count DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':library_id', $libraryId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBookUtilizationStats($libraryId, $limit = 10) {
        $query = "SELECT 
                    b.title,
                    b.class_level,
                    b.total_copies,
                    b.available_copies,
                    (b.total_copies - b.available_copies) as borrowed_copies,
                    ROUND(((b.total_copies - b.available_copies) * 100.0 / b.total_copies), 1) as utilization_rate,
                    COUNT(br.id) as total_borrows
                  FROM books b
                  LEFT JOIN borrows br ON br.book_id = b.id
                  WHERE b.library_id = :library_id
                  AND b.total_copies > 0
                  GROUP BY b.id, b.title, b.class_level, b.total_copies, b.available_copies
                  ORDER BY utilization_rate DESC
                  LIMIT :limit";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':library_id', $libraryId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>