<?php
class LibrarianController extends Controller {
    private $userModel;
    private $bookModel;
    private $studentModel;
    private $borrowModel;
    private $libraryModel;
    private $categoryModel;

    public function __construct() {
        // Session is already started in index.php
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'librarian') {
            $this->redirect(BASE_PATH . '/login');
        }
        $this->userModel = new User();
        $this->bookModel = new Book();
        $this->studentModel = new Student();
        $this->borrowModel = new Borrow();
        $this->libraryModel = new Library();
        require_once __DIR__ . '/../models/Category.php';
        $this->categoryModel = new Category();
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
            'at_risk_students' => $this->studentModel->getAtRiskStudents($libraryId),
            'lost_count' => $this->borrowModel->getLostCounts($libraryId, 30),
            'lost_books' => $this->borrowModel->getLostBooks($libraryId, 5, 30)
        ];
        
        $this->view('librarian/dashboard', $data);
    }

                    // Removed stray Security::logActivity outside function
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
            'categories' => $this->categoryModel->getCategoriesByLibrary($libraryId),
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
            $this->redirect(BASE_PATH . '/librarian/books');
            return;
        }

        $book = $this->bookModel->getBookWithLibrary($id);
        if (!$book || $book['library_id'] != $libraryId) {
            $_SESSION['error'] = "Book not found or access denied.";
            $this->redirect(BASE_PATH . '/librarian/books');
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
            // Verify CSRF token
            if (!isset($_POST['csrf_token']) || !Security::verifyCSRFToken($_POST['csrf_token'])) {
                $_SESSION['error'] = "Invalid security token. Please try again.";
                $this->redirect(BASE_PATH . '/librarian/create-book');
                return;
            }
            
            // Sanitize inputs
            $title = Security::sanitizeInput($_POST['title'] ?? '');
            $author = Security::sanitizeInput($_POST['author'] ?? '');
            $isbn = Security::sanitizeInput($_POST['isbn'] ?? '');
            $publisher = Security::sanitizeInput($_POST['publisher'] ?? '');
            $publicationYear = Security::sanitizeInput($_POST['publication_year'] ?? '');
            $classLevel = Security::sanitizeInput($_POST['class_level'] ?? '');
            $totalCopies = (int)($_POST['total_copies'] ?? 1);
            $categoryId = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
            
            // Validate required fields
            if (empty($title) || empty($author)) {
                $_SESSION['error'] = "Title and author are required.";
                $this->redirect(BASE_PATH . '/librarian/create-book');
                return;
            }
            
            // Validate total copies
            if ($totalCopies < 1 || $totalCopies > 10000) {
                $_SESSION['error'] = "Total copies must be between 1 and 10000.";
                $this->redirect(BASE_PATH . '/librarian/create-book');
                return;
            }
            
            // Validate publication year if provided
            if (!empty($publicationYear) && !Security::validateNumber($publicationYear, 1000, date('Y') + 1)) {
                $_SESSION['error'] = "Invalid publication year.";
                $this->redirect(BASE_PATH . '/librarian/create-book');
                return;
            }
            
            $data = [
                'title' => $title,
                'author' => $author,
                'isbn' => !empty($isbn) ? $isbn : null, null,
                'publisher' => !empty($publisher) ? $publisher : null,
                'publication_year' => !empty($publicationYear) ? $publicationYear : null,
                'category_id' => $categoryId,
                'cover_image' => null, // Will be updated after insert if file uploaded
                'class_level' => !empty($classLevel) ? $classLevel : null,
                'total_copies' => $totalCopies,
                'available_copies' => $totalCopies,
                'library_id' => $libraryId,
                'created_by' => $_SESSION['user_id']
            ];

            // Check if ISBN already exists
            if (!empty($data['isbn'])) {
                if ($this->bookModel->checkISBNExists($data['isbn'], $libraryId)) {
                    $_SESSION['error'] = "A book with this ISBN already exists in your library.";
                    $this->redirect(BASE_PATH . '/librarian/create-book');
                    return;
                }
            }

            $bookId = $this->bookModel->create($data);
            if ($bookId) {
                // Handle cover image upload with validation
                if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
                    // Validate file size
                    if ($_FILES['cover_image']['size'] > MAX_FILE_SIZE) {
                        $_SESSION['warning'] = "Book added successfully, but cover image is too large (max 5MB).";
                    } else {
                        // Validate file type
                        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                        $fileType = mime_content_type($_FILES['cover_image']['tmp_name']);
                        
                        if (!in_array($fileType, $allowedTypes)) {
                            $_SESSION['warning'] = "Book added successfully, but invalid cover image type. Only JPG, PNG, GIF allowed.";
                        } else {
                            $uploadResult = $this->bookModel->uploadBookCover($_FILES['cover_image'], $bookId);
                            if (!$uploadResult['success']) {
                                $_SESSION['warning'] = "Book added successfully, but cover image upload failed: " . Security::sanitizeInput($uploadResult['message']);
                            }
                        }
                    }
                }
                
                // Log book creation
                Security::logActivity(
                    $_SESSION['user_id'],
                    'book_created',
                    'data',
                    "Created book: {$title} by {$author}",
                    ['book_id' => $bookId, 'library_id' => $libraryId],
                    'info'
                );
                
                $_SESSION['success'] = "Book added successfully!";
                $this->redirect(BASE_PATH . '/librarian/books');
                return;
            } else {
                $_SESSION['error'] = "Failed to add book.";
            }
        }

        $library = $this->libraryModel->find($libraryId);
        $data = [
            'categories' => $this->categoryModel->getAllCategories(),
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
            
            // Extract and sanitize POST data
            $title = Security::sanitizeInput($_POST['title'] ?? '');
            $author = Security::sanitizeInput($_POST['author'] ?? '');
            $isbn = Security::sanitizeInput($_POST['isbn'] ?? '');
            $publisher = Security::sanitizeInput($_POST['publisher'] ?? '');
            $publicationYear = Security::sanitizeInput($_POST['publication_year'] ?? '');
            $categoryId = Security::sanitizeInput($_POST['category_id'] ?? '');
            $classLevel = Security::sanitizeInput($_POST['class_level'] ?? '');
            $totalCopies = intval($_POST['total_copies'] ?? 0);
            
            // Get current book data to preserve cover image if not updating
            $currentBook = $this->bookModel->find($id);
            $coverImage = $currentBook['cover_image'] ?? null;
            
            // Handle cover image upload with validation
            if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
                // Validate file size
                if ($_FILES['cover_image']['size'] > MAX_FILE_SIZE) {
                    $_SESSION['warning'] = "Cover image is too large (max 5MB).";
                } else {
                    // Validate file type
                    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                    $fileType = mime_content_type($_FILES['cover_image']['tmp_name']);
                    
                    if (!in_array($fileType, $allowedTypes)) {
                        $_SESSION['warning'] = "Invalid cover image type. Only JPG, PNG, GIF allowed.";
                    } else {
                        $uploadResult = $this->bookModel->uploadBookCover($_FILES['cover_image'], $id);
                        if ($uploadResult['success']) {
                            $coverImage = $uploadResult['filename'];
                        } else {
                            $_SESSION['warning'] = "Failed to upload cover image: " . Security::sanitizeInput($uploadResult['message']);
                        }
                    }
                }
            }
            
            $data = [
                'title' => $title,
                'author' => $author,
                'isbn' => !empty($isbn) ? $isbn : null,
                'publisher' => !empty($publisher) ? $publisher : null,
                'publication_year' => !empty($publicationYear) ? $publicationYear : null,
                'category_id' => $categoryId,
                'cover_image' => $coverImage,
                'class_level' => !empty($classLevel) ? $classLevel : null,
                'total_copies' => $totalCopies
            ];

            // Calculate available copies based on current borrows
            $borrowCount = $this->bookModel->getBorrowedCount($id);
            $data['available_copies'] = max(0, $data['total_copies'] - $borrowCount['borrowed_count']);

            if ($this->bookModel->updateBook($data, $id, $libraryId)) {
                // Log book update
                Security::logActivity(
                    $_SESSION['user_id'],
                    'book_updated',
                    'data',
                    "Updated book: {$title} (ID: {$id})",
                    ['book_id' => $id, 'library_id' => $libraryId],
                    'info'
                );
                $_SESSION['success'] = "Book updated successfully!";
            } else {
                $_SESSION['error'] = "Failed to update book.";
            }
            
            $this->redirect(BASE_PATH . '/librarian/books');
            return;
        }

        // GET request - show edit form
        $book = $this->bookModel->getBookWithLibrary($id);
        if (!$book || $book['library_id'] != $libraryId) {
            $_SESSION['error'] = "Book not found or access denied.";
            $this->redirect(BASE_PATH . '/librarian/books');
            return;
        }

        $library = $this->libraryModel->find($libraryId);
        $data = [
            'book' => $book,
            'categories' => $this->categoryModel->getAllCategories(),
            'class_levels' => $this->bookModel->getClassLevelsForLibrary($libraryId),
            'library' => $library
        ];
        $this->view('librarian/edit-book', $data);
    }

    public function deleteBook() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = "Invalid request method.";
            $this->redirect(BASE_PATH . '/librarian/books');
            return;
        }

        $bookId = $_POST['id'] ?? null;
        $password = $_POST['password'] ?? null;
        $libraryId = $_SESSION['library_id'] ?? null;
        $userId = $_SESSION['user_id'] ?? null;
        
        if (!$bookId) {
            $_SESSION['error'] = "Book ID is required.";
            $this->redirect(BASE_PATH . '/librarian/books');
            return;
        }

        if (!$password) {
            $_SESSION['error'] = "Password is required to delete a book.";
            $this->redirect(BASE_PATH . '/librarian/books');
            return;
        }

        if (!$libraryId) {
            $_SESSION['error'] = "Library ID not found in session.";
            $this->redirect(BASE_PATH . '/librarian/books');
            return;
        }

        try {
            // Verify password
            $userModel = new \User();
            $user = $userModel->find($userId);
            
            if (!$user || !password_verify($password, $user['password'])) {
                $_SESSION['error'] = "Invalid password. Book deletion requires password confirmation.";
                $this->redirect(BASE_PATH . '/librarian/books');
                return;
            }

            // Get book details
            $book = $this->bookModel->find($bookId);
            
            if (!$book) {
                $_SESSION['error'] = "Book not found.";
                $this->redirect(BASE_PATH . '/librarian/books');
                return;
            }

            // Verify library ownership
            if ($book['library_id'] != $libraryId) {
                $_SESSION['error'] = "Access denied. This book belongs to another library.";
                $this->redirect(BASE_PATH . '/librarian/books');
                return;
            }

            // Check if already deleted
            if ($book['deleted_at']) {
                $_SESSION['error'] = "This book has already been deleted.";
                $this->redirect(BASE_PATH . '/librarian/books');
                return;
            }

            // Check for active borrows
            $activeBorrows = $this->bookModel->checkActiveBorrows($bookId);
            
            if ($activeBorrows['count'] > 0) {
                $_SESSION['error'] = "Cannot delete '{$book['title']}'. It has {$activeBorrows['count']} active borrow(s).";
                $this->redirect(BASE_PATH . '/librarian/books');
                return;
            }

            // Soft delete the book
            $deleted = $this->bookModel->deleteBook($bookId, $libraryId, $userId);
            
            if ($deleted) {
                $_SESSION['success'] = "Book '{$book['title']}' has been deleted successfully.";
            } else {
                $_SESSION['error'] = "Failed to delete the book. Please try again.";
            }
            
        } catch (Exception $e) {
            $_SESSION['error'] = "An error occurred: " . $e->getMessage();
        }

        $this->redirect(BASE_PATH . '/librarian/books');
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
            // Verify CSRF token
            if (!isset($_POST['csrf_token']) || !Security::verifyCSRFToken($_POST['csrf_token'])) {
                $_SESSION['error'] = "Invalid security token. Please try again.";
                $this->redirect(BASE_PATH . '/librarian/create-student');
                return;
            }
            
            // Sanitize inputs
            $fullName = Security::sanitizeInput($_POST['full_name'] ?? '');
            $email = Security::sanitizeInput($_POST['email'] ?? '');
            $phone = Security::sanitizeInput($_POST['phone'] ?? '');
            $class = Security::sanitizeInput($_POST['class'] ?? '');
            $section = Security::sanitizeInput($_POST['section'] ?? '');
            
            // Validate required fields
            if (empty($fullName) || strlen($fullName) < 3) {
                $_SESSION['error'] = "Full name must be at least 3 characters long.";
                $this->redirect(BASE_PATH . '/librarian/create-student');
                return;
            }
            
            // Validate email if provided
            if (!empty($email) && !Security::validateEmail($email)) {
                $_SESSION['error'] = "Please enter a valid email address.";
                $this->redirect(BASE_PATH . '/librarian/create-student');
                return;
            }
            
            // Validate phone if provided
            if (!empty($phone) && !Security::validatePhone($phone)) {
                $_SESSION['error'] = "Please enter a valid phone number.";
                $this->redirect(BASE_PATH . '/librarian/create-student');
                return;
            }
            
            $class = $_POST['class'];
            
            // Validate class based on library type
            if (!$studentModel->validateClassForLibrary($libraryId, $class)) {
                $library = (new Library())->find($libraryId);
                $validRange = $library['type'] === 'primary' ? '1-8' : '1-4';
                $_SESSION['error'] = "Invalid class for {$library['type']} library. Valid classes: {$validRange}";
                $this->redirect(BASE_PATH . '/librarian/create-student');
                return;
            }

            $data = [
                'student_id' => $studentModel->generateStudentId($libraryId),
                'full_name' => $fullName,
                'email' => !empty($email) ? $email : null,
                'phone' => !empty($phone) ? $phone : null,
                'class' => $class,
                'section' => !empty($section) ? $section : null,
                'library_id' => $libraryId,
                'created_by' => $_SESSION['user_id'],
                'status' => 'active'
            ];

            if ($studentModel->create($data)) {
                // Log student creation
                Security::logActivity(
                    $_SESSION['user_id'],
                    'student_created',
                    'data',
                    "Created student: {$fullName} (ID: {$data['student_id']})",
                    ['student_id' => $data['student_id'], 'library_id' => $libraryId],
                    'info'
                );
                $_SESSION['success'] = "Student added successfully! Student ID: " . $data['student_id'];
                $this->redirect(BASE_PATH . '/librarian/students');
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
            $this->redirect(BASE_PATH . '/librarian/students');
            return;
        }
        
        $student = $studentModel->getStudentWithBorrows($id);
        
        if (!$student || $student['library_id'] != $_SESSION['library_id']) {
            $_SESSION['error'] = "Student not found or access denied.";
            $this->redirect(BASE_PATH . '/librarian/students');
            return;
        }

        $data = [
            'student' => $student,
            'active_borrows' => $borrowModel->getActiveBorrowsByStudent($id),
            'borrow_history' => $borrowModel->getBorrowHistoryByStudent($id)
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
            // Verify CSRF token
            if (!isset($_POST['csrf_token']) || !Security::verifyCSRFToken($_POST['csrf_token'])) {
                $_SESSION['error'] = "Invalid security token. Please try again.";
                $this->redirect(BASE_PATH . '/librarian/students');
                return;
            }
            
            $id = (int)($_POST['id'] ?? 0);
            
            // Verify student belongs to librarian's library
            $existingStudent = $studentModel->find($id);
            if (!$existingStudent || $existingStudent['library_id'] != $libraryId) {
                $_SESSION['error'] = "Student not found or access denied.";
                Security::logSecurity(
                    $_SESSION['user_id'],
                    'unauthorized_student_edit_attempt',
                    "Attempted to edit student {$id} from different library",
                    'warning'
                );
                $this->redirect(BASE_PATH . '/librarian/students');
                return;
            }
            
            // Sanitize inputs
            $fullName = Security::sanitizeInput($_POST['full_name'] ?? '');
            $email = Security::sanitizeInput($_POST['email'] ?? '');
            $phone = Security::sanitizeInput($_POST['phone'] ?? '');
            $class = Security::sanitizeInput($_POST['class'] ?? '');
            $section = Security::sanitizeInput($_POST['section'] ?? '');
            
            // Validate required fields
            if (empty($fullName) || strlen($fullName) < 3) {
                $_SESSION['error'] = "Full name must be at least 3 characters long.";
                $this->redirect(BASE_PATH . "/librarian/edit-student?id={$id}");
                return;
            }
            
            // Validate email if provided
            if (!empty($email) && !Security::validateEmail($email)) {
                $_SESSION['error'] = "Please enter a valid email address.";
                $this->redirect(BASE_PATH . "/librarian/edit-student?id={$id}");
                return;
            }
            
            // Validate phone if provided
            if (!empty($phone) && !Security::validatePhone($phone)) {
                $_SESSION['error'] = "Please enter a valid phone number.";
                $this->redirect(BASE_PATH . "/librarian/edit-student?id={$id}");
                return;
            }
            
            // Validate class based on library type
            if (!$studentModel->validateClassForLibrary($libraryId, $class)) {
                $library = (new Library())->find($libraryId);
                $validRange = $library['type'] === 'primary' ? '1-8' : '1-4';
                $_SESSION['error'] = "Invalid class for {$library['type']} library. Valid classes: {$validRange}";
                $this->redirect(BASE_PATH . "/librarian/edit-student?id=$id");
                return;
            }

            $data = [
                'full_name' => $fullName,
                'email' => !empty($email) ? $email : null,
                'phone' => !empty($phone) ? $phone : null,
                'class' => $class,
                'section' => !empty($section) ? $section : null,
                'status' => Security::sanitizeInput($_POST['status'] ?? 'active')
            ];

            if ($studentModel->updateStudent($id, $data, $libraryId)) {
                // Log student update
                Security::logActivity(
                    $_SESSION['user_id'],
                    'student_updated',
                    'data',
                    "Updated student: {$fullName} (ID: {$id})",
                    ['student_id' => $id, 'library_id' => $libraryId],
                    'info'
                );
                $_SESSION['success'] = "Student updated successfully!";
            } else {
                $_SESSION['error'] = "Failed to update student.";
            }
            
            $this->redirect(BASE_PATH . '/librarian/students');
            return;
        }

        // GET request - show edit form
        if (!$id) {
            $_SESSION['error'] = "Student ID is required.";
            $this->redirect(BASE_PATH . '/librarian/students');
            return;
        }
        
        $student = $studentModel->find($id);
        if (!$student || $student['library_id'] != $libraryId) {
            $_SESSION['error'] = "Student not found or access denied.";
            $this->redirect(BASE_PATH . '/librarian/students');
            return;
        }

        $library = $this->libraryModel->find($libraryId);
        $data = [
            'student' => $student,
            'library' => $library
        ];
        $this->view('librarian/edit-student', $data);
    }

    public function deactivateStudent() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(BASE_PATH . '/librarian/students');
            return;
        }

        // Verify CSRF token
        if (!isset($_POST['csrf_token']) || !Security::verifyCSRFToken($_POST['csrf_token'])) {
            $_SESSION['error'] = "Invalid security token. Please try again.";
            $this->redirect(BASE_PATH . '/librarian/students');
            return;
        }

        $libraryId = $_SESSION['library_id'];
        $studentModel = new Student();
        $studentId = $_POST['student_id'] ?? null;
        $reason = $_POST['reason'] ?? '';

        if (!$studentId || empty($reason)) {
            $_SESSION['error'] = 'Student ID and reason are required.';
            $this->redirect(BASE_PATH . '/librarian/students');
            return;
        }

        $student = $studentModel->find($studentId);
        if (!$student || $student['library_id'] != $libraryId) {
            $_SESSION['error'] = 'Student not found or access denied.';
            $this->redirect(BASE_PATH . '/librarian/students');
            return;
        }

        try {
            $result = $studentModel->updateStatus($studentId, 'inactive');
            
            if ($result) {
                // Log the activity
                Security::logActivity(
                    $_SESSION['user_id'],
                    'student_deactivated',
                    'student_management',
                    "Deactivated student: {$student['full_name']} (ID: {$student['student_id']}). Reason: {$reason}"
                );
                
                $_SESSION['success'] = 'Student deactivated successfully.';
            } else {
                $_SESSION['error'] = 'Failed to deactivate student.';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        $this->redirect(BASE_PATH . '/librarian/students');
    }

    public function activateStudent() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(BASE_PATH . '/librarian/students');
            return;
        }

        $libraryId = $_SESSION['library_id'];
        $studentModel = new Student();
        $studentId = $_POST['student_id'] ?? null;
        $reason = $_POST['reason'] ?? '';

        if (!$studentId || empty($reason)) {
            $_SESSION['error'] = 'Student ID and reason are required.';
            $this->redirect(BASE_PATH . '/librarian/students');
            return;
        }

        $student = $studentModel->find($studentId);
        if (!$student || $student['library_id'] != $libraryId) {
            $_SESSION['error'] = 'Student not found or access denied.';
            $this->redirect(BASE_PATH . '/librarian/students');
            return;
        }

        try {
            $result = $studentModel->updateStatus($studentId, 'active');
            
            if ($result) {
                // Log the activity
                Security::logActivity(
                    $_SESSION['user_id'],
                    'student_activated',
                    'student_management',
                    "Activated student: {$student['full_name']} (ID: {$student['student_id']}). Reason: {$reason}"
                );
                
                $_SESSION['success'] = 'Student activated successfully.';
            } else {
                $_SESSION['error'] = 'Failed to activate student.';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        $this->redirect(BASE_PATH . '/librarian/students');
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
            // Verify CSRF token
            if (!isset($_POST['csrf_token']) || !Security::verifyCSRFToken($_POST['csrf_token'])) {
                $_SESSION['error'] = "Invalid security token. Please try again.";
                $this->redirect(BASE_PATH . '/librarian/borrows');
                return;
            }
            
            $bookId = (int)($_POST['book_id'] ?? 0);
            $studentId = (int)($_POST['student_id'] ?? 0);
            
            // Validate IDs
            if ($bookId <= 0 || $studentId <= 0) {
                $_SESSION['error'] = "Invalid book or student selection.";
                $this->redirect(BASE_PATH . '/librarian/borrow-book');
                return;
            }
            
            // Verify book belongs to librarian's library
            $book = $this->bookModel->find($bookId);
            if (!$book || $book['library_id'] != $libraryId) {
                $_SESSION['error'] = "Book not found or access denied.";
                Security::logSecurity(
                    $_SESSION['user_id'],
                    'unauthorized_borrow_attempt',
                    "Attempted to borrow book {$bookId} from different library",
                    'warning'
                );
                $this->redirect(BASE_PATH . '/librarian/borrow-book');
                return;
            }
            
            // Verify student belongs to librarian's library
            $student = $this->studentModel->find($studentId);
            if (!$student || $student['library_id'] != $libraryId) {
                $_SESSION['error'] = "Student not found or access denied.";
                Security::logSecurity(
                    $_SESSION['user_id'],
                    'unauthorized_borrow_attempt',
                    "Attempted to assign book to student {$studentId} from different library",
                    'warning'
                );
                $this->redirect(BASE_PATH . '/librarian/borrow-book');
                return;
            }
            
            $borrowModel = new Borrow();

            try {
                if ($borrowModel->borrowBook($bookId, $studentId, $_SESSION['user_id'])) {
                    // Log borrow action
                    Security::logActivity(
                        $_SESSION['user_id'],
                        'book_borrowed',
                        'data',
                        "Book '{$book['title']}' borrowed by student '{$student['full_name']}')",
                        ['book_id' => $bookId, 'student_id' => $studentId, 'library_id' => $libraryId],
                        'info'
                    );
                    $_SESSION['success'] = "Book borrowed successfully!";
                }
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
            }
            
            $this->redirect(BASE_PATH . '/librarian/borrows');
            return;
        }

        // GET request - show borrow form
        $bookModel = new Book();
        $studentModel = new Student();
        $libraryModel = new Library();
        
        $data = [
            'books' => $bookModel->getBooksByLibrary($libraryId, ['status' => 'available']),
            'students' => $studentModel->getStudentsByLibrary($libraryId, ['status' => 'active']),
            'loan_period' => $libraryModel->getLoanPeriod($libraryId)
        ];
        $this->view('librarian/borrow-book', $data);
    }

    public function returnBook() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verify CSRF token (skip for AJAX requests with alternative validation)
            $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
            
            if (!$isAjax && (!isset($_POST['csrf_token']) || !Security::verifyCSRFToken($_POST['csrf_token']))) {
                $_SESSION['error'] = "Invalid security token. Please try again.";
                $this->redirect(BASE_PATH . '/librarian/borrows');
                return;
            }
            
            $borrowId = (int)($_POST['borrow_id'] ?? 0);
            
            // Validate borrow ID
            if ($borrowId <= 0) {
                $error = "Invalid borrow ID.";
                if ($isAjax) {
                    http_response_code(400);
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => $error]);
                    return;
                }
                $_SESSION['error'] = $error;
                $this->redirect(BASE_PATH . '/librarian/borrows');
                return;
            }
            
            // Verify borrow belongs to librarian's library
            $borrow = $this->borrowModel->find($borrowId);
            if (!$borrow) {
                $error = "Borrow record not found.";
                if ($isAjax) {
                    http_response_code(404);
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => $error]);
                    return;
                }
                $_SESSION['error'] = $error;
                $this->redirect(BASE_PATH . '/librarian/borrows');
                return;
            }
            
            $book = $this->bookModel->find($borrow['book_id']);
            if (!$book || $book['library_id'] != $_SESSION['library_id']) {
                $error = "Access denied. This borrow belongs to another library.";
                Security::logSecurity(
                    $_SESSION['user_id'],
                    'unauthorized_return_attempt',
                    "Attempted to return book from different library (borrow ID: {$borrowId})",
                    'warning'
                );
                if ($isAjax) {
                    http_response_code(403);
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => $error]);
                    return;
                }
                $_SESSION['error'] = $error;
                $this->redirect(BASE_PATH . '/librarian/borrows');
                return;
            }
            
            $borrowId = $_POST['borrow_id'] ?? null;
            $borrowModel = new Borrow();

            try {
                $result = $borrowModel->returnBook($borrowId, $_SESSION['user_id']);
                
                // Log return action
                $logMessage = "Book '{$result['book_title']}' returned by '{$result['student_name']}'";
                if ($result['fine_amount'] > 0) {
                    $logMessage .= " (Fine: MK {$result['fine_amount']})";
                }
                Security::logActivity(
                    $_SESSION['user_id'],
                    'book_returned',
                    'data',
                    $logMessage,
                    ['borrow_id' => $borrowId, 'fine_amount' => $result['fine_amount']],
                    'info'
                );
                
                // AJAX: return JSON without redirect
                if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                    header('Content-Type: application/json');
                    echo json_encode([
                        'success' => true,
                        'message' => ($result['fine_amount'] > 0)
                            ? "Book '{$result['book_title']}' returned successfully! Fine amount: MK " . number_format($result['fine_amount'])
                            : "Book '{$result['book_title']}' returned successfully!",
                        'data' => [
                            'fine_amount' => (float)$result['fine_amount'],
                            'book_title' => $result['book_title'],
                            'student_name' => $result['student_name'],
                            'returned_date' => date('Y-m-d'),
                            'status' => 'returned'
                        ]
                    ]);
                    return;
                }

                if ($result['fine_amount'] > 0) {
                    $_SESSION['success'] = "Book '{$result['book_title']}' returned successfully! Fine amount: MK " . number_format($result['fine_amount']);
                } else {
                    $_SESSION['success'] = "Book '{$result['book_title']}' returned successfully!";
                }
            } catch (Exception $e) {
                // AJAX error
                if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                    http_response_code(400);
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                    return;
                }
                $_SESSION['error'] = $e->getMessage();
            }
        }
    $this->redirect(BASE_PATH . '/librarian/borrows');
    }

    public function payFine() {
        // Allow POST to process payment; redirect GET back to borrows
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/librarian/borrows');
            return;
        }

        $borrowId = $_POST['borrow_id'] ?? null;
        // Optional: amount; if not provided, take full remaining
        $amount = isset($_POST['amount']) && $_POST['amount'] !== '' ? (float)$_POST['amount'] : null;

        if (!$borrowId) {
            $_SESSION['error'] = 'Borrow ID is required.';
            $this->redirect('/librarian/borrows');
            return;
        }

        try {
            $result = $this->borrowModel->recordFinePayment($borrowId, $amount ?? PHP_FLOAT_MAX);
            $paidNow = (float)$result['paid_now'];
            $remainingRaw = (float)$result['remaining'];
            $paidNowFmt = number_format($paidNow, 2);
            $remainingFmt = number_format($remainingRaw, 2);

            // AJAX: return JSON without redirect
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'message' => ($remainingRaw <= 0)
                        ? "Fine payment recorded: MK {$paidNowFmt}. Fine fully paid."
                        : "Fine payment recorded: MK {$paidNowFmt}. Remaining: MK {$remainingFmt}.",
                    'data' => [
                        'paid_now' => $paidNow,
                        'remaining' => $remainingRaw
                    ]
                ]);
                return;
            }

            if ($remainingRaw <= 0) {
                $_SESSION['success'] = "Fine payment recorded: MK {$paidNowFmt}. Fine fully paid.";
            } else {
                $_SESSION['success'] = "Fine payment recorded: MK {$paidNowFmt}. Remaining: MK {$remainingFmt}.";
            }
        } catch (Exception $e) {
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                http_response_code(400);
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                return;
            }
            $_SESSION['error'] = $e->getMessage();
        }

        $this->redirect('/librarian/borrows');
    }

    public function markLost() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(BASE_PATH . '/librarian/borrows');
            return;
        }
        $borrowId = $_POST['borrow_id'] ?? null;
        try {
            $ok = $this->borrowModel->markLost($borrowId, $_SESSION['user_id']);
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'Marked as lost']);
                return;
            }
            $_SESSION['success'] = 'Marked as lost';
        } catch (Exception $e) {
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                http_response_code(400);
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                return;
            }
            $_SESSION['error'] = $e->getMessage();
        }
        $this->redirect(BASE_PATH . '/librarian/borrows');
    }

    public function markFound() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/librarian/borrows');
            return;
        }
        $borrowId = $_POST['borrow_id'] ?? null;
        try {
            $ok = $this->borrowModel->markFound($borrowId, $_SESSION['user_id']);
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'Marked as found']);
                return;
            }
            $_SESSION['success'] = 'Marked as found';
        } catch (Exception $e) {
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                http_response_code(400);
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                return;
            }
            $_SESSION['error'] = $e->getMessage();
        }
        $this->redirect('/librarian/borrows');
    }

    public function quickBorrow() {
        $libraryId = $_SESSION['library_id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verify CSRF token
            if (!isset($_POST['csrf_token']) || !Security::verifyCSRFToken($_POST['csrf_token'])) {
                $_SESSION['error'] = "Invalid security token. Please try again.";
                $this->redirect(BASE_PATH . '/librarian/quick-borrow');
                return;
            }

            $studentIdInput = $_POST['student_id'] ?? '';
            $isbn = $_POST['isbn'] ?? '';
            $borrowModel = new Borrow();
            $studentModel = new Student();
            $bookModel = new Book();

            // Find student by student_id
            $student = $studentModel->getStudentByStudentId($studentIdInput, $libraryId);

            if (!$student) {
                $_SESSION['error'] = "Student not found with ID: " . $studentIdInput;
                $this->redirect(BASE_PATH . '/librarian/quick-borrow');
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

    public function searchStudents() {
        if (!isset($_SESSION['library_id'])) { http_response_code(401); exit; }
        $term = $_GET['q'] ?? '';
        $studentModel = new Student();
        $results = $studentModel->getStudentsByLibrary($_SESSION['library_id'], ['search' => $term]);
        $results = array_slice($results, 0, 10);
        header('Content-Type: application/json');
        echo json_encode(array_map(function($s){
            return [
                'id' => $s['id'],
                'student_id' => $s['student_id'],
                'full_name' => $s['full_name'],
                'class' => $s['class']
            ];
        }, $results));
    }

    public function searchBooks() {
        if (!isset($_SESSION['library_id'])) { http_response_code(401); exit; }
        $term = $_GET['q'] ?? '';
        $bookModel = new Book();
        $results = $bookModel->getBooksByLibrary($_SESSION['library_id'], ['search' => $term]);
        $results = array_slice($results, 0, 10);
        header('Content-Type: application/json');
        echo json_encode(array_map(function($b){
            return [
                'id' => $b['id'],
                'isbn' => $b['isbn'],
                'title' => $b['title'],
                'author' => $b['author'],
                'available_copies' => $b['available_copies']
            ];
        }, $results));
    }

    public function borrowsData() {
        if (!isset($_SESSION['library_id'])) { http_response_code(401); exit; }
        $libraryId = $_SESSION['library_id'];
        $filters = [
            'status' => $_GET['status'] ?? '',
            'student_id' => $_GET['student_id'] ?? '',
            'book_title' => $_GET['book_title'] ?? ''
        ];
        $borrowModel = new Borrow();
        $borrows = $borrowModel->getBorrowsByLibrary($libraryId, $filters);
        header('Content-Type: application/json');
        echo json_encode(['borrows' => $borrows]);
    }

    // Enhanced Reports Methods
    public function reports() {
    $libraryId = $_SESSION['library_id'];
    require_once '../app/models/Category.php';
    $reportModel = new Report();
    $bookModel = new Book();
    $studentModel = new Student();
    $categoryModel = new Category();

        $data = [
            'book_stats' => $bookModel->getDashboardStats($libraryId),
            'categories' => $categoryModel->getCategoriesByLibrary($libraryId),
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
            
            // Build filters from POST data
            $filters = [];
            
            // Date range filters
            if (!empty($_POST['date_range'])) {
                $dateRange = $_POST['date_range'];
                if ($dateRange === 'custom') {
                    if (!empty($_POST['start_date'])) $filters['start_date'] = $_POST['start_date'];
                    if (!empty($_POST['end_date'])) $filters['end_date'] = $_POST['end_date'];
                } else {
                    $days = intval($dateRange);
                    $filters['start_date'] = date('Y-m-d', strtotime("-$days days"));
                    $filters['end_date'] = date('Y-m-d');
                }
            }
            
            // Other filters
            if (!empty($_POST['category'])) $filters['category'] = $_POST['category'];
            if (!empty($_POST['status'])) $filters['status'] = $_POST['status'];
            if (!empty($_POST['class'])) $filters['class'] = $_POST['class'];
            if (!empty($_POST['student_status'])) $filters['status'] = $_POST['student_status'];
            
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

            // Return JSON for AJAX requests
            header('Content-Type: application/json');
            echo json_encode($data);
            exit;
        }
    }

    public function exportReport() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $libraryId = $_SESSION['library_id'];
            $reportModel = new Report();
            $type = $_GET['report_type'] ?? '';
            $format = $_GET['export_format'] ?? 'excel';
            
            // Build filters from GET data
            $filters = [];
            
            // Date range filters
            if (!empty($_GET['date_range'])) {
                $dateRange = $_GET['date_range'];
                if ($dateRange === 'custom') {
                    if (!empty($_GET['start_date'])) $filters['start_date'] = $_GET['start_date'];
                    if (!empty($_GET['end_date'])) $filters['end_date'] = $_GET['end_date'];
                } else {
                    $days = intval($dateRange);
                    $filters['start_date'] = date('Y-m-d', strtotime("-$days days"));
                    $filters['end_date'] = date('Y-m-d');
                }
            }
            
            // Other filters
            if (!empty($_GET['category'])) $filters['category'] = $_GET['category'];
            if (!empty($_GET['status'])) $filters['status'] = $_GET['status'];
            if (!empty($_GET['class'])) $filters['class'] = $_GET['class'];
            if (!empty($_GET['student_status'])) $filters['status'] = $_GET['student_status'];
            
            switch ($type) {
                case 'books':
                    $data = $reportModel->generateBooksReport($libraryId, $filters);
                    $filename = 'books-report';
                    break;
                case 'students':
                    $data = $reportModel->generateStudentsReport($libraryId, $filters);
                    $filename = 'students-report';
                    break;
                case 'borrows':
                    $data = $reportModel->generateBorrowingReport($libraryId, $filters);
                    $filename = 'borrows-report';
                    break;
                case 'financial':
                    $data = $reportModel->generateFinancialReport($libraryId, $filters);
                    $filename = 'financial-report';
                    break;
                default:
                    $data = [];
                    $filename = 'report';
            }

            $filename .= '-' . date('Y-m-d-His');

            if ($format === 'pdf') {
                $this->exportToPDF($data, $filename, $type);
            } else {
                $this->exportToExcel($data, $filename);
            }
        }
    }

    private function exportToExcel($data, $filename) {
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $filename . '.xls"');
        header('Cache-Control: max-age=0');
        
        if (!empty($data)) {
            echo "<table border='1'>";
            echo "<tr>";
            foreach (array_keys($data[0]) as $header) {
                echo "<th>" . htmlspecialchars($header) . "</th>";
            }
            echo "</tr>";
            
            foreach ($data as $row) {
                echo "<tr>";
                foreach ($row as $cell) {
                    echo "<td>" . htmlspecialchars($cell ?? '') . "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No data available</p>";
        }
        exit;
    }

    private function exportToPDF($data, $filename, $reportType) {
        header('Content-Type: text/html');
        header('Content-Disposition: attachment; filename="' . $filename . '.html"');
        
        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>' . htmlspecialchars($filename) . '</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #333; text-align: center; margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #4CAF50; color: white; font-weight: bold; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        .footer { margin-top: 30px; text-align: center; color: #666; font-size: 12px; }
    </style>
</head>
<body>
    <h1>' . ucfirst($reportType) . ' Report</h1>
    <p><strong>Generated:</strong> ' . date('F j, Y g:i A') . '</p>';
        
        if (!empty($data)) {
            $html .= '<table>';
            $html .= '<thead><tr>';
            foreach (array_keys($data[0]) as $header) {
                $html .= '<th>' . htmlspecialchars($header) . '</th>';
            }
            $html .= '</tr></thead><tbody>';
            
            foreach ($data as $row) {
                $html .= '<tr>';
                foreach ($row as $cell) {
                    $html .= '<td>' . htmlspecialchars($cell ?? '') . '</td>';
                }
                $html .= '</tr>';
            }
            $html .= '</tbody></table>';
        } else {
            $html .= '<p>No data available for this report.</p>';
        }
        
        $html .= '<div class="footer">
        <p>Jacaranda Library Management System</p>
    </div>
</body>
</html>';
        
        echo $html;
        exit;
    }

    public function addCategory() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = 'Invalid request method';
            $this->redirect(BASE_PATH . '/librarian/add-category');
            return;
        }

        // Verify CSRF token
        if (!isset($_POST['csrf_token']) || !Security::verifyCSRFToken($_POST['csrf_token'])) {
            $_SESSION['error'] = 'Invalid security token';
            $this->redirect(BASE_PATH . '/librarian/add-category');
            return;
        }

        $libraryId = $_SESSION['library_id'];
        $categoryName = trim($_POST['name'] ?? '');

        if (empty($categoryName)) {
            $_SESSION['error'] = 'Category name is required';
            $this->redirect(BASE_PATH . '/librarian/add-category');
            return;
        }

        $createdBy = $_SESSION['user_id'] ?? null;
        if ($this->categoryModel->addCategory($categoryName, $createdBy)) {
            $_SESSION['success'] = 'Category added successfully';
            $this->redirect(BASE_PATH . '/librarian/categories');
        } else {
            $_SESSION['error'] = 'Category already exists or failed to add';
            $this->redirect(BASE_PATH . '/librarian/add-category');
        }
    }
}
?>