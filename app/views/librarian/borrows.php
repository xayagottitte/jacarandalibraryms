<?php 
$title = "Borrow Management - Multi-Library System";
include '../app/views/shared/header.php'; 
include '../app/views/shared/layout-header.php'; 
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Borrow Management</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="<?= BASE_PATH ?>/librarian/borrow-book" class="btn btn-primary me-2">
                <i class="fas fa-book"></i> Borrow Book
            </a>
            <a href="<?= BASE_PATH ?>/librarian/quick-borrow" class="btn btn-success">
                <i class="fas fa-bolt"></i> Quick Borrow
            </a>
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
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Total Borrows</h5>
                            <h2><?= $borrow_stats['total_borrows'] ?? 0 ?></h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-exchange-alt fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Current Borrows</h5>
                            <h2><?= $borrow_stats['current_borrows'] ?? 0 ?></h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-book fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Overdue Books</h5>
                            <h2><?= $borrow_stats['overdue_books'] ?? 0 ?></h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Total Fines</h5>
                            <h2>$<?= $borrow_stats['total_fines'] ?? 0 ?></h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-money-bill fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Form -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="POST" action="<?= BASE_PATH ?>/librarian/borrows">
                <div class="row">
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">All Status</option>
                            <option value="borrowed" <?= ($filters['status'] ?? '') === 'borrowed' ? 'selected' : '' ?>>Borrowed</option>
                            <option value="returned" <?= ($filters['status'] ?? '') === 'returned' ? 'selected' : '' ?>>Returned</option>
                            <option value="overdue" <?= ($filters['status'] ?? '') === 'overdue' ? 'selected' : '' ?>>Overdue</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="student_id" class="form-label">Student ID</label>
                        <input type="text" class="form-control" id="student_id" name="student_id" 
                               value="<?= htmlspecialchars($filters['student_id'] ?? '') ?>" 
                               placeholder="Student ID">
                    </div>
                    <div class="col-md-3">
                        <label for="book_title" class="form-label">Book Title</label>
                        <input type="text" class="form-control" id="book_title" name="book_title" 
                               value="<?= htmlspecialchars($filters['book_title'] ?? '') ?>" 
                               placeholder="Book Title">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Borrows Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Book</th>
                            <th>Borrowed Date</th>
                            <th>Due Date</th>
                            <th>Returned Date</th>
                            <th>Status</th>
                            <th>Fine</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($borrows as $borrow): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($borrow['student_id']) ?></strong><br>
                                    <small><?= htmlspecialchars($borrow['student_name']) ?> - Class <?= htmlspecialchars($borrow['class']) ?></small>
                                </td>
                                <td>
                                    <strong><?= htmlspecialchars($borrow['title']) ?></strong><br>
                                    <small>by <?= htmlspecialchars($borrow['author']) ?></small>
                                </td>
                                <td><?= date('M j, Y', strtotime($borrow['borrowed_date'])) ?></td>
                                <td>
                                    <?= date('M j, Y', strtotime($borrow['due_date'])) ?>
                                    <?php if ($borrow['status'] === 'borrowed' && $borrow['days_overdue_calc'] > 0): ?>
                                        <br><span class="badge bg-danger">Overdue <?= $borrow['days_overdue_calc'] ?> days</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($borrow['returned_date']): ?>
                                        <?= date('M j, Y', strtotime($borrow['returned_date'])) ?>
                                    <?php else: ?>
                                        <span class="text-muted">Not returned</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-<?= 
                                        $borrow['status'] === 'borrowed' ? 'warning' : 
                                        ($borrow['status'] === 'overdue' ? 'danger' : 'success')
                                    ?>">
                                        <?= ucfirst($borrow['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($borrow['fine_amount'] > 0): ?>
                                        <span class="text-danger">$<?= $borrow['fine_amount'] ?></span>
                                        <?php if ($borrow['paid_amount'] > 0): ?>
                                            <br><small>Paid: $<?= $borrow['paid_amount'] ?></small>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted">$0</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($borrow['status'] !== 'returned'): ?>
                                        <form method="POST" action="<?= BASE_PATH ?>/librarian/return-book" class="d-inline">
                                            <input type="hidden" name="borrow_id" value="<?= $borrow['id'] ?>">
                                            <button type="submit" class="btn btn-success btn-sm" title="Return Book">
                                                <i class="fas fa-undo"></i> Return
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <span class="text-muted">Completed</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <?php if (empty($borrows)): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-exchange-alt fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No borrowing records found.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include '../app/views/shared/footer.php'; ?>