<?php
class DashboardController extends Controller {
    public function __construct() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }
    }

    public function index() {
        if ($_SESSION['role'] === 'super_admin') {
            $this->redirect('/admin/dashboard');
        } else {
            $this->redirect('/librarian/dashboard');
        }
    }
}
?>