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
        
        $teacherId = $_SESSION['user_id'];
        $libraryId = $_SESSION['library_id'];
        
        // Get filters
        $filterType = $_GET['type'] ?? '';
        $filterSubject = $_GET['subject'] ?? '';
        $filterGrade = $_GET['grade'] ?? '';
        $search = $_GET['search'] ?? '';
        
        // Get resources
        $resources = $this->getDigitalResources($libraryId, $teacherId, [
            'type' => $filterType,
            'subject' => $filterSubject,
            'grade' => $filterGrade,
            'search' => $search
        ]);
        
        // Get statistics
        $stats = $this->getResourceStats($teacherId, $libraryId);
        
        // Get unique subjects and grades for filters
        $subjects = $this->getUniqueValues('subject', $libraryId);
        $grades = $this->getUniqueValues('grade_level', $libraryId);
        
        Security::logActivity(
            $_SESSION['user_id'],
            'access_resources',
            'data',
            'Accessed digital resources'
        );
        
        $this->view('teacher/resources', [
            'resources' => $resources,
            'stats' => $stats,
            'subjects' => $subjects,
            'grades' => $grades,
            'filters' => [
                'type' => $filterType,
                'subject' => $filterSubject,
                'grade' => $filterGrade,
                'search' => $search
            ]
        ]);
    }

    public function uploadResource() {
        $this->requireTeacher();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(BASE_PATH . '/teacher/resources');
            return;
        }

        $teacherId = $_SESSION['user_id'];
        $libraryId = $_SESSION['library_id'];
        
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $resourceType = $_POST['resource_type'] ?? '';
        $resourceUrl = $_POST['resource_url'] ?? '';
        $source = $_POST['source'] ?? 'teacher_upload';
        $subject = $_POST['subject'] ?? null;
        $gradeLevel = $_POST['grade_level'] ?? null;
        $tags = $_POST['tags'] ?? '';
        $thumbnailUrl = $_POST['thumbnail_url'] ?? null;
        $duration = $_POST['duration'] ?? null;

        if (empty($title) || empty($resourceType) || empty($resourceUrl)) {
            $_SESSION['error'] = 'Title, type, and URL are required.';
            $this->redirect(BASE_PATH . '/teacher/resources');
            return;
        }

        // Process tags
        $tagsArray = array_filter(array_map('trim', explode(',', $tags)));
        $tagsJson = json_encode($tagsArray);

        try {
            $stmt = $this->db->prepare("
                INSERT INTO resources (
                    title, description, resource_type, resource_url, source,
                    subject, grade_level, tags, thumbnail_url, duration,
                    uploaded_by, library_id, status, created_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'active', NOW())
            ");
            
            $stmt->execute([
                $title,
                $description,
                $resourceType,
                $resourceUrl,
                $source,
                $subject,
                $gradeLevel,
                $tagsJson,
                $thumbnailUrl,
                $duration,
                $teacherId,
                $libraryId
            ]);

            Security::logActivity(
                $teacherId,
                'resource_uploaded',
                'data',
                "Uploaded resource: {$title}",
                ['resource_type' => $resourceType, 'source' => $source]
            );

            $_SESSION['success'] = 'Resource uploaded successfully!';
        } catch (Exception $e) {
            error_log("Error uploading resource: " . $e->getMessage());
            $_SESSION['error'] = 'Failed to upload resource.';
        }

        $this->redirect(BASE_PATH . '/teacher/resources');
    }

    public function deleteResource() {
        $this->requireTeacher();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            return;
        }

        $resourceId = $_POST['resource_id'] ?? null;
        $teacherId = $_SESSION['user_id'];

        if (!$resourceId) {
            echo json_encode(['success' => false, 'message' => 'Resource ID required']);
            return;
        }

        try {
            // Verify ownership
            $stmt = $this->db->prepare("SELECT id FROM resources WHERE id = ? AND uploaded_by = ?");
            $stmt->execute([$resourceId, $teacherId]);
            
            if (!$stmt->fetch()) {
                echo json_encode(['success' => false, 'message' => 'Resource not found or access denied']);
                return;
            }

            // Delete resource
            $stmt = $this->db->prepare("DELETE FROM resources WHERE id = ?");
            $stmt->execute([$resourceId]);

            Security::logActivity(
                $teacherId,
                'resource_deleted',
                'data',
                "Deleted resource ID: {$resourceId}"
            );

            echo json_encode(['success' => true, 'message' => 'Resource deleted successfully']);
        } catch (Exception $e) {
            error_log("Error deleting resource: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Failed to delete resource']);
        }
    }

    public function trackView() {
        $this->requireTeacher();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false]);
            return;
        }

        $resourceId = $_POST['resource_id'] ?? null;
        $userId = $_SESSION['user_id'];

        if (!$resourceId) {
            echo json_encode(['success' => false]);
            return;
        }

        try {
            // Insert view record
            $stmt = $this->db->prepare("INSERT INTO resource_views (resource_id, viewed_by) VALUES (?, ?)");
            $stmt->execute([$resourceId, $userId]);

            // Update view count
            $stmt = $this->db->prepare("UPDATE resources SET views = views + 1 WHERE id = ?");
            $stmt->execute([$resourceId]);

            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            error_log("Error tracking view: " . $e->getMessage());
            echo json_encode(['success' => false]);
        }
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
                'resources_shared' => 0
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

            // Resources shared
            $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM resources WHERE uploaded_by = ? AND status = 'active'");
            $stmt->execute([$teacherId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $stats['resources_shared'] = $result['count'] ?? 0;
            
            return $stats;
        } catch (PDOException $e) {
            error_log("Error getting teacher stats: " . $e->getMessage());
            return [
                'books_borrowed' => 0,
                'recommendations' => 0,
                'students_tracked' => 0,
                'resources_shared' => 0
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
    
    private function getDigitalResources($libraryId, $teacherId, $filters = []) {
        $conditions = ["r.library_id = ?"];
        $params = [$libraryId];
        
        if (!empty($filters['type'])) {
            $conditions[] = "r.resource_type = ?";
            $params[] = $filters['type'];
        }
        
        if (!empty($filters['subject'])) {
            $conditions[] = "r.subject = ?";
            $params[] = $filters['subject'];
        }
        
        if (!empty($filters['grade'])) {
            $conditions[] = "r.grade_level = ?";
            $params[] = $filters['grade'];
        }
        
        if (!empty($filters['search'])) {
            $conditions[] = "(r.title LIKE ? OR r.description LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        $whereClause = implode(' AND ', $conditions);
        
        try {
            $stmt = $this->db->prepare("
                SELECT r.*, 
                       u.username as uploader_name,
                       u.full_name as uploader_full_name
                FROM resources r
                LEFT JOIN users u ON r.uploaded_by = u.id
                WHERE {$whereClause} AND r.status = 'active'
                ORDER BY r.created_at DESC
            ");
            
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting resources: " . $e->getMessage());
            return [];
        }
    }

    private function getResourceStats($teacherId, $libraryId) {
        $stats = [
            'total_resources' => 0,
            'my_uploads' => 0,
            'total_views' => 0,
            'videos' => 0
        ];
        
        try {
            // Total resources in library
            $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM resources WHERE library_id = ? AND status = 'active'");
            $stmt->execute([$libraryId]);
            $stats['total_resources'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
            
            // My uploads
            $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM resources WHERE uploaded_by = ? AND status = 'active'");
            $stmt->execute([$teacherId]);
            $stats['my_uploads'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
            
            // Total views on my resources
            $stmt = $this->db->prepare("
                SELECT SUM(r.views) as total_views 
                FROM resources r 
                WHERE r.uploaded_by = ? AND r.status = 'active'
            ");
            $stmt->execute([$teacherId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $stats['total_views'] = $result['total_views'] ?? 0;
            
            // Videos count
            $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM resources WHERE library_id = ? AND resource_type = 'video' AND status = 'active'");
            $stmt->execute([$libraryId]);
            $stats['videos'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
            
        } catch (Exception $e) {
            error_log("Error getting resource stats: " . $e->getMessage());
        }
        
        return $stats;
    }

    private function getUniqueValues($field, $libraryId) {
        try {
            $stmt = $this->db->prepare("
                SELECT DISTINCT {$field} as value 
                FROM resources 
                WHERE library_id = ? AND {$field} IS NOT NULL AND status = 'active'
                ORDER BY {$field}
            ");
            $stmt->execute([$libraryId]);
            return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'value');
        } catch (Exception $e) {
            error_log("Error getting unique values: " . $e->getMessage());
            return [];
        }
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
