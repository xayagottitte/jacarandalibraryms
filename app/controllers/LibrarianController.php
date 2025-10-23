<?php
class LibrarianController extends Controller {
    private $userModel;
    private $bookModel;
    private $studentModel;
    private $borrowModel;
    private $libraryModel;

    public function __construct() {
        // Session is already started in index.php
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
            'library' => $this->libraryModel->find($libraryId),
            'popular_books' => $this->bookModel->getPopularBooks($libraryId, 5),
            'underutilized_books' => $this->bookModel->getUnderutilizedBooks($libraryId, 5),
            'class_borrow_stats' => $this->studentModel->getClassBorrowStats($libraryId),
            'borrowing_trends' => $this->borrowModel->getBorrowingTrends($libraryId, 30),
            'at_risk_students' => $this->studentModel->getAtRiskStudents($libraryId)
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

    public function viewBook($id = null) {
        $libraryId = $_SESSION['library_id'];
        
        // Get book ID from GET or POST
        if (!$id && isset($_GET['id'])) {
            $id = $_GET['id'];
        }
        
        if (!$id) {
            $_SESSION['error'] = "Book ID is required.";
            $this->redirect('/librarian/books');
            return;
        }

        $book = $this->bookModel->getBookWithLibrary($id);
        if (!$book || $book['library_id'] != $libraryId) {
            $_SESSION['error'] = "Book not found or access denied.";
            $this->redirect('/librarian/books');
            return;
        }

        $data = [
            'book' => $book,
            'borrow_history' => $this->borrowModel->getBookBorrowHistory($id),
            'library' => $this->libraryModel->find($libraryId)
        ];
        
        $this->view('librarian/view-book', $data);
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
                'class_level' => $_POST['class_level'] ?? null,
                'total_copies' => $_POST['total_copies'] ?? 1,
                'available_copies' => $_POST['total_copies'] ?? 1,
                'library_id' => $libraryId,
                'created_by' => $_SESSION['user_id']
            ];

            // Check if ISBN already exists
            if (!empty($data['isbn'])) {
                if ($this->bookModel->checkISBNExists($data['isbn'], $libraryId)) {
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

        $library = $this->libraryModel->find($libraryId);
        $data = [
            'categories' => $this->bookModel->getCategoriesByLibrary($libraryId),
            'class_levels' => $this->bookModel->getClassLevelsForLibrary($libraryId),
            'library' => $library
        ];
        $this->view('librarian/create-book', $data);
    }

    public function editBook($id = null) {
        $libraryId = $_SESSION['library_id'];

        // Get book ID from GET parameters if not provided as method parameter
        if (!$id && isset($_GET['id'])) {
            $id = $_GET['id'];
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $data = [
                'title' => $_POST['title'],
                'author' => $_POST['author'],
                'isbn' => $_POST['isbn'] ?? null,
                'publisher' => $_POST['publisher'] ?? null,
                'publication_year' => $_POST['publication_year'] ?? null,
                'category' => $_POST['category'] ?? null,
                'class_level' => $_POST['class_level'] ?? null,
                'total_copies' => $_POST['total_copies'] ?? 1
            ];

            // Calculate available copies based on current borrows
            $borrowCount = $this->bookModel->getBorrowedCount($id);
            $data['available_copies'] = max(0, $data['total_copies'] - $borrowCount['borrowed_count']);

            if ($this->bookModel->updateBook($data, $id, $libraryId)) {
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

        $library = $this->libraryModel->find($libraryId);
        $data = [
            'book' => $book,
            'categories' => $this->bookModel->getCategoriesByLibrary($libraryId),
            'class_levels' => $this->bookModel->getClassLevelsForLibrary($libraryId),
            'library' => $library
        ];
        $this->view('librarian/edit-book', $data);
    }

    public function deleteBook() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $libraryId = $_SESSION['library_id'];
            
            if (!$id) {
                $_SESSION['error'] = "Book ID is required for deletion.";
                $this->redirect('/librarian/books');
                return;
            }
            
            // Verify the book belongs to this library
            $book = $this->bookModel->getBookWithLibrary($id);
            
            if (!$book || $book['library_id'] != $libraryId) {
                $_SESSION['error'] = "Book not found or access denied.";
                $this->redirect('/librarian/books');
                return;
            }
            
            // Check if book has active borrows
            $result = $this->bookModel->checkActiveBorrows($id);

            if ($result['count'] > 0) {
                $_SESSION['error'] = "Cannot delete book '{$book['title']}'. It has {$result['count']} active borrow(s).";
            } else {
                $deleteResult = $this->bookModel->deleteBook($id, $libraryId);
                
                if ($deleteResult) {
                    $_SESSION['success'] = "Book '{$book['title']}' deleted successfully!";
                } else {
                    $_SESSION['error'] = "Failed to delete book '{$book['title']}'. Database error occurred.";
                }
            }
        } else {
            $_SESSION['error'] = "Invalid request method for book deletion.";
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
        
        // Get student ID from GET parameter if not provided as method parameter
        if (!$id && isset($_GET['id'])) {
            $id = $_GET['id'];
        }
        
        if (!$id) {
            $_SESSION['error'] = "Student ID is required.";
            $this->redirect('/librarian/students');
            return;
        }
        
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

        // Get student ID from GET parameter if not provided as method parameter
        if (!$id && isset($_GET['id'])) {
            $id = $_GET['id'];
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $class = $_POST['class'];
            
            // Validate class based on library type
            if (!$studentModel->validateClassForLibrary($libraryId, $class)) {
                $library = (new Library())->find($libraryId);
                $validRange = $library['type'] === 'primary' ? '1-8' : '1-4';
                $_SESSION['error'] = "Invalid class for {$library['type']} library. Valid classes: {$validRange}";
                $this->redirect("/librarian/edit-student?id=$id");
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

            if ($studentModel->updateStudent($id, $data, $libraryId)) {
                $_SESSION['success'] = "Student updated successfully!";
            } else {
                $_SESSION['error'] = "Failed to update student.";
            }
            
            $this->redirect('/librarian/students');
            return;
        }

        // GET request - show edit form
        if (!$id) {
            $_SESSION['error'] = "Student ID is required.";
            $this->redirect('/librarian/students');
            return;
        }
        
        $student = $studentModel->find($id);
        if (!$student || $student['library_id'] != $libraryId) {
            $_SESSION['error'] = "Student not found or access denied.";
            $this->redirect('/librarian/students');
            return;
        }

        $library = $this->libraryModel->find($libraryId);
        $data = [
            'student' => $student,
            'library' => $library
        ];
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
        $libraryModel = new Library();
        
        $data = [
            'books' => $bookModel->getBooksByLibrary($libraryId, ['status' => 'available']),
            'students' => $studentModel->getStudentsByLibrary($libraryId),
            'loan_period' => $libraryModel->getLoanPeriod($libraryId)
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
                    $_SESSION['success'] = "Book '{$result['book_title']}' returned successfully! Fine amount: MK " . number_format($result['fine_amount']);
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
            $book = $bookModel->getBookByISBN($isbn, $libraryId);

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
            'book_stats' => $bookModel->getDashboardStats($libraryId),
            'categories' => $bookModel->getCategoriesByLibrary($libraryId),
            'classes' => $studentModel->getClassesByLibrary($libraryId),
            'saved_reports' => $reportModel->getUserReports($_SESSION['user_id']),
            'category_stats' => $bookModel->getCategoryBorrowStats($libraryId),
            'overdue_stats' => $this->borrowModel->getOverdueBookStats($libraryId),
            'financial_stats' => $this->borrowModel->getFinancialStats($libraryId, 30),
            'utilization_stats' => $bookModel->getBookUtilizationStats($libraryId, 10)
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
            if (!empty($_POST['report_title'])) {
                $reportModel->saveReport([
                    'title' => $_POST['report_title'],
                    'type' => $type,
                    'generated_by' => $_SESSION['user_id'],
                    'library_id' => $libraryId,
                    'date_range_start' => $filters['start_date'] ?? null,
                    'date_range_end' => $filters['end_date'] ?? null,
                    'filters' => json_encode($filters)
                ]);
            }

            // Instead of echoing JSON, load a view to generate a CSV
            $this->view('librarian/report-csv', [
                'title' => $_POST['report_title'] ?? 'Generated Report',
                'type' => $type,
                'data' => $data
            ]);
            exit;
        }
    }
}
?>