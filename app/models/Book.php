<?php
class Book extends Model {
    public function create($data) {
        $query = "INSERT INTO books (title, author, isbn, publisher, publication_year, category_id, cover_image, class_level, total_copies, available_copies, library_id, created_by, created_at) VALUES (:title, :author, :isbn, :publisher, :publication_year, :category_id, :cover_image, :class_level, :total_copies, :available_copies, :library_id, :created_by, NOW())";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':author', $data['author']);
        $stmt->bindParam(':isbn', $data['isbn']);
        $stmt->bindParam(':publisher', $data['publisher']);
        $stmt->bindParam(':publication_year', $data['publication_year']);
        $stmt->bindParam(':category_id', $data['category_id']);
        $stmt->bindParam(':cover_image', $data['cover_image']);
        $stmt->bindParam(':class_level', $data['class_level']);
        $stmt->bindParam(':total_copies', $data['total_copies']);
        $stmt->bindParam(':available_copies', $data['available_copies']);
        $stmt->bindParam(':library_id', $data['library_id']);
        $stmt->bindParam(':created_by', $data['created_by']);
        
        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }
    protected $table = 'books';

    public function __construct() {
        parent::__construct();
    }

    // Override find method to exclude soft-deleted books by default
    public function find($id, $includeDeleted = false) {
        $query = "SELECT * FROM books WHERE id = :id";
        if (!$includeDeleted) {
            $query .= " AND deleted_at IS NULL";
        }
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getBooksByLibrary($libraryId, $filters = []) {
        $query = "SELECT b.*, l.name as library_name, l.type as library_type, c.name as category_name 
                  FROM books b 
                  LEFT JOIN libraries l ON b.library_id = l.id 
                  LEFT JOIN categories c ON b.category_id = c.id
                  WHERE b.library_id = ? AND b.deleted_at IS NULL";
        
        $params = [$libraryId];

        // Add filters
        if (!empty($filters['search'])) {
            $query .= " AND (b.title LIKE ? OR b.author LIKE ? OR b.isbn LIKE ?)";
            $params[] = '%' . $filters['search'] . '%';
            $params[] = '%' . $filters['search'] . '%';
            $params[] = '%' . $filters['search'] . '%';
        }

        if (!empty($filters['category'])) {
            $query .= " AND b.category_id = ?";
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
        $query = "SELECT b.*, l.name as library_name, l.type as library_type 
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
        $query = "SELECT c.id, c.name, COUNT(b.id) as book_count 
                  FROM categories c
                  LEFT JOIN books b ON c.id = b.category_id AND b.library_id = c.library_id
                  WHERE c.library_id = :library_id 
                  GROUP BY c.id, c.name
                  ORDER BY c.name";
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
                   WHERE bk.library_id = ? AND b.status IN ('borrowed','overdue')) as borrowed_books,
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
                 category_id = ?, cover_image = ?, class_level = ?, total_copies = ?, 
                 available_copies = ? 
                 WHERE id = ? AND library_id = ?";

        $stmt = $this->db->prepare($query);
        $result = $stmt->execute([
            $data['title'], $data['author'], $data['isbn'],
            $data['publisher'], $data['publication_year'],
            $data['category_id'], $data['cover_image'], $data['class_level'], $data['total_copies'],
            $data['available_copies'], $id, $libraryId
        ]);
        if ($result) {
            \Security::logActivity($data['updated_by'] ?? null, 'book_update', 'data', 'Updated book ID ' . $id);
        }
        return $result;
    }

    public function checkActiveBorrows($bookId) {
        $checkQuery = "SELECT COUNT(*) as count FROM borrows 
                      WHERE book_id = ? AND status IN ('borrowed', 'overdue')";
        $stmt = $this->db->prepare($checkQuery);
        $stmt->execute([$bookId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteBook($id, $libraryId, $deletedBy = null) {
        try {
            // Soft delete - mark as deleted instead of removing from database
            $query = "UPDATE books SET deleted_at = NOW(), deleted_by = ? WHERE id = ? AND library_id = ? AND deleted_at IS NULL";
            $stmt = $this->db->prepare($query);
            $result = $stmt->execute([$deletedBy, $id, $libraryId]);
            $rowsUpdated = $stmt->rowCount();
            
            if ($rowsUpdated > 0) {
                // Log the deletion
                $userId = $_SESSION['user_id'] ?? 0;
                \Security::logActivity($userId, 'book_delete', 'data', "Soft deleted book ID {$id} from library {$libraryId}");
                return true;
            } else {
                error_log("Book soft deletion failed: No book found with ID {$id} in library {$libraryId} or already deleted");
                return false;
            }
            
        } catch (PDOException $e) {
            error_log("Book soft deletion error: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            return false;
        }
    }

    public function getDeletedBooks($libraryId = null) {
        $query = "SELECT b.*, l.name as library_name, c.name as category_name, 
                  u.full_name as deleted_by_name, b.deleted_at
                  FROM books b 
                  LEFT JOIN libraries l ON b.library_id = l.id 
                  LEFT JOIN categories c ON b.category_id = c.id
                  LEFT JOIN users u ON b.deleted_by = u.id
                  WHERE b.deleted_at IS NOT NULL";
        
        $params = [];
        if ($libraryId) {
            $query .= " AND b.library_id = ?";
            $params[] = $libraryId;
        }
        
        $query .= " ORDER BY b.deleted_at DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function restoreBook($id) {
        try {
            $query = "UPDATE books SET deleted_at = NULL, deleted_by = NULL WHERE id = ? AND deleted_at IS NOT NULL";
            $stmt = $this->db->prepare($query);
            $result = $stmt->execute([$id]);
            $rowsUpdated = $stmt->rowCount();
            
            if ($rowsUpdated > 0) {
                // Log the restoration
                $userId = $_SESSION['user_id'] ?? 0;
                \Security::logActivity($userId, 'book_restore', 'data', "Restored book ID {$id}");
                return true;
            }
            return false;
            
        } catch (PDOException $e) {
            error_log("Book restoration error: " . $e->getMessage());
            return false;
        }
    }

    public function permanentlyDeleteBook($id) {
        try {
            // Use a transaction for permanent deletion
            $this->db->beginTransaction();
            
            // Delete all borrow records first
            $deleteBorrowsQuery = "DELETE FROM borrows WHERE book_id = ?";
            $stmt = $this->db->prepare($deleteBorrowsQuery);
            $stmt->execute([$id]);
            $deletedBorrows = $stmt->rowCount();
            
            // Permanently delete the book
            $query = "DELETE FROM books WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $result = $stmt->execute([$id]);
            $rowsDeleted = $stmt->rowCount();
            
            if ($rowsDeleted > 0) {
                $this->db->commit();
                $userId = $_SESSION['user_id'] ?? 0;
                \Security::logActivity($userId, 'book_permanent_delete', 'data', "Permanently deleted book ID {$id} (removed {$deletedBorrows} borrow records)");
                return true;
            } else {
                $this->db->rollBack();
                return false;
            }
            
        } catch (PDOException $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            error_log("Book permanent deletion error: " . $e->getMessage());
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
            $query .= " AND b.category_id = ?";
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
            $query .= " AND b.category_id = ?";
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
                    c.name as category,
                    COUNT(*) as book_count,
                    SUM(b.total_copies) as total_copies,
                    SUM(b.available_copies) as available_copies,
                    l.name as library_name
                  FROM books b 
                  LEFT JOIN libraries l ON b.library_id = l.id 
                  LEFT JOIN categories c ON b.category_id = c.id
                  WHERE b.category_id IS NOT NULL";
        
        $params = [];
        if ($libraryId) {
            $query .= " AND b.library_id = ?";
            $params[] = $libraryId;
        }
        
        $query .= " GROUP BY c.id, c.name, b.library_id, l.name
                  ORDER BY c.name, l.name";
        
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
                  LEFT JOIN categories c ON b.category_id = c.id
                  WHERE b.library_id = :library_id 
                  AND (c.name = 'Educational' OR c.name = 'education')
                  GROUP BY b.id, b.title, b.class_level
                  ORDER BY borrow_count ASC, b.created_at DESC
                  LIMIT :limit";        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':library_id', $libraryId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCategoryBorrowStats($libraryId) {
        $query = "SELECT 
                    c.name as category,
                    COUNT(DISTINCT br.id) as borrow_count
                  FROM books b
                  LEFT JOIN borrows br ON br.book_id = b.id
                  LEFT JOIN categories c ON b.category_id = c.id
                  WHERE b.library_id = :library_id 
                  AND b.category_id IS NOT NULL 
                  GROUP BY c.id, c.name
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

    public function uploadBookCover($file, $bookId) {
        try {
            $uploadDir = __DIR__ . '/../../public/assets/img/books/';
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $maxFileSize = 2 * 1024 * 1024; // 2MB
            
            // Validate file
            if (!in_array($file['type'], $allowedTypes)) {
                throw new Exception('Invalid file type. Please upload JPEG, PNG, GIF, or WebP images only.');
            }
            
            if ($file['size'] > $maxFileSize) {
                throw new Exception('File size too large. Maximum size is 2MB.');
            }
            
            // Generate unique filename
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = 'book_' . $bookId . '_' . time() . '.' . $extension;
            $filepath = $uploadDir . $filename;
            
            // Create directory if it doesn't exist
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            // Move uploaded file
            if (move_uploaded_file($file['tmp_name'], $filepath)) {
                // Update book record with new cover image
                $query = "UPDATE books SET cover_image = ? WHERE id = ?";
                $stmt = $this->db->prepare($query);
                $result = $stmt->execute([$filename, $bookId]);
                
                if ($result) {
                    return [
                        'success' => true,
                        'filename' => $filename,
                        'message' => 'Book cover uploaded successfully'
                    ];
                } else {
                    // Remove file if database update failed
                    unlink($filepath);
                    throw new Exception('Failed to update book record');
                }
            } else {
                throw new Exception('Failed to upload file');
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function getBookCoverUrl($coverImage) {
        if (!empty($coverImage)) {
            $filePath = __DIR__ . '/../../public/assets/img/books/' . $coverImage;
            if (file_exists($filePath)) {
                return '/public/assets/img/books/' . $coverImage;
            }
        }
        return '/public/assets/img/books/default-cover.svg';
    }

    public function getTotalBooksCount($libraryId = null) {
        $query = "SELECT COUNT(*) as count FROM books";
        
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

    public function getAvailableBooksCount($libraryId = null) {
        $query = "SELECT SUM(available_copies) as count FROM books";
        
        if ($libraryId) {
            $query .= " WHERE library_id = :library_id";
        }
        
        $stmt = $this->db->prepare($query);
        if ($libraryId) {
            $stmt->bindParam(':library_id', $libraryId);
        }
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] ?? 0;
    }

    public function getTotalCopiesCount($libraryId = null) {
        $query = "SELECT SUM(total_copies) as count FROM books";
        
        if ($libraryId) {
            $query .= " WHERE library_id = :library_id";
        }
        
        $stmt = $this->db->prepare($query);
        if ($libraryId) {
            $stmt->bindParam(':library_id', $libraryId);
        }
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] ?? 0;
    }
}
?>