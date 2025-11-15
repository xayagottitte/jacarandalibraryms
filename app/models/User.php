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

    public function storePasswordResetToken($email, $token, $expires = null) {
        // Let MySQL handle the expiration time to avoid timezone issues
        $query = "INSERT INTO password_resets (email, token, expires_at, created_at) 
                 VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 1 HOUR), NOW()) 
                 ON DUPLICATE KEY UPDATE 
                    token = VALUES(token), 
                    expires_at = DATE_ADD(NOW(), INTERVAL 1 HOUR), 
                    created_at = NOW()";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$email, $token]);
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
        try {
            $this->db->beginTransaction();
            
            // Instead of preventing deletion, reassign all records to NULL or keep them orphaned
            // This allows deletion while preserving data integrity
            
            // 1. Update libraries - set created_by to NULL
            $stmt = $this->db->prepare("UPDATE libraries SET created_by = NULL WHERE created_by = ?");
            $stmt->execute([$userId]);
            
            // 2. Update books - set created_by to NULL
            $stmt = $this->db->prepare("UPDATE books SET created_by = NULL WHERE created_by = ?");
            $stmt->execute([$userId]);
            
            // 3. Update students - set created_by to NULL
            $stmt = $this->db->prepare("UPDATE students SET created_by = NULL WHERE created_by = ?");
            $stmt->execute([$userId]);
            
            // 4. Update categories - set created_by to NULL
            $stmt = $this->db->prepare("UPDATE categories SET created_by = NULL WHERE created_by = ?");
            $stmt->execute([$userId]);
            
            // 5. Update borrows - set created_by to NULL
            $stmt = $this->db->prepare("UPDATE borrows SET created_by = NULL WHERE created_by = ?");
            $stmt->execute([$userId]);
            
            // 6. Update reservations - set created_by to NULL
            $stmt = $this->db->prepare("UPDATE reservations SET created_by = NULL WHERE created_by = ?");
            $stmt->execute([$userId]);
            
            // 7. Delete the user
            $query = "DELETE FROM users WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $result = $stmt->execute([$userId]);
            
            $this->db->commit();
            return $result;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error deleting user: " . $e->getMessage());
            throw new Exception("Failed to delete user: " . $e->getMessage());
        }
    }

    public function assignLibrary($userId, $libraryId) {
        $query = "UPDATE users SET library_id = ? WHERE id = ? AND role = 'librarian'";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$libraryId, $userId]);
    }

    public function update($data) {
        $userId = $data['id'];
        $setClause = [];
        $params = [];

        // Update basic fields
        if (isset($data['username'])) {
            $setClause[] = "username = ?";
            $params[] = $data['username'];
        }
        if (isset($data['full_name'])) {
            $setClause[] = "full_name = ?";
            $params[] = $data['full_name'];
        }
        if (isset($data['email'])) {
            $setClause[] = "email = ?";
            $params[] = $data['email'];
        }
        if (isset($data['role'])) {
            $setClause[] = "role = ?";
            $params[] = $data['role'];
        }
        
        // Handle library_id properly
        if (isset($data['library_id'])) {
            // If role is super_admin, always set library_id to NULL
            if (isset($data['role']) && $data['role'] === 'super_admin') {
                $setClause[] = "library_id = NULL";
            } else {
                // For librarians, set library_id (can be NULL or a valid ID)
                if (empty($data['library_id'])) {
                    $setClause[] = "library_id = NULL";
                } else {
                    $setClause[] = "library_id = ?";
                    $params[] = $data['library_id'];
                }
            }
        }
        
        if (isset($data['password'])) {
            $setClause[] = "password = ?";
            $params[] = $data['password'];
        }

        if (empty($setClause)) {
            return false;
        }

        $params[] = $userId;
        $query = "UPDATE users SET " . implode(", ", $setClause) . " WHERE id = ?";
        
        $stmt = $this->db->prepare($query);
        return $stmt->execute($params);
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
        $this->ensureProfileColumns();
        error_log("User::updateProfile() - User ID: " . $userId);
        error_log("User::updateProfile() - Input data: " . print_r($data, true));
        
        $allowedFields = ['full_name', 'employee_id', 'date_of_birth', 'gender', 'phone', 'email', 'address'];
        $setClause = [];
        $params = [];

        foreach ($data as $field => $value) {
            if (in_array($field, $allowedFields)) {
                $setClause[] = "$field = ?";
                $params[] = $value;
                error_log("User::updateProfile() - Added field: $field = $value");
            } else {
                error_log("User::updateProfile() - Skipped field: $field (not allowed)");
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
        $this->ensureProfileColumns();
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

    private function ensureProfileColumns() {
        try {
            $needed = [
                'employee_id' => "ALTER TABLE users ADD COLUMN employee_id VARCHAR(50) DEFAULT NULL",
                'date_of_birth' => "ALTER TABLE users ADD COLUMN date_of_birth DATE DEFAULT NULL",
                'gender' => "ALTER TABLE users ADD COLUMN gender ENUM('male','female','other') DEFAULT NULL",
                'address' => "ALTER TABLE users ADD COLUMN address TEXT DEFAULT NULL",
                'profile_photo' => "ALTER TABLE users ADD COLUMN profile_photo VARCHAR(255) DEFAULT NULL",
                'supervisor' => "ALTER TABLE users ADD COLUMN supervisor VARCHAR(255) DEFAULT NULL"
            ];
            foreach ($needed as $col => $ddl) {
                $check = $this->db->prepare("SHOW COLUMNS FROM users LIKE :c");
                $check->execute([':c' => $col]);
                if (!$check->fetch(PDO::FETCH_ASSOC)) {
                    $this->db->exec($ddl);
                }
            }
        } catch (Exception $e) {
            // Fail silently; profile updates may still work for existing cols
        }
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