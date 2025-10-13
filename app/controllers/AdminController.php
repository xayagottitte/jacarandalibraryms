<?php
class AdminController extends Controller {
    private $userModel;
    private $libraryModel;
    private $bookModel;
    private $mailer;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'super_admin') {
            $this->redirect('/login');
        }
        $this->userModel = new User();
        $this->libraryModel = new Library();
        $this->bookModel = new Book();
        $this->mailer = new Mailer();
    }

    public function dashboard() {
        $data = [
            'pending_users' => $this->userModel->getPendingUsers(),
            'stats' => $this->userModel->getDashboardStats(),
            'libraries' => $this->libraryModel->getAllWithStats()
        ];
        
        $this->view('admin/dashboard', $data);
    }

    // User Management Methods
    public function users() {
        // Get filters from GET parameters
        $filters = [
            'search' => $_GET['search'] ?? '',
            'role' => $_GET['role'] ?? '',
            'status' => $_GET['status'] ?? '',
            'library' => $_GET['library'] ?? ''
        ];

        $data = [
            'users' => $this->userModel->getAllUsers($filters),
            'libraries' => $this->libraryModel->all(),
            'filters' => $filters
        ];
        $this->view('admin/users', $data);
    }

    public function approveUser($userId = null) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_POST['user_id'] ?? null;
            
            if ($userId && $this->userModel->approveUser($userId, $_SESSION['user_id'])) {
                // Get user details for welcome email
                $user = $this->userModel->find($userId);
                if ($user && $user['email']) {
                    // Send welcome email
                    $this->mailer->sendWelcomeEmail($user['email'], $user['full_name'], $user['role']);
                }
                
                $_SESSION['success'] = "User approved successfully! Welcome email sent.";
            } else {
                $_SESSION['error'] = "Failed to approve user.";
            }
        }
        $this->redirect('/admin/users');
    }

    public function rejectUser($userId = null) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_POST['user_id'] ?? null;
            
            if ($userId && $this->userModel->rejectUser($userId)) {
                $_SESSION['success'] = "User rejected successfully!";
            } else {
                $_SESSION['error'] = "Failed to reject user.";
            }
        }
        $this->redirect('/admin/users');
    }

    public function createUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate password confirmation
            if ($_POST['password'] !== $_POST['confirm_password']) {
                $_SESSION['error'] = "Passwords do not match.";
                $this->view('admin/create-user');
                return;
            }

            // Validate password length
            if (strlen($_POST['password']) < 6) {
                $_SESSION['error'] = "Password must be at least 6 characters long.";
                $this->view('admin/create-user');
                return;
            }

            $data = [
                'username' => $_POST['username'],
                'full_name' => $_POST['full_name'],
                'email' => $_POST['email'],
                'phone' => $_POST['phone'],
                'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                'role' => $_POST['role'],
                'status' => 'active'
            ];

            // Check if user already exists
            if ($this->userModel->findByEmail($data['email'])) {
                $_SESSION['error'] = "Email already registered.";
            } elseif ($this->userModel->findByUsername($data['username'])) {
                $_SESSION['error'] = "Username already taken.";
            } else {
                if ($this->userModel->create($data)) {
                    $_SESSION['success'] = "User created successfully!";
                    $this->redirect('/admin/users');
                    return;
                } else {
                    $_SESSION['error'] = "Failed to create user.";
                }
            }
        }
        
        $this->view('admin/create-user');
    }

    public function deactivateUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_POST['user_id'] ?? null;
            
            if ($userId && $this->userModel->deactivateUser($userId)) {
                $_SESSION['success'] = "User deactivated successfully!";
            } else {
                $_SESSION['error'] = "Failed to deactivate user.";
            }
        }
        $this->redirect('/admin/users');
    }

    public function activateUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_POST['user_id'] ?? null;
            
            if ($userId && $this->userModel->activateUser($userId)) {
                $_SESSION['success'] = "User activated successfully!";
            } else {
                $_SESSION['error'] = "Failed to activate user.";
            }
        }
        $this->redirect('/admin/users');
    }

    public function deleteUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_POST['user_id'] ?? null;
            
            if ($userId && $this->userModel->deleteUser($userId)) {
                $_SESSION['success'] = "User deleted successfully!";
            } else {
                $_SESSION['error'] = "Failed to delete user.";
            }
        }
        $this->redirect('/admin/users');
    }

    public function assignLibrary() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_POST['user_id'] ?? null;
            $libraryId = $_POST['library_id'] ?? null;
            
            if ($userId && $libraryId && $this->userModel->assignLibrary($userId, $libraryId)) {
                $_SESSION['success'] = "Library assigned successfully!";
            } else {
                $_SESSION['error'] = "Failed to assign library.";
            }
        }
        $this->redirect('/admin/users');
    }

    // Library Management Methods
    public function libraries() {
        // Get filters from GET parameters
        $filters = [
            'search' => $_GET['search'] ?? '',
            'type' => $_GET['type'] ?? '',
            'librarian_status' => $_GET['librarian_status'] ?? '',
            'sort_by' => $_GET['sort_by'] ?? 'name'
        ];

        $data = [
            'libraries' => $this->libraryModel->getAllWithStats($filters),
            'available_librarians' => $this->libraryModel->getAvailableLibrarians(),
            'filters' => $filters
        ];
        $this->view('admin/libraries', $data);
    }

    public function createLibrary() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'],
                'type' => $_POST['type'],
                'address' => $_POST['address'],
                'loan_period_days' => $_POST['loan_period_days'] ?? 5,
                'created_by' => $_SESSION['user_id']
            ];

            if ($this->libraryModel->create($data)) {
                $_SESSION['success'] = "Library created successfully!";
                $this->redirect('/admin/libraries');
                return;
            } else {
                $_SESSION['error'] = "Failed to create library.";
            }
        }
        
        $this->view('admin/create-library');
    }

    public function editLibrary($id = null) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $data = [
                'name' => $_POST['name'],
                'type' => $_POST['type'],
                'address' => $_POST['address'],
                'loan_period_days' => $_POST['loan_period_days'] ?? 5
            ];

            if ($this->libraryModel->updateLibrary($data, $id)) {
                $_SESSION['success'] = "Library updated successfully!";
            } else {
                $_SESSION['error'] = "Failed to update library.";
            }
            
            $this->redirect('/admin/libraries');
            return;
        }

        // GET request - show edit form
        $library = $this->libraryModel->find($id);
        if (!$library) {
            $_SESSION['error'] = "Library not found.";
            $this->redirect('/admin/libraries');
            return;
        }

        $data = ['library' => $library];
        $this->view('admin/edit-library', $data);
    }

    public function deleteLibrary() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            
            // Check if library has users or books
            $checkQuery = "SELECT COUNT(*) as count FROM users WHERE library_id = :id";
            $stmt = $this->libraryModel->db->prepare($checkQuery);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result['count'] > 0) {
                $_SESSION['error'] = "Cannot delete library. It has assigned librarians.";
            } else {
                $deleteQuery = "DELETE FROM libraries WHERE id = :id";
                $deleteStmt = $this->libraryModel->db->prepare($deleteQuery);
                $deleteStmt->bindParam(':id', $id);
                
                if ($deleteStmt->execute()) {
                    $_SESSION['success'] = "Library deleted successfully!";
                } else {
                    $_SESSION['error'] = "Failed to delete library.";
                }
            }
        }
        $this->redirect('/admin/libraries');
    }

    public function assignLibrarian() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $libraryId = $_POST['library_id'] ?? null;
            $librarianId = $_POST['librarian_id'] ?? null;
            
            if ($libraryId && $librarianId) {
                if ($this->libraryModel->assignLibrarian($libraryId, $librarianId, $_SESSION['user_id'])) {
                    $_SESSION['success'] = "Librarian assigned successfully!";
                } else {
                    $_SESSION['error'] = "Failed to assign librarian.";
                }
            } else {
                $_SESSION['error'] = "Please select both library and librarian.";
            }
        }
        $this->redirect('/admin/libraries');
    }

    public function removeLibrarianAssignment() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $librarianId = $_POST['librarian_id'] ?? null;
            
            if ($librarianId && $this->libraryModel->removeLibrarianAssignment($librarianId)) {
                $_SESSION['success'] = "Librarian assignment removed successfully!";
            } else {
                $_SESSION['error'] = "Failed to remove librarian assignment.";
            }
        }
        $this->redirect('/admin/libraries');
    }

    // System Settings Methods
    public function settings() {
        $settingsModel = new SystemSettings();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            foreach ($_POST['settings'] as $key => $value) {
                $settingsModel->updateSetting($key, $value, $_SESSION['user_id']);
            }
            $_SESSION['success'] = "System settings updated successfully!";
            $this->redirect('/admin/settings');
            return;
        }

        $data = [
            'settings' => $settingsModel->getAllSettings()
        ];
        $this->view('admin/settings', $data);
    }

    // Reports Methods
    public function reports() {
        $reportModel = new Report();
        $libraryModel = new Library();
        
        $data = [
            'libraries' => $libraryModel->getAllWithStats(),
            'saved_reports' => $reportModel->getUserReports($_SESSION['user_id'])
        ];
        $this->view('admin/reports', $data);
    }

    public function generateReport() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $reportModel = new Report();
            $type = $_POST['report_type'] ?? '';
            $libraryId = $_POST['library_id'] ?? null;
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
                'title' => $_POST['report_title'] ?? 'Untitled Report',
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

    public function exportReport() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $type = $_POST['export_type'] ?? 'csv';
            $reportData = json_decode($_POST['report_data'] ?? '[]', true);
            $filename = $_POST['filename'] ?? 'report';
            
            if ($type === 'csv') {
                $this->exportToCSV($reportData, $filename);
            } elseif ($type === 'pdf') {
                $this->exportToPDF($reportData, $filename);
            }
        }
    }

    private function exportToCSV($data, $filename) {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        if (!empty($data)) {
            // Add headers
            fputcsv($output, array_keys($data[0]));
            
            // Add data
            foreach ($data as $row) {
                fputcsv($output, $row);
            }
        }
        
        fclose($output);
        exit;
    }

    private function exportToPDF($data, $filename) {
        // Simple PDF generation using HTML
        $html = '<html><head><style>
                body { font-family: Arial, sans-serif; }
                table { width: 100%; border-collapse: collapse; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f2f2f2; }
                </style></head><body>';
        $html .= '<h1>' . htmlspecialchars($filename) . '</h1>';
        
        if (!empty($data)) {
            $html .= '<table>';
            $html .= '<tr>';
            foreach (array_keys($data[0]) as $header) {
                $html .= '<th>' . htmlspecialchars($header) . '</th>';
            }
            $html .= '</tr>';
            
            foreach ($data as $row) {
                $html .= '<tr>';
                foreach ($row as $cell) {
                    $html .= '<td>' . htmlspecialchars($cell) . '</td>';
                }
                $html .= '</tr>';
            }
            $html .= '</table>';
        } else {
            $html .= '<p>No data available</p>';
        }
        
        $html .= '</body></html>';
        
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $filename . '.pdf"');
        
        // For a real implementation, use a PDF library like TCPDF or Dompdf
        echo $html;
        exit;
    }

    // Activity Logs
    public function activityLogs() {
        $activityModel = new ActivityLog();
        
        $data = [
            'activities' => $activityModel->getSystemActivities()
        ];
        $this->view('admin/activity-logs', $data);
    }

    // Book Management Methods
    public function books() {
        // Get filters from GET parameters
        $filters = [
            'search' => $_GET['search'] ?? '',
            'category' => $_GET['category'] ?? '',
            'library_filter' => $_GET['library_filter'] ?? '',
            'status' => $_GET['status'] ?? ''
        ];

        // Get selected library ID for statistics
        $selectedLibraryId = !empty($filters['library_filter']) ? $filters['library_filter'] : null;

        // Load all books with filters applied
        $allBooks = $this->bookModel->getAllBooksWithLibrary($filters);
        $bookStats = $this->bookModel->getBooksStatistics($selectedLibraryId);
        $categoryStats = $this->bookModel->getCategoryStatistics($selectedLibraryId);

        $data = [
            'all_books' => $allBooks,
            'book_stats' => $bookStats,
            'category_stats' => $categoryStats,
            'libraries' => $this->libraryModel->getAllWithStats(),
            'filters' => $filters,
            'selected_library_id' => $selectedLibraryId
        ];
        
        $this->view('admin/books', $data);
    }

    public function createBook() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $libraryId = $_POST['library_id'];
            
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

            // Check if ISBN already exists in the selected library
            if (!empty($data['isbn'])) {
                if ($this->bookModel->checkISBNExists($data['isbn'], $libraryId)) {
                    $_SESSION['error'] = "A book with this ISBN already exists in the selected library.";
                    $this->redirect('/admin/create-book');
                    return;
                }
            }

            if ($this->bookModel->create($data)) {
                $_SESSION['success'] = "Book added successfully to " . $this->libraryModel->find($libraryId)['name'] . "!";
                $this->redirect('/admin/books');
                return;
            } else {
                $_SESSION['error'] = "Failed to add book.";
            }
        }

        $data = [
            'libraries' => $this->libraryModel->all()
        ];
        $this->view('admin/create-book', $data);
    }

    public function editBook($id = null) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $libraryId = $_POST['library_id'];
            
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
            
            $this->redirect('/admin/books');
            return;
        }

        // GET request - show edit form
        $book = $this->bookModel->getBookWithLibrary($id);
        if (!$book) {
            $_SESSION['error'] = "Book not found.";
            $this->redirect('/admin/books');
            return;
        }

        $data = [
            'book' => $book,
            'libraries' => $this->libraryModel->all(),
            'categories' => $this->bookModel->getCategoriesByLibrary($book['library_id'])
        ];
        
        $this->view('admin/edit-book', $data);
    }

    public function deleteBook() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            
            // Get book info first
            $book = $this->bookModel->find($id);
            if (!$book) {
                $_SESSION['error'] = "Book not found.";
                $this->redirect('/admin/books');
                return;
            }
            
            // Check if book has active borrows
            $result = $this->bookModel->checkActiveBorrows($id);

            if ($result['count'] > 0) {
                $_SESSION['error'] = "Cannot delete book. It has active borrows.";
            } else {
                if ($this->bookModel->deleteBook($id, $book['library_id'])) {
                    $_SESSION['success'] = "Book deleted successfully!";
                } else {
                    $_SESSION['error'] = "Failed to delete book.";
                }
            }
        }
        $this->redirect('/admin/books');
    }
}
?>