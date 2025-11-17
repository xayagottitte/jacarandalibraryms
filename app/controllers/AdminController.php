<?php
class AdminController extends Controller {
    private $userModel;
    private $libraryModel;
    private $bookModel;
    private $borrowModel;
    private $studentModel;
    private $mailer;

    public function __construct() {
        // Session is already started in index.php
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'super_admin') {
            $this->redirect('/login');
        }
        $this->userModel = new User();
        $this->libraryModel = new Library();
        $this->bookModel = new Book();
        $this->borrowModel = new Borrow();
        $this->studentModel = new Student();
        $this->mailer = new Mailer();
    }

    public function dashboard() {
        // Get selected library from query parameter (0 = all libraries)
        $selectedLibraryId = isset($_GET['library']) ? (int)$_GET['library'] : 0;
        
        $data = [
            'pending_users' => $this->userModel->getPendingUsers(),
            'stats' => $this->userModel->getDashboardStats(),
            'libraries' => $this->libraryModel->getAllWithStats(),
            'selected_library' => $selectedLibraryId
        ];
        
        // If a specific library is selected, fetch its chart data
        if ($selectedLibraryId > 0) {
            $data['popular_books'] = $this->bookModel->getPopularBooks($selectedLibraryId, 5);
            $data['underutilized_books'] = $this->bookModel->getUnderutilizedBooks($selectedLibraryId, 5);
            $data['class_borrow_stats'] = $this->studentModel->getClassBorrowStats($selectedLibraryId);
            $data['borrowing_trends'] = $this->borrowModel->getBorrowingTrends($selectedLibraryId, 30);
            $data['lost_count'] = $this->borrowModel->getLostCounts($selectedLibraryId, 30);
            $data['lost_books'] = $this->borrowModel->getLostBooks($selectedLibraryId, 10, 30);
        } else {
            // For "All Libraries", we'll use aggregated data
            $data['popular_books'] = [];
            $data['underutilized_books'] = [];
            $data['class_borrow_stats'] = [];
            $data['borrowing_trends'] = [];
            $data['lost_count'] = $this->borrowModel->getLostCounts(null, 30);
            $data['lost_books'] = $this->borrowModel->getLostBooks(null, 10, 30);
        }
        
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
            // Verify CSRF token
            if (!isset($_POST['csrf_token']) || !Security::verifyCSRFToken($_POST['csrf_token'])) {
                $_SESSION['error'] = "Invalid security token. Please try again.";
                $this->redirect('/admin/users');
                return;
            }
            
            $userId = (int)($_POST['user_id'] ?? 0);
            
            // Validate user ID
            if ($userId <= 0) {
                $_SESSION['error'] = "Invalid user ID.";
                $this->redirect('/admin/users');
                return;
            }
            
            // Check if user exists and is pending
            $user = $this->userModel->find($userId);
            if (!$user) {
                $_SESSION['error'] = "User not found.";
                $this->redirect('/admin/users');
                return;
            }
            
            if ($user['status'] !== 'pending') {
                $_SESSION['error'] = "Only pending users can be approved.";
                $this->redirect('/admin/users');
                return;
            }
            
            if ($this->userModel->approveUser($userId, $_SESSION['user_id'])) {
                // Get updated user details for welcome email
                $user = $this->userModel->find($userId);
                if ($user && $user['email']) {
                    // Send welcome email
                    $this->mailer->sendWelcomeEmail($user['email'], $user['full_name'], $user['role']);
                }
                
                // Log approval
                Security::logActivity(
                    $_SESSION['user_id'],
                    'user_approved',
                    'security',
                    "Approved user registration: {$user['email']} ({$user['role']})",
                    ['approved_user_id' => $userId],
                    'info'
                );
                
                $_SESSION['success'] = "User approved successfully! Welcome email sent.";;
            } else {
                $_SESSION['error'] = "Failed to approve user.";
            }
        }
        $this->redirect('/admin/users');
    }

    public function rejectUser($userId = null) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verify CSRF token
            if (!isset($_POST['csrf_token']) || !Security::verifyCSRFToken($_POST['csrf_token'])) {
                $_SESSION['error'] = "Invalid security token. Please try again.";
                $this->redirect('/admin/users');
                return;
            }
            
            $userId = (int)($_POST['user_id'] ?? 0);
            
            // Validate user ID
            if ($userId <= 0) {
                $_SESSION['error'] = "Invalid user ID.";
                $this->redirect('/admin/users');
                return;
            }
            
            // Get user details before rejection for email
            $user = $this->userModel->find($userId);
            
            if (!$user) {
                $_SESSION['error'] = "User not found.";
                $this->redirect('/admin/users');
                return;
            }
            
            if ($this->userModel->rejectUser($userId)) {
                // Send rejection email if user has email
                if ($user && $user['email']) {
                    $this->mailer->sendRejectionEmail($user['email'], $user['full_name']);
                }
                
                // Log rejection
                Security::logActivity(
                    $_SESSION['user_id'],
                    'user_rejected',
                    'security',
                    "Rejected user registration: {$user['email']}",
                    ['rejected_user_id' => $userId],
                    'info'
                );
                
                $_SESSION['success'] = "User rejected successfully! Notification email sent.";
            } else {
                $_SESSION['error'] = "Failed to reject user.";
            }
        }
        $this->redirect('/admin/users');
    }

    public function createUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verify CSRF token
            if (!isset($_POST['csrf_token']) || !Security::verifyCSRFToken($_POST['csrf_token'])) {
                $_SESSION['error'] = "Invalid security token. Please try again.";
                $this->view('admin/create-user');
                return;
            }
            
            // Sanitize inputs
            $username = Security::sanitizeInput($_POST['username'] ?? '');
            $fullName = Security::sanitizeInput($_POST['full_name'] ?? '');
            $email = Security::sanitizeInput($_POST['email'] ?? '');
            $phone = Security::sanitizeInput($_POST['phone'] ?? '');
            $role = Security::sanitizeInput($_POST['role'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            // Validate username
            if (!Security::validateUsername($username)) {
                $_SESSION['error'] = "Username must be 3-50 characters (letters, numbers, underscore, hyphen only).";
                $this->view('admin/create-user');
                return;
            }
            
            // Validate full name
            if (empty($fullName) || strlen($fullName) < 3) {
                $_SESSION['error'] = "Full name must be at least 3 characters long.";
                $this->view('admin/create-user');
                return;
            }
            
            // Validate email
            if (!Security::validateEmail($email)) {
                $_SESSION['error'] = "Please enter a valid email address.";
                $this->view('admin/create-user');
                return;
            }
            
            // Validate phone if provided
            if (!empty($phone) && !Security::validatePhone($phone)) {
                $_SESSION['error'] = "Please enter a valid phone number.";
                $this->view('admin/create-user');
                return;
            }
            
            // Validate role
            if (!in_array($role, ['super_admin', 'librarian'])) {
                $_SESSION['error'] = "Invalid role selected.";
                $this->view('admin/create-user');
                return;
            }
            
            // Validate password
            $passwordValidation = Security::validatePassword($password);
            if (!$passwordValidation['valid']) {
                $_SESSION['error'] = implode(' ', $passwordValidation['errors']);
                $this->view('admin/create-user');
                return;
            }
            
            // Validate password confirmation
            if ($password !== $confirmPassword) {
                $_SESSION['error'] = "Passwords do not match.";
                $this->view('admin/create-user');
                return;
            }
            
            // XSS protection for password
            if (!Security::isPasswordSafeFromXSS($password)) {
                $_SESSION['error'] = "Password contains invalid characters.";
                Security::logSecurity(
                    $_SESSION['user_id'],
                    'user_creation_xss_attempt',
                    "XSS attempt in password field while creating user {$email}",
                    'critical'
                );
                $this->view('admin/create-user');
                return;
            }

            $data = [
                'username' => $username,
                'full_name' => $fullName,
                'email' => $email,
                'phone' => !empty($phone) ? $phone : null,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'role' => $role,
                'status' => 'active'
            ];

            // Check if user already exists
            if ($this->userModel->findByEmail($data['email'])) {
                $_SESSION['error'] = "Email already registered.";
            } elseif ($this->userModel->findByUsername($data['username'])) {
                $_SESSION['error'] = "Username already taken.";
            } else {
                if ($this->userModel->create($data)) {
                    // Log user creation
                    Security::logActivity(
                        $_SESSION['user_id'],
                        'user_created',
                        'security',
                        "Created user account: {$email} ({$role})",
                        ['created_user_email' => $email, 'role' => $role],
                        'info'
                    );
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
            // Verify CSRF token
            if (!isset($_POST['csrf_token']) || !Security::verifyCSRFToken($_POST['csrf_token'])) {
                $_SESSION['error'] = "Invalid security token. Please try again.";
                $this->redirect('/admin/users');
                return;
            }
            
            $userId = (int)($_POST['user_id'] ?? 0);
            
            // Validate user ID
            if ($userId <= 0) {
                $_SESSION['error'] = "Invalid user ID.";
                $this->redirect('/admin/users');
                return;
            }
            
            // Prevent self-deactivation
            if ($userId == $_SESSION['user_id']) {
                $_SESSION['error'] = "You cannot deactivate your own account.";
                $this->redirect('/admin/users');
                return;
            }
            
            // Get user details
            $user = $this->userModel->find($userId);
            if (!$user) {
                $_SESSION['error'] = "User not found.";
                $this->redirect('/admin/users');
                return;
            }
            
            if ($this->userModel->deactivateUser($userId)) {
                // Log user deactivation
                Security::logActivity(
                    $_SESSION['user_id'],
                    'user_deactivated',
                    'security',
                    "Deactivated user account: {$user['email']}",
                    ['deactivated_user_id' => $userId],
                    'info'
                );
                $_SESSION['success'] = "User deactivated successfully!";
            } else {
                $_SESSION['error'] = "Failed to deactivate user.";
            }
        }
        $this->redirect('/admin/users');
    }

    public function activateUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verify CSRF token
            if (!isset($_POST['csrf_token']) || !Security::verifyCSRFToken($_POST['csrf_token'])) {
                $_SESSION['error'] = "Invalid security token. Please try again.";
                $this->redirect('/admin/users');
                return;
            }
            
            $userId = (int)($_POST['user_id'] ?? 0);
            
            // Validate user ID
            if ($userId <= 0) {
                $_SESSION['error'] = "Invalid user ID.";
                $this->redirect('/admin/users');
                return;
            }
            
            // Get user details
            $user = $this->userModel->find($userId);
            if (!$user) {
                $_SESSION['error'] = "User not found.";
                $this->redirect('/admin/users');
                return;
            }
            
            if ($this->userModel->activateUser($userId)) {
                // Log user activation
                Security::logActivity(
                    $_SESSION['user_id'],
                    'user_activated',
                    'security',
                    "Activated user account: {$user['email']}",
                    ['activated_user_id' => $userId],
                    'info'
                );
                $_SESSION['success'] = "User activated successfully!";
            } else {
                $_SESSION['error'] = "Failed to activate user.";
            }
        }
        $this->redirect('/admin/users');
    }

    public function deleteUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verify CSRF token
            if (!isset($_POST['csrf_token']) || !Security::verifyCSRFToken($_POST['csrf_token'])) {
                $_SESSION['error'] = "Invalid security token. Please try again.";
                $this->redirect('/admin/users');
                return;
            }
            
            $userId = (int)($_POST['user_id'] ?? 0);
            
            // Validate user ID
            if ($userId <= 0) {
                $_SESSION['error'] = "Invalid user ID.";
                $this->redirect('/admin/users');
                return;
            }
            
            // Prevent self-deletion
            if ($userId == $_SESSION['user_id']) {
                $_SESSION['error'] = "You cannot delete your own account.";
                $this->redirect('/admin/users');
                return;
            }
            
            // Get user details before deletion
            $user = $this->userModel->find($userId);
            if (!$user) {
                $_SESSION['error'] = "User not found.";
                $this->redirect('/admin/users');
                return;
            }
            
            try {
                if ($this->userModel->deleteUser($userId)) {
                    // Log user deletion
                    Security::logActivity(
                        $_SESSION['user_id'],
                        'user_deleted',
                        'security',
                        "Deleted user account: {$user['email']} ({$user['role']})",
                        ['deleted_user_id' => $userId],
                        'warning'
                    );
                    $_SESSION['success'] = "User deleted successfully!";
                } else {
                    $_SESSION['error'] = "Failed to delete user.";
                }
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
            }
        }
        $this->redirect('/admin/users');
    }

    public function assignLibrary() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verify CSRF token
            if (!isset($_POST['csrf_token']) || !Security::verifyCSRFToken($_POST['csrf_token'])) {
                $_SESSION['error'] = "Invalid security token. Please try again.";
                $this->redirect('/admin/users');
                return;
            }
            
            $userId = (int)($_POST['user_id'] ?? 0);
            $libraryId = (int)($_POST['library_id'] ?? 0);
            
            // Validate IDs
            if ($userId <= 0 || $libraryId <= 0) {
                $_SESSION['error'] = "Invalid user or library ID.";
                $this->redirect('/admin/users');
                return;
            }
            
            // Verify user exists and is a librarian
            $user = $this->userModel->find($userId);
            if (!$user || $user['role'] !== 'librarian') {
                $_SESSION['error'] = "Only librarians can be assigned to libraries.";
                $this->redirect('/admin/users');
                return;
            }
            
            // Verify library exists
            $library = $this->libraryModel->find($libraryId);
            if (!$library) {
                $_SESSION['error'] = "Library not found.";
                $this->redirect('/admin/users');
                return;
            }
            
            if ($this->userModel->assignLibrary($userId, $libraryId)) {
                // Log library assignment
                Security::logActivity(
                    $_SESSION['user_id'],
                    'library_assigned',
                    'security',
                    "Assigned librarian {$user['email']} to library {$library['name']}",
                    ['user_id' => $userId, 'library_id' => $libraryId],
                    'info'
                );
                $_SESSION['success'] = "Library assigned successfully!";
            } else {
                $_SESSION['error'] = "Failed to assign library.";
            }
        }
        $this->redirect('/admin/users');
    }

    public function editUser($userId = null) {
        if (!$userId) {
            $_SESSION['error'] = "User ID is required.";
            $this->redirect('/admin/users');
            return;
        }

        $user = $this->userModel->find($userId);
        if (!$user) {
            $_SESSION['error'] = "User not found.";
            $this->redirect('/admin/users');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verify CSRF token
            if (!isset($_POST['csrf_token']) || !Security::verifyCSRFToken($_POST['csrf_token'])) {
                $_SESSION['error'] = "Invalid security token. Please try again.";
                $this->view('admin/edit-user', ['user' => $user, 'libraries' => $this->libraryModel->all()]);
                return;
            }
            
            // Clean and prepare library_id
            $libraryId = isset($_POST['library_id']) && !empty($_POST['library_id']) ? (int)$_POST['library_id'] : null;
            
            // Sanitize inputs
            $username = Security::sanitizeInput($_POST['username'] ?? '');
            $fullName = Security::sanitizeInput($_POST['full_name'] ?? '');
            $email = Security::sanitizeInput($_POST['email'] ?? '');
            $role = Security::sanitizeInput($_POST['role'] ?? '');
            
            // Validate username
            if (!Security::validateUsername($username)) {
                $_SESSION['error'] = "Username must be 3-50 characters (letters, numbers, underscore, hyphen only).";
                $this->view('admin/edit-user', ['user' => $user, 'libraries' => $this->libraryModel->all()]);
                return;
            }
            
            // Validate full name
            if (empty($fullName) || strlen($fullName) < 3) {
                $_SESSION['error'] = "Full name must be at least 3 characters long.";
                $this->view('admin/edit-user', ['user' => $user, 'libraries' => $this->libraryModel->all()]);
                return;
            }
            
            // Validate email
            if (!Security::validateEmail($email)) {
                $_SESSION['error'] = "Please enter a valid email address.";
                $this->view('admin/edit-user', ['user' => $user, 'libraries' => $this->libraryModel->all()]);
                return;
            }
            
            // Validate role
            if (!in_array($role, ['super_admin', 'librarian'])) {
                $_SESSION['error'] = "Invalid role selected.";
                $this->view('admin/edit-user', ['user' => $user, 'libraries' => $this->libraryModel->all()]);
                return;
            }
            
            $data = [
                'id' => $userId,
                'username' => $username,
                'full_name' => $fullName,
                'email' => $email,
                'role' => $role,
                'library_id' => $libraryId
            ];

            // Update password only if provided
            if (!empty($_POST['password'])) {
                $password = $_POST['password'];
                $confirmPassword = $_POST['confirm_password'] ?? '';
                
                if ($password !== $confirmPassword) {
                    $_SESSION['error'] = "Passwords do not match.";
                    $this->view('admin/edit-user', ['user' => $user, 'libraries' => $this->libraryModel->all()]);
                    return;
                }
                
                // Validate password
                $passwordValidation = Security::validatePassword($password);
                if (!$passwordValidation['valid']) {
                    $_SESSION['error'] = implode(' ', $passwordValidation['errors']);
                    $this->view('admin/edit-user', ['user' => $user, 'libraries' => $this->libraryModel->all()]);
                    return;
                }
                
                // XSS protection for password
                if (!Security::isPasswordSafeFromXSS($password)) {
                    $_SESSION['error'] = "Password contains invalid characters.";
                    Security::logSecurity(
                        $_SESSION['user_id'],
                        'user_edit_xss_attempt',
                        "XSS attempt in password field while editing user {$email}",
                        'critical'
                    );
                    $this->view('admin/edit-user', ['user' => $user, 'libraries' => $this->libraryModel->all()]);
                    return;
                }
                
                $data['password'] = password_hash($password, PASSWORD_DEFAULT);
            }

            if ($this->userModel->update($data)) {
                // Log user update
                Security::logActivity(
                    $_SESSION['user_id'],
                    'user_updated',
                    'security',
                    "Updated user account: {$email}",
                    ['updated_user_id' => $userId],
                    'info'
                );
                $_SESSION['success'] = "User updated successfully!";
                $this->redirect('/admin/users');
                return;
            } else {
                $_SESSION['error'] = "Failed to update user.";
            }
        }

        $data = [
            'user' => $user,
            'libraries' => $this->libraryModel->all()
        ];
        
        $this->view('admin/edit-user', $data);
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

        $libraries = $this->libraryModel->getAllWithStats($filters);
        
        // Calculate summary statistics from the library data
        $totalLibraries = count($libraries);
        $totalBooks = 0;
        $totalCopies = 0;
        $primaryLibraries = 0;
        $secondaryLibraries = 0;
        $totalLibrarians = 0;
        
        foreach ($libraries as $library) {
            $totalBooks += (int)$library['total_books'];
            $totalCopies += (int)$library['total_copies'];
            $totalLibrarians += (int)$library['total_librarians'];
            
            if ($library['type'] === 'primary') {
                $primaryLibraries++;
            } else if ($library['type'] === 'secondary') {
                $secondaryLibraries++;
            }
        }

        $data = [
            'libraries' => $libraries,
            'available_librarians' => $this->libraryModel->getAvailableLibrarians(),
            'filters' => $filters,
            'totalLibraries' => $totalLibraries,
            'totalBooks' => $totalBooks,
            'totalCopies' => $totalCopies,
            'primaryLibraries' => $primaryLibraries,
            'secondaryLibraries' => $secondaryLibraries,
            'totalLibrarians' => $totalLibrarians
        ];
        $this->view('admin/libraries', $data);
    }

    public function createLibrary() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verify CSRF token
            if (!isset($_POST['csrf_token']) || !Security::verifyCSRFToken($_POST['csrf_token'])) {
                $_SESSION['error'] = "Invalid security token. Please try again.";
                $this->view('admin/create-library');
                return;
            }
            
            // Sanitize inputs
            $name = Security::sanitizeInput($_POST['name'] ?? '');
            $type = Security::sanitizeInput($_POST['type'] ?? '');
            $address = Security::sanitizeInput($_POST['address'] ?? '');
            $loanPeriod = (int)($_POST['loan_period_days'] ?? 5);
            
            // Validate name
            if (empty($name) || strlen($name) < 3) {
                $_SESSION['error'] = "Library name must be at least 3 characters long.";
                $this->view('admin/create-library');
                return;
            }
            
            // Validate type
            if (!in_array($type, ['primary', 'secondary'])) {
                $_SESSION['error'] = "Invalid library type selected.";
                $this->view('admin/create-library');
                return;
            }
            
            // Validate address
            if (empty($address) || strlen($address) < 5) {
                $_SESSION['error'] = "Address must be at least 5 characters long.";
                $this->view('admin/create-library');
                return;
            }
            
            // Validate loan period
            if ($loanPeriod < 1 || $loanPeriod > 90) {
                $_SESSION['error'] = "Loan period must be between 1 and 90 days.";
                $this->view('admin/create-library');
                return;
            }
            
            $data = [
                'name' => $name,
                'type' => $type,
                'address' => $address,
                'loan_period_days' => $loanPeriod,
                'created_by' => $_SESSION['user_id']
            ];

            if ($this->libraryModel->create($data)) {
                // Log library creation
                Security::logActivity(
                    $_SESSION['user_id'],
                    'library_created',
                    'security',
                    "Created library: {$name} ({$type})",
                    ['library_name' => $name, 'type' => $type],
                    'info'
                );
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
            // Verify CSRF token
            if (!isset($_POST['csrf_token']) || !Security::verifyCSRFToken($_POST['csrf_token'])) {
                $_SESSION['error'] = "Invalid security token. Please try again.";
                $this->redirect('/admin/libraries');
                return;
            }
            
            $id = (int)($_POST['id'] ?? 0);
            
            // Validate library ID
            if ($id <= 0) {
                $_SESSION['error'] = "Invalid library ID.";
                $this->redirect('/admin/libraries');
                return;
            }
            
            // Verify library exists
            $library = $this->libraryModel->find($id);
            if (!$library) {
                $_SESSION['error'] = "Library not found.";
                $this->redirect('/admin/libraries');
                return;
            }
            
            // Sanitize inputs
            $name = Security::sanitizeInput($_POST['name'] ?? '');
            $type = Security::sanitizeInput($_POST['type'] ?? '');
            $address = Security::sanitizeInput($_POST['address'] ?? '');
            $loanPeriod = (int)($_POST['loan_period_days'] ?? 5);
            
            // Validate name
            if (empty($name) || strlen($name) < 3) {
                $_SESSION['error'] = "Library name must be at least 3 characters long.";
                $this->redirect('/admin/libraries');
                return;
            }
            
            // Validate type
            if (!in_array($type, ['primary', 'secondary'])) {
                $_SESSION['error'] = "Invalid library type selected.";
                $this->redirect('/admin/libraries');
                return;
            }
            
            // Validate address
            if (empty($address) || strlen($address) < 5) {
                $_SESSION['error'] = "Address must be at least 5 characters long.";
                $this->redirect('/admin/libraries');
                return;
            }
            
            // Validate loan period
            if ($loanPeriod < 1 || $loanPeriod > 90) {
                $_SESSION['error'] = "Loan period must be between 1 and 90 days.";
                $this->redirect('/admin/libraries');
                return;
            }
            
            $data = [
                'name' => $name,
                'type' => $type,
                'address' => $address,
                'loan_period_days' => $loanPeriod
            ];

            if ($this->libraryModel->updateLibrary($data, $id)) {
                // Log library update
                Security::logActivity(
                    $_SESSION['user_id'],
                    'library_updated',
                    'security',
                    "Updated library: {$name}",
                    ['library_id' => $id],
                    'info'
                );
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
            // Verify CSRF token
            if (!isset($_POST['csrf_token']) || !Security::verifyCSRFToken($_POST['csrf_token'])) {
                $_SESSION['error'] = "Invalid security token. Please try again.";
                $this->redirect('/admin/libraries');
                return;
            }
            
            $id = (int)($_POST['id'] ?? 0);
            $password = $_POST['admin_password'] ?? '';
            
            // Validate library ID
            if ($id <= 0) { 
                $_SESSION['error'] = 'Invalid library ID.'; 
                $this->redirect('/admin/libraries'); 
                return; 
            }
            
            // Verify library exists
            $library = $this->libraryModel->find($id);
            if (!$library) {
                $_SESSION['error'] = 'Library not found.';
                $this->redirect('/admin/libraries');
                return;
            }
            
            // Verify admin password
            if (empty($password)) {
                $_SESSION['error'] = 'Password is required to delete a library.';
                $this->redirect('/admin/libraries');
                return;
            }
            
            // Get current admin user
            $userId = $_SESSION['user_id'];
            $userModel = new User();
            $user = $userModel->getUserProfile($userId);
            
            if (!$user || !password_verify($password, $user['password'])) {
                // Log failed deletion attempt
                Security::logSecurity(
                    $userId,
                    'library_deletion_failed',
                    "Failed library deletion attempt - incorrect password for library: {$library['name']}",
                    'warning'
                );
                $_SESSION['error'] = 'Incorrect password. Library deletion cancelled.';
                $this->redirect('/admin/libraries');
                return;
            }
            
            // Check if library can be deleted
            list($ok, $reason) = $this->libraryModel->canDelete($id);
            if (!$ok) {
                $_SESSION['error'] = "Cannot delete library. " . $reason;
            } else if ($this->libraryModel->deleteLibraryById($id)) {
                // Log the deletion
                Security::logActivity(
                    $userId,
                    'library_deleted',
                    'security',
                    "Deleted library: {$library['name']}",
                    ['library_id' => $id],
                    'warning'
                );
                $_SESSION['success'] = "Library deleted successfully!";
            } else {
                $_SESSION['error'] = "Failed to delete library.";
            }
        }
        $this->redirect('/admin/libraries');
    }

    public function assignLibrarian() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verify CSRF token
            if (!isset($_POST['csrf_token']) || !Security::verifyCSRFToken($_POST['csrf_token'])) {
                $_SESSION['error'] = "Invalid security token. Please try again.";
                $this->redirect('/admin/libraries');
                return;
            }
            
            $libraryId = (int)($_POST['library_id'] ?? 0);
            $librarianId = (int)($_POST['librarian_id'] ?? 0);
            
            // Validate IDs
            if ($libraryId <= 0 || $librarianId <= 0) {
                $_SESSION['error'] = "Invalid library or librarian ID.";
                $this->redirect('/admin/libraries');
                return;
            }
            
            // Verify library and librarian exist
            $library = $this->libraryModel->find($libraryId);
            $librarian = $this->userModel->find($librarianId);
            
            if (!$library || !$librarian) {
                $_SESSION['error'] = "Library or librarian not found.";
                $this->redirect('/admin/libraries');
                return;
            }
            
            // Verify user is a librarian
            if ($librarian['role'] !== 'librarian') {
                $_SESSION['error'] = "Selected user is not a librarian.";
                $this->redirect('/admin/libraries');
                return;
            }
            
            if ($this->libraryModel->assignLibrarian($libraryId, $librarianId, $_SESSION['user_id'])) {
                // Log librarian assignment
                Security::logActivity(
                    $_SESSION['user_id'],
                    'librarian_assigned',
                    'security',
                    "Assigned librarian {$librarian['email']} to library {$library['name']}",
                    ['librarian_id' => $librarianId, 'library_id' => $libraryId],
                    'info'
                );
                $_SESSION['success'] = "Librarian assigned successfully!";
            } else {
                $_SESSION['error'] = "Failed to assign librarian.";
            }
        }
        $this->redirect('/admin/libraries');
    }

    public function removeLibrarianAssignment() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verify CSRF token
            if (!isset($_POST['csrf_token']) || !Security::verifyCSRFToken($_POST['csrf_token'])) {
                $_SESSION['error'] = "Invalid security token. Please try again.";
                $this->redirect('/admin/libraries');
                return;
            }
            
            $librarianId = (int)($_POST['librarian_id'] ?? 0);
            
            // Validate ID
            if ($librarianId <= 0) {
                $_SESSION['error'] = "Invalid librarian ID.";
                $this->redirect('/admin/libraries');
                return;
            }
            
            // Verify librarian exists
            $librarian = $this->userModel->find($librarianId);
            if (!$librarian) {
                $_SESSION['error'] = "Librarian not found.";
                $this->redirect('/admin/libraries');
                return;
            }
            
            if ($this->libraryModel->removeLibrarianAssignment($librarianId)) {
                // Log librarian removal
                Security::logActivity(
                    $_SESSION['user_id'],
                    'librarian_removed',
                    'security',
                    "Removed librarian assignment: {$librarian['email']}",
                    ['librarian_id' => $librarianId],
                    'info'
                );
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
            // Verify CSRF token
            if (!isset($_POST['csrf_token']) || !Security::verifyCSRFToken($_POST['csrf_token'])) {
                $_SESSION['error'] = "Invalid security token. Please try again.";
                $this->redirect('/admin/settings');
                return;
            }
            
            if (!isset($_POST['settings']) || !is_array($_POST['settings'])) {
                $_SESSION['error'] = "Invalid settings data.";
                $this->redirect('/admin/settings');
                return;
            }
            
            $updatedSettings = [];
            foreach ($_POST['settings'] as $key => $value) {
                // Sanitize setting key and value
                $key = Security::sanitizeInput($key);
                $value = Security::sanitizeInput($value);
                
                $settingsModel->updateSetting($key, $value, $_SESSION['user_id']);
                $updatedSettings[] = $key;
            }
            
            // Log settings update
            Security::logActivity(
                $_SESSION['user_id'],
                'settings_updated',
                'security',
                "Updated system settings: " . implode(', ', $updatedSettings),
                ['settings' => $updatedSettings],
                'info'
            );
            
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
        $bookModel = new Book();
        $borrowModel = new Borrow();
        $studentModel = new Student();
        
        // Get comprehensive statistics across all libraries
        $stats = [
            'total_books' => $bookModel->getTotalBooksCount(),
            'total_copies' => $bookModel->getTotalCopiesCount(),
            'total_available' => $bookModel->getAvailableBooksCount(),
            'total_students' => $studentModel->getTotalCount(),
            'active_students' => $studentModel->getActiveCount(),
            'total_borrows' => $borrowModel->getTotalBorrowsCount(),
            'overdue_books' => $borrowModel->getOverdueCount(),
            'libraries_count' => count($libraryModel->getAllWithStats()),
        ];
        
        // Calculate derived metrics
        $stats['utilization_rate'] = $stats['total_copies'] > 0 
            ? round((($stats['total_copies'] - $stats['total_available']) / $stats['total_copies']) * 100, 1) 
            : 0;
        $stats['student_engagement'] = $stats['total_students'] > 0 
            ? round(($stats['active_students'] / $stats['total_students']) * 100, 1) 
            : 0;
        
        $data = [
            'libraries' => $libraryModel->getAllWithStats(),
            'saved_reports' => $reportModel->getAllReports(),
            'stats' => $stats
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
            // DEBUG: Log POST data
            error_log("POST data: " . print_r($_POST, true));
            error_log("category_id raw: " . var_export($_POST['category_id'] ?? 'NOT SET', true));
            
            $libraryId = $_POST['library_id'];
            
            $data = [
                'title' => $_POST['title'],
                'author' => $_POST['author'],
                'isbn' => $_POST['isbn'] ?? null,
                'publisher' => $_POST['publisher'] ?? null,
                'publication_year' => $_POST['publication_year'] ?? null,
                'category_id' => !empty($_POST['category_id']) ? $_POST['category_id'] : null,
                'cover_image' => null, // Will be updated after insert if file uploaded
                'class_level' => $_POST['class_level'] ?? null,
                'total_copies' => $_POST['total_copies'] ?? 1,
                'available_copies' => $_POST['total_copies'] ?? 1,
                'library_id' => $_POST['library_id'],
                'created_by' => $_SESSION['user_id']
            ];
            
            // DEBUG: Log final data array
            error_log("Final data array category_id: " . var_export($data['category_id'], true));

            // Check if ISBN already exists in the selected library
            if (!empty($data['isbn'])) {
                if ($this->bookModel->checkISBNExists($data['isbn'], $libraryId)) {
                    $_SESSION['error'] = "A book with this ISBN already exists in the selected library.";
                    $this->redirect('/admin/create-book');
                    return;
                }
            }

            $bookId = $this->bookModel->create($data);
            if ($bookId) {
                // Handle cover image upload
                if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
                    $uploadResult = $this->bookModel->uploadBookCover($_FILES['cover_image'], $bookId);
                    if (!$uploadResult['success']) {
                        $_SESSION['warning'] = "Book added successfully, but cover image upload failed: " . $uploadResult['message'];
                    }
                }
                
                $_SESSION['success'] = "Book added successfully to " . $this->libraryModel->find($libraryId)['name'] . "!";
                $this->redirect('/admin/books');
                return;
            } else {
                $_SESSION['error'] = "Failed to add book.";
            }
        }

        // Get all categories (universal across libraries)
        require_once '../app/models/Category.php';
        $categoryModel = new Category();
        
        $data = [
            'libraries' => $this->libraryModel->all(),
            'categories' => $categoryModel->getAllCategories()
        ];
        $this->view('admin/create-book', $data);
    }

    public function editBook($id = null) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $libraryId = $_POST['library_id'];
            
            // Get current book data to preserve cover image if not updating
            $currentBook = $this->bookModel->find($id);
            $coverImage = $currentBook['cover_image'] ?? null;
            
            // Handle cover image upload
            if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
                $uploadResult = $this->bookModel->uploadBookCover($_FILES['cover_image'], $id);
                if ($uploadResult['success']) {
                    $coverImage = $uploadResult['filename'];
                } else {
                    $_SESSION['warning'] = "Failed to upload cover image: " . $uploadResult['message'];
                }
            }
            
            $data = [
                'title' => $_POST['title'],
                'author' => $_POST['author'],
                'isbn' => $_POST['isbn'] ?? null,
                'publisher' => $_POST['publisher'] ?? null,
                'publication_year' => $_POST['publication_year'] ?? null,
                'category_id' => !empty($_POST['category_id']) ? $_POST['category_id'] : null,
                'cover_image' => $coverImage,
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

        require_once '../app/models/Category.php';
        $categoryModel = new Category();
        
        $data = [
            'book' => $book,
            'libraries' => $this->libraryModel->all(),
            'categories' => $categoryModel->getAllCategories()
        ];
        
        $this->view('admin/edit-book', $data);
    }

    public function deleteBook() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = "Invalid request method.";
            $this->redirect(BASE_PATH . '/admin/books');
            return;
        }

        $bookId = $_POST['id'] ?? null;
        
        if (!$bookId) {
            $_SESSION['error'] = "Book ID is required.";
            $this->redirect(BASE_PATH . '/admin/books');
            return;
        }

        try {
            // Get book details
            $book = $this->bookModel->find($bookId);
            
            if (!$book) {
                $_SESSION['error'] = "Book not found.";
                $this->redirect(BASE_PATH . '/admin/books');
                return;
            }

            // Check if already deleted
            if ($book['deleted_at']) {
                $_SESSION['error'] = "This book has already been deleted.";
                $this->redirect(BASE_PATH . '/admin/books');
                return;
            }

            // Check for active borrows
            $activeBorrows = $this->bookModel->checkActiveBorrows($bookId);
            
            if ($activeBorrows['count'] > 0) {
                $_SESSION['error'] = "Cannot delete '{$book['title']}'. It has {$activeBorrows['count']} active borrow(s).";
                $this->redirect(BASE_PATH . '/admin/books');
                return;
            }

            // Soft delete the book
            $userId = $_SESSION['user_id'] ?? null;
            $deleted = $this->bookModel->deleteBook($bookId, $book['library_id'], $userId);
            
            if ($deleted) {
                $_SESSION['success'] = "Book '{$book['title']}' has been deleted successfully.";
            } else {
                $_SESSION['error'] = "Failed to delete the book. Please try again.";
            }
            
        } catch (Exception $e) {
            $_SESSION['error'] = "An error occurred: " . $e->getMessage();
        }

        $this->redirect(BASE_PATH . '/admin/books');
    }

    public function deletedBooks() {
        // Admin can view all deleted books across all libraries
        $deletedBooks = $this->bookModel->getDeletedBooks();
        
        $data = [
            'title' => 'Deleted Books',
            'books' => $deletedBooks
        ];
        
        $this->view('admin/deleted-books', $data);
    }

    public function restoreBook() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = "Invalid request method.";
            $this->redirect(BASE_PATH . '/admin/deleted-books');
            return;
        }

        $bookId = $_POST['id'] ?? null;
        
        if (!$bookId) {
            $_SESSION['error'] = "Book ID is required.";
            $this->redirect(BASE_PATH . '/admin/deleted-books');
            return;
        }

        try {
            $restored = $this->bookModel->restoreBook($bookId);
            
            if ($restored) {
                $_SESSION['success'] = "Book has been restored successfully.";
            } else {
                $_SESSION['error'] = "Failed to restore the book. It may not exist or is not deleted.";
            }
            
        } catch (Exception $e) {
            $_SESSION['error'] = "An error occurred: " . $e->getMessage();
        }

        $this->redirect(BASE_PATH . '/admin/deleted-books');
    }

    public function permanentlyDeleteBook() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = "Invalid request method.";
            $this->redirect(BASE_PATH . '/admin/deleted-books');
            return;
        }

        $bookId = $_POST['id'] ?? null;
        $confirm = $_POST['confirm'] ?? null;
        
        if (!$bookId) {
            $_SESSION['error'] = "Book ID is required.";
            $this->redirect(BASE_PATH . '/admin/deleted-books');
            return;
        }

        if ($confirm !== 'PERMANENTLY DELETE') {
            $_SESSION['error'] = "Confirmation text is required to permanently delete.";
            $this->redirect(BASE_PATH . '/admin/deleted-books');
            return;
        }

        try {
            $deleted = $this->bookModel->permanentlyDeleteBook($bookId);
            
            if ($deleted) {
                $_SESSION['success'] = "Book has been permanently deleted from the database.";
            } else {
                $_SESSION['error'] = "Failed to permanently delete the book.";
            }
            
        } catch (Exception $e) {
            $_SESSION['error'] = "An error occurred: " . $e->getMessage();
        }

        $this->redirect(BASE_PATH . '/admin/deleted-books');
    }

    public function activityLogs() {
        $activityLogModel = new ActivityLog();
        
        // Get filters from query string
        $filters = [
            'user_id' => $_GET['user_id'] ?? null,
            'event_type' => $_GET['event_type'] ?? null,
            'event_category' => $_GET['event_category'] ?? null,
            'severity' => $_GET['severity'] ?? null,
            'date_from' => $_GET['date_from'] ?? null,
            'date_to' => $_GET['date_to'] ?? null,
        ];
        
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $perPage = 50;
        
        // Get logs with pagination
        $result = $activityLogModel->getActivityLogs($filters, $page, $perPage);
        
        // Get statistics
        $stats = $activityLogModel->getStatistics(7);
        
        // Get all users for filter dropdown
        $users = $this->userModel->getAllUsers();
        
        $data = [
            'logs' => $result['logs'],
            'pagination' => [
                'current' => $result['page'],
                'total' => $result['totalPages'],
                'perPage' => $result['perPage'],
                'totalRecords' => $result['total']
            ],
            'filters' => $filters,
            'stats' => $stats,
            'users' => $users
        ];
        
        $this->view('admin/activity-logs', $data);
    }
}
?>