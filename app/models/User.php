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
                  WHERE u.email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
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

    public function getAllUsers() {
        $query = "SELECT u.*, l.name as library_name 
                  FROM users u 
                  LEFT JOIN libraries l ON u.library_id = l.id 
                  ORDER BY u.role, u.status, u.created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function approveUser($userId, $approvedBy) {
        $query = "UPDATE users SET status = 'active', approved_by = :approved_by, approved_at = NOW() 
                  WHERE id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':approved_by', $approvedBy);
        return $stmt->execute();
    }

    public function rejectUser($userId) {
        $query = "UPDATE users SET status = 'inactive' WHERE id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        return $stmt->execute();
    }

    public function getUsersByRole($role) {
        $query = "SELECT u.*, l.name as library_name 
                  FROM users u 
                  LEFT JOIN libraries l ON u.library_id = l.id 
                  WHERE u.role = :role 
                  ORDER BY u.status, u.full_name";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':role', $role);
        $stmt->execute();
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
}
?>