<?php
class TeacherController extends Controller {
    private function requireTeacher() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'teacher') {
            $_SESSION['error'] = "Access denied.";
            $this->redirect('/login');
            exit;
        }
    }

    public function dashboard() {
        $this->requireTeacher();
        $this->view('teacher/dashboard');
    }

    public function borrow() {
        $this->requireTeacher();
        $this->view('teacher/borrow');
    }

    public function borrowing() {
        $this->requireTeacher();
        $this->view('teacher/borrowing');
    }

    public function resources() {
        $this->requireTeacher();
        $this->view('teacher/resources');
    }

    public function analytics() {
        $this->requireTeacher();
        $this->view('teacher/analytics');
    }

    public function reserve() {
        $this->requireTeacher();
        $this->view('teacher/reserve');
    }

    public function upload() {
        $this->requireTeacher();
        $this->view('teacher/upload');
    }

    public function recommend() {
        $this->requireTeacher();
        $this->view('teacher/recommend');
    }
}
