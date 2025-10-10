<?php
class LibrarianController extends Controller {
    private $userModel;
    private $bookModel;
    private $studentModel;
    private $borrowModel;
    private $libraryModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'librarian') {
            $this->redirect('/login');
        }
        $this->userModel = new User();
        $this->bookModel = new Book();
        $this->studentModel = new Student();
        $this->borrowModel = new Borrow();
        $this->libraryModel = new Library();
    }

    public function dashboard() {
        $libraryId = $_SESSION['library_id'];
        
        // Update overdue status first
        $this->borrowModel->updateOverdueStatus();

        $data = [
            'book_stats' => $this->bookModel->getDashboardStats($libraryId),
            'student_count' => count($this->studentModel->getStudentsByLibrary($libraryId)),
            'recent_borrows' => $this->borrowModel->getBorrowsByLibrary($libraryId, ['status' => 'borrowed']),
            'overdue_books' => $this->borrowModel->getOverdueBorrows($libraryId),
            'library' => $this->libraryModel->find($libraryId)
        ];
        
        $this->view('librarian/dashboard', $data);
    }

    // Book Management Methods
    public function books() {
        $libraryId = $_SESSION['library_id'];
        $filters = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $filters = [
                'search' => $_POST['search'] ?? '',
                'category' => $_POST['category'] ?? '',
                'status' => $_POST['status'] ?? ''
            ];
        }

        $data = [
            'books' => $this->bookModel->getBooksByLibrary($libraryId, $filters),
            'categories' => $this->bookModel->getCategoriesByLibrary($libraryId),
            'filters' => $filters
        ];
        
        $this->view('librarian/books', $data);
    }

    public function createBook() {
        $libraryId = $_SESSION['library_id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => $_POST['title'],
                'author' => $_POST['author'],
                'isbn' => $_POST['isbn'] ?? null,
                'publisher' => $_POST['publisher'] ?? null,
                'publication_year' => $_POST['publication_year'] ?? null,
                'category' => $_POST['category'] ?? null,
                'total_copies' => $_POST['total_copies'] ?? 1,
                'available_copies' => $_POST['total_copies'] ?? 1,
                'library_id' => $libraryId,
                'created_by' => $_SESSION['user_id']
            ];

            // Check if ISBN already exists
            if (!empty($data['isbn'])) {
                $query = "SELECT id FROM books WHERE isbn = :isbn AND library_id = :library_id";
                $stmt = $this->bookModel->db->prepare($query);
                $stmt->bindParam(':isbn', $data['isbn']);
                $stmt->bindParam(':library_id', $libraryId);
                $stmt->execute();
                
                if ($stmt->fetch(PDO::FETCH_ASSOC)) {
                    $_SESSION['error'] = "A book with this ISBN already exists in your library.";
                    $this->redirect('/librarian/create-book');
                    return;
                }
            }

            if ($this->bookModel->create($data)) {
                $_SESSION['success'] = "Book added successfully!";
                $this->redirect('/librarian/books');
                return;
            } else {
                $_SESSION['error'] = "Failed to add book.";
            }
        }

        $data = [
            'categories' => $this->bookModel->getCategoriesByLibrary($libraryId)
        ];
        $this->view('librarian/create-book', $data);
    }

    public function editBook($id = null) {
        $libraryId = $_SESSION['library_id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $data = [
                'title' => $_POST['title'],
                'author' => $_POST['author'],
                'isbn' => $_POST['isbn'] ?? null,
                'publisher' => $_POST['publisher'] ?? null,
                'publication_year' => $_POST['publication_year'] ?? null,
                'category' => $_POST['category'] ?? null,
                'total_copies' => $_POST['total_copies'] ?? 1
            ];

            // Calculate available copies based on current borrows
            $borrowQuery = "SELECT COUNT(*) as borrowed_count FROM borrows 
                           WHERE book_id = :book_id AND status IN ('borrowed', 'overdue')";
            $borrowStmt = $this->bookModel->db->prepare($borrowQuery);
            $borrowStmt->bindParam(':book_id', $id);
            $borrowStmt->execute();
            $borrowCount = $borrowStmt->fetch(PDO::FETCH_ASSOC);

            $data['available_copies'] = max(0, $data['total_copies'] - $borrowCount['borrowed_count']);

            $query = "UPDATE books SET 
                     title = :title, author = :author, isbn = :isbn, 
                     publisher = :publisher, publication_year = :publication_year,
                     category = :category, total_copies = :total_copies, 
                     available_copies = :available_copies 
                     WHERE id = :id AND library_id = :library_id";

            $stmt = $this->bookModel->db->prepare($query);
            $stmt->bindParam(':title', $data['title']);
            $stmt->bindParam(':author', $data['author']);
            $stmt->bindParam(':isbn', $data['isbn']);
            $stmt->bindParam(':publisher', $data['publisher']);
            $stmt->bindParam(':publication_year', $data['publication_year']);
            $stmt->bindParam(':category', $data['category']);
            $stmt->bindParam(':total_copies', $data['total_copies']);
            $stmt->bindParam(':available_copies', $data['available_copies']);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':library_id', $libraryId);

            if ($stmt->execute()) {
                $_SESSION['success'] = "Book updated successfully!";
            } else {
                $_SESSION['error'] = "Failed to update book.";
            }
            
            $this->redirect('/librarian/books');
            return;
        }

        // GET request - show edit form
        $book = $this->bookModel->getBookWithLibrary($id);
        if (!$book || $book['library_id'] != $libraryId) {
            $_SESSION['error'] = "Book not found or access denied.";
            $this->redirect('/librarian/books');
            return;
        }

        $data = [
            'book' => $book,
            'categories' => $this->bookModel->getCategoriesByLibrary($libraryId)
        ];
        $this->view('librarian/edit-book', $data);
    }

    public function deleteBook() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $libraryId = $_SESSION['library_id'];
            
            // Check if book has active borrows
            $checkQuery = "SELECT COUNT(*) as count FROM borrows 
                          WHERE book_id = :book_id AND status IN ('borrowed', 'overdue')";
            $stmt = $this->bookModel->db->prepare($checkQuery);
            $stmt->bindParam(':book_id', $id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result['count'] > 0) {
                $_SESSION['error'] = "Cannot delete book. It has active borrows.";
            } else {
                $deleteQuery = "DELETE FROM books WHERE id = :id AND library_id = :library_id";
                $deleteStmt = $this->bookModel->db->prepare($deleteQuery);
                $deleteStmt->bindParam(':id', $id);
                $deleteStmt->bindParam(':library_id', $libraryId);
                
                if ($deleteStmt->execute()) {
                    $_SESSION['success'] = "Book deleted successfully!";
                } else {
                    $_SESSION['error'] = "Failed to delete book.";
                }
            }
        }
        $this->redirect('/librarian/books');
    }

    // Student Management Methods
    public function students() {
        $libraryId = $_SESSION['library_id'];
        $studentModel = new Student();
        $filters = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $filters = [
                'search' => $_POST['search'] ?? '',
                'class' => $_POST['class'] ?? '',
                'status' => $_POST['status'] ?? ''
            ];
        }

        $data = [
            'students' => $studentModel->getStudentsByLibrary($libraryId, $filters),
            'classes' => $studentModel->getClassesByLibrary($libraryId),
            'filters' => $filters,
            'library' => (new Library())->find($libraryId)
        ];
        
        $this->view('librarian/students', $data);
    }

    public function createStudent() {
        $libraryId = $_SESSION['library_id'];
        $studentModel = new Student();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $class = $_POST['class'];
            
            // Validate class based on library type
            if (!$studentModel->validateClassForLibrary($libraryId, $class)) {
                $library = (new Library())->find($libraryId);
                $validRange = $library['type'] === 'primary' ? '1-8' : '1-4';
                $_SESSION['error'] = "Invalid class for {$library['type']} library. Valid classes: {$validRange}";
                $this->redirect('/librarian/create-student');
                return;
            }

            $data = [
                'student_id' => $studentModel->generateStudentId($libraryId),
                'full_name' => $_POST['full_name'],
                'email' => $_POST['email'] ?? null,
                'phone' => $_POST['phone'] ?? null,
                'class' => $class,
                'section' => $_POST['section'] ?? null,
                'library_id' => $libraryId,
                'created_by' => $_SESSION['user_id'],
                'status' => 'active'
            ];

            if ($studentModel->create($data)) {
                $_SESSION['success'] = "Student added successfully! Student ID: " . $data['student_id'];
                $this->redirect('/librarian/students');
                return;
            } else {
                $_SESSION['error'] = "Failed to add student.";
            }
        }

        $this->view('librarian/create-student');
    }

    public function viewStudent($id = null) {
        $studentModel = new Student();
        $borrowModel = new Borrow();
        
        $student = $studentModel->getStudentWithBorrows($id);
        
        if (!$student || $student['library_id'] != $_SESSION['library_id']) {
            $_SESSION['error'] = "Student not found or access denied.";
            $this->redirect('/librarian/students');
            return;
        }

        $data = [
            'student' => $student,
            'active_borrows' => $borrowModel->getActiveBorrowsByStudent($id)
        ];
        $this->view('librarian/view-student', $data);
    }

    public function editStudent($id = null) {
        $libraryId = $_SESSION['library_id'];
        $studentModel = new Student();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $class = $_POST['class'];
            
            // Validate class based on library type
            if (!$studentModel->validateClassForLibrary($libraryId, $class)) {
                $library = (new Library())->find($libraryId);
                $validRange = $library['type'] === 'primary' ? '1-8' : '1-4';
                $_SESSION['error'] = "Invalid class for {$library['type']} library. Valid classes: {$validRange}";
                $this->redirect("/librarian/edit-student/$id");
                return;
            }

            $data = [
                'full_name' => $_POST['full_name'],
                'email' => $_POST['email'] ?? null,
                'phone' => $_POST['phone'] ?? null,
                'class' => $class,
                'section' => $_POST['section'] ?? null,
                'status' => $_POST['status'] ?? 'active'
            ];

            $query = "UPDATE students SET 
                     full_name = :full_name, email = :email, phone = :phone,
                     class = :class, section = :section, status = :status
                     WHERE id = :id AND library_id = :library_id";

            $stmt = $studentModel->db->prepare($query);
            $stmt->bindParam(':full_name', $data['full_name']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':phone', $data['phone']);
            $stmt->bindParam(':class', $data['class']);
            $stmt->bindParam(':section', $data['section']);
            $stmt->bindParam(':status', $data['status']);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':library_id', $libraryId);

            if ($stmt->execute()) {
                $_SESSION['success'] = "Student updated successfully!";
            } else {
                $_SESSION['error'] = "Failed to update student.";
            }
            
            $this->redirect('/librarian/students');
            return;
        }

        // GET request - show edit form
        $student = $studentModel->find($id);
        if (!$student || $student['library_id'] != $libraryId) {
            $_SESSION['error'] = "Student not found or access denied.";
            $this->redirect('/librarian/students');
            return;
        }

        $data = ['student' => $student];
        $this->view('librarian/edit-student', $data);
    }

    // Borrow Management Methods
    public function borrows() {
        $libraryId = $_SESSION['library_id'];
        $borrowModel = new Borrow();
        $filters = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $filters = [
                'status' => $_POST['status'] ?? '',
                'student_id' => $_POST['student_id'] ?? '',
                'book_title' => $_POST['book_title'] ?? ''
            ];
        }

        $data = [
            'borrows' => $borrowModel->getBorrowsByLibrary($libraryId, $filters),
            'borrow_stats' => $borrowModel->getBorrowStatistics($libraryId),
            'filters' => $filters
        ];
        
        $this->view('librarian/borrows', $data);
    }

    public function borrowBook() {
        $libraryId = $_SESSION['library_id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bookId = $_POST['book_id'] ?? null;
            $studentId = $_POST['student_id'] ?? null;
            $borrowModel = new Borrow();

            try {
                if ($borrowModel->borrowBook($bookId, $studentId, $_SESSION['user_id'])) {
                    $_SESSION['success'] = "Book borrowed successfully!";
                }
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
            }
            
            $this->redirect('/librarian/borrows');
            return;
        }

        // GET request - show borrow form
        $bookModel = new Book();
        $studentModel = new Student();
        
        $data = [
            'books' => $bookModel->getBooksByLibrary($libraryId, ['status' => 'available']),
            'students' => $studentModel->getStudentsByLibrary($libraryId)
        ];
        $this->view('librarian/borrow-book', $data);
    }

    public function returnBook() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $borrowId = $_POST['borrow_id'] ?? null;
            $borrowModel = new Borrow();

            try {
                $result = $borrowModel->returnBook($borrowId, $_SESSION['user_id']);
                
                if ($result['fine_amount'] > 0) {
                    $_SESSION['success'] = "Book '{$result['book_title']}' returned successfully! Fine amount: $" . $result['fine_amount'];
                } else {
                    $_SESSION['success'] = "Book '{$result['book_title']}' returned successfully!";
                }
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
            }
        }
        $this->redirect('/librarian/borrows');
    }

    public function quickBorrow() {
        $libraryId = $_SESSION['library_id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $studentIdInput = $_POST['student_id'] ?? '';
            $isbn = $_POST['isbn'] ?? '';
            $borrowModel = new Borrow();
            $studentModel = new Student();
            $bookModel = new Book();

            // Find student by student_id
            $student = $studentModel->getStudentByStudentId($studentIdInput, $libraryId);

            if (!$student) {
                $_SESSION['error'] = "Student not found with ID: " . $studentIdInput;
                $this->redirect('/librarian/quick-borrow');
                return;
            }

            // Find book by ISBN
            $bookQuery = "SELECT id, available_copies, title FROM books WHERE isbn = :isbn AND library_id = :library_id";
            $bookStmt = $bookModel->db->prepare($bookQuery);
            $bookStmt->bindParam(':isbn', $isbn);
            $bookStmt->bindParam(':library_id', $libraryId);
            $bookStmt->execute();
            $book = $bookStmt->fetch(PDO::FETCH_ASSOC);

            if (!$book) {
                $_SESSION['error'] = "Book not found with ISBN: " . $isbn;
                $this->redirect('/librarian/quick-borrow');
                return;
            }

            if ($book['available_copies'] <= 0) {
                $_SESSION['error'] = "Book '{$book['title']}' is not available for borrowing.";
                $this->redirect('/librarian/quick-borrow');
                return;
            }

            try {
                if ($borrowModel->borrowBook($book['id'], $student['id'], $_SESSION['user_id'])) {
                    $_SESSION['success'] = "Book '{$book['title']}' borrowed successfully to {$student['full_name']}!";
                }
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
            }
            
            $this->redirect('/librarian/borrows');
            return;
        }

        $this->view('librarian/quick-borrow');
    }

    // Enhanced Reports Methods
    public function reports() {
        $libraryId = $_SESSION['library_id'];
        $reportModel = new Report();
        $bookModel = new Book();
        $studentModel = new Student();
        
        $data = [
            'book_stats' => $reportModel->getLibraryStatistics($libraryId),
            'categories' => $bookModel->getCategoriesByLibrary($libraryId),
            'classes' => $studentModel->getClassesByLibrary($libraryId),
            'saved_reports' => (new Report())->getUserReports($_SESSION['user_id'])
        ];
        
        $this->view('librarian/reports', $data);
    }

    public function generateLibraryReport() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $libraryId = $_SESSION['library_id'];
            $reportModel = new Report();
            $type = $_POST['report_type'] ?? '';
            $filters = json_decode($_POST['filters'] ?? '{}', true);
            
            switch ($type) {
                case 'books':
                    $data = $reportModel->generateBooksReport($libraryId, $filters);
                    break;
                case 'students':
                    $data = $reportModel->generateStudentsReport($libraryId, $filters);
                    break;
                case 'borrows':
                    $data = $reportModel->generateBorrowingReport($libraryId, $filters);
                    break;
                case 'financial':
                    $data = $reportModel->generateFinancialReport($libraryId, $filters);
                    break;
                default:
                    $data = [];
            }

            // Save report record
            $reportModel->saveReport([
                'title' => $_POST['report_title'] ?? 'Library Report',
                'type' => $type,
                'generated_by' => $_SESSION['user_id'],
                'library_id' => $libraryId,
                'date_range_start' => $filters['start_date'] ?? null,
                'date_range_end' => $filters['end_date'] ?? null,
                'filters' => json_encode($filters)
            ]);

            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'data' => $data]);
            exit;
        }
    }
}
?>