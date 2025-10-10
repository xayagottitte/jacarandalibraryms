<?php
class ActivityLog extends Model {
    protected $table = 'activity_logs';

    public function __construct() {
        parent::__construct();
    }

    public function logActivity($userId, $action, $description = null) {
        $data = [
            'user_id' => $userId,
            'action' => $action,
            'description' => $description,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'Unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown'
        ];
        return $this->create($data);
    }

    public function getUserActivities($userId, $limit = 50) {
        $query = "SELECT * FROM activity_logs 
                  WHERE user_id = :user_id 
                  ORDER BY created_at DESC 
                  LIMIT :limit";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSystemActivities($limit = 100) {
        $query = "SELECT al.*, u.username, u.role 
                  FROM activity_logs al
                  JOIN users u ON al.user_id = u.id
                  ORDER BY al.created_at DESC 
                  LIMIT :limit";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>