<?php
class Category extends Model {
    protected $table = 'categories';

    public function __construct() {
        parent::__construct();
    }

    public function getCategoriesByLibrary($libraryId) {
        // Categories are now universal - return all categories
        $query = "SELECT * FROM categories ORDER BY name";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getAllCategories() {
        $query = "SELECT * FROM categories ORDER BY name";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function categoryExists($name) {
        $query = "SELECT COUNT(*) FROM categories WHERE LOWER(name) = LOWER(:name)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function addCategory($name, $createdBy = null) {
        if ($this->categoryExists($name)) {
            return false;
        }
        if ($createdBy === null && isset($_SESSION['user_id'])) {
            $createdBy = $_SESSION['user_id'];
        }
        $query = "INSERT INTO categories (name, created_by, library_id) VALUES (:name, :created_by, NULL)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':created_by', $createdBy);
        return $stmt->execute();
    }

    public function deleteCategory($categoryId) {
        $query = "DELETE FROM categories WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $categoryId);
        return $stmt->execute();
    }

    public function getActiveCategoriesCount() {
        $query = "SELECT COUNT(DISTINCT c.id) as count 
                  FROM categories c
                  INNER JOIN books b ON c.id = b.category_id
                  WHERE b.total_copies > 0";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }
}
