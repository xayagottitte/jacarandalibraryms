<?php
class SystemSettings extends Model {
    protected $table = 'system_settings';

    public function __construct() {
        parent::__construct();
    }

    public function getSetting($key) {
        $query = "SELECT setting_value FROM system_settings WHERE setting_key = :key";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':key', $key);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['setting_value'] : null;
    }

    public function getAllSettings() {
        $query = "SELECT * FROM system_settings ORDER BY setting_key";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateSetting($key, $value, $updatedBy = null) {
        $query = "UPDATE system_settings SET setting_value = :value, updated_by = :updated_by WHERE setting_key = :key";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':value', $value);
        $stmt->bindParam(':updated_by', $updatedBy);
        $stmt->bindParam(':key', $key);
        return $stmt->execute();
    }

    public function getLoanPeriod() {
        return (int) $this->getSetting('loan_period_days') ?: 5; // Default to 5 days
    }

    public function getMaxBooksPerStudent() {
        return (int) $this->getSetting('max_books_per_student') ?: 3;
    }

    public function getFinePerDay() {
        return (float) $this->getSetting('fine_per_day') ?: 100.0;
    }
}
?>