<?php 
$title = "Borrow Management - Multi-Library System";
include '../app/views/shared/header.php'; 
include '../app/views/shared/layout-header.php'; 
?>

<style>
/* Modern Dashboard Color Variables */
:root {
    --primary-purple: #663399;
    --dark-purple: #522d7a;
    --light-purple: #8a4baf;
    --accent-purple: #b07acc;
    --jacaranda-primary: #663399;
    --jacaranda-secondary: #8a4baf;
    --grey-dark: #374151;
    --grey-medium: #6b7280;
    --grey-light: #e5e7eb;
    --grey-lighter: #f3f4f6;
    --white: #ffffff;
    --red-gradient-start: #ef4444;
    --red-gradient-end: #dc2626;
    --success-gradient-start: #10b981;
    --success-gradient-end: #059669;
    --warning-gradient-start: #f59e0b;
    --warning-gradient-end: #d97706;
}

.borrows-container {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    min-height: 100vh;
    padding: 2rem 0;
}

.page-header {
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--dark-purple) 100%);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 10px 40px rgba(99, 102, 241, 0.2);
    color: white;
}

.page-header h1 {
    font-weight: 700;
    font-size: 2rem;
    margin: 0;
    color: white;
}

.header-actions {
    display: flex;
    gap: 0.75rem;
}

.btn-header {
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.875rem;
    transition: all 0.3s;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-header:hover {
    background: rgba(255, 255, 255, 0.3);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}

.btn-header.success {
    background: linear-gradient(135deg, var(--success-gradient-start) 0%, var(--success-gradient-end) 100%);
    border: none;
}

/* Modern Alert Messages */
.alert-modern {
    border-radius: 15px;
    border: none;
    padding: 1.25rem 1.5rem;
    font-weight: 500;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    margin-bottom: 1.5rem;
}

.alert-success-modern {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    color: #065f46;
}

.alert-danger-modern {
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    color: #991b1b;
}

/* Statistics Cards */
.stat-card-modern {
    border-radius: 20px;
    border: none;
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    height: 100%;
}

.stat-card-modern:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
}

.stat-card-modern .card-body {
    padding: 2rem;
}

.stat-card-modern .stat-icon {
    width: 70px;
    height: 70px;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    margin-bottom: 1.5rem;
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
}

.stat-card-modern h5 {
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    opacity: 0.9;
    margin-bottom: 0.5rem;
}

.stat-card-modern h2 {
    font-size: 2.5rem;
    font-weight: 700;
    margin: 0;
}

.card-purple {
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--dark-purple) 100%);
    color: white;
}

.card-warning {
    background: linear-gradient(135deg, var(--warning-gradient-start) 0%, var(--warning-gradient-end) 100%);
    color: white;
}

.card-danger {
    background: linear-gradient(135deg, var(--red-gradient-start) 0%, var(--red-gradient-end) 100%);
    color: white;
}

.card-success {
    background: linear-gradient(135deg, var(--success-gradient-start) 0%, var(--success-gradient-end) 100%);
    color: white;
}

/* Search Card */
.search-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    padding: 1.5rem 2rem;
    margin-bottom: 1.5rem;
}

.search-input-group {
    position: relative;
}

.search-input-group .input-group-text {
    background: var(--grey-lighter);
    border: 2px solid var(--grey-light);
    border-right: none;
    border-radius: 12px 0 0 12px;
    color: var(--primary-purple);
}

.search-input-group input {
    border: 2px solid var(--grey-light);
    border-left: none;
    border-radius: 0 12px 12px 0;
    padding: 0.75rem 1rem;
    font-size: 0.95rem;
    transition: all 0.3s;
}

.search-input-group input:focus {
    border-color: var(--primary-purple);
    box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.25);
    outline: none;
}

#searchResults {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    color: var(--grey-medium);
    font-weight: 600;
}

/* Filter Card */
.filter-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    padding: 2rem;
    margin-bottom: 2rem;
}

.filter-card label {
    font-weight: 600;
    color: var(--grey-dark);
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.5rem;
}

.filter-card .form-control,
.filter-card .form-select {
    border: 2px solid var(--grey-light);
    border-radius: 12px;
    padding: 0.75rem 1rem;
    font-size: 0.95rem;
    transition: all 0.3s;
}

.filter-card .form-control:focus,
.filter-card .form-select:focus {
    border-color: var(--primary-purple);
    box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.25);
    outline: none;
}

.btn-filter {
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--dark-purple) 100%);
    border: none;
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.875rem;
    transition: all 0.3s;
}

.btn-filter:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(99, 102, 241, 0.3);
    color: white;
}

/* Table Card */
.table-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
}

.modern-borrows-table {
    width: 100%;
    margin-bottom: 0;
    border-collapse: collapse;
    font-size: 0.9rem;
}

.modern-borrows-table thead th {
    background: linear-gradient(135deg, #7c3aed 0%, #6366f1 100%);
    border-bottom: none;
    padding: 1.25rem 1rem;
    font-weight: 700;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
    text-align: left;
    color: white;
    text-transform: uppercase;
}

.modern-borrows-table tbody tr {
    transition: all 0.2s ease;
    border-bottom: 1px solid var(--grey-light);
}

.modern-borrows-table tbody tr:hover {
    background: var(--grey-lighter);
    transform: scale(1.005);
}

.modern-borrows-table tbody td {
    padding: 1.25rem 1rem;
    vertical-align: middle;
    color: var(--grey-medium);
    font-weight: 500;
}

.student-info strong {
    color: var(--grey-dark);
    font-weight: 700;
    font-family: 'Courier New', monospace;
}

.book-info strong {
    color: var(--grey-dark);
    font-weight: 600;
}

/* Status Badges */
.badge-modern {
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: inline-block;
}

.badge-borrowed {
    background: linear-gradient(135deg, var(--warning-gradient-start) 0%, var(--warning-gradient-end) 100%);
    color: white;
}

.badge-returned {
    background: linear-gradient(135deg, var(--success-gradient-start) 0%, var(--success-gradient-end) 100%);
    color: white;
}

.badge-overdue {
    background: linear-gradient(135deg, var(--red-gradient-start) 0%, var(--red-gradient-end) 100%);
    color: white;
}

.badge-danger-small {
    background: linear-gradient(135deg, var(--red-gradient-start) 0%, var(--red-gradient-end) 100%);
    color: white;
    padding: 0.35rem 0.75rem;
    border-radius: 50px;
    font-size: 0.7rem;
    font-weight: 600;
}

/* Fine Badges */
.badge-fine {
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 700;
    display: inline-block;
    letter-spacing: 0.3px;
}

.badge-danger {
    background: linear-gradient(135deg, var(--red-gradient-start) 0%, var(--red-gradient-end) 100%);
    color: white;
}

.badge-success {
    background: linear-gradient(135deg, var(--success-gradient-start) 0%, var(--success-gradient-end) 100%);
    color: white;
}

.fine-info {
    display: flex;
    flex-direction: column;
    gap: 0.35rem;
}

.paid-info {
    color: var(--success-gradient-start);
    font-weight: 600;
    font-size: 0.75rem;
}

/* Action Buttons */
.btn-action {
    border: none;
    padding: 0.6rem 1.25rem;
    border-radius: 8px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.75rem;
    transition: all 0.3s;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    line-height: 1;
    height: 38px;
}

.btn-return {
    background: linear-gradient(135deg, var(--success-gradient-start) 0%, var(--success-gradient-end) 100%);
    color: white;
}

.btn-return:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(16, 185, 129, 0.35);
    color: white;
}

.btn-lost {
    background: linear-gradient(135deg, var(--red-gradient-start) 0%, var(--red-gradient-end) 100%);
    color: white;
}

.btn-lost:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(239, 68, 68, 0.35);
    color: white;
}

.action-buttons {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.returned-label {
    color: var(--success-gradient-start);
    font-weight: 600;
    font-size: 0.875rem;
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
}

.returned-label i {
    font-size: 1rem;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: linear-gradient(135deg, var(--grey-lighter) 0%, white 100%);
    border-radius: 12px;
    margin: 2rem;
}

.empty-state i {
    color: var(--grey-medium);
    font-size: 4rem;
    margin-bottom: 1.5rem;
}

.empty-state p {
    color: var(--grey-dark);
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.empty-state small {
    color: var(--grey-medium);
    font-size: 0.95rem;
}
</style>

<div class="container-fluid borrows-container">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <h1><i class="fas fa-exchange-alt me-3"></i>Borrow Management</h1>
            <div class="header-actions">
                <a href="<?= BASE_PATH ?>/librarian/borrow-book" class="btn-header">
                    <i class="fas fa-book"></i> Borrow Book
                </a>
                <a href="<?= BASE_PATH ?>/librarian/quick-borrow" class="btn-header success">
                    <i class="fas fa-bolt"></i> Quick Borrow
                </a>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success-modern alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i><?= $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger-modern alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><?= $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card stat-card-modern card-purple">
                <div class="card-body">
                    <div class="stat-icon">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <h5>Total Borrows</h5>
                    <h2><?= $borrow_stats['total_borrows'] ?? 0 ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card-modern card-warning">
                <div class="card-body">
                    <div class="stat-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <h5>Current Borrows</h5>
                    <h2><?= $borrow_stats['current_borrows'] ?? 0 ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card-modern card-danger">
                <div class="card-body">
                    <div class="stat-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h5>Overdue Books</h5>
                    <h2><?= $borrow_stats['overdue_books'] ?? 0 ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card-modern card-success">
                <div class="card-body">
                    <div class="stat-icon">
                        <i class="fas fa-money-bill"></i>
                    </div>
                    <h5>Total Fines</h5>
                    <h2>MK <?= number_format($borrow_stats['total_fines'] ?? 0) ?></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Live Search -->
    <div class="search-card">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="search-input-group input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" id="liveSearch" class="form-control" placeholder="Search borrows (student, book, ID, status...)">
                </div>
            </div>
            <div class="col-md-6">
                <div id="searchResults">
                    <small>Showing <span id="visibleCount">0</span> of <span id="totalCount">0</span> records</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Form -->
    <div class="filter-card">
        <form method="POST" action="<?= BASE_PATH ?>/librarian/borrows">
            <div class="row g-3">
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
                    <button type="submit" class="btn btn-filter w-100 d-none">Filter</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Borrows Table -->
    <div class="table-card">
        <div class="table-responsive">
            <table id="borrowsTable" class="modern-borrows-table">
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
                        <tr class="borrow-row">
                            <td class="student-info">
                                <strong><?= htmlspecialchars($borrow['student_id']) ?></strong><br>
                                <small><?= htmlspecialchars($borrow['student_name']) ?> - Class <?= htmlspecialchars($borrow['class']) ?></small>
                            </td>
                            <td class="book-info">
                                <strong><?= htmlspecialchars($borrow['title']) ?></strong><br>
                                <small>by <?= htmlspecialchars($borrow['author']) ?></small>
                            </td>
                            <td><?= date('M j, Y', strtotime($borrow['borrowed_date'])) ?></td>
                            <td>
                                <?= date('M j, Y', strtotime($borrow['due_date'])) ?>
                                <?php if ($borrow['status'] === 'borrowed' && $borrow['days_overdue_calc'] > 0): ?>
                                    <br><span class="badge-danger-small">Overdue <?= $borrow['days_overdue_calc'] ?> days</span>
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
                                <span class="badge-modern badge-<?= 
                                    $borrow['status'] === 'borrowed' ? 'borrowed' : 
                                    ($borrow['status'] === 'overdue' ? 'overdue' : 'returned')
                                ?>">
                                    <?= ucfirst($borrow['status']) ?>
                                </span>
                            </td>
                            <td>
                                <?php 
                                    $fineTotal = (float)($borrow['fine_amount'] ?? 0);
                                    $finePaid = (float)($borrow['paid_amount'] ?? 0);
                                    $fineRemaining = max(0.0, $fineTotal - $finePaid);
                                ?>
                                <?php if ($fineTotal > 0): ?>
                                    <div class="fine-info">
                                        <?php if ($borrow['status'] === 'returned' && $fineRemaining <= 0): ?>
                                            <span class="badge-fine badge-success">Paid: MK <?= number_format($finePaid, 2) ?></span>
                                            <br><small class="paid-info">Fine fully paid</small>
                                        <?php else: ?>
                                            <span class="badge-fine badge-danger">MK <?= number_format($fineTotal, 2) ?></span>
                                            <?php if ($finePaid > 0): ?>
                                                <br><small class="paid-info">Paid: MK <?= number_format($finePaid, 2) ?></small>
                                            <?php endif; ?>
                                            <?php if ($borrow['status'] === 'returned'): ?>
                                                <br><small class="text-muted">Remaining: MK <?= number_format($fineRemaining, 2) ?></small>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <span class="badge-fine badge-success">MK 0.00</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <?php if ($borrow['status'] !== 'returned'): ?>
                                        <form method="POST" action="<?= BASE_PATH ?>/librarian/return-book" class="d-inline">
                                            <input type="hidden" name="borrow_id" value="<?= $borrow['id'] ?>">
                                            <button type="submit" class="btn-action btn-return" title="Return Book">
                                                <i class="fas fa-undo"></i> Return
                                            </button>
                                        </form>
                                        <form method="POST" action="<?= BASE_PATH ?>/librarian/mark-lost" class="d-inline">
                                            <input type="hidden" name="borrow_id" value="<?= $borrow['id'] ?>">
                                            <button type="submit" class="btn-action btn-lost" title="Mark Lost">
                                                <i class="fas fa-book-dead"></i> Mark Lost
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <span class="returned-label">
                                            <i class="fas fa-check-circle"></i> Returned
                                        </span>
                                        <?php if ($fineRemaining > 0): ?>
                                            <button type="button" class="btn-action btn-return" 
                                                    onclick="openPaymentModal(<?= $borrow['id'] ?>, <?= $fineAmount ?>, <?= $paidAmount ?>, '<?= htmlspecialchars($borrow['book_title']) ?>', '<?= htmlspecialchars($borrow['student_name']) ?>')" 
                                                    title="Pay Fine">
                                                <i class="fas fa-money-bill-wave"></i> Pay Fine
                                            </button>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?php if (empty($borrows)): ?>
                <div class="empty-state">
                    <i class="fas fa-exchange-alt"></i>
                    <p>No borrowing records found</p>
                    <small>Try adjusting your filters or search terms</small>
                </div>
            <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">
                    <i class="fas fa-money-bill-wave me-2"></i>Record Fine Payment
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="paymentForm" method="POST" action="<?= BASE_PATH ?>/librarian/pay-fine">
                <div class="modal-body">
                    <input type="hidden" id="payment_borrow_id" name="borrow_id">
                    
                    <div class="mb-3">
                        <p class="mb-2"><strong>Book:</strong> <span id="payment_book"></span></p>
                        <p class="mb-3"><strong>Student:</strong> <span id="payment_student"></span></p>
                    </div>

                    <div class="alert alert-info mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span><strong>Total Fine:</strong></span>
                            <span class="text-end"><strong>MK <span id="payment_total">0.00</span></strong></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Already Paid:</span>
                            <span class="text-end text-success">MK <span id="payment_paid">0.00</span></span>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between">
                            <span><strong>Remaining:</strong></span>
                            <span class="text-end"><strong class="text-danger">MK <span id="payment_remaining">0.00</span></strong></span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="payment_amount" class="form-label">
                            Payment Amount <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">MK</span>
                            <input type="number" class="form-control" id="payment_amount" name="amount" 
                                   step="0.01" min="0.01" required placeholder="Enter amount">
                            <button type="button" class="btn btn-outline-secondary" id="payFullBtn">
                                Pay Full
                            </button>
                        </div>
                        <small class="form-text text-muted">
                            Enter any amount up to the remaining balance. Partial payments are allowed.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check me-2"></i>Record Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let paymentModal;
document.addEventListener('DOMContentLoaded', function() {
    paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));
    const searchInput = document.getElementById('liveSearch');
    const table = document.getElementById('borrowsTable');
    const tbody = table.querySelector('tbody');
    const rows = tbody.querySelectorAll('tr');
    const visibleCountSpan = document.getElementById('visibleCount');
    const totalCountSpan = document.getElementById('totalCount');

    // Initialize counts
    let totalRows = rows.length;
    totalCountSpan.textContent = totalRows;
    visibleCountSpan.textContent = totalRows;

    // Function to highlight matching text
    function highlightText(text, searchTerm) {
        if (!searchTerm) return text;
        const regex = new RegExp(`(${searchTerm})`, 'gi');
        return text.replace(regex, '<mark>$1</mark>');
    }

    // Function to remove existing highlights
    function removeHighlights(element) {
        element.innerHTML = element.innerHTML.replace(/<mark>/g, '').replace(/<\/mark>/g, '');
    }

    // Live search function
    function performSearch() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        let visibleRows = 0;

        rows.forEach(row => {
            // Remove existing highlights
            const cells = row.querySelectorAll('td');
            cells.forEach(cell => removeHighlights(cell));

            if (!searchTerm) {
                row.style.display = '';
                visibleRows++;
                return;
            }

            // Get text content from all cells
            const rowText = Array.from(cells).map(cell => cell.textContent.toLowerCase()).join(' ');

            if (rowText.includes(searchTerm)) {
                row.style.display = '';
                visibleRows++;

                // Highlight matching terms
                cells.forEach(cell => {
                    const originalText = cell.textContent;
                    if (originalText.toLowerCase().includes(searchTerm)) {
                        cell.innerHTML = highlightText(originalText, searchTerm);
                    }
                });
            } else {
                row.style.display = 'none';
            }
        });

        visibleCountSpan.textContent = visibleRows;

        // Show message if no results
        const noResultsRow = tbody.querySelector('.no-results-row');
        if (visibleRows === 0 && searchTerm) {
            if (!noResultsRow) {
                const newRow = document.createElement('tr');
                newRow.className = 'no-results-row';
                newRow.innerHTML = `
                    <td colspan="7" class="text-center py-4">
                        <i class="fas fa-search fa-2x text-muted mb-2"></i>
                        <p class="text-muted">No borrows found matching "${searchInput.value}"</p>
                    </td>
                `;
                tbody.appendChild(newRow);
            }
        } else if (noResultsRow) {
            noResultsRow.remove();
        }
    }

    // Add event listener for real-time search
    searchInput.addEventListener('input', performSearch);

    // Clear search on escape key
    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            this.value = '';
            performSearch();
        }
    });
    // AJAX: handle Return and Pay Fine without reload
    function showInlineAlert(message, type = 'success') {
        const container = document.querySelector('.borrows-container');
        if (!container) return;
        const alert = document.createElement('div');
        alert.className = 'alert alert-' + (type === 'success' ? 'success-modern' : 'danger-modern') + ' alert-dismissible fade show';
        alert.setAttribute('role', 'alert');
        alert.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} me-2"></i>${message}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
        container.insertBefore(alert, container.firstChild.nextSibling);
        setTimeout(() => { if (alert) alert.remove(); }, 5000);
    }

    function findCell(row, index) {
        return row.querySelectorAll('td')[index];
    }

    function updateRowAfterReturn(row, data) {
        // Columns: 0 student, 1 book, 2 borrowed, 3 due, 4 returned, 5 status, 6 fine, 7 actions
        const returnedCell = findCell(row, 4);
        const statusCell = findCell(row, 5);
        const fineCell = findCell(row, 6);
        const actionsCell = findCell(row, 7);

        if (returnedCell) {
            const dt = new Date(data.returned_date);
            const options = { month: 'short', day: 'numeric', year: 'numeric' };
            returnedCell.textContent = dt.toLocaleDateString(undefined, options);
        }

        if (statusCell) {
            statusCell.innerHTML = '<span class="badge-modern badge-returned">Returned</span>';
        }

        if (fineCell) {
            const fineTotal = Number(data.fine_amount || 0);
            if (fineTotal > 0) {
                // Keep existing paid amount in the row if present
                const paidMatch = fineCell.innerText.match(/Paid:\s*MK\s*([0-9,.]+)/i);
                const paidValue = paidMatch ? Number(paidMatch[1].replace(/,/g, '')) : 0;
                const remaining = Math.max(0, fineTotal - paidValue);
                fineCell.innerHTML = `
                    <div class="fine-info">
                        <span class="badge-fine badge-danger">MK ${fineTotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',')}</span>
                        ${paidValue > 0 ? `<br><small class="paid-info">Paid: MK ${paidValue.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',')}</small>` : ''}
                        <br><small class="text-muted">Remaining: MK ${remaining.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',')}</small>
                    </div>
                `;
            } else {
                fineCell.innerHTML = '<span class="badge-fine badge-success">MK 0.00</span>';
            }
        }

        if (actionsCell) {
            // Replace Return form with Returned label and Pay button (if fine)
            const hasFine = Number(data.fine_amount || 0) > 0;
            let html = `
                <span class="returned-label">
                    <i class="fas fa-check-circle me-1"></i> Returned
                </span>
            `;
            if (hasFine) {
                const borrowId = row.querySelector('input[name="borrow_id"]').value;
                const bookTitle = findCell(row, 2)?.innerText || 'Unknown';
                const studentName = findCell(row, 1)?.innerText || 'Unknown';
                const fineAmount = Number(data.fine_amount || 0);
                const paidAmount = Number(data.paid_amount || 0);
                html = `
                    <div class="action-buttons">
                        ${html}
                        <button type="button" class="btn-action btn-return" 
                                onclick="openPaymentModal(${borrowId}, ${fineAmount}, ${paidAmount}, '${bookTitle.replace(/'/g, "\\'")}',' ${studentName.replace(/'/g, "\\'")}')" 
                                title="Pay Fine">
                            <i class="fas fa-money-bill-wave"></i> Pay Fine
                        </button>
                    </div>
                `;
            } else {
                html = `<div class="action-buttons">${html}</div>`;
            }
            actionsCell.innerHTML = html;
        }
    }

    function updateRowAfterPayment(row, data) {
        const fineCell = findCell(row, 6);
        const actionsCell = findCell(row, 7);
        if (!fineCell) return;

        // Parse current totals
        const totalMatch = fineCell.innerText.match(/MK\s*([0-9,.]+)/);
        const paidMatch = fineCell.innerText.match(/Paid:\s*MK\s*([0-9,.]+)/i);
        const total = totalMatch ? Number(totalMatch[1].replace(/,/g, '')) : 0;
        const prevPaid = paidMatch ? Number(paidMatch[1].replace(/,/g, '')) : 0;
        const newPaid = prevPaid + Number(data.paid_now || 0);
        const remaining = Math.max(0, total - newPaid);

        if (remaining <= 0) {
            fineCell.innerHTML = `
                <div class="fine-info">
                    <span class="badge-fine badge-success">Paid: MK ${newPaid.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',')}</span>
                    <br><small class="paid-info">Fine fully paid</small>
                </div>
            `;
            if (actionsCell) {
                // Remove Pay button
                actionsCell.innerHTML = `
                    <span class="returned-label">
                        <i class="fas fa-check-circle me-1"></i> Returned
                    </span>
                `;
            }
        } else {
            fineCell.innerHTML = `
                <div class="fine-info">
                    <span class="badge-fine badge-danger">MK ${total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',')}</span>
                    <br><small class="paid-info">Paid: MK ${newPaid.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',')}</small>
                    <br><small class="text-muted">Remaining: MK ${remaining.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',')}</small>
                </div>
            `;
        }
    }

    function attachAjaxHandlers(scope) {
        const returnForms = (scope || document).querySelectorAll('form[action$="/librarian/return-book"]');
        returnForms.forEach(form => {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const row = e.target.closest('tr');
                const formData = new FormData(form);
                try {
                    const res = await fetch(form.action, {
                        method: 'POST',
                        headers: { 'X-Requested-With': 'XMLHttpRequest' },
                        body: formData
                    });
                    const json = await res.json();
                    if (!res.ok || !json.success) throw new Error(json.message || 'Failed to return');
                    updateRowAfterReturn(row, json.data || {});
                    showInlineAlert(json.message || 'Book returned');
                    // Re-attach handler for newly injected Pay Fine form
                    attachAjaxHandlers(row);
                } catch (err) {
                    showInlineAlert(err.message || 'Error returning book', 'error');
                }
            });
        });

        // Pay fine now handled by modal, no inline forms needed

        const lostForms = (scope || document).querySelectorAll('form[action$="/librarian/mark-lost"]');
        lostForms.forEach(form => {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const row = e.target.closest('tr');
                const formData = new FormData(form);
                try {
                    const res = await fetch(form.action, {
                        method: 'POST',
                        headers: { 'X-Requested-With': 'XMLHttpRequest' },
                        body: formData
                    });
                    const json = await res.json();
                    if (!res.ok || !json.success) throw new Error(json.message || 'Failed to mark lost');
                    // Visual cue: add a red badge in status cell
                    const statusCell = findCell(row, 5);
                    if (statusCell) {
                        statusCell.innerHTML = '<span class="badge-modern badge-overdue">Overdue (Lost)</span>';
                    }
                    showInlineAlert('Marked as lost');
                } catch (err) {
                    showInlineAlert(err.message || 'Error marking lost', 'error');
                }
            });
        });
    }

    attachAjaxHandlers();
    
    // Payment form handler
    const paymentForm = document.getElementById('paymentForm');
    if (paymentForm) {
        paymentForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(paymentForm);
            const borrowId = formData.get('borrow_id');
            
            try {
                const res = await fetch(paymentForm.action, {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    body: formData
                });
                const json = await res.json();
                if (!res.ok || !json.success) throw new Error(json.message || 'Failed to record payment');
                
                // Close modal
                paymentModal.hide();
                
                // Find and update the row
                const row = document.querySelector(`input[name="borrow_id"][value="${borrowId}"]`)?.closest('tr');
                if (row) {
                    updateRowAfterPayment(row, json.data || {});
                }
                
                showInlineAlert(json.message || 'Payment recorded successfully');
                
                // Reload page after 1 second to reflect changes
                setTimeout(() => window.location.reload(), 1000);
            } catch (err) {
                showInlineAlert(err.message || 'Error recording payment', 'error');
            }
        });
    }
    
    // Pay Full button
    const payFullBtn = document.getElementById('payFullBtn');
    if (payFullBtn) {
        payFullBtn.addEventListener('click', function() {
            const remaining = parseFloat(document.getElementById('payment_remaining').textContent.replace(/,/g, ''));
            document.getElementById('payment_amount').value = remaining.toFixed(2);
        });
    }
});

function openPaymentModal(borrowId, totalFine, paidAmount, bookTitle, studentName) {
    document.getElementById('payment_borrow_id').value = borrowId;
    document.getElementById('payment_book').textContent = bookTitle;
    document.getElementById('payment_student').textContent = studentName;
    document.getElementById('payment_total').textContent = Number(totalFine).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    document.getElementById('payment_paid').textContent = Number(paidAmount).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    
    const remaining = Number(totalFine) - Number(paidAmount);
    document.getElementById('payment_remaining').textContent = remaining.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    document.getElementById('payment_amount').value = '';
    document.getElementById('payment_amount').setAttribute('max', remaining.toFixed(2));
    
    paymentModal.show();
    
    // Focus on amount input after modal is shown
    setTimeout(() => {
        document.getElementById('payment_amount').focus();
    }, 300);
}

// Live filter via AJAX (no reload, no button click)
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.filter-card form');
    const tbody = document.querySelector('#borrowsTable tbody');
    const inputs = form.querySelectorAll('select, input');

    let filterTimer;
    inputs.forEach(inp => {
        inp.addEventListener('input', queueFilter);
        inp.addEventListener('change', queueFilter);
    });

    function queueFilter() {
        clearTimeout(filterTimer);
        filterTimer = setTimeout(applyFilter, 250);
    }

    async function applyFilter() {
        const params = new URLSearchParams(new FormData(form));
        const url = '<?= BASE_PATH ?>/librarian/borrows-data?' + params.toString();
        try {
            const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }});
            const json = await res.json();
            const rows = (json.borrows || []).map(renderRow).join('');
            tbody.innerHTML = rows;
            // Re-attach handlers for AJAX actions
            (function(){ attachAjaxHandlers(); })();
        } catch(e) {
            console.error(e);
        }
    }

    function renderRow(borrow) {
        const returnedDate = borrow.returned_date ? new Date(borrow.returned_date) : null;
        const dueDate = new Date(borrow.due_date);
        const borrowedDate = new Date(borrow.borrowed_date);
        const status = borrow.status;
        const fine = Number(borrow.fine_amount || 0);
        const paid = Number(borrow.paid_amount || 0);
        const remaining = Math.max(0, fine - paid);
        return `
        <tr class="borrow-row">
            <td class="student-info">
                <strong>${escapeHtml(borrow.student_id)}</strong><br>
                <small>${escapeHtml(borrow.student_name)} - Class ${escapeHtml(borrow.class || '')}</small>
            </td>
            <td class="book-info">
                <strong>${escapeHtml(borrow.title)}</strong><br>
                <small>by ${escapeHtml(borrow.author || '')}</small>
            </td>
            <td>${formatDate(borrowedDate)}</td>
            <td>${formatDate(dueDate)}${(status==='borrowed' && (borrow.days_overdue_calc||0)>0) ? `<br><span class="badge-danger-small">Overdue ${borrow.days_overdue_calc} days</span>` : ''}</td>
            <td>${returnedDate ? formatDate(returnedDate) : '<span class="text-muted">Not returned</span>'}</td>
            <td><span class="badge-modern ${status==='borrowed' ? 'badge-borrowed' : (status==='overdue' ? 'badge-overdue' : 'badge-returned')}">${capitalize(status)}</span></td>
            <td>${renderFine(fine, paid, remaining, status)}</td>
            <td>${renderActions(borrow.id, status, remaining)}</td>
        </tr>`;
    }

    function renderFine(fine, paid, remaining, status){
        if (fine>0) {
            if (status==='returned' && remaining<=0) {
                return `<div class="fine-info"><span class="badge-fine badge-success">Paid: MK ${fmt(paid)}</span><br><small class="paid-info">Fine fully paid</small></div>`;
            }
            return `<div class="fine-info"><span class="badge-fine badge-danger">MK ${fmt(fine)}</span>${paid>0?`<br><small class="paid-info">Paid: MK ${fmt(paid)}</small>`:''}${status==='returned'?`<br><small class="text-muted">Remaining: MK ${fmt(remaining)}</small>`:''}</div>`;
        }
        return `<span class="badge-fine badge-success">MK 0.00</span>`;
    }

    function renderActions(id, status, remaining){
        if (status!=='returned') {
            return `<div class="action-buttons">
                <form method="POST" action="<?= BASE_PATH ?>/librarian/return-book" class="d-inline">
                    <input type="hidden" name="borrow_id" value="${id}">
                    <button type="submit" class="btn-action btn-return" title="Return Book">
                        <i class="fas fa-undo"></i> Return
                    </button>
                </form>
                <form method="POST" action="<?= BASE_PATH ?>/librarian/mark-lost" class="d-inline">
                    <input type="hidden" name="borrow_id" value="${id}">
                    <button type="submit" class="btn-action btn-lost" title="Mark Lost">
                        <i class="fas fa-book-dead"></i> Mark Lost
                    </button>
                </form>
            </div>`;
        }
        return `<div class="action-buttons">
            <span class="returned-label"><i class="fas fa-check-circle"></i> Returned</span>
            ${remaining>0?`<form method="POST" action="<?= BASE_PATH ?>/librarian/pay-fine" class="d-inline js-pay-fine-form">
                <input type="hidden" name="borrow_id" value="${id}">
                <input type="number" name="amount" step="0.01" min="0.01" placeholder="Amount" style="max-width:120px; padding:6px 8px; border:1px solid #e5e7eb; border-radius:8px;">
                <button type="submit" class="btn-action btn-return" title="Pay Fine">
                    <i class="fas fa-money-bill-wave"></i> Pay Fine
                </button>
            </form>`:''}
        </div>`;
    }

    function formatDate(d){ return d.toLocaleDateString(undefined,{month:'short',day:'numeric',year:'numeric'}); }
    function fmt(n){ return Number(n).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ','); }
    function capitalize(s){ return (s||'').charAt(0).toUpperCase() + (s||'').slice(1); }
    function escapeHtml(s){ return (s||'').toString().replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;','\'':'&#39;'}[c])); }
});
</script>

<?php include '../app/views/shared/footer.php'; ?>