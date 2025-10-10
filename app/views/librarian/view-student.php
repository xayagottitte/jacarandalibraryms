<?php 
$title = "Student Details - Multi-Library System";
include '../app/views/shared/header.php'; 
include '../app/views/shared/librarian-sidebar.php'; 
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Student Details</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="/librarian/edit-student/<?= $student['id'] ?>" class="btn btn-outline-primary me-2">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="/librarian/borrow-book?student_id=<?= $student['id'] ?>" class="btn btn-success">
                <i class="fas fa-book"></i> Borrow Book
            </a>
        </div>
    </div>

    <!-- Student Information -->
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Student Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <th width="40%">Student ID:</th>
                                    <td><strong><?= htmlspecialchars($student['student_id']) ?></strong></td>
                                </tr>
                                <tr>
                                    <th>Full Name:</th>
                                    <td><?= htmlspecialchars($student['full_name']) ?></td>
                                </tr>
                                <tr>
                                    <th>Class & Section:</th>
                                    <td>
                                        Class <?= htmlspecialchars($student['class']) ?>
                                        <?php if ($student['section']): ?>
                                            - Section <?= htmlspecialchars($student['section']) ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <th width="40%">Email:</th>
                                    <td>
                                        <?php if ($student['email']): ?>
                                            <?= htmlspecialchars($student['email']) ?>
                                        <?php else: ?>
                                            <span class="text-muted">Not provided</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Phone:</th>
                                    <td>
                                        <?php if ($student['phone']): ?>
                                            <?= htmlspecialchars($student['phone']) ?>
                                        <?php else: ?>
                                            <span class="text-muted">Not provided</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <span class="badge bg-<?= $student['status'] === 'active' ? 'success' : 'secondary' ?>">
                                            <?= ucfirst($student['status']) ?>
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Borrows -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Currently Borrowed Books</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($active_borrows)): ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Book Title</th>
                                        <th>Author</th>
                                        <th>Borrowed Date</th>
                                        <th>Due Date</th>
                                        <th>Days Remaining</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($active_borrows as $borrow): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($borrow['title']) ?></td>
                                            <td><?= htmlspecialchars($borrow['author']) ?></td>
                                            <td><?= date('M j, Y', strtotime($borrow['borrowed_date'])) ?></td>
                                            <td><?= date('M j, Y', strtotime($borrow['due_date'])) ?></td>
                                            <td>
                                                <?php if ($borrow['days_remaining'] > 0): ?>
                                                    <span class="badge bg-success"><?= $borrow['days_remaining'] ?> days</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Overdue by <?= abs($borrow['days_remaining']) ?> days</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?= $borrow['status'] === 'borrowed' ? 'warning' : 'danger' ?>">
                                                    <?= ucfirst($borrow['status']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <form method="POST" action="/librarian/return-book" class="d-inline">
                                                    <input type="hidden" name="borrow_id" value="<?= $borrow['id'] ?>">
                                                    <button type="submit" class="btn btn-success btn-sm">
                                                        <i class="fas fa-undo"></i> Return
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center">No currently borrowed books.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Borrowing Statistics -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Borrowing Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <h3 class="text-primary"><?= count($active_borrows) ?></h3>
                        <p class="text-muted">Currently Borrowed Books</p>
                    </div>
                    
                    <?php 
                    $totalBorrows = count($student['borrow_history']);
                    $returnedBorrows = count(array_filter($student['borrow_history'], function($b) {
                        return $b['status'] === 'returned';
                    }));
                    ?>
                    
                    <div class="row text-center">
                        <div class="col-6">
                            <h5 class="text-success"><?= $returnedBorrows ?></h5>
                            <small class="text-muted">Returned</small>
                        </div>
                        <div class="col-6">
                            <h5 class="text-warning"><?= $totalBorrows - $returnedBorrows ?></h5>
                            <small class="text-muted">Active</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="/librarian/borrow-book?student_id=<?= $student['id'] ?>" class="btn btn-success">
                            <i class="fas fa-book"></i> Borrow New Book
                        </a>
                        <a href="/librarian/edit-student/<?= $student['id'] ?>" class="btn btn-outline-primary">
                            <i class="fas fa-edit"></i> Edit Student
                        </a>
                        <a href="/librarian/students" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Students
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../app/views/shared/footer.php'; ?>