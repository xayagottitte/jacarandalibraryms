<?php
class TeacherController extends Controller {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }
    
    private function requireTeacher() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'teacher') {
            Security::logSecurity(
                $_SESSION['user_id'] ?? null,
                'unauthorized_access_attempt',
                'Attempted to access teacher area without proper role',
                'warning'
            );
            $_SESSION['error'] = "Access denied. Teacher privileges required.";
            $this->redirect('/login');
            exit;
        }
        
        // Check if user is active
        if (isset($_SESSION['status']) && $_SESSION['status'] !== 'active') {
            $_SESSION['error'] = "Your account is not active.";
            $this->redirect('/login');
            exit;
        }
    }

    public function dashboard() {
        $this->requireTeacher();
        
        // Get teacher statistics
        $teacherId = $_SESSION['user_id'];
        $stats = $this->getTeacherStats($teacherId);
        
        // Log dashboard access
        Security::logActivity(
            $teacherId,
            'dashboard_access',
            'data',
            'Accessed teacher dashboard'
        );
        
        $this->view('teacher/dashboard', ['stats' => $stats]);
    }

    public function borrow() {
        $this->requireTeacher();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verify CSRF token
            if (!isset($_POST['csrf_token']) || !Security::verifyCSRFToken($_POST['csrf_token'])) {
                $_SESSION['error'] = "Invalid security token.";
                $this->redirect('/teacher/borrow');
                return;
            }
            
            // Handle book borrowing for teachers
            $this->processBorrow();
        } else {
            // Get available books
            $books = $this->getAvailableBooks();
            $this->view('teacher/borrow', ['books' => $books]);
        }
    }

    public function borrowing() {
        $this->requireTeacher();
        
        $teacherId = $_SESSION['user_id'];
        
        // Get student borrowing data for teacher's classes
        $borrowingData = $this->getStudentBorrowingData($teacherId);
        
        Security::logActivity(
            $teacherId,
            'view_student_borrowing',
            'data',
            'Viewed student borrowing statistics'
        );
        
        $this->view('teacher/borrowing', ['borrowingData' => $borrowingData]);
    }

    public function resources() {
        $this->requireTeacher();
        
        // Get digital resources
        $resources = $this->getDigitalResources();
        
        Security::logActivity(
            $_SESSION['user_id'],
            'access_resources',
            'data',
            'Accessed digital resources'
        );
        
        $this->view('teacher/resources', ['resources' => $resources]);
    }

    public function analytics() {
        $this->requireTeacher();
        
        $teacherId = $_SESSION['user_id'];
        
        // Get analytics data
        $analytics = $this->getTeacherAnalytics($teacherId);
        
        Security::logActivity(
            $teacherId,
            'view_analytics',
            'data',
            'Viewed analytics dashboard'
        );
        
        $this->view('teacher/analytics', ['analytics' => $analytics]);
    }

    public function reserve() {
        $this->requireTeacher();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verify CSRF token
            if (!isset($_POST['csrf_token']) || !Security::verifyCSRFToken($_POST['csrf_token'])) {
                $_SESSION['error'] = "Invalid security token.";
                $this->redirect('/teacher/reserve');
                return;
            }
            
            $this->processReservation();
        } else {
            $books = $this->getAvailableBooks();
            $this->view('teacher/reserve', ['books' => $books]);
        }
    }

    public function upload() {
        $this->requireTeacher();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verify CSRF token
            if (!isset($_POST['csrf_token']) || !Security::verifyCSRFToken($_POST['csrf_token'])) {
                $_SESSION['error'] = "Invalid security token.";
                $this->redirect('/teacher/upload');
                return;
            }
            
            $this->processUpload();
        } else {
            $this->view('teacher/upload');
        }
    }

    public function recommend() {
        $this->requireTeacher();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verify CSRF token
            if (!isset($_POST['csrf_token']) || !Security::verifyCSRFToken($_POST['csrf_token'])) {
                $_SESSION['error'] = "Invalid security token.";
                $this->redirect('/teacher/recommend');
                return;
            }
            
            $this->processRecommendation();
        } else {
            $this->view('teacher/recommend');
        }
    }
    
    // Private helper methods
    
    private function getTeacherStats($teacherId) {
        try {
            $stats = [
                'books_borrowed' => 0,
                'recommendations' => 0,
                'students_tracked' => 0,
                'reservations' => 0
            ];
            
            // Get books borrowed by teacher
            $stmt = $this->db->prepare("
                SELECT COUNT(*) as count 
                FROM borrows b
                INNER JOIN books bk ON b.book_id = bk.id
                WHERE b.created_by = ? AND b.status = 'borrowed'
            ");
            $stmt->execute([$teacherId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $stats['books_borrowed'] = $result['count'] ?? 0;
            
            return $stats;
        } catch (PDOException $e) {
            error_log("Error getting teacher stats: " . $e->getMessage());
            return [
                'books_borrowed' => 0,
                'recommendations' => 0,
                'students_tracked' => 0,
                'reservations' => 0
            ];
        }
    }
    
    private function getAvailableBooks() {
        try {
            $stmt = $this->db->prepare("
                SELECT b.*, c.name as category_name, l.name as library_name
                FROM books b
                LEFT JOIN categories c ON b.category = c.id
                LEFT JOIN libraries l ON b.library_id = l.id
                WHERE b.available_copies > 0
                ORDER BY b.title ASC
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting available books: " . $e->getMessage());
            return [];
        }
    }
    
    private function getStudentBorrowingData($teacherId) {
        try {
            $stmt = $this->db->prepare("
                SELECT s.full_name, s.class, COUNT(b.id) as borrow_count,
                       MAX(b.borrowed_date) as last_borrow
                FROM students s
                LEFT JOIN borrows b ON s.id = b.student_id
                WHERE s.library_id IN (SELECT library_id FROM users WHERE id = ?)
                GROUP BY s.id
                ORDER BY borrow_count DESC
                LIMIT 50
            ");
            $stmt->execute([$teacherId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting student borrowing data: " . $e->getMessage());
            return [];
        }
    }
    
    private function getDigitalResources() {
        // Placeholder for digital resources
        return [];
    }
    
    private function getTeacherAnalytics($teacherId) {
        try {
            $analytics = [];
            
            // Most borrowed books
            $stmt = $this->db->prepare("
                SELECT bk.title, COUNT(b.id) as borrow_count
                FROM borrows b
                INNER JOIN books bk ON b.book_id = bk.id
                WHERE b.created_by = ?
                GROUP BY bk.id
                ORDER BY borrow_count DESC
                LIMIT 10
            ");
            $stmt->execute([$teacherId]);
            $analytics['top_books'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $analytics;
        } catch (PDOException $e) {
            error_log("Error getting teacher analytics: " . $e->getMessage());
            return ['top_books' => []];
        }
    }
    
    private function processBorrow() {
        // Placeholder - implement book borrowing logic
        $_SESSION['success'] = "Book borrowing functionality coming soon.";
        $this->redirect('/teacher/borrow');
    }
    
    private function processReservation() {
        // Placeholder - implement reservation logic
        $_SESSION['success'] = "Book reservation functionality coming soon.";
        $this->redirect('/teacher/reserve');
    }
    
    private function processUpload() {
        // Placeholder - implement upload logic
        $_SESSION['success'] = "File upload functionality coming soon.";
        $this->redirect('/teacher/upload');
    }
    
    private function processRecommendation() {
        $teacherId = $_SESSION['user_id'];
        $title = trim($_POST['title'] ?? '');
        $author = trim($_POST['author'] ?? '');
        $type = $_POST['type'] ?? 'book';
        $subject = trim($_POST['subject'] ?? '');
        $targetGrade = trim($_POST['target_grade'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $link = trim($_POST['link'] ?? '');
        $priority = $_POST['priority'] ?? 'normal';
        
        // Validate inputs
        if (empty($title)) {
            $_SESSION['error'] = "Title is required.";
            $this->redirect('/teacher/recommend');
            return;
        }
        
        if (empty($description) || strlen($description) < 50) {
            $_SESSION['error'] = "Description must be at least 50 characters.";
            $this->redirect('/teacher/recommend');
            return;
        }
        
        // Sanitize inputs
        $title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
        $author = htmlspecialchars($author, ENT_QUOTES, 'UTF-8');
        $subject = htmlspecialchars($subject, ENT_QUOTES, 'UTF-8');
        $targetGrade = htmlspecialchars($targetGrade, ENT_QUOTES, 'UTF-8');
        $description = htmlspecialchars($description, ENT_QUOTES, 'UTF-8');
        $link = htmlspecialchars($link, ENT_QUOTES, 'UTF-8');
        
        try {
            // Store recommendation (using reports table with proper structure)
            $stmt = $this->db->prepare("
                INSERT INTO reports (title, type, generated_by, library_id, date_range_start, filters, created_at)
                VALUES (?, 'recommendation', ?, ?, NOW(), ?, NOW())
            ");
            
            $filters = json_encode([
                'recommendation' => true,
                'material_type' => $type,
                'author' => $author,
                'subject' => $subject,
                'target_grade' => $targetGrade,
                'description' => $description,
                'link' => $link,
                'priority' => $priority,
                'status' => 'pending'
            ]);
            
            $stmt->execute([$title, $teacherId, $_SESSION['library_id'], $filters]);
            
            Security::logActivity(
                $teacherId,
                'submit_recommendation',
                'data',
                "Recommended material: {$title} (Type: {$type}, Priority: {$priority})"
            );
            
            $_SESSION['success'] = "Your recommendation has been submitted successfully. Admin/librarian will review it shortly.";
        } catch (PDOException $e) {
            error_log("Error submitting recommendation: " . $e->getMessage());
            $_SESSION['error'] = "Failed to submit recommendation. Please try again.";
        }
        
        $this->redirect('/teacher/recommend');
    }
}
