<?php
// Front Controller - Handle all requests

// Load configuration first (before starting session)
require_once '../config/config.php';
require_once '../config/database.php';

// Start session after configuration is loaded
session_start();

// Load core classes
require_once '../app/core/Router.php';
require_once '../app/core/Controller.php';
require_once '../app/core/Model.php';
require_once '../app/core/Security.php';
require_once '../app/core/Mailer.php';

// Load models
require_once '../app/models/Database.php';
require_once '../app/models/User.php';
require_once '../app/models/Auth.php';
require_once '../app/models/Library.php';
require_once '../app/models/Report.php';
require_once '../app/models/Book.php';
require_once '../app/models/Student.php';
require_once '../app/models/Borrow.php';
require_once '../app/models/ActivityLog.php';
require_once '../app/models/SystemSettings.php';
require_once '../app/models/SystemStatistics.php';
require_once '../app/models/UserPreferences.php';

// Create router
$router = new Router();

// Authentication routes
$router->add('/', 'AuthController', 'login');
$router->add('/login', 'AuthController', 'login');
$router->add('/register', 'AuthController', 'register');
$router->add('/logout', 'AuthController', 'logout');

// Password recovery routes
$router->add('/forgot-password', 'ForgotPasswordController', 'show');
$router->add('/forgot-password/send', 'ForgotPasswordController', 'sendResetLink');
$router->add('/reset-password', 'ResetPasswordController', 'show');
$router->add('/reset-password/reset', 'ResetPasswordController', 'reset');

// Admin routes
$router->add('/admin/dashboard', 'AdminController', 'dashboard');
$router->add('/admin/users', 'AdminController', 'users');
$router->add('/admin/create-user', 'AdminController', 'createUser');
$router->add('/admin/approve-user', 'AdminController', 'approveUser');
$router->add('/admin/reject-user', 'AdminController', 'rejectUser');
$router->add('/admin/libraries', 'AdminController', 'libraries');
$router->add('/admin/create-library', 'AdminController', 'createLibrary');
$router->add('/admin/edit-library', 'AdminController', 'editLibrary');
$router->add('/admin/delete-library', 'AdminController', 'deleteLibrary');
$router->add('/admin/assign-librarian', 'AdminController', 'assignLibrarian');
$router->add('/admin/remove-librarian-assignment', 'AdminController', 'removeLibrarianAssignment');
$router->add('/admin/books', 'AdminController', 'books');
$router->add('/admin/create-book', 'AdminController', 'createBook');
$router->add('/admin/edit-book', 'AdminController', 'editBook');
$router->add('/admin/delete-book', 'AdminController', 'deleteBook');
$router->add('/admin/settings', 'AdminController', 'settings');
$router->add('/admin/reports', 'AdminController', 'reports');
$router->add('/admin/generate-report', 'AdminController', 'generateReport');
$router->add('/admin/export-report', 'AdminController', 'exportReport');
$router->add('/admin/activity-logs', 'AdminController', 'activityLogs');

// Librarian routes
$router->add('/librarian/dashboard', 'LibrarianController', 'dashboard');
$router->add('/librarian/books', 'LibrarianController', 'books');
$router->add('/librarian/view-book', 'LibrarianController', 'viewBook');
$router->add('/librarian/create-book', 'LibrarianController', 'createBook');
$router->add('/librarian/edit-book', 'LibrarianController', 'editBook');
$router->add('/librarian/delete-book', 'LibrarianController', 'deleteBook');
$router->add('/librarian/students', 'LibrarianController', 'students');
$router->add('/librarian/create-student', 'LibrarianController', 'createStudent');
$router->add('/librarian/view-student', 'LibrarianController', 'viewStudent');
$router->add('/librarian/edit-student', 'LibrarianController', 'editStudent');
$router->add('/librarian/borrows', 'LibrarianController', 'borrows');
$router->add('/librarian/borrow-book', 'LibrarianController', 'borrowBook');
$router->add('/librarian/return-book', 'LibrarianController', 'returnBook');
$router->add('/librarian/quick-borrow', 'LibrarianController', 'quickBorrow');
$router->add('/librarian/reports', 'LibrarianController', 'reports');
$router->add('/librarian/generate-report', 'LibrarianController', 'generateLibraryReport');

// Report routes
$router->add('/report', 'ReportController', 'index');
$router->add('/report/generate-advanced', 'ReportController', 'generateAdvancedReport');
$router->add('/report/view', 'ReportController', 'viewSavedReport');
$router->add('/report/delete', 'ReportController', 'deleteReport');
$router->add('/report/export', 'ReportController', 'exportReport');

// System routes
$router->add('/system/maintenance', 'SystemController', 'maintenance');
$router->add('/system/backup', 'SystemController', 'backup');
$router->add('/system/preferences', 'SystemController', 'userPreferences');

// Get requested URL
$request_url = $_SERVER['REQUEST_URI'];

// Use the same BASE_PATH logic for URL processing
if (isset($_SERVER['SERVER_SOFTWARE']) && strpos($_SERVER['SERVER_SOFTWARE'], 'Apache') !== false) {
    // XAMPP or Apache server with .htaccess redirect
    $base_path = '/jacarandalibraryms';
    // Also handle cases where /public is in the URL
    $request_url = str_replace('/jacarandalibraryms/public', '', $request_url);
    $request_url = str_replace('/jacarandalibraryms', '', $request_url);
} elseif (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '8000') {
    // PHP development server - no base path to remove
    $base_path = '';
} else {
    // Default fallback for XAMPP
    $base_path = '/jacarandalibraryms';
    $request_url = str_replace($base_path, '', $request_url);
}

// Remove query string if present
if (($pos = strpos($request_url, '?')) !== false) {
    $request_url = substr($request_url, 0, $pos);
}

// Debug: Uncomment these lines to see what's happening
// if (isset($_GET['debug'])) {
//     echo "Original URL: " . $_SERVER['REQUEST_URI'] . "<br>";
//     echo "Base Path: " . $base_path . "<br>";
//     echo "Processed URL: " . $request_url . "<br>";
//     echo "Server Software: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "<br>";
//     echo "Server Port: " . ($_SERVER['SERVER_PORT'] ?? 'Unknown') . "<br>";
//     echo "Available routes: <pre>" . print_r(array_keys($router->getRoutes()), true) . "</pre>";
// }

// Dispatch the request
$router->dispatch($request_url);
?>