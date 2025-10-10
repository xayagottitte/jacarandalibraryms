<?php
// Front Controller - Handle all requests

// Start session
session_start();

// Load configuration
require_once '../config/database.php';

// Load core classes
require_once '../app/core/Router.php';
require_once '../app/core/Controller.php';
require_once '../app/core/Model.php';
require_once '../app/core/Security.php';

// Load models
require_once '../app/models/User.php';
require_once '../app/models/Auth.php';
require_once '../app/models/Database.php';

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
$router->add('/admin/settings', 'AdminController', 'settings');
$router->add('/admin/reports', 'AdminController', 'reports');
$router->add('/admin/generate-report', 'AdminController', 'generateReport');
$router->add('/admin/export-report', 'AdminController', 'exportReport');
$router->add('/admin/activity-logs', 'AdminController', 'activityLogs');

// Librarian routes
$router->add('/librarian/dashboard', 'LibrarianController', 'dashboard');
$router->add('/librarian/books', 'LibrarianController', 'books');
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
$base_path = '/jacarandalibraryms/public';
$request_url = str_replace($base_path, '', $request_url);

// Debug: Uncomment these lines to see what's happening
// echo "Original URL: " . $_SERVER['REQUEST_URI'] . "<br>";
// echo "Base Path: " . $base_path . "<br>";
// echo "Processed URL: " . $request_url . "<br>";

// Dispatch the request
$router->dispatch($request_url);
?>