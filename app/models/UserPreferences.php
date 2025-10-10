<?php
class UserPreferences extends Model {
    protected $table = 'user_preferences';

    public function __construct() {
        parent::__construct();
    }

    public function getPreference($userId, $key, $default = null) {
        $query = "SELECT preference_value FROM user_preferences 
                  WHERE user_id = :user_id AND preference_key = :key";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':key', $key);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['preference_value'] : $default;
    }

    public function setPreference($userId, $key, $value) {
        // Check if preference exists
        $checkQuery = "SELECT id FROM user_preferences 
                      WHERE user_id = :user_id AND preference_key = :key";
        $checkStmt = $this->db->prepare($checkQuery);
        $checkStmt->bindParam(':user_id', $userId);
        $checkStmt->bindParam(':key', $key);
        $checkStmt->execute();
        
        if ($checkStmt->fetch(PDO::FETCH_ASSOC)) {
            // Update existing
            $updateQuery = "UPDATE user_preferences SET preference_value = :value 
                           WHERE user_id = :user_id AND preference_key = :key";
            $updateStmt = $this->db->prepare($updateQuery);
            $updateStmt->bindParam(':value', $value);
            $updateStmt->bindParam(':user_id', $userId);
            $updateStmt->bindParam(':key', $key);
            return $updateStmt->execute();
        } else {
            // Insert new
            return $this->create([
                'user_id' => $userId,
                'preference_key' => $key,
                'preference_value' => $value
            ]);
        }
    }

    public function getUserPreferences($userId) {
        $query = "SELECT preference_key, preference_value 
                  FROM user_preferences 
                  WHERE user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $preferences = [];
        foreach ($results as $result) {
            $preferences[$result['preference_key']] = $result['preference_value'];
        }
        
        return $preferences;
    }
}
?>