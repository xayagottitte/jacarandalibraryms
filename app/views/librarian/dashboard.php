<?php 
$title = "Librarian Dashboard - Multi-Library System";
include '../app/views/shared/header.php'; 
include '../app/views/shared/layout-header.php'; 
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Librarian Dashboard</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <span class="badge bg-primary fs-6">
                <i class="fas fa-library"></i> <?= htmlspecialchars($library['name']) ?>
            </span>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Total Books</h5>
                            <h2><?= $book_stats['total_books'] ?? 0 ?></h2>
                            <small><?= $book_stats['total_copies'] ?? 0 ?> copies</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-book fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Available Books</h5>
                            <h2><?= $book_stats['available_copies'] ?? 0 ?></h2>
                            <small>Ready to borrow</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Borrowed Books</h5>
                            <h2><?= $book_stats['borrowed_books'] ?? 0 ?></h2>
                            <small>Currently issued</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-exchange-alt fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Overdue Books</h5>
                            <h2><?= $book_stats['overdue_books'] ?? 0 ?></h2>
                            <small>Need attention</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Borrowings -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Borrowings</h5>
                    <a href="<?= BASE_PATH ?>/librarian/borrows" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    <?php if (!empty($recent_borrows)): ?>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Student</th>
                                        <th>Book</th>
                                        <th>Due Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (array_slice($recent_borrows, 0, 5) as $borrow): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($borrow['student_name']) ?></td>
                                            <td><?= htmlspecialchars($borrow['title']) ?></td>
                                            <td><?= date('M j, Y', strtotime($borrow['due_date'])) ?></td>
                                            <td>
                                                <span class="badge bg-<?= 
                                                    $borrow['status'] === 'borrowed' ? 'warning' : 
                                                    ($borrow['status'] === 'overdue' ? 'danger' : 'success')
                                                ?>">
                                                    <?= ucfirst($borrow['status']) ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center">No recent borrowings</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Overdue Books -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Overdue Books</h5>
                    <span class="badge bg-danger"><?= count($overdue_books) ?></span>
                </div>
                <div class="card-body">
                    <?php if (!empty($overdue_books)): ?>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Student</th>
                                        <th>Book</th>
                                        <th>Days Overdue</th>
                                        <th>Due Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (array_slice($overdue_books, 0, 5) as $borrow): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($borrow['student_name']) ?></td>
                                            <td><?= htmlspecialchars($borrow['title']) ?></td>
                                            <td>
                                                <span class="badge bg-danger"><?= $borrow['days_overdue'] ?> days</span>
                                            </td>
                                            <td><?= date('M j, Y', strtotime($borrow['due_date'])) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-success text-center"><i class="fas fa-check-circle"></i> No overdue books</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 mb-3">
                            <a href="<?= BASE_PATH ?>/librarian/quick-borrow" class="btn btn-success btn-lg w-100">
                                <i class="fas fa-bolt fa-2x mb-2"></i><br>
                                Quick Borrow
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="<?= BASE_PATH ?>/librarian/create-book" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-plus fa-2x mb-2"></i><br>
                                Add Book
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="<?= BASE_PATH ?>/librarian/create-student" class="btn btn-info btn-lg w-100">
                                <i class="fas fa-user-plus fa-2x mb-2"></i><br>
                                Add Student
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="<?= BASE_PATH ?>/librarian/reports" class="btn btn-warning btn-lg w-100">
                                <i class="fas fa-chart-bar fa-2x mb-2"></i><br>
                                View Reports
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../app/views/shared/footer.php'; ?>