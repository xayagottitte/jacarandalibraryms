<?php 
$title = "Borrow Book - Multi-Library System";
include '../app/views/shared/header.php'; 
include '../app/views/shared/layout-header.php'; 
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Borrow Book</h1>
        <a href="/jacarandalibraryms/librarian/borrows" class="btn btn-secondary">Back to Borrow Management</a>
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
            <form method="POST" action="/jacarandalibraryms/librarian/borrow-book">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="student_id" class="form-label">Select Student <span class="text-danger">*</span></label>
                            <select class="form-select" id="student_id" name="student_id" required>
                                <option value="">Choose a student...</option>
                                <?php foreach ($students as $student): ?>
                                    <option value="<?= $student['id'] ?>" 
                                        <?= (isset($_GET['student_id']) && $_GET['student_id'] == $student['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($student['full_name']) ?> 
                                        (<?= htmlspecialchars($student['student_id']) ?> - Class <?= htmlspecialchars($student['class']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="book_id" class="form-label">Select Book <span class="text-danger">*</span></label>
                            <select class="form-select" id="book_id" name="book_id" required>
                                <option value="">Choose a book...</option>
                                <?php foreach ($books as $book): ?>
                                    <option value="<?= $book['id'] ?>">
                                        <?= htmlspecialchars($book['title']) ?> by <?= htmlspecialchars($book['author']) ?>
                                        (ISBN: <?= htmlspecialchars($book['isbn'] ?? 'N/A') ?>)
                                        - <?= $book['available_copies'] ?> available
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>Information:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Loan period: <?= $loan_period ?? 5 ?> days</li>
                        <li>Maximum books per student: 5</li>
                        <li>Fine for overdue books: $5 per day</li>
                        <li>Books are due on <?= date('M j, Y', strtotime('+' . ($loan_period ?? 5) . ' days')) ?></li>
                    </ul>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="/jacarandalibraryms/librarian/borrows" class="btn btn-secondary me-md-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">Borrow Book</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-bolt fa-2x text-success mb-3"></i>
                    <h5>Quick Borrow</h5>
                    <p class="text-muted">Use student ID and ISBN for faster borrowing</p>
                    <a href="/jacarandalibraryms/librarian/quick-borrow" class="btn btn-success">Go to Quick Borrow</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-plus fa-2x text-primary mb-3"></i>
                    <h5>Add New Student</h5>
                    <p class="text-muted">Can't find a student? Add them to the system</p>
                    <a href="/jacarandalibraryms/librarian/create-student" class="btn btn-primary">Add Student</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const studentSelect = document.getElementById('student_id');
    const bookSelect = document.getElementById('book_id');

    // Auto-select student if coming from student page
    const urlParams = new URLSearchParams(window.location.search);
    const studentId = urlParams.get('student_id');
    if (studentId) {
        studentSelect.value = studentId;
    }
});
</script>

<?php include '../app/views/shared/footer.php'; ?>