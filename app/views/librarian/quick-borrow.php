<?php 
$title = "Quick Borrow - Multi-Library System";
include '../app/views/shared/header.php'; 
include '../app/views/shared/layout-header.php'; 
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Quick Borrow</h1>
        <a href="<?= BASE_PATH ?>/librarian/borrow-book" class="btn btn-outline-primary">Standard Borrow</a>
    </div>

    <!-- Flash Messages -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="<?= BASE_PATH ?>/librarian/quick-borrow">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="student_id" class="form-label">Student ID <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="student_id" name="student_id" 
                                   placeholder="Enter Student ID (e.g., STU2024001001)" required>
                            <div class="form-text">Enter the student's unique ID</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="isbn" class="form-label">Book ISBN <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="isbn" name="isbn" 
                                   placeholder="Enter Book ISBN" required>
                            <div class="form-text">Enter the book's ISBN number</div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Note:</strong> This method uses Student ID and ISBN for faster processing. 
                    Make sure you have the correct information.
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="<?= BASE_PATH ?>/librarian/borrows" class="btn btn-secondary me-md-2">Cancel</a>
                    <button type="submit" class="btn btn-success">Quick Borrow</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Recent Students (for reference) -->
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">Recent Students (for reference)</h5>
        </div>
        <div class="card-body">
            <?php
            $studentModel = new Student();
            $recentStudents = $studentModel->getStudentsByLibrary($_SESSION['library_id'], []);
            $recentStudents = array_slice($recentStudents, 0, 5);
            ?>
            
            <?php if (!empty($recentStudents)): ?>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Student ID</th>
                                <th>Name</th>
                                <th>Class</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentStudents as $student): ?>
                                <tr>
                                    <td><code><?= htmlspecialchars($student['student_id']) ?></code></td>
                                    <td><?= htmlspecialchars($student['full_name']) ?></td>
                                    <td>Class <?= htmlspecialchars($student['class']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted text-center">No students found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const studentIdInput = document.getElementById('student_id');
    const isbnInput = document.getElementById('isbn');

    // Focus on first input
    studentIdInput.focus();

    // Auto-format ISBN (remove dashes and spaces)
    isbnInput.addEventListener('blur', function() {
        this.value = this.value.replace(/[-\s]/g, '');
    });
});
</script>

<?php include '../app/views/shared/footer.php'; ?>