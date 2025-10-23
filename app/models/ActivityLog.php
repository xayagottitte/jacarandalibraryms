<?php
class ActivityLog extends Model {
    protected $table = 'activity_logs';

    public function __construct() {
        parent::__construct();
    }

    /**
     * Get activity logs with filters and pagination
     */
    public function getActivityLogs($filters = [], $page = 1, $perPage = 50) {
        $offset = ($page - 1) * $perPage;
        $conditions = [];
        $params = [];
        
        // Build WHERE conditions
        if (!empty($filters['user_id'])) {
            $conditions[] = "al.user_id = :user_id";
            $params[':user_id'] = $filters['user_id'];
        }
        
        if (!empty($filters['event_type'])) {
            $conditions[] = "al.event_type = :event_type";
            $params[':event_type'] = $filters['event_type'];
        }
        
        if (!empty($filters['event_category'])) {
            $conditions[] = "al.event_category = :event_category";
            $params[':event_category'] = $filters['event_category'];
        }
        
        if (!empty($filters['severity'])) {
            $conditions[] = "al.severity = :severity";
            $params[':severity'] = $filters['severity'];
        }
        
        if (!empty($filters['date_from'])) {
            $conditions[] = "DATE(al.created_at) >= :date_from";
            $params[':date_from'] = $filters['date_from'];
        }
        
        if (!empty($filters['date_to'])) {
            $conditions[] = "DATE(al.created_at) <= :date_to";
            $params[':date_to'] = $filters['date_to'];
        }
        
        $whereClause = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';
        
        // Get total count
        $countQuery = "SELECT COUNT(*) as total FROM activity_logs al $whereClause";
        $countStmt = $this->db->prepare($countQuery);
        $countStmt->execute($params);
        $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Get logs with user info
        $query = "SELECT al.*, 
                         u.username, 
                         u.email, 
                         u.role 
                  FROM activity_logs al
                  LEFT JOIN users u ON al.user_id = u.id
                  $whereClause
                  ORDER BY al.created_at DESC 
                  LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return [
            'logs' => $stmt->fetchAll(PDO::FETCH_ASSOC),
            'total' => $total,
            'page' => $page,
            'perPage' => $perPage,
            'totalPages' => ceil($total / $perPage)
        ];
    }

    /**
     * Get recent activity logs
     */
    public function getRecentLogs($limit = 100) {
        $query = "SELECT al.*, 
                         u.username, 
                         u.email, 
                         u.role 
                  FROM activity_logs al
                  LEFT JOIN users u ON al.user_id = u.id
                  ORDER BY al.created_at DESC 
                  LIMIT :limit";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get activity statistics
     */
    public function getStatistics($days = 7) {
        $query = "SELECT 
                    event_category,
                    severity,
                    COUNT(*) as count
                  FROM activity_logs
                  WHERE created_at >= DATE_SUB(NOW(), INTERVAL :days DAY)
                  GROUP BY event_category, severity
                  ORDER BY count DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':days', $days, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get user-specific activity logs
     */
    public function getUserActivities($userId, $limit = 50) {
        $query = "SELECT * FROM activity_logs 
                  WHERE user_id = :user_id 
                  ORDER BY created_at DESC 
                  LIMIT :limit";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':user_id', $userId);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>