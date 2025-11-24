<?php

/**
 * ============================================================================
 * JACARANDA LIBRARY MANAGEMENT SYSTEM - DEVELOPER DOCUMENTATION
 * ============================================================================
 * 
 * This documentation provides a comprehensive overview of how major systems
 * are implemented in this application. Please read this before making changes
 * to ensure consistency and avoid breaking existing functionality.
 * 
 * Last Updated: November 24, 2025
 * ============================================================================
 * 
 * TABLE OF CONTENTS:
 * 1. Authentication & Login System
 * 2. User Management System
 * 3. Book Management System
 * 4. Borrow System
 * 5. Student Management System
 * 6. Email System
 * 7. Report System
 * 8. Activity Logging & Insights
 * 9. Security Implementation
 * 10. Database Architecture
 * 
 * ============================================================================
 * 
 * 1. AUTHENTICATION & LOGIN SYSTEM
 * ============================================================================
 * 
 * Location: app/controllers/AuthController.php
 * Models: app/models/Auth.php, app/models/User.php
 * Views: app/views/auth/login.php, register.php
 * 
 * IMPLEMENTATION DETAILS:
 * 
 * A) Login Flow:
 *    - CSRF token validation (Security::verifyCSRFToken)
 *    - Input sanitization (Security::sanitizeInput)
 *    - Account lockout check (Security::isAccountLocked)
 *    - Password verification using password_verify()
 *    - Session regeneration for security
 *    - Role-based redirection (admin/librarian/student)
 *    - Failed login tracking with IP logging
 * 
 * B) Registration Flow:
 *    - Email uniqueness validation
 *    - Password strength requirements (min 8 chars)
 *    - Status set to 'pending' for admin approval
 *    - Email verification (optional)
 *    - Activity log entry on registration
 * 
 * C) Google OAuth Integration:
 *    - Uses google/apiclient library
 *    - Configuration: config/google-oauth.php
 *    - Auto-creates user account if doesn't exist
 *    - Stores google_id in users table
 *    - No password required for OAuth users
 * 
 * D) Password Reset:
 *    - Token-based system (32-byte random token)
 *    - Tokens stored in password_resets table
 *    - 1-hour expiration (configurable)
 *    - Email sent via Mailer class
 *    - Token invalidated after use
 * 
 * E) Security Features:
 *    - Account lockout after 5 failed attempts
 *    - 15-minute lockout duration
 *    - CSRF protection on all forms
 *    - Password hashing with PASSWORD_DEFAULT
 *    - Session fixation prevention
 *    - XSS protection via htmlspecialchars
 * 
 * TABLES USED:
 *    - users (id, email, password, role, status, google_id)
 *    - failed_logins (email, ip_address, attempt_time)
 *    - password_resets (email, token, expires_at)
 * 
 * KEY FILES:
 *    - app/core/Security.php (security utilities)
 *    - app/controllers/ForgotPasswordController.php
 *    - app/controllers/ResetPasswordController.php
 * 
 * ============================================================================
 * 
 * 2. USER MANAGEMENT SYSTEM
 * ============================================================================
 * 
 * Location: app/controllers/AdminController.php (users, createUser, editUser)
 * Models: app/models/User.php
 * Views: app/views/admin/users.php, create-user.php, edit-user.php
 * 
 * IMPLEMENTATION DETAILS:
 * 
 * A) User Roles:
 *    - admin: Full system access, user management, reports, settings
 *    - librarian: Book management, borrow operations, student management
 *    - student: Borrow books, view history, manage profile
 * 
 * B) User Status:
 *    - pending: Awaiting admin approval (new registrations)
 *    - active: Can log in and use system
 *    - inactive: Cannot log in (soft delete)
 * 
 * C) Admin Functions:
 *    - Approve pending users (changes status to 'active')
 *    - Reject users (DELETES from database, not soft delete)
 *    - Create users directly (bypasses approval)
 *    - Edit user details (name, email, role)
 *    - Deactivate users (sets status to 'inactive')
 *    - Reset user passwords
 * 
 * D) Profile Management:
 *    - Users can update their own profile
 *    - Controller: app/controllers/ProfileController.php
 *    - Can change name, email, password
 *    - Profile photo upload support
 *    - Activity log tracks all changes
 * 
 * E) User Preferences:
 *    - Model: app/models/UserPreferences.php
 *    - Stores per-user settings (theme, notifications, etc.)
 *    - Dashboard layout preferences
 *    - Email notification preferences
 * 
 * TABLES USED:
 *    - users (all user data)
 *    - user_preferences (user-specific settings)
 *    - activity_logs (tracks user actions)
 * 
 * ============================================================================
 * 
 * 3. BOOK MANAGEMENT SYSTEM
 * ============================================================================
 * 
 * Location: app/controllers/AdminController.php, LibrarianController.php
 * Models: app/models/Book.php, app/models/Category.php
 * Views: app/views/admin/books.php, create-book.php, edit-book.php
 * 
 * IMPLEMENTATION DETAILS:
 * 
 * A) Book Data Structure:
 *    - ISBN (unique identifier)
 *    - Title, author, publisher, publication year
 *    - Category (foreign key to categories table)
 *    - Library assignment (foreign key to libraries table)
 *    - Quantity (total copies)
 *    - Available quantity (calculated dynamically)
 *    - Book cover image (stored in public/assets/img/books/)
 * 
 * B) Book Operations:
 *    Admin:
 *    - Create book (with ISBN validation)
 *    - Edit book details
 *    - Delete book (soft delete - sets deleted_at timestamp)
 *    - Restore deleted books
 *    - Permanently delete books
 *    
 *    Librarian:
 *    - Same as admin but library-scoped
 *    - Can only manage books in their assigned library
 * 
 * C) Category System:
 *    - Categories can be library-specific or universal
 *    - Admin can create categories for any library
 *    - Librarians can create categories for their library
 *    - Books must have a category assigned
 *    - Categories can be deleted if no books use them
 * 
 * D) Book Availability:
 *    - Calculated as: quantity - (active borrows + reserved)
 *    - Real-time availability checking
 *    - Prevents overbooking
 *    - Updates on borrow/return/reserve actions
 * 
 * E) Book Search & Filtering:
 *    - Search by: title, author, ISBN, publisher
 *    - Filter by: category, library, availability
 *    - Pagination support (configurable items per page)
 *    - AJAX-based for better UX
 * 
 * TABLES USED:
 *    - books (id, isbn, title, author, category_id, library_id, quantity)
 *    - categories (id, name, library_id, description)
 *    - libraries (id, name, location, librarian_id)
 * 
 * KEY METHODS:
 *    - Book::getAllBooksWithLibrary() - Gets books with JOIN data
 *    - Book::getAvailableQuantity() - Calculates real-time availability
 *    - Book::softDelete() - Marks book as deleted
 *    - Book::restore() - Restores soft-deleted book
 * 
 * ============================================================================
 * 
 * 4. BORROW SYSTEM
 * ============================================================================
 * 
 * Location: app/controllers/LibrarianController.php
 * Models: app/models/Borrow.php, app/models/Book.php, app/models/Student.php
 * Views: app/views/librarian/borrow-book.php, borrows.php, return-book.php
 * 
 * IMPLEMENTATION DETAILS:
 * 
 * A) Borrow Flow:
 *    1. Check student eligibility (no overdue books, not at borrow limit)
 *    2. Verify book availability (available_quantity > 0)
 *    3. Calculate due date (library's loan_period setting)
 *    4. Create borrow record (status: 'borrowed')
 *    5. Decrement book available_quantity
 *    6. Log activity
 *    7. Send email notification (optional)
 * 
 * B) Return Flow:
 *    1. Locate borrow record (by borrow_id or book+student)
 *    2. Check if overdue (current_date > due_date)
 *    3. Calculate fine if overdue (configurable rate)
 *    4. Update borrow record (status: 'returned', return_date: now)
 *    5. Reset is_lost flag if book was marked lost
 *    6. Increment book available_quantity
 *    7. Create fine record if applicable
 *    8. Log activity
 * 
 * C) Lost Book Handling:
 *    - Librarian marks book as lost (is_lost = 1)
 *    - Records who marked it (lost_marked_by) and when (lost_marked_at)
 *    - Book remains unavailable until found or replaced
 *    - If book is returned, is_lost resets to 0
 *    - Lost books excluded from dashboard counts when returned
 * 
 * D) Overdue Management:
 *    - System checks daily for overdue books (cron job recommended)
 *    - Email reminders sent at configurable intervals
 *    - Fine calculation: days_overdue * daily_rate
 *    - Grace period support (configurable)
 *    - Admin can waive or adjust fines
 * 
 * E) Borrow Rules:
 *    - Max books per student (default: 3, configurable per library)
 *    - Loan period (default: 14 days, configurable per library)
 *    - Students with overdue books cannot borrow
 *    - Students with unpaid fines cannot borrow (configurable threshold)
 * 
 * F) Reservation System:
 *    - Students can reserve unavailable books
 *    - Queue system (FIFO)
 *    - Email notification when book becomes available
 *    - Reservation expires after 24 hours if not picked up
 *    - Settings: app/views/admin/reservation-settings.php
 * 
 * TABLES USED:
 *    - borrows (id, book_id, student_id, borrow_date, due_date, return_date, 
 *               status, is_lost, lost_marked_at, lost_marked_by)
 *    - fines (id, borrow_id, amount, paid, payment_date)
 *    - reservations (id, book_id, student_id, reserved_date, status)
 * 
 * KEY METHODS:
 *    - Borrow::createBorrow() - Creates new borrow record
 *    - Borrow::returnBook() - Handles book return with fine calculation
 *    - Borrow::getOverdueBooks() - Gets all overdue borrows
 *    - Borrow::getLostBooks() - Gets lost books (excludes returned)
 *    - Borrow::markAsLost() - Marks book as lost
 * 
 * ============================================================================
 * 
 * 5. STUDENT MANAGEMENT SYSTEM
 * ============================================================================
 * 
 * Location: app/controllers/LibrarianController.php
 * Models: app/models/Student.php
 * Views: app/views/librarian/students.php, add-student.php, edit-student.php
 * 
 * IMPLEMENTATION DETAILS:
 * 
 * A) Student Data:
 *    - Name, email, phone number
 *    - Student ID (unique identifier)
 *    - Course/program, year level
 *    - Library assignment (can borrow from specific library)
 *    - Status (active/inactive)
 * 
 * B) Student Operations:
 *    - Add student (with duplicate checking)
 *    - Edit student details
 *    - View borrow history
 *    - Check fines and outstanding books
 *    - Soft delete (sets status to 'inactive')
 * 
 * C) Student Validations:
 *    - Email must be unique
 *    - Student ID must be unique
 *    - Phone number format validation
 *    - Cannot delete student with active borrows
 * 
 * D) Student Dashboard:
 *    - Current borrowed books
 *    - Borrow history
 *    - Outstanding fines
 *    - Reservation queue
 *    - Overdue notifications
 * 
 * TABLES USED:
 *    - students (id, student_id, name, email, phone, course, year, 
 *                library_id, status)
 * 
 * ============================================================================
 * 
 * 6. EMAIL SYSTEM
 * ============================================================================
 * 
 * Location: app/core/Mailer.php
 * Library: PHPMailer (vendor/phpmailer/phpmailer)
 * Configuration: config/config.php (SMTP settings)
 * 
 * IMPLEMENTATION DETAILS:
 * 
 * A) Email Configuration:
 *    - SMTP host, port, username, password in config/config.php
 *    - Supports Gmail, Outlook, custom SMTP servers
 *    - TLS/SSL encryption support
 *    - From email and name configurable
 * 
 * B) Email Types Sent:
 *    - Welcome email (new user registration)
 *    - Password reset email (with secure token link)
 *    - Borrow confirmation
 *    - Return confirmation
 *    - Overdue book reminders
 *    - Fine notifications
 *    - Reservation ready notifications
 *    - Book availability alerts
 * 
 * C) Email Templates:
 *    - HTML email templates with inline CSS
 *    - Responsive design
 *    - System logo and branding
 *    - Unsubscribe option (for notifications)
 * 
 * D) Email Queue (Recommended Enhancement):
 *    - Current: Synchronous sending (blocks request)
 *    - Recommended: Queue system for background processing
 *    - Prevents timeout on bulk emails
 * 
 * E) Error Handling:
 *    - Graceful failure (doesn't break app if email fails)
 *    - Error logging to logs/email_errors.log
 *    - Retry mechanism for failed emails (optional)
 * 
 * KEY METHODS:
 *    - Mailer::send() - Sends single email
 *    - Mailer::sendPasswordReset() - Password reset email
 *    - Mailer::sendOverdueReminder() - Overdue notification
 * 
 * TROUBLESHOOTING:
 *    - Check SMTP credentials in config.php
 *    - Verify firewall allows SMTP port (587 or 465)
 *    - Enable "Less secure app access" for Gmail (or use App Password)
 *    - Check logs/email_errors.log for detailed errors
 * 
 * ============================================================================
 * 
 * 7. REPORT SYSTEM
 * ============================================================================
 * 
 * Location: app/controllers/ReportController.php
 * Models: app/models/Report.php
 * Views: app/views/admin/reports.php, view-report.php
 * 
 * IMPLEMENTATION DETAILS:
 * 
 * A) Report Types:
 *    1. Borrow Statistics
 *       - Total borrows, returns, overdue by date range
 *       - Breakdown by library, category, student
 *    
 *    2. Book Inventory
 *       - Total books, available, borrowed, lost
 *       - Breakdown by category and library
 *    
 *    3. Student Activity
 *       - Most active borrowers
 *       - Overdue offenders
 *       - Fine payments
 *    
 *    4. Financial Reports
 *       - Fines collected vs outstanding
 *       - Revenue by period
 *    
 *    5. Library Performance
 *       - Circulation rates
 *       - Popular books/categories
 *       - Return rates
 * 
 * B) Report Generation:
 *    - Filters: date range, library, category, status
 *    - Export formats: PDF, Excel, CSV
 *    - Scheduled reports (daily/weekly/monthly)
 *    - Email delivery option
 * 
 * C) Report Storage:
 *    - Generated reports saved to database
 *    - PDF files stored in public/assets/reports/
 *    - Automatic cleanup of old reports (configurable retention)
 * 
 * D) Charts & Visualizations:
 *    - Chart.js library for interactive charts
 *    - Line charts for trends
 *    - Bar charts for comparisons
 *    - Pie charts for distributions
 * 
 * TABLES USED:
 *    - reports (id, type, parameters, generated_at, generated_by, file_path)
 * 
 * KEY METHODS:
 *    - Report::generateBorrowReport()
 *    - Report::generateInventoryReport()
 *    - Report::exportToPDF()
 *    - Report::exportToExcel()
 * 
 * ============================================================================
 * 
 * 8. ACTIVITY LOGGING & INSIGHTS
 * ============================================================================
 * 
 * Location: app/models/ActivityLog.php, app/controllers/AdminController.php
 * Views: app/views/admin/activity-logs.php, dashboard.php
 * 
 * IMPLEMENTATION DETAILS:
 * 
 * A) Activity Logging:
 *    - Tracks all major user actions
 *    - Records: user_id, action, description, ip_address, timestamp
 *    - Actions logged:
 *      * User login/logout
 *      * Book borrow/return
 *      * User/book/student CRUD operations
 *      * Report generation
 *      * Settings changes
 *      * Password resets
 * 
 * B) Dashboard Insights (Admin):
 *    - Total users (active/pending/inactive breakdown)
 *    - Total books (available/borrowed/lost)
 *    - Active borrows and overdue count
 *    - Recent activities (last 10 actions)
 *    - System health indicators
 *    - Quick stats cards
 * 
 * C) Dashboard Insights (Librarian):
 *    - Library-scoped statistics
 *    - Books in their library
 *    - Active borrows from their library
 *    - Pending returns
 *    - Overdue books in their library
 * 
 * D) Analytics:
 *    - Model: app/models/SystemStatistics.php
 *    - Caches frequently accessed statistics
 *    - Refreshes hourly (configurable)
 *    - Provides data for dashboard widgets
 * 
 * E) Audit Trail:
 *    - Complete audit trail of all database changes
 *    - Who did what, when, and from where
 *    - Searchable and filterable
 *    - Retention policy (default: 90 days)
 * 
 * TABLES USED:
 *    - activity_logs (id, user_id, action, description, ip_address, 
 *                     created_at)
 * 
 * KEY METHODS:
 *    - ActivityLog::log() - Logs an activity
 *    - ActivityLog::getRecentActivities() - Gets recent logs
 *    - SystemStatistics::getDashboardStats() - Gets cached stats
 * 
 * ============================================================================
 * 
 * 9. SECURITY IMPLEMENTATION
 * ============================================================================
 * 
 * Location: app/core/Security.php
 * 
 * SECURITY MEASURES IMPLEMENTED:
 * 
 * A) Input Validation & Sanitization:
 *    - Security::sanitizeInput() - Removes malicious content
 *    - Security::validateEmail() - Email format validation
 *    - Security::validateURL() - URL validation
 *    - htmlspecialchars() used in all views for XSS prevention
 * 
 * B) CSRF Protection:
 *    - Token generation: Security::generateCSRFToken()
 *    - Token verification: Security::verifyCSRFToken()
 *    - Tokens stored in session
 *    - All forms include CSRF token
 * 
 * C) Password Security:
 *    - PASSWORD_DEFAULT algorithm (bcrypt)
 *    - Minimum 8 characters requirement
 *    - Password strength validation
 *    - Secure password reset with tokens
 * 
 * D) Session Security:
 *    - session_regenerate_id() on login
 *    - HTTPOnly and Secure flags on cookies
 *    - Session timeout (configurable)
 *    - Session fixation prevention
 * 
 * E) SQL Injection Prevention:
 *    - PDO prepared statements throughout
 *    - No raw SQL queries with user input
 *    - Parameter binding for all queries
 * 
 * F) Account Lockout:
 *    - 5 failed attempts triggers lockout
 *    - 15-minute lockout duration
 *    - IP address tracking
 *    - Failed login logging
 * 
 * G) File Upload Security:
 *    - File type validation (whitelist)
 *    - File size limits
 *    - Secure file naming (no user input in filename)
 *    - Storage outside web root (recommended)
 * 
 * H) Directory Protection:
 *    - .htaccess files deny access to sensitive directories
 *    - config/, app/, vendor/, databasesql/ protected
 *    - Directory listing disabled (Options -Indexes)
 *    - Sensitive file extensions blocked (.sql, .log, .ini, etc.)
 * 
 * I) Access Control:
 *    - Role-based access control (RBAC)
 *    - Middleware checks on protected routes
 *    - Library-scoped access for librarians
 *    - Admin-only routes protected
 * 
 * J) Error Handling:
 *    - Production: Generic error messages
 *    - Development: Detailed errors
 *    - Error logging to files (not displayed)
 *    - No sensitive data in error messages
 * 
 * ============================================================================
 * 
 * 10. DATABASE ARCHITECTURE
 * ============================================================================
 * 
 * Database: MySQL (multi_library_system)
 * Location: config/database.php, app/models/Database.php
 * 
 * CORE TABLES:
 * 
 * users:
 *    - id, email, password, name, role, status, google_id
 *    - created_at, updated_at
 *    - Indexes: email (unique), google_id, status
 * 
 * students:
 *    - id, student_id (unique), name, email, phone, course, year
 *    - library_id (FK), status, created_at
 *    - Indexes: student_id (unique), email, library_id
 * 
 * books:
 *    - id, isbn (unique), title, author, publisher, publication_year
 *    - category_id (FK), library_id (FK), quantity, cover_image
 *    - deleted_at (soft delete), created_at, updated_at
 *    - Indexes: isbn (unique), category_id, library_id
 * 
 * categories:
 *    - id, name, description, library_id (FK, NULL for universal)
 *    - created_at, updated_at
 *    - Indexes: library_id
 * 
 * libraries:
 *    - id, name, location, contact, librarian_id (FK)
 *    - loan_period (days), max_books_per_student
 *    - created_at, updated_at
 *    - Indexes: librarian_id
 * 
 * borrows:
 *    - id, book_id (FK), student_id (FK), librarian_id (FK)
 *    - borrow_date, due_date, return_date, status
 *    - is_lost, lost_marked_at, lost_marked_by
 *    - created_at, updated_at
 *    - Indexes: book_id, student_id, status, is_lost
 *    - Statuses: 'borrowed', 'returned', 'overdue'
 * 
 * fines:
 *    - id, borrow_id (FK), amount, paid, payment_date
 *    - created_at, updated_at
 *    - Indexes: borrow_id, paid
 * 
 * reservations:
 *    - id, book_id (FK), student_id (FK), reserved_date
 *    - status, expires_at, created_at
 *    - Indexes: book_id, student_id, status
 * 
 * activity_logs:
 *    - id, user_id (FK), action, description, ip_address
 *    - created_at
 *    - Indexes: user_id, action, created_at
 * 
 * reports:
 *    - id, type, parameters (JSON), generated_at, generated_by (FK)
 *    - file_path, created_at
 *    - Indexes: type, generated_by, generated_at
 * 
 * password_resets:
 *    - email, token, expires_at, created_at
 *    - Indexes: email, token
 * 
 * failed_logins:
 *    - email, ip_address, attempt_time
 *    - Indexes: email, ip_address, attempt_time
 * 
 * system_settings:
 *    - setting_key (unique), setting_value, updated_at
 *    - Indexes: setting_key (unique)
 * 
 * RELATIONSHIPS:
 *    - users -> libraries (one-to-many, librarian_id)
 *    - students -> libraries (many-to-one)
 *    - books -> categories (many-to-one)
 *    - books -> libraries (many-to-one)
 *    - borrows -> books, students, users (many-to-one each)
 *    - fines -> borrows (one-to-one)
 *    - reservations -> books, students (many-to-one each)
 *    - activity_logs -> users (many-to-one)
 * 
 * FOREIGN KEY CONSTRAINTS:
 *    - ON DELETE CASCADE: fines.borrow_id
 *    - ON DELETE SET NULL: libraries.librarian_id
 *    - ON DELETE RESTRICT: All other FKs (prevents orphan records)
 * 
 * ============================================================================
 * 
 * MIGRATION & MAINTENANCE:
 * 
 * Database Backup:
 *    - Model: app/models/DatabaseBackup.php
 *    - Admin: app/views/admin/system-maintainance.php
 *    - Automated backups (cron recommended)
 *    - Backup location: backups/ (not in web root)
 * 
 * Schema Changes:
 *    - All schema changes documented in databasesql/ folder
 *    - Migration scripts for version updates
 *    - Always backup before schema changes
 * 
 * ============================================================================
 * 
 * DEVELOPMENT BEST PRACTICES:
 * 
 * 1. Always use prepared statements for database queries
 * 2. Log all important actions using ActivityLog::log()
 * 3. Validate and sanitize all user inputs
 * 4. Use CSRF tokens on all forms
 * 5. Follow MVC pattern strictly (no business logic in views)
 * 6. Use consistent naming conventions (snake_case for DB, camelCase for PHP)
 * 7. Comment complex logic and algorithms
 * 8. Test all changes in development environment first
 * 9. Check for existing methods before creating new ones
 * 10. Update this documentation when adding new features
 * 
 * ============================================================================
 * 
 * COMMON DEBUGGING TIPS:
 * 
 * 1. Check logs/error.log for PHP errors
 * 2. Check logs/email_errors.log for email issues
 * 3. Check browser console for JavaScript errors
 * 4. Verify database connections in config/database.php
 * 5. Clear sessions if experiencing login issues
 * 6. Check .htaccess if getting 403/404 errors
 * 7. Verify file permissions (755 for directories, 644 for files)
 * 8. Use var_dump() and exit() for quick debugging (remove before commit)
 * 9. Check activity_logs table to trace user actions
 * 10. Verify CSRF tokens are present in forms
 * 
 * ============================================================================
 * 
 * FUTURE ENHANCEMENTS TO CONSIDER:
 * 
 * 1. API endpoints for mobile app integration
 * 2. Email queue system for background processing
 * 3. SMS notifications for overdue books
 * 4. Barcode scanning for faster book checkout
 * 5. Multi-language support (i18n)
 * 6. Advanced search with Elasticsearch
 * 7. Book recommendation system
 * 8. Integration with external library systems
 * 9. Mobile responsive improvements
 * 10. Real-time notifications using WebSockets
 * 
 * ============================================================================
 * 
 * For questions or clarifications, please refer to:
 * - README.md for setup instructions
 * - Individual controller/model files for detailed method documentation
 * - Database schema in databasesql/schema.sql
 * 
 * ============================================================================
 */

class AuthController extends Controller {
    private $authModel;

    public function __construct() {
        // Session is already started in index.php
        $this->authModel = new Auth();
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verify CSRF token
            if (!isset($_POST['csrf_token']) || !Security::verifyCSRFToken($_POST['csrf_token'])) {
                $_SESSION['error'] = "Invalid security token. Please try again.";
                $this->redirect('/login');
                return;
            }
            
            // Sanitize and validate inputs
            $email = Security::sanitizeInput($_POST['email'] ?? '');
            $password = $_POST['password'] ?? ''; // Don't sanitize password (may have special chars)
            
            // Validate email format
            if (!Security::validateEmail($email)) {
                $_SESSION['error'] = "Please enter a valid email address.";
                $this->redirect('/login');
                return;
            }
            
            // Validate password not empty
            if (empty($password)) {
                $_SESSION['error'] = "Password is required.";
                $this->redirect('/login');
                return;
            }
            
            // Pre-check if account is locked
            $lockStatus = Security::isAccountLocked($email);
            
            if ($lockStatus['locked']) {
                $remainingTime = Security::formatLockoutTime($lockStatus['remaining_time']);
                $_SESSION['error'] = "Account temporarily locked due to too many failed login attempts. Please try again in {$remainingTime}.";
                $_SESSION['lockout_info'] = [
                    'locked' => true,
                    'remaining_time' => $lockStatus['remaining_time'],
                    'attempts' => $lockStatus['attempts']
                ];
                $this->redirect('/login');
                return;
            }
            
            try {
                if ($this->authModel->login($email, $password)) {
                    $_SESSION['success'] = "Login successful!";
                    
                    // Redirect based on role
                    if ($_SESSION['role'] === 'super_admin') {
                        $this->redirect('/admin/dashboard');
                    } else {
                        $this->redirect('/librarian/dashboard');
                    }
                    return;
                } else {
                    // Get updated attempt count after failed login
                    $lockStatus = Security::isAccountLocked($email);
                    
                    if ($lockStatus['locked']) {
                        $remainingTime = Security::formatLockoutTime($lockStatus['remaining_time']);
                        $_SESSION['error'] = "Too many failed attempts. Account locked for {$remainingTime}.";
                        $_SESSION['lockout_info'] = [
                            'locked' => true,
                            'remaining_time' => $lockStatus['remaining_time'],
                            'attempts' => $lockStatus['attempts']
                        ];
                    } else {
                        $_SESSION['error'] = "Invalid email or password. {$lockStatus['remaining_attempts']} attempt(s) remaining.";
                        $_SESSION['lockout_info'] = [
                            'locked' => false,
                            'attempts' => $lockStatus['attempts'],
                            'remaining_attempts' => $lockStatus['remaining_attempts']
                        ];
                    }
                }
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
                
                // Check if account is now locked after exception
                $lockStatus = Security::isAccountLocked($email);
                $_SESSION['lockout_info'] = [
                    'locked' => $lockStatus['locked'],
                    'attempts' => $lockStatus['attempts'],
                    'remaining_attempts' => $lockStatus['remaining_attempts'] ?? 0,
                    'remaining_time' => $lockStatus['remaining_time'] ?? 0
                ];
            }
        }
        
        $this->view('auth/login');
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verify CSRF token
            if (!isset($_POST['csrf_token']) || !Security::verifyCSRFToken($_POST['csrf_token'])) {
                $_SESSION['error'] = "Invalid security token. Please try again.";
                $this->redirect('/register');
                return;
            }
            
            // Sanitize inputs
            $username = Security::sanitizeInput($_POST['username'] ?? '');
            $full_name = Security::sanitizeInput($_POST['full_name'] ?? '');
            $email = Security::sanitizeInput($_POST['email'] ?? '');
            $phone = Security::sanitizeInput($_POST['phone'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            
            // Validate username
            if (!Security::validateUsername($username)) {
                $_SESSION['error'] = "Username must be 3-50 characters (letters, numbers, underscore, hyphen only).";
                $this->redirect('/register');
                return;
            }
            
            // Validate full name
            if (empty($full_name) || strlen($full_name) < 3) {
                $_SESSION['error'] = "Full name must be at least 3 characters long.";
                $this->redirect('/register');
                return;
            }
            
            // Validate email
            if (!Security::validateEmail($email)) {
                $_SESSION['error'] = "Please enter a valid email address.";
                $this->redirect('/register');
                return;
            }
            
            // Validate phone
            if (!empty($phone) && !Security::validatePhone($phone)) {
                $_SESSION['error'] = "Please enter a valid phone number.";
                $this->redirect('/register');
                return;
            }
            
            // Validate password
            $passwordValidation = Security::validatePassword($password);
            if (!$passwordValidation['valid']) {
                $_SESSION['error'] = implode(' ', $passwordValidation['errors']);
                $this->redirect('/register');
                return;
            }
            
            // Additional XSS protection for password field
            if (!Security::isPasswordSafeFromXSS($password)) {
                $_SESSION['error'] = "Password contains invalid characters.";
                Security::logSecurity(
                    null,
                    'registration_xss_attempt',
                    "Registration attempt with XSS in password field from email: {$email}",
                    'critical'
                );
                $this->redirect('/register');
                return;
            }
            
            // Confirm password match
            if ($password !== $confirm_password) {
                $_SESSION['error'] = "Passwords do not match.";
                $this->redirect('/register');
                return;
            }
            
            $data = [
                'username' => $username,
                'full_name' => $full_name,
                'email' => $email,
                'phone' => $phone,
                'password' => $password,
                'role' => 'librarian', // Default role for registrations
                'status' => 'pending'
            ];
            
            // Check if user already exists
            $userModel = new User();
            if ($userModel->findByEmail($data['email'])) {
                $_SESSION['error'] = "Email already registered.";
            } elseif ($userModel->findByUsername($data['username'])) {
                $_SESSION['error'] = "Username already taken.";
            } else {
                // Hash password
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                
                if ($this->authModel->register($data)) {
                    $_SESSION['success'] = "Registration successful! Please wait for admin approval.";
                    $this->redirect('/login');
                    return;
                } else {
                    $_SESSION['error'] = "Registration failed. Please try again.";
                }
            }
        }
        
        $this->view('auth/register');
    }

    public function logout() {
        $this->authModel->logout();
        $this->redirect('/login');
    }

    public function googleAuth() {
        require_once '../vendor/autoload.php';
        
        $client = new Google_Client();
        $client->setClientId(getenv('GOOGLE_CLIENT_ID') ?: 'your-google-client-id');
        $client->setClientSecret(getenv('GOOGLE_CLIENT_SECRET') ?: 'your-google-client-secret');
        $client->setRedirectUri(getenv('BASE_URL') ? getenv('BASE_URL') . '/google-callback' : 'http://localhost/jacarandalibraryms/google-callback');
        $client->addScope('email');
        $client->addScope('profile');
        
        $authUrl = $client->createAuthUrl();
        header('Location: ' . $authUrl);
        exit();
    }

    public function googleCallback() {
        require_once '../vendor/autoload.php';
        
        if (!isset($_GET['code'])) {
            $_SESSION['error'] = "Google authentication failed. No authorization code received.";
            $this->redirect('/login');
            return;
        }
        
        $client = new Google_Client();
        $client->setClientId(getenv('GOOGLE_CLIENT_ID') ?: 'your-google-client-id');
        $client->setClientSecret(getenv('GOOGLE_CLIENT_SECRET') ?: 'your-google-client-secret');
        $client->setRedirectUri(getenv('BASE_URL') ? getenv('BASE_URL') . '/google-callback' : 'http://localhost/jacarandalibraryms/google-callback');
        
        try {
            $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
            
            if (isset($token['error'])) {
                $_SESSION['error'] = "Google authentication failed: " . $token['error_description'];
                $this->redirect('/login');
                return;
            }
            
            $client->setAccessToken($token);
            
            // Get user info from Google
            $oauth2 = new Google_Service_Oauth2($client);
            $userInfo = $oauth2->userinfo->get();
            
            $email = $userInfo->email;
            $name = $userInfo->name;
            $googleId = $userInfo->id;
            $picture = $userInfo->picture;
            
            // Check if user exists
            $userModel = new User();
            $existingUser = $userModel->findByEmail($email);
            
            if ($existingUser) {
                // Update Google ID and profile photo if not set
                if (empty($existingUser['google_id'])) {
                    $this->updateUserGoogleInfo($existingUser['id'], $googleId, $picture);
                }
                
                // Log the user in
                $_SESSION['user_id'] = $existingUser['id'];
                $_SESSION['username'] = $existingUser['username'];
                $_SESSION['role'] = $existingUser['role'];
                $_SESSION['library_id'] = $existingUser['library_id'];
                $_SESSION['success'] = "Welcome back, " . $existingUser['full_name'] . "!";
                
                // Redirect based on role
                if ($existingUser['role'] === 'super_admin') {
                    $this->redirect('/admin/dashboard');
                } else {
                    $this->redirect('/librarian/dashboard');
                }
            } else {
                // Create new user account
                $username = $this->generateUniqueUsername($email, $name);
                
                $userData = [
                    'username' => $username,
                    'full_name' => $name,
                    'email' => $email,
                    'password' => password_hash(uniqid(), PASSWORD_DEFAULT), // Random password
                    'role' => 'librarian', // Default role
                    'status' => 'pending', // Requires admin approval
                    'google_id' => $googleId,
                    'profile_photo' => $this->downloadGoogleProfileImage($picture, $googleId)
                ];
                
                if ($this->authModel->register($userData)) {
                    $_SESSION['success'] = "Account created with Google! Please wait for admin approval before you can access the system.";
                } else {
                    $_SESSION['error'] = "Failed to create account. Please try again.";
                }
                
                $this->redirect('/login');
            }
            
        } catch (Exception $e) {
            $_SESSION['error'] = "Google authentication failed: " . $e->getMessage();
            $this->redirect('/login');
        }
    }

    private function updateUserGoogleInfo($userId, $googleId, $picture) {
        try {
            $db = new Database();
            $conn = $db->connect();
            
            $profilePhoto = $this->downloadGoogleProfileImage($picture, $googleId);
            
            $query = "UPDATE users SET google_id = ?, profile_photo = ?, updated_at = NOW() WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->execute([$googleId, $profilePhoto, $userId]);
        } catch (Exception $e) {
            // Silently fail - not critical
            error_log("Failed to update Google info: " . $e->getMessage());
        }
    }

    private function generateUniqueUsername($email, $name) {
        $userModel = new User();
        
        // Try email prefix first
        $baseUsername = strtolower(explode('@', $email)[0]);
        $baseUsername = preg_replace('/[^a-z0-9]/', '', $baseUsername);
        
        if (strlen($baseUsername) < 3) {
            // Use name if email prefix is too short
            $baseUsername = strtolower(preg_replace('/[^a-z0-9]/', '', $name));
        }
        
        $username = $baseUsername;
        $counter = 1;
        
        // Keep trying until we find a unique username
        while ($userModel->findByUsername($username)) {
            $username = $baseUsername . $counter;
            $counter++;
        }
        
        return $username;
    }

    private function downloadGoogleProfileImage($pictureUrl, $googleId) {
        if (empty($pictureUrl)) {
            return null;
        }
        
        try {
            // Create directory if it doesn't exist
            $uploadDir = '../public/assets/img/profiles/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            // Download image
            $imageContent = file_get_contents($pictureUrl);
            if ($imageContent === false) {
                return null;
            }
            
            // Save with unique filename
            $filename = 'google_' . $googleId . '_' . time() . '.jpg';
            $filePath = $uploadDir . $filename;
            
            if (file_put_contents($filePath, $imageContent)) {
                return '/assets/img/profiles/' . $filename;
            }
            
        } catch (Exception $e) {
            error_log("Failed to download Google profile image: " . $e->getMessage());
        }
        
        return null;
    }
}
?>