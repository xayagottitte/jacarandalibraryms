<?php
class SystemController extends Controller {
    private $settingsModel;
    private $activityLog;
    private $userPreferences;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'super_admin') {
            $this->redirect('/login');
        }
        $this->settingsModel = new SystemSettings();
        $this->activityLog = new ActivityLog();
        $this->userPreferences = new UserPreferences();
    }

    public function maintenance() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            
            switch ($action) {
                case 'cleanup_reports':
                    $days = $this->settingsModel->getSetting('report_auto_delete_days') ?: 30;
                    $deleted = (new Report())->cleanupOldReports($days);
                    $_SESSION['success'] = "Cleaned up reports older than $days days. Deleted: $deleted";
                    break;
                    
                case 'record_stats':
                    $statsModel = new SystemStatistics();
                    // Record stats for all libraries and system-wide
                    $statsModel->recordDailyStats(); // System-wide
                    $libraries = (new Library())->all();
                    foreach ($libraries as $library) {
                        $statsModel->recordDailyStats($library['id']);
                    }
                    $_SESSION['success'] = "Recorded daily statistics for all libraries.";
                    break;
                    
                case 'optimize_tables':
                    $tables = ['books', 'students', 'borrows', 'users', 'libraries', 'reports'];
                    $optimized = 0;
                    
                    foreach ($tables as $table) {
                        $stmt = $this->settingsModel->db->prepare("OPTIMIZE TABLE $table");
                        if ($stmt->execute()) {
                            $optimized++;
                        }
                    }
                    $_SESSION['success'] = "Optimized $optimized database tables.";
                    break;
            }
            
            $this->redirect('/system/maintenance');
            return;
        }

        $data = [
            'system_info' => $this->getSystemInfo(),
            'database_info' => $this->getDatabaseInfo()
        ];
        $this->view('admin/system-maintenance', $data);
    }

    public function backup() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $backupType = $_POST['backup_type'] ?? 'full';
            $result = $this->createBackup($backupType);
            
            if ($result['success']) {
                $_SESSION['success'] = "Backup created successfully: " . $result['filename'];
            } else {
                $_SESSION['error'] = "Backup failed: " . $result['error'];
            }
            
            $this->redirect('/system/backup');
            return;
        }

        $data = [
            'backup_logs' => $this->getBackupLogs(),
            'backup_settings' => [
                'auto_enabled' => $this->settingsModel->getSetting('backup_auto_enabled'),
                'schedule' => $this->settingsModel->getSetting('backup_schedule')
            ]
        ];
        $this->view('admin/system-backup', $data);
    }

    public function userPreferences() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Save each preference from the form
            $preferences = [
                'theme', 'language', 'timezone', 'date_format', 
                'items_per_page', 'default_view', 'email_notifications',
                'browser_notifications', 'daily_summary', 'system_alerts'
            ];
            
            foreach ($preferences as $key) {
                $value = $_POST[$key] ?? '0';
                $this->userPreferences->setPreference($_SESSION['user_id'], $key, $value);
            }
            
            $_SESSION['success'] = "Preferences updated successfully!";
            $this->redirect('/system/preferences');
            return;
        }

        $data = [
            'preferences' => $this->userPreferences->getUserPreferences($_SESSION['user_id'])
        ];
        $this->view('admin/user-preferences', $data);
    }

    private function getSystemInfo() {
        return [
            'php_version' => phpversion(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'database_version' => $this->getDatabaseVersion(),
            'system_time' => date('Y-m-d H:i:s'),
            'timezone' => date_default_timezone_get(),
            'memory_usage' => round(memory_get_usage(true) / 1024 / 1024, 2) . ' MB',
            'peak_memory' => round(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB',
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'max_execution_time' => ini_get('max_execution_time') . ' seconds'
        ];
    }

    private function getDatabaseInfo() {
        $tables = ['books', 'students', 'borrows', 'users', 'libraries', 'reports'];
        $info = [];
        
        foreach ($tables as $table) {
            $stmt = $this->settingsModel->db->prepare("SELECT COUNT(*) as count FROM $table");
            $stmt->execute();
            $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
            $info[$table] = $count;
        }
        
        // Database size
        $sizeStmt = $this->settingsModel->db->prepare("
            SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) as size_mb 
            FROM information_schema.tables 
            WHERE table_schema = DATABASE()
        ");
        $sizeStmt->execute();
        $info['database_size'] = $sizeStmt->fetch(PDO::FETCH_ASSOC)['size_mb'] . ' MB';
        
        return $info;
    }

    private function getDatabaseVersion() {
        $stmt = $this->settingsModel->db->query("SELECT VERSION() as version");
        return $stmt->fetch(PDO::FETCH_ASSOC)['version'];
    }

    private function createBackup($type = 'full') {
        $backup = new DatabaseBackup();
        return $backup->createBackup();
    }

    private function getBackupLogs() {
        $query = "SELECT bl.*, u.username 
                  FROM backup_logs bl
                  JOIN users u ON bl.created_by = u.id
                  ORDER BY bl.created_at DESC
                  LIMIT 10";
        $stmt = $this->settingsModel->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>