<?php
class CategoryController extends Controller {
    private $categoryModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'librarian') {
            $this->redirect(BASE_PATH . '/login');
        }
        require_once '../app/models/Category.php';
        $this->categoryModel = new Category();
    }

    public function index() {
        $libraryId = $_SESSION['library_id'];
        $categories = $this->categoryModel->getCategoriesByLibrary($libraryId);
        require_once '../app/views/librarian/categories.php';
    }

    public function add() {
        $libraryId = $_SESSION['library_id'];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            if ($name) {
                if ($this->categoryModel->addCategory($libraryId, $name)) {
                    $_SESSION['success'] = "Category added successfully!";
                } else {
                    $_SESSION['error'] = "Category '$name' already exists.";
                }
            } else {
                $_SESSION['error'] = "Category name is required.";
                        }
                        header('Location: ' . BASE_PATH . '/librarian/categories');
                        exit;
                    }
                    require_once '../app/views/librarian/add-category.php';
                }

                public function delete() {
                    $libraryId = $_SESSION['library_id'];
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $categoryId = $_POST['id'] ?? null;
                        if ($categoryId) {
                            $this->categoryModel->deleteCategory($categoryId, $libraryId);
                            $_SESSION['success'] = "Category deleted successfully!";
                        } else {
                            $_SESSION['error'] = "Category ID is required.";
                        }
                        header('Location: ' . BASE_PATH . '/librarian/categories');
                        exit;
                    }
                }
            }
