<?php
class Controller {
    protected function view($view, $data = []) {
        // Sanitize all data before passing to view
        $data = $this->sanitizeData($data);
        extract($data);
        
        // Add CSRF token to all forms
        $csrfToken = Security::generateCSRFToken();
        
        require_once '../app/views/' . $view . '.php';
    }

    protected function redirect($url) {
        // Add base path if not already included
        if (!str_starts_with($url, 'http') && !str_starts_with($url, BASE_PATH)) {
            $url = BASE_PATH . $url;
        }
        header("Location: " . $url);
        exit();
    }

    protected function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    protected function isSuperAdmin() {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'super_admin';
    }

    protected function isLibrarian() {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'librarian';
    }

    protected function requireAuth() {
        if (!$this->isLoggedIn()) {
            $_SESSION['error'] = "Please login to access this page.";
            $this->redirect('/login');
        }
    }

    protected function requireRole($role) {
        $this->requireAuth();
        
        if ($_SESSION['role'] !== $role) {
            $_SESSION['error'] = "Access denied. Insufficient permissions.";
            $this->redirect('/dashboard');
        }
    }

    protected function sanitizeData($data) {
        if (is_array($data)) {
            return array_map([$this, 'sanitizeData'], $data);
        }
        return is_string($data) ? Security::sanitizeInput($data) : $data;
    }

    protected function validateCSRF() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf_token'] ?? '';
            if (!Security::verifyCSRFToken($token)) {
                $_SESSION['error'] = "Security token invalid. Please try again.";
                $this->redirect($_SERVER['HTTP_REFERER'] ?? '/');
            }
        }
    }

    protected function logActivity($action, $description = null) {
        if (isset($_SESSION['user_id'])) {
            $activityLog = new ActivityLog();
            $activityLog->logActivity($_SESSION['user_id'], $action, $description);
        }
    }
}
?>