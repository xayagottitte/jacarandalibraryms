<?php 
$title = "View Book - Multi-Library System";
include '../app/views/shared/header.php'; 
include '../app/views/shared/layout-header.php'; 
?>

    <div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Book Details</h2>
        <div class="d-flex gap-2">
            <a href="/jacarandalibraryms/librarian/edit-book?id=<?= $book['id'] ?>" class="btn btn-primary">
                <i class="fas fa-edit me-2"></i>Edit Book
            </a>
            <a href="/jacarandalibraryms/librarian/books" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Books
            </a>
        </div>
    </div>    <!-- Flash Messages -->
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

    <div class="row">
        <!-- Book Information Card -->
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-book me-2"></i>Book Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="140">Title:</th>
                                    <td><?= htmlspecialchars($book['title']) ?></td>
                                </tr>
                                <tr>
                                    <th>Author:</th>
                                    <td><?= htmlspecialchars($book['author']) ?></td>
                                </tr>
                                <tr>
                                    <th>ISBN:</th>
                                    <td><?= htmlspecialchars($book['isbn'] ?? 'N/A') ?></td>
                                </tr>
                                <tr>
                                    <th>Publisher:</th>
                                    <td><?= htmlspecialchars($book['publisher'] ?? 'N/A') ?></td>
                                </tr>
                                <tr>
                                    <th>Publication Year:</th>
                                    <td><?= htmlspecialchars($book['publication_year'] ?? 'N/A') ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="140">Category:</th>
                                    <td><?= htmlspecialchars($book['category'] ?? 'N/A') ?></td>
                                </tr>
                                <tr>
                                    <th>Class Level:</th>
                                    <td><?= htmlspecialchars($book['class_level'] ?? 'N/A') ?></td>
                                </tr>
                                <tr>
                                    <th>Total Copies:</th>
                                    <td><span class="badge bg-info"><?= $book['total_copies'] ?></span></td>
                                </tr>
                                <tr>
                                    <th>Available:</th>
                                    <td>
                                        <?php if ($book['available_copies'] > 0): ?>
                                            <span class="badge bg-success"><?= $book['available_copies'] ?> Available</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">0 Available</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <?php if ($book['available_copies'] > 0): ?>
                                            <span class="badge bg-success">Available for Borrowing</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning">All Copies Borrowed</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Card -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Book Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-primary mb-0"><?= $book['total_copies'] ?></h4>
                                <small class="text-muted">Total Copies</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success mb-0"><?= $book['available_copies'] ?></h4>
                            <small class="text-muted">Available</small>
                        </div>
                    </div>
                    <hr>
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-warning mb-0"><?= $book['total_copies'] - $book['available_copies'] ?></h4>
                                <small class="text-muted">Borrowed</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-info mb-0"><?= count($borrow_history ?? []) ?></h4>
                            <small class="text-muted">Total Borrows</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Library Information -->
            <div class="card shadow-sm mt-3">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0"><i class="fas fa-building me-2"></i>Library Information</h6>
                </div>
                <div class="card-body">
                    <p><strong>Library:</strong> <?= htmlspecialchars($library['name']) ?></p>
                    <p><strong>Type:</strong> <?= ucfirst(htmlspecialchars($library['type'])) ?> School</p>
                    <?php if (!empty($library['address'])): ?>
                        <p><strong>Address:</strong> <?= htmlspecialchars($library['address']) ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Borrow History -->
    <?php if (!empty($borrow_history)): ?>
    <div class="card shadow-sm mt-4">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0"><i class="fas fa-history me-2"></i>Borrow History</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Student ID</th>
                            <th>Class</th>
                            <th>Borrowed Date</th>
                            <th>Due Date</th>
                            <th>Returned Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($borrow_history as $borrow): ?>
                        <tr>
                            <td><?= htmlspecialchars($borrow['student_name']) ?></td>
                            <td><?= htmlspecialchars($borrow['student_id']) ?></td>
                            <td><?= htmlspecialchars($borrow['class']) ?></td>
                            <td><?= date('M d, Y', strtotime($borrow['borrowed_date'])) ?></td>
                            <td><?= date('M d, Y', strtotime($borrow['due_date'])) ?></td>
                            <td>
                                <?php if ($borrow['returned_date']): ?>
                                    <?= date('M d, Y', strtotime($borrow['returned_date'])) ?>
                                <?php else: ?>
                                    <em class="text-muted">Not returned</em>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($borrow['status'] === 'returned'): ?>
                                    <span class="badge bg-success">Returned</span>
                                <?php elseif ($borrow['status'] === 'overdue'): ?>
                                    <span class="badge bg-danger">Overdue</span>
                                <?php else: ?>
                                    <span class="badge bg-warning">Borrowed</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<style>
.card {
    border: none;
    border-radius: 10px;
}

.card-header {
    border-radius: 10px 10px 0 0 !important;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
}

.table td {
    vertical-align: middle;
}

.badge {
    font-size: 0.85rem;
}
</style>

<?php include '../app/views/shared/layout-footer.php'; ?>
<?php include '../app/views/shared/footer.php'; ?>
