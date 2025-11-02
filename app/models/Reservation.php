<?php
class Reservation extends Model {
    protected $table = 'reservations';

    public function createReservation($bookId, $userId, $expiresAt) {
        $query = "INSERT INTO reservations (book_id, user_id, status, requested_at, expires_at) VALUES (?, ?, 'pending', NOW(), ?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$bookId, $userId, $expiresAt]);
    }

    public function approveReservation($reservationId, $approvedBy) {
        $query = "UPDATE reservations SET status = 'approved', approved_by = ?, approved_at = NOW() WHERE id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$approvedBy, $reservationId]);
    }

    public function denyReservation($reservationId, $deniedBy) {
        $query = "UPDATE reservations SET status = 'denied', denied_by = ?, denied_at = NOW() WHERE id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$deniedBy, $reservationId]);
    }

    public function getReservationsByUser($userId) {
        $query = "SELECT * FROM reservations WHERE user_id = ? ORDER BY requested_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPendingReservations() {
        $query = "SELECT * FROM reservations WHERE status = 'pending' ORDER BY requested_at ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllRequests() {
        $stmt = $this->db->prepare("SELECT * FROM reservations ORDER BY requested_at DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
