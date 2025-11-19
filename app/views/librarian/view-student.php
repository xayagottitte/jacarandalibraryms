<?php 
$title = "Student Details - Jacaranda Libraries";
include '../app/views/shared/header.php'; 
include '../app/views/shared/layout-header.php'; 
?>

<style>
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
    --success-gradient-start: #10b981;
    --success-gradient-end: #059669;
    --warning-gradient-start: #f59e0b;
    --warning-gradient-end: #d97706;
    --danger-gradient-start: #ef4444;
    --danger-gradient-end: #dc2626;
    --blue-gradient-start: #3b82f6;
    --blue-gradient-end: #2563eb;
}

.view-student-container {
    padding: 2rem;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    min-height: 100vh;
}

/* Page Header */
.page-header {
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--dark-purple) 100%);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 10px 30px rgba(99, 102, 241, 0.25);
    color: white;
}

.page-header h2 {
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.page-header h2 i {
    font-size: 2rem;
}

.header-actions {
    display: flex;
    gap: 0.75rem;
}

.btn-header {
    background: rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    font-size: 0.95rem;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-header:hover {
    background: rgba(255, 255, 255, 0.35);
    transform: translateY(-2px);
    color: white;
    box-shadow: 0 6px 20px rgba(255, 255, 255, 0.2);
}

.btn-header.primary {
    background: rgba(255, 255, 255, 0.95);
    color: var(--primary-purple);
}

.btn-header.primary:hover {
    background: white;
    color: var(--dark-purple);
}

/* Info Card */
.info-card {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    margin-bottom: 2rem;
}

.card-header-custom {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid var(--grey-light);
}

.card-header-custom i {
    font-size: 1.5rem;
    color: var(--primary-purple);
}

.card-header-custom h5 {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--grey-dark);
    margin: 0;
}

/* Info Table */
.info-table {
    width: 100%;
}

.info-table tr {
    border-bottom: 1px solid var(--grey-lighter);
}

.info-table tr:last-child {
    border-bottom: none;
}

.info-table th {
    padding: 1rem 0;
    font-weight: 700;
    color: var(--grey-dark);
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    width: 160px;
}

.info-table td {
    padding: 1rem 0;
    color: var(--grey-medium);
    font-weight: 500;
    font-size: 1rem;
}

/* Badges */
.badge-custom {
    padding: 0.5rem 1rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.875rem;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.badge-success {
    background: linear-gradient(135deg, var(--success-gradient-start) 0%, var(--success-gradient-end) 100%);
    color: white;
}

.badge-warning {
    background: linear-gradient(135deg, var(--warning-gradient-start) 0%, var(--warning-gradient-end) 100%);
    color: white;
}

.badge-danger {
    background: linear-gradient(135deg, var(--danger-gradient-start) 0%, var(--danger-gradient-end) 100%);
    color: white;
}

.badge-purple {
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--dark-purple) 100%);
    color: white;
}

.badge-secondary {
    background: linear-gradient(135deg, var(--grey-medium) 0%, var(--grey-dark) 100%);
    color: white;
}

/* Student ID Badge */
.student-id-badge {
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--dark-purple) 100%);
    color: white;
    padding: 0.75rem 1.25rem;
    border-radius: 12px;
    font-weight: 700;
    font-size: 1.125rem;
    letter-spacing: 1px;
}

/* Stat Cards */
.stat-card {
    text-align: center;
    padding: 1.5rem;
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    transition: all 0.3s;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
}

.stat-value {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.stat-label {
    color: var(--grey-medium);
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-icon {
    font-size: 2rem;
    margin-bottom: 1rem;
}

/* Borrow Table */
.borrow-table {
    width: 100%;
    border-collapse: collapse;
}

.borrow-table thead {
    background: linear-gradient(135deg, var(--jacaranda-primary) 0%, var(--jacaranda-secondary) 100%);
}

.borrow-table thead th {
    padding: 1rem;
    color: white;
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    text-align: left;
}

.borrow-table tbody tr {
    border-bottom: 1px solid var(--grey-lighter);
    transition: all 0.2s;
}

.borrow-table tbody tr:hover {
    background: var(--grey-lighter);
}

.borrow-table tbody td {
    padding: 1rem;
    color: var(--grey-dark);
    font-weight: 500;
}

/* Quick Action Buttons */
.action-button {
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--dark-purple) 100%);
    border: none;
    color: white;
    padding: 1rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    text-decoration: none;
    cursor: pointer;
    width: 100%;
    box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
}

.action-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 25px rgba(99, 102, 241, 0.4);
    color: white;
}

.action-button.success {
    background: linear-gradient(135deg, var(--success-gradient-start) 0%, var(--success-gradient-end) 100%);
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
}

.action-button.success:hover {
    box-shadow: 0 6px 25px rgba(16, 185, 129, 0.4);
}

.action-button.outline {
    background: white;
    border: 2px solid var(--grey-light);
    color: var(--grey-dark);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
}

.action-button.outline:hover {
    background: var(--grey-lighter);
    border-color: var(--grey-medium);
    box-shadow: 0 6px 25px rgba(0, 0, 0, 0.12);
}

/* Return Button */
.btn-return {
    background: linear-gradient(135deg, var(--success-gradient-start) 0%, var(--success-gradient-end) 100%);
    border: none;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.75rem;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
}

.btn-return:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem;
    color: var(--grey-medium);
}

.empty-state i {
    font-size: 3rem;
    color: var(--grey-light);
    margin-bottom: 1rem;
}

/* Responsive */
@media (max-width: 768px) {
    .view-student-container {
        padding: 1rem;
    }
    
    .page-header {
        padding: 1.5rem;
    }
    
    .page-header h2 {
        font-size: 1.5rem;
    }
    
    .header-actions {
        flex-direction: column;
        width: 100%;
    }
    
    .btn-header {
        width: 100%;
        justify-content: center;
    }
}
</style>

<div class="container-fluid view-student-container">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h2><i class="fas fa-user-graduate"></i>Student Details</h2>
            <div class="header-actions">
                <a href="<?= BASE_PATH ?>/librarian/borrow-book?student_id=<?= $student['id'] ?>" class="btn-header primary">
                    <i class="fas fa-book"></i>Borrow Book
                </a>
                <a href="<?= BASE_PATH ?>/librarian/edit-student?id=<?= $student['id'] ?>" class="btn-header">
                    <i class="fas fa-edit"></i>Edit
                </a>
            </div>
        </div>
    </div>

    <!-- Student Information -->
    <div class="row">
        <div class="col-lg-8">
            <div class="info-card">
                <div class="card-header-custom">
                    <i class="fas fa-id-card"></i>
                    <h5>Student Information</h5>
                </div>
                <table class="info-table">
                    <tr>
                        <th><i class="fas fa-hashtag me-2" style="color: var(--primary-purple);"></i>Student ID</th>
                        <td><span class="student-id-badge"><?= htmlspecialchars($student['student_id']) ?></span></td>
                    </tr>
                    <tr>
                        <th><i class="fas fa-user me-2" style="color: var(--primary-purple);"></i>Full Name</th>
                        <td><?= htmlspecialchars($student['full_name']) ?></td>
                    </tr>
                    <tr>
                        <th><i class="fas fa-chalkboard me-2" style="color: var(--primary-purple);"></i>Class & Section</th>
                        <td>
                            <span class="badge-purple badge-custom">
                                Class <?= htmlspecialchars($student['class']) ?>
                                <?php if ($student['section']): ?>
                                    - Section <?= htmlspecialchars($student['section']) ?>
                                <?php endif; ?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th><i class="fas fa-envelope me-2" style="color: var(--primary-purple);"></i>Email</th>
                        <td>
                            <?php if ($student['email']): ?>
                                <?= htmlspecialchars($student['email']) ?>
                            <?php else: ?>
                                <span style="color: var(--grey-light);">Not provided</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th><i class="fas fa-phone me-2" style="color: var(--primary-purple);"></i>Phone</th>
                        <td>
                            <?php if ($student['phone']): ?>
                                <?= htmlspecialchars($student['phone']) ?>
                            <?php else: ?>
                                <span style="color: var(--grey-light);">Not provided</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th><i class="fas fa-info-circle me-2" style="color: var(--primary-purple);"></i>Status</th>
                        <td>
                            <span class="badge-custom badge-<?= $student['status'] === 'active' ? 'success' : 'secondary' ?>">
                                <i class="fas fa-<?= $student['status'] === 'active' ? 'check-circle' : 'times-circle' ?>"></i>
                                <?= ucfirst($student['status']) ?>
                            </span>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Active Borrows -->
            <div class="info-card">
                <div class="card-header-custom">
                    <i class="fas fa-book-reader"></i>
                    <h5>Currently Borrowed Books</h5>
                </div>
                <?php if (!empty($active_borrows)): ?>
                    <div class="table-responsive">
                        <table class="borrow-table">
                            <thead>
                                <tr>
                                    <th width="60"><i class="fas fa-image me-2"></i>Cover</th>
                                    <th><i class="fas fa-book me-2"></i>Book Title</th>
                                    <th><i class="fas fa-user-edit me-2"></i>Author</th>
                                    <th><i class="fas fa-calendar-check me-2"></i>Borrowed</th>
                                    <th><i class="fas fa-calendar-times me-2"></i>Due Date</th>
                                    <th><i class="fas fa-clock me-2"></i>Days Left</th>
                                    <th><i class="fas fa-info-circle me-2"></i>Status</th>
                                    <th><i class="fas fa-cog me-2"></i>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($active_borrows as $borrow): ?>
                                    <tr>
                                        <td class="text-center">
                                            <?php 
                                            $bookModel = new Book();
                                            $coverUrl = BASE_PATH . $bookModel->getBookCoverUrl($borrow['cover_image'] ?? null);
                                            ?>
                                            <img src="<?= $coverUrl ?>" alt="Book Cover" 
                                                 style="width: 40px; height: 60px; object-fit: cover; border-radius: 4px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);"
                                                 title="<?= htmlspecialchars($borrow['title']) ?>">
                                        </td>
                                        <td><strong><?= htmlspecialchars($borrow['title']) ?></strong></td>
                                        <td><?= htmlspecialchars($borrow['author']) ?></td>
                                        <td><?= date('M j, Y', strtotime($borrow['borrowed_date'])) ?></td>
                                        <td><?= date('M j, Y', strtotime($borrow['due_date'])) ?></td>
                                        <td>
                                            <?php if ($borrow['days_remaining'] > 0): ?>
                                                <span class="badge-success badge-custom">
                                                    <i class="fas fa-check"></i><?= $borrow['days_remaining'] ?> days
                                                </span>
                                            <?php else: ?>
                                                <span class="badge-danger badge-custom">
                                                    <i class="fas fa-exclamation-triangle"></i>Overdue <?= abs($borrow['days_remaining']) ?> days
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge-custom badge-<?= $borrow['status'] === 'borrowed' ? 'warning' : 'danger' ?>">
                                                <i class="fas fa-<?= $borrow['status'] === 'borrowed' ? 'hand-holding' : 'exclamation-circle' ?>"></i>
                                                <?= ucfirst($borrow['status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <form method="POST" action="<?= BASE_PATH ?>/librarian/return-book" class="d-inline">
                                                <input type="hidden" name="borrow_id" value="<?= $borrow['id'] ?>">
                                                <button type="submit" class="btn-return">
                                                    <i class="fas fa-undo"></i>Return
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-book-open"></i>
                        <p class="mb-0">No currently borrowed books.</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Borrowing History -->
            <div class="info-card">
                <div class="card-header-custom">
                    <i class="fas fa-history"></i>
                    <h5>Borrowing History</h5>
                </div>
                <?php if (!empty($borrow_history)): ?>
                    <div class="table-responsive">
                        <table class="borrow-table">
                            <thead>
                                <tr>
                                    <th width="60"><i class="fas fa-image me-2"></i>Cover</th>
                                    <th><i class="fas fa-book me-2"></i>Book Title</th>
                                    <th><i class="fas fa-calendar-check me-2"></i>Borrowed</th>
                                    <th><i class="fas fa-calendar-times me-2"></i>Due Date</th>
                                    <th><i class="fas fa-calendar-day me-2"></i>Returned</th>
                                    <th><i class="fas fa-info-circle me-2"></i>Status</th>
                                    <th><i class="fas fa-exclamation-triangle me-2"></i>Overdue</th>
                                    <th><i class="fas fa-dollar-sign me-2"></i>Fine</th>
                                    <th><i class="fas fa-money-bill-wave me-2"></i>Paid</th>
                                    <th><i class="fas fa-balance-scale me-2"></i>Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($borrow_history as $history): ?>
                                    <tr>
                                        <td class="text-center">
                                            <?php 
                                            $bookModel = new Book();
                                            $coverUrl = BASE_PATH . $bookModel->getBookCoverUrl($history['cover_image'] ?? null);
                                            ?>
                                            <img src="<?= $coverUrl ?>" alt="Book Cover" 
                                                 style="width: 40px; height: 60px; object-fit: cover; border-radius: 4px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);"
                                                 title="<?= htmlspecialchars($history['title']) ?>">
                                        </td>
                                        <td><strong><?= htmlspecialchars($history['title']) ?></strong></td>
                                        <td><?= date('M j, Y', strtotime($history['borrowed_date'])) ?></td>
                                        <td><?= date('M j, Y', strtotime($history['due_date'])) ?></td>
                                        <td>
                                            <?php if ($history['returned_date']): ?>
                                                <?= date('M j, Y', strtotime($history['returned_date'])) ?>
                                            <?php else: ?>
                                                <span class="badge-custom badge-warning">
                                                    <i class="fas fa-clock"></i>Not Returned
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge-custom badge-<?= 
                                                $history['status'] === 'returned' ? 'success' : 
                                                ($history['status'] === 'borrowed' ? 'warning' : 'danger') 
                                            ?>">
                                                <i class="fas fa-<?= 
                                                    $history['status'] === 'returned' ? 'check-circle' : 
                                                    ($history['status'] === 'borrowed' ? 'hand-holding' : 'exclamation-circle') 
                                                ?>"></i>
                                                <?= ucfirst($history['status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($history['days_overdue'] > 0): ?>
                                                <span class="badge-custom badge-danger">
                                                    <i class="fas fa-exclamation-triangle"></i><?= $history['days_overdue'] ?> days
                                                </span>
                                            <?php else: ?>
                                                <span class="badge-custom badge-success">
                                                    <i class="fas fa-check"></i>On Time
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($history['calculated_fine'] > 0): ?>
                                                <span class="text-danger fw-bold">MK <?= number_format($history['calculated_fine'], 2) ?></span>
                                            <?php else: ?>
                                                <span class="text-success">MK 0.00</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($history['paid_amount'] > 0): ?>
                                                <span class="text-success fw-bold">MK <?= number_format($history['paid_amount'], 2) ?></span>
                                            <?php else: ?>
                                                <span class="text-muted">MK 0.00</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php 
                                            $balance = $history['calculated_fine'] - $history['paid_amount'];
                                            if ($balance > 0): ?>
                                                <span class="badge-custom badge-danger">
                                                    <i class="fas fa-exclamation-circle"></i>MK <?= number_format($balance, 2) ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="badge-custom badge-success">
                                                    <i class="fas fa-check-circle"></i>Paid
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-history"></i>
                        <p class="mb-0">No borrowing history found.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Statistics & Quick Actions Sidebar -->
        <div class="col-lg-4">
            <!-- Borrowing Statistics -->
            <div class="info-card">
                <div class="card-header-custom">
                    <i class="fas fa-chart-pie"></i>
                    <h5>Statistics</h5>
                </div>
                
                <?php 
                $totalBorrows = count($student['borrow_history']);
                $returnedBorrows = count(array_filter($student['borrow_history'], function($b) {
                    return $b['status'] === 'returned';
                }));
                $activeBorrows = count($active_borrows);
                ?>
                
                <div class="row g-3">
                    <div class="col-12">
                        <div class="stat-card">
                            <div class="stat-icon" style="color: var(--primary-purple);">
                                <i class="fas fa-book-reader"></i>
                            </div>
                            <div class="stat-value" style="background: linear-gradient(135deg, var(--primary-purple) 0%, var(--dark-purple) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"><?= $activeBorrows ?></div>
                            <div class="stat-label">Currently Borrowed</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card">
                            <div class="stat-icon" style="color: var(--success-gradient-start);">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="stat-value" style="background: linear-gradient(135deg, var(--success-gradient-start) 0%, var(--success-gradient-end) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"><?= $returnedBorrows ?></div>
                            <div class="stat-label">Returned</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card">
                            <div class="stat-icon" style="color: var(--blue-gradient-start);">
                                <i class="fas fa-history"></i>
                            </div>
                            <div class="stat-value" style="background: linear-gradient(135deg, var(--blue-gradient-start) 0%, var(--blue-gradient-end) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"><?= $totalBorrows ?></div>
                            <div class="stat-label">Total Borrows</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="info-card">
                <div class="card-header-custom">
                    <i class="fas fa-bolt"></i>
                    <h5>Quick Actions</h5>
                </div>
                <div class="d-flex flex-column gap-3">
                    <a href="<?= BASE_PATH ?>/librarian/borrow-book?student_id=<?= $student['id'] ?>" class="action-button success">
                        <i class="fas fa-book"></i>
                        <span>Borrow New Book</span>
                    </a>
                    <a href="<?= BASE_PATH ?>/librarian/edit-student?id=<?= $student['id'] ?>" class="action-button">
                        <i class="fas fa-edit"></i>
                        <span>Edit Student Info</span>
                    </a>
                    <a href="<?= BASE_PATH ?>/librarian/students" class="action-button outline">
                        <i class="fas fa-arrow-left"></i>
                        <span>Back to Students</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../app/views/shared/footer.php'; ?>