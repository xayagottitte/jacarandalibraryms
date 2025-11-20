<?php
class ProfileController extends Controller {
    private $userModel;
    private $libraryModel;
    private $borrowModel;
    private $activityLogModel;

    public function __construct() {
        // Set up custom error logging
        $logPath = __DIR__ . '/../../public/debug.log';
        ini_set('log_errors', 1);
        ini_set('error_log', $logPath);
        
        // Session is already started in index.php
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }
        $this->userModel = new User();
        $this->libraryModel = new Library();
        $this->borrowModel = new Borrow();
        $this->activityLogModel = new ActivityLog();
    }

    public function index() {
        $userId = $_SESSION['user_id'];
        
        // Get user profile data
        $user = null;
        if (method_exists($this->userModel, 'getUserProfile')) {
            $user = $this->userModel->getUserProfile($userId);
        } else {
            // Fallback to basic find method
            $user = $this->userModel->find($userId);
        }
        
        // Get library information if librarian
        $library = null;
        if ($_SESSION['role'] === 'librarian' && isset($_SESSION['library_id'])) {
            $library = $this->libraryModel->find($_SESSION['library_id']);
        }

        // Get performance data
        $performanceData = $this->getPerformanceData($userId);
        
        // Get activity logs
        $activityLogs = $this->getActivityLogs($userId);

        $data = [
            'user' => $user,
            'library' => $library,
            'performance' => $performanceData,
            'activity_logs' => $activityLogs
        ];

        $this->view('profile/index', $data);
    }

    public function update() {
    error_log("[ProfileController] update() method called at top");
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            error_log("[ProfileController] update() called");
            // Verify CSRF token
            if (!isset($_POST['csrf_token']) || !Security::verifyCSRFToken($_POST['csrf_token'])) {
                error_log("[ProfileController] Invalid CSRF token: " . ($_POST['csrf_token'] ?? 'missing'));
                $_SESSION['error'] = "Invalid security token. Please try again.";
                $this->redirect('/profile');
                return;
            }

            $userId = $_SESSION['user_id'];
            error_log("[ProfileController] User ID: $userId");
            error_log("[ProfileController] Raw POST data: " . json_encode($_POST));

            // Sanitize all inputs
            $updateData = [
                'full_name' => Security::sanitizeInput($_POST['full_name'] ?? ''),
                'employee_id' => Security::sanitizeInput($_POST['employee_id'] ?? ''),
                'date_of_birth' => Security::sanitizeInput($_POST['date_of_birth'] ?? ''),
                'gender' => Security::sanitizeInput($_POST['gender'] ?? ''),
                'phone' => Security::sanitizeInput($_POST['phone'] ?? ''),
                'email' => Security::sanitizeInput($_POST['email'] ?? ''),
                'address' => Security::sanitizeInput($_POST['address'] ?? '')
            ];
            error_log("[ProfileController] Sanitized updateData: " . json_encode($updateData));

            // Validate email if provided
            if (!empty($updateData['email']) && !Security::validateEmail($updateData['email'])) {
                error_log("[ProfileController] Invalid email: " . $updateData['email']);
                $_SESSION['error'] = "Please enter a valid email address.";
                $this->redirect('/profile');
                return;
            }

            // Validate phone if provided
            if (!empty($updateData['phone']) && !Security::validatePhone($updateData['phone'])) {
                error_log("[ProfileController] Invalid phone: " . $updateData['phone']);
                $_SESSION['error'] = "Please enter a valid phone number.";
                $this->redirect('/profile');
                return;
            }

            // Validate date of birth if provided
            if (!empty($updateData['date_of_birth'])) {
                $date = DateTime::createFromFormat('Y-m-d', $updateData['date_of_birth']);
                if (!$date || $date->format('Y-m-d') !== $updateData['date_of_birth']) {
                    error_log("[ProfileController] Invalid date_of_birth: " . $updateData['date_of_birth']);
                    $_SESSION['error'] = "Please enter a valid date of birth.";
                    $this->redirect('/profile');
                    return;
                }
            }

            // Validate gender if provided
            if (!empty($updateData['gender']) && !in_array($updateData['gender'], ['male', 'female', 'other'])) {
                error_log("[ProfileController] Invalid gender: " . $updateData['gender']);
                $_SESSION['error'] = "Invalid gender selection.";
                $this->redirect('/profile');
                return;
            }

            error_log("[ProfileController] updateData to model: " . json_encode($updateData));

            $success = false;
            if (method_exists($this->userModel, 'updateProfile')) {
                error_log("[ProfileController] Using updateProfile method");
                $success = $this->userModel->updateProfile($userId, $updateData);
            } else {
                // Fallback to basic update method if it exists
                if (method_exists($this->userModel, 'update')) {
                    error_log("[ProfileController] Using fallback update method");
                    $success = $this->userModel->update($userId, $updateData);
                } else {
                    error_log("[ProfileController] No update method available");
                }
            }

            error_log("[ProfileController] Update result: " . ($success ? 'SUCCESS' : 'FAILED'));

            if ($success) {
                // Log profile update
                $changedFields = array_keys($updateData);
                error_log("[ProfileController] Logging profile update for fields: " . implode(', ', $changedFields));
                Security::logProfile(
                    $userId,
                    'profile_updated',
                    "User updated profile fields: " . implode(', ', $changedFields),
                    ['fields' => $changedFields]
                );
                $_SESSION['success'] = "Profile updated successfully!";
            } else {
                error_log("[ProfileController] Failed to update profile for user $userId");
                $_SESSION['error'] = "Failed to update profile.";
            }
        }
        error_log("[ProfileController] Redirecting to /profile");
        $this->redirect('/profile');
    }

    public function uploadPhoto() {
        // Log function entry
        error_log("ProfileController::uploadPhoto() - Function called");
        error_log("ProfileController::uploadPhoto() - REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD']);
        error_log("ProfileController::uploadPhoto() - FILES data: " . print_r($_FILES, true));
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_photo'])) {
            // Verify CSRF token
            if (!isset($_POST['csrf_token']) || !Security::verifyCSRFToken($_POST['csrf_token'])) {
                $_SESSION['error'] = "Invalid security token. Please try again.";
                $this->redirect('/profile');
                return;
            }
            
            $userId = $_SESSION['user_id'];
            $file = $_FILES['profile_photo'];
            
            error_log("ProfileController::uploadPhoto() - User ID: " . $userId);
            error_log("ProfileController::uploadPhoto() - File data: " . print_r($file, true));
            
            // Validate file
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $maxSize = 5 * 1024 * 1024; // 5MB

            if (!in_array($file['type'], $allowedTypes)) {
                error_log("ProfileController::uploadPhoto() - Invalid file type: " . $file['type']);
                $_SESSION['error'] = "Please upload a valid image file (JPEG, PNG, or GIF).";
                $this->redirect('/profile');
                return;
            }

            if ($file['size'] > $maxSize) {
                error_log("ProfileController::uploadPhoto() - File too large: " . $file['size'] . " bytes");
                $_SESSION['error'] = "File size must be less than 5MB.";
                $this->redirect('/profile');
                return;
            }

            // Create upload directory
            $uploadDir = __DIR__ . '/../../public/assets/img/profiles/';
            error_log("ProfileController::uploadPhoto() - Upload directory: " . $uploadDir);
            if (!is_dir($uploadDir)) {
                error_log("ProfileController::uploadPhoto() - Creating directory: " . $uploadDir);
                $created = mkdir($uploadDir, 0755, true);
                error_log("ProfileController::uploadPhoto() - Directory creation result: " . ($created ? 'success' : 'failed'));
            } else {
                error_log("ProfileController::uploadPhoto() - Directory already exists");
            }

            // Generate unique filename
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = 'profile_' . $userId . '_' . time() . '.' . $extension;
            $uploadPath = $uploadDir . $filename;
            
            error_log("ProfileController::uploadPhoto() - Filename: " . $filename);
            error_log("ProfileController::uploadPhoto() - Upload path: " . $uploadPath);
            error_log("ProfileController::uploadPhoto() - Temp file: " . $file['tmp_name']);

            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                error_log("ProfileController::uploadPhoto() - File moved successfully");
                
                // Update user profile with photo path (include /public/ for web access)
                $photoPath = '/public/assets/img/profiles/' . $filename;
                error_log("ProfileController::uploadPhoto() - Photo path for DB: " . $photoPath);
                
                $success = false;
                
                if (method_exists($this->userModel, 'updateProfilePhoto')) {
                    error_log("ProfileController::uploadPhoto() - Using updateProfilePhoto method");
                    $success = $this->userModel->updateProfilePhoto($userId, $photoPath);
                } else if (method_exists($this->userModel, 'update')) {
                    error_log("ProfileController::uploadPhoto() - Using update method fallback");
                    $success = $this->userModel->update($userId, ['profile_photo' => $photoPath]);
                } else {
                    error_log("ProfileController::uploadPhoto() - No suitable update method found");
                }
                
                error_log("ProfileController::uploadPhoto() - DB update result: " . ($success ? 'success' : 'failed'));
                
                if ($success) {
                    // Log photo upload
                    Security::logProfile(
                        $userId,
                        'profile_photo_updated',
                        "User updated profile photo",
                        ['filename' => $filename]
                    );
                    
                    $_SESSION['success'] = "Profile photo updated successfully!";
                } else {
                    $_SESSION['error'] = "Failed to update profile photo in database.";
                }
            } else {
                error_log("ProfileController::uploadPhoto() - File move failed");
                error_log("ProfileController::uploadPhoto() - Error details: " . error_get_last()['message'] ?? 'Unknown error');
                $_SESSION['error'] = "Failed to upload profile photo.";
            }
        } else {
            error_log("ProfileController::uploadPhoto() - Invalid request - METHOD: " . $_SERVER['REQUEST_METHOD'] . ", FILES: " . (isset($_FILES['profile_photo']) ? 'set' : 'not set'));
            $_SESSION['error'] = "Invalid file upload request.";
        }
        
        error_log("ProfileController::uploadPhoto() - Function complete, redirecting to profile");
        $this->redirect('/profile');
    }

    private function getPerformanceData($userId) {
        $data = [
            'books_issued' => 0,
            'books_returned' => 0,
            'books_lost' => 0,
            'fines_collected' => 0,
            'reports_generated' => 0,
            'students_registered' => 0,
            'total_transactions' => 0
        ];

        try {
            // Get borrowing statistics where user was the librarian who processed
            if ($this->borrowModel && method_exists($this->borrowModel, 'getLibrarianStats')) {
                $borrowStats = $this->borrowModel->getLibrarianStats($userId);
                if ($borrowStats) {
                    $data['books_issued'] = $borrowStats['books_issued'] ?? 0;
                    $data['books_returned'] = $borrowStats['books_returned'] ?? 0;
                    $data['fines_collected'] = $borrowStats['fines_collected'] ?? 0;
                }
            }
            
            // Get books lost count
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("SELECT COUNT(*) as count FROM borrows WHERE created_by = ? AND status = 'lost'");
            $stmt->execute([$userId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $data['books_lost'] = (int)($result['count'] ?? 0);
            
            // Get reports generated count
            $stmt = $db->prepare("SELECT COUNT(*) as count FROM reports WHERE created_by = ?");
            $stmt->execute([$userId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $data['reports_generated'] = (int)($result['count'] ?? 0);

            // Get other statistics from user model
            if ($this->userModel && method_exists($this->userModel, 'getUserStatistics')) {
                $userStats = $this->userModel->getUserStatistics($userId);
                if ($userStats) {
                    $data = array_merge($data, $userStats);
                }
            }
            
            // Calculate total transactions (borrows + returns)
            $data['total_transactions'] = $data['books_issued'] + $data['books_returned'];

        } catch (Exception $e) {
            // Handle gracefully if methods don't exist
            error_log("Error getting performance data: " . $e->getMessage());
        }

        return $data;
    }

    private function getActivityLogs($userId, $limit = 50) {
        $logs = [];
        
        try {
            // Use ActivityLog model as primary source
            if ($this->activityLogModel && method_exists($this->activityLogModel, 'getUserActivities')) {
                $activityLogs = $this->activityLogModel->getUserActivities($userId, $limit);
                
                // Format activity logs for display
                foreach ($activityLogs as $log) {
                    $logs[] = [
                        'activity' => $this->formatEventType($log['event_type']),
                        'details' => $log['description'],
                        'status' => $log['severity'] === 'critical' ? 'danger' : ($log['severity'] === 'warning' ? 'warning' : 'success'),
                        'created_at' => $log['created_at']
                    ];
                }
            }
            
            // If no activity logs, fallback to borrow activities
            if (empty($logs) && $this->borrowModel && method_exists($this->borrowModel, 'getRecentActivities')) {
                $logs = $this->borrowModel->getRecentActivities($userId, $limit);
            }
            
            // If still no logs found, create default entry
            if (empty($logs)) {
                $user = $this->userModel->find($userId);
                $logs = [
                    [
                        'activity' => 'Account Created',
                        'details' => 'User account was created.',
                        'status' => 'success',
                        'created_at' => $user['created_at'] ?? date('Y-m-d H:i:s')
                    ]
                ];
            }
        } catch (Exception $e) {
            error_log("Error getting activity logs: " . $e->getMessage());
            $logs = [
                [
                    'activity' => 'System',
                    'details' => 'Activity logs are being generated.',
                    'status' => 'success',
                    'created_at' => date('Y-m-d H:i:s')
                ]
            ];
        }

        return $logs;
    }
    
    private function formatEventType($eventType) {
        $formatted = str_replace('_', ' ', $eventType);
        return ucwords($formatted);
    }
}
?>