<?php
class Category extends Model {
    protected $table = 'categories';

    public function __construct() {
        parent::__construct();
    }

    public function getCategoriesByLibrary($libraryId) {
        $query = "SELECT * FROM categories WHERE library_id = :library_id ORDER BY name";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':library_id', $libraryId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function categoryExists($libraryId, $name) {
        $query = "SELECT COUNT(*) FROM categories WHERE library_id = :library_id AND name = :name";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':library_id', $libraryId);
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function addCategory($libraryId, $name) {
        if ($this->categoryExists($libraryId, $name)) {
            return false;
        }
        $query = "INSERT INTO categories (library_id, name, created_by) VALUES (:library_id, :name, :created_by)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':library_id', $libraryId);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':created_by', $_SESSION['user_id']);
        return $stmt->execute();
    }

    public function deleteCategory($categoryId, $libraryId) {
        $query = "DELETE FROM categories WHERE id = :id AND library_id = :library_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $categoryId);
        $stmt->bindParam(':library_id', $libraryId);
        return $stmt->execute();
    }
}
