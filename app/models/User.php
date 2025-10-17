<?php
class User extends Model {
    protected $table = 'users';

    public function __construct() {
        parent::__construct();
    }

    public function findByEmail($email) {
        $query = "SELECT u.*, l.name as library_name 
                  FROM users u 
                  LEFT JOIN libraries l ON u.library_id = l.id 
                  WHERE u.email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByUsername($username) {
        $query = "SELECT u.*, l.name as library_name 
                  FROM users u 
                  LEFT JOIN libraries l ON u.library_id = l.id 
                  WHERE u.username = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPendingUsers() {
        $query = "SELECT u.*, l.name as library_name 
                  FROM users u 
                  LEFT JOIN libraries l ON u.library_id = l.id 
                  WHERE u.status = 'pending' 
                  ORDER BY u.created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllUsers($filters = []) {
        $query = "SELECT u.*, l.name as library_name 
                  FROM users u 
                  LEFT JOIN libraries l ON u.library_id = l.id 
                  WHERE 1=1";
        
        $params = [];

        // Add filters
        if (!empty($filters['search'])) {
            $query .= " AND (u.username LIKE ? OR u.full_name LIKE ? OR u.email LIKE ?)";
            $params[] = '%' . $filters['search'] . '%';
            $params[] = '%' . $filters['search'] . '%';
            $params[] = '%' . $filters['search'] . '%';
        }

        if (!empty($filters['role'])) {
            $query .= " AND u.role = ?";
            $params[] = $filters['role'];
        }

        if (!empty($filters['status'])) {
            $query .= " AND u.status = ?";
            $params[] = $filters['status'];
        }

        if (!empty($filters['library'])) {
            $query .= " AND u.library_id = ?";
            $params[] = $filters['library'];
        }

        $query .= " ORDER BY u.role, u.status, u.created_at DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function approveUser($userId, $approvedBy) {
        $query = "UPDATE users SET status = 'active', approved_by = ?, approved_at = NOW() 
                  WHERE id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$approvedBy, $userId]);
    }

    public function rejectUser($userId) {
        $query = "UPDATE users SET status = 'inactive' WHERE id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$userId]);
    }

    public function getUsersByRole($role) {
        $query = "SELECT u.*, l.name as library_name 
                  FROM users u 
                  LEFT JOIN libraries l ON u.library_id = l.id 
                  WHERE u.role = ? 
                  ORDER BY u.status, u.full_name";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$role]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDashboardStats() {
        $query = "SELECT 
                  (SELECT COUNT(*) FROM users WHERE role = 'super_admin' AND status = 'active') as total_super_admins,
                  (SELECT COUNT(*) FROM users WHERE role = 'librarian' AND status = 'active') as total_librarians,
                  (SELECT COUNT(*) FROM users WHERE role = 'librarian' AND status = 'pending') as pending_librarians,
                  (SELECT COUNT(*) FROM libraries) as total_libraries";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function storePasswordResetToken($email, $token, $expires) {
        $query = "INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?) 
                 ON DUPLICATE KEY UPDATE token = VALUES(token), expires_at = VALUES(expires_at), created_at = NOW()";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$email, $token, $expires]);
    }

    public function findByPasswordResetToken($token) {
        $query = "SELECT pr.*, u.id as user_id, u.email, u.username 
                  FROM password_resets pr 
                  JOIN users u ON pr.email = u.email 
                  WHERE pr.token = ? AND pr.expires_at > NOW()";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deletePasswordResetToken($token) {
        $query = "DELETE FROM password_resets WHERE token = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$token]);
    }

    public function updatePassword($userId, $hashedPassword) {
        $query = "UPDATE users SET password = ? WHERE id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$hashedPassword, $userId]);
    }

    public function deactivateUser($userId) {
        $query = "UPDATE users SET status = 'inactive' WHERE id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$userId]);
    }

    public function activateUser($userId) {
        $query = "UPDATE users SET status = 'active' WHERE id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$userId]);
    }

    public function deleteUser($userId) {
        $query = "DELETE FROM users WHERE id = ? AND role != 'super_admin'";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$userId]);
    }

    public function assignLibrary($userId, $libraryId) {
        $query = "UPDATE users SET library_id = ? WHERE id = ? AND role = 'librarian'";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$libraryId, $userId]);
    }

    public function getUserProfile($userId) {
        $query = "SELECT u.*, l.name as library_name, l.type as library_type 
                  FROM users u 
                  LEFT JOIN libraries l ON u.library_id = l.id 
                  WHERE u.id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateProfile($userId, $data) {
        error_log("User::updateProfile() - User ID: " . $userId);
        error_log("User::updateProfile() - Input data: " . print_r($data, true));
        
        $allowedFields = ['full_name', 'employee_id', 'date_of_birth', 'gender', 'phone', 'email', 'address'];
        $setClause = [];
        $params = [];

        foreach ($data as $field => $value) {
            if (in_array($field, $allowedFields) && $value !== null && $value !== '') {
                $setClause[] = "$field = ?";
                $params[] = $value;
                error_log("User::updateProfile() - Added field: $field = $value");
            } else {
                error_log("User::updateProfile() - Skipped field: $field (not allowed or empty)");
            }
        }

        if (empty($setClause)) {
            error_log("User::updateProfile() - No valid fields to update");
            return false;
        }

        $params[] = $userId;
        $query = "UPDATE users SET " . implode(', ', $setClause) . ", updated_at = NOW() WHERE id = ?";
        
        error_log("User::updateProfile() - Query: " . $query);
        error_log("User::updateProfile() - Params: " . print_r($params, true));
        
        $stmt = $this->db->prepare($query);
        $result = $stmt->execute($params);
        
        error_log("User::updateProfile() - Execute result: " . ($result ? 'success' : 'failed'));
        error_log("User::updateProfile() - Affected rows: " . $stmt->rowCount());
        
        if (!$result) {
            error_log("User::updateProfile() - Error info: " . print_r($stmt->errorInfo(), true));
        }
        
        return $result;
    }

    public function updateProfilePhoto($userId, $photoPath) {
        error_log("User::updateProfilePhoto() - User ID: " . $userId);
        error_log("User::updateProfilePhoto() - Photo path: " . $photoPath);
        
        $query = "UPDATE users SET profile_photo = ?, updated_at = NOW() WHERE id = ?";
        
        error_log("User::updateProfilePhoto() - Query: " . $query);
        
        $stmt = $this->db->prepare($query);
        $result = $stmt->execute([$photoPath, $userId]);
        
        error_log("User::updateProfilePhoto() - Execute result: " . ($result ? 'success' : 'failed'));
        error_log("User::updateProfilePhoto() - Affected rows: " . $stmt->rowCount());
        
        if (!$result) {
            error_log("User::updateProfilePhoto() - Error info: " . print_r($stmt->errorInfo(), true));
        }
        
        return $result;
    }

    public function getUserStatistics($userId) {
        // This method can be expanded based on your database structure
        // For now, returning basic stats
        $stats = [
            'students_registered' => 0,
            'reports_generated' => 0,
            'total_transactions' => 0
        ];

        try {
            // Count students registered by this user (if you track who created students)
            $query = "SELECT COUNT(*) as count FROM students WHERE created_by = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $stats['students_registered'] = $result['count'] ?? 0;

            // You can add more statistics queries here
            // Example: reports generated, books added, etc.

        } catch (Exception $e) {
            error_log("Error getting user statistics: " . $e->getMessage());
        }

        return $stats;
    }
}
?>