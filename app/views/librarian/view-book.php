<?php 
$title = "View Book - Multi-Library System";
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
    --red-gradient-start: #ef4444;
    --red-gradient-end: #dc2626;
    --blue-gradient-start: #3b82f6;
    --blue-gradient-end: #2563eb;
}

.view-book-container {
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

.btn-edit {
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

.btn-edit:hover {
    background: rgba(255, 255, 255, 0.35);
    transform: translateY(-2px);
    color: white;
    box-shadow: 0 6px 20px rgba(255, 255, 255, 0.2);
}

.btn-back {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.25);
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

.btn-back:hover {
    background: rgba(255, 255, 255, 0.25);
    transform: translateY(-2px);
    color: white;
    box-shadow: 0 6px 20px rgba(255, 255, 255, 0.15);
}

/* Modern Cards */
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

/* Book Info Table */
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
    background: linear-gradient(135deg, var(--red-gradient-start) 0%, var(--red-gradient-end) 100%);
    color: white;
}

.badge-info {
    background: linear-gradient(135deg, var(--blue-gradient-start) 0%, var(--blue-gradient-end) 100%);
    color: white;
}

.badge-purple {
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--dark-purple) 100%);
    color: white;
}

/* Stat Card */
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
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--accent-purple) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
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

.stat-icon.purple {
    color: var(--primary-purple);
}

.stat-icon.success {
    color: var(--success-gradient-start);
}

.stat-icon.warning {
    color: var(--warning-gradient-start);
}

.stat-icon.info {
    color: var(--blue-gradient-start);
}

/* Borrow History Table */
.history-table {
    width: 100%;
    border-collapse: collapse;
}

.history-table thead {
    background: linear-gradient(135deg, var(--jacaranda-primary) 0%, var(--jacaranda-secondary) 100%);
}

.history-table thead th {
    padding: 1rem;
    color: white;
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    text-align: left;
}

.history-table tbody tr {
    border-bottom: 1px solid var(--grey-lighter);
    transition: all 0.2s;
}

.history-table tbody tr:hover {
    background: var(--grey-lighter);
}

.history-table tbody td {
    padding: 1rem;
    color: var(--grey-dark);
    font-weight: 500;
}

.history-table tbody tr:last-child {
    border-bottom: none;
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
    .view-book-container {
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
    
    .btn-edit, .btn-back {
        width: 100%;
        justify-content: center;
    }
}
</style>

<div class="container-fluid view-book-container">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h2><i class="fas fa-book-open"></i>Book Details</h2>
            <div class="header-actions">
                <a href="/jacarandalibraryms/librarian/edit-book?id=<?= $book['id'] ?>" class="btn-edit">
                    <i class="fas fa-edit"></i>Edit Book
                </a>
                <a href="/jacarandalibraryms/librarian/books" class="btn-back">
                    <i class="fas fa-arrow-left"></i>Back to Books
                </a>
            </div>
        </div>
    </div>    <!-- Flash Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show auto-dismiss-alert" role="alert">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show auto-dismiss-alert" role="alert">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Book Information -->
    <div class="row">
        <div class="col-lg-8">
            <div class="info-card">
                <div class="card-header-custom">
                    <i class="fas fa-info-circle"></i>
                    <h5>Book Information</h5>
                </div>
                <table class="info-table">
                    <tr>
                        <th><i class="fas fa-book me-2" style="color: var(--primary-purple);"></i>Title</th>
                        <td><?= htmlspecialchars($book['title']) ?></td>
                    </tr>
                    <tr>
                        <th><i class="fas fa-user me-2" style="color: var(--primary-purple);"></i>Author</th>
                        <td><?= htmlspecialchars($book['author']) ?></td>
                    </tr>
                    <tr>
                        <th><i class="fas fa-barcode me-2" style="color: var(--primary-purple);"></i>ISBN</th>
                        <td><span class="badge-purple badge-custom"><?= htmlspecialchars($book['isbn'] ?? 'N/A') ?></span></td>
                    </tr>
                    <tr>
                        <th><i class="fas fa-building me-2" style="color: var(--primary-purple);"></i>Publisher</th>
                        <td><?= htmlspecialchars($book['publisher'] ?? 'N/A') ?></td>
                    </tr>
                    <tr>
                        <th><i class="fas fa-calendar me-2" style="color: var(--primary-purple);"></i>Publication Year</th>
                        <td><?= htmlspecialchars($book['publication_year'] ?? 'N/A') ?></td>
                    </tr>
                    <tr>
                        <th><i class="fas fa-tag me-2" style="color: var(--primary-purple);"></i>Category</th>
                        <td><span class="badge-info badge-custom"><?= htmlspecialchars($book['category'] ?? 'N/A') ?></span></td>
                    </tr>
                    <tr>
                        <th><i class="fas fa-layer-group me-2" style="color: var(--primary-purple);"></i>Class Level</th>
                        <td><?= htmlspecialchars($book['class_level'] ?? 'N/A') ?></td>
                    </tr>
                    <tr>
                        <th><i class="fas fa-copy me-2" style="color: var(--primary-purple);"></i>Total Copies</th>
                        <td><strong style="color: var(--primary-purple); font-size: 1.125rem;"><?= $book['total_copies'] ?></strong></td>
                    </tr>
                    <tr>
                        <th><i class="fas fa-check-circle me-2" style="color: var(--success-gradient-start);"></i>Available</th>
                        <td>
                            <?php if ($book['available_copies'] > 0): ?>
                                <span class="badge-success badge-custom"><?= $book['available_copies'] ?> Available</span>
                            <?php else: ?>
                                <span class="badge-danger badge-custom">0 Available</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th><i class="fas fa-info-circle me-2" style="color: var(--blue-gradient-start);"></i>Status</th>
                        <td>
                            <?php if ($book['available_copies'] > 0): ?>
                                <span class="badge-success badge-custom"><i class="fas fa-check-circle"></i>Available for Borrowing</span>
                            <?php else: ?>
                                <span class="badge-warning badge-custom"><i class="fas fa-exclamation-triangle"></i>All Copies Borrowed</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Statistics Sidebar -->
        <div class="col-lg-4">
            <div class="info-card">
                <div class="card-header-custom">
                    <i class="fas fa-chart-pie"></i>
                    <h5>Statistics</h5>
                </div>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="stat-card">
                            <div class="stat-icon purple">
                                <i class="fas fa-books"></i>
                            </div>
                            <div class="stat-value"><?= $book['total_copies'] ?></div>
                            <div class="stat-label">Total Copies</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card">
                            <div class="stat-icon success">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="stat-value" style="background: linear-gradient(135deg, var(--success-gradient-start) 0%, var(--success-gradient-end) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"><?= $book['available_copies'] ?></div>
                            <div class="stat-label">Available</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card">
                            <div class="stat-icon warning">
                                <i class="fas fa-hand-holding-heart"></i>
                            </div>
                            <div class="stat-value" style="background: linear-gradient(135deg, var(--warning-gradient-start) 0%, var(--warning-gradient-end) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"><?= $book['total_copies'] - $book['available_copies'] ?></div>
                            <div class="stat-label">Borrowed</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card">
                            <div class="stat-icon info">
                                <i class="fas fa-history"></i>
                            </div>
                            <div class="stat-value" style="background: linear-gradient(135deg, var(--blue-gradient-start) 0%, var(--blue-gradient-end) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"><?= count($borrow_history ?? []) ?></div>
                            <div class="stat-label">Total Borrows</div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
            
            <!-- Library Information -->
            <div class="info-card">
                <div class="card-header-custom">
                    <i class="fas fa-university"></i>
                    <h5>Library Information</h5>
                </div>
                <table class="info-table">
                    <tr>
                        <th><i class="fas fa-landmark me-2" style="color: var(--primary-purple);"></i>Library Name</th>
                        <td><?= htmlspecialchars($book['library_name'] ?? 'N/A') ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
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
    <div class="info-card">
        <div class="card-header-custom">
            <i class="fas fa-history"></i>
            <h5>Borrow History</h5>
        </div>
        <?php if (!empty($borrow_history)): ?>
        <div class="table-responsive">
            <table class="history-table">
                <thead>
                    <tr>
                        <th><i class="fas fa-user me-2"></i>Student</th>
                        <th><i class="fas fa-id-card me-2"></i>Student ID</th>
                        <th><i class="fas fa-chalkboard me-2"></i>Class</th>
                        <th><i class="fas fa-calendar-check me-2"></i>Borrowed</th>
                        <th><i class="fas fa-calendar-times me-2"></i>Due Date</th>
                        <th><i class="fas fa-calendar-day me-2"></i>Returned</th>
                        <th><i class="fas fa-info-circle me-2"></i>Status</th>
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
                                <em style="color: var(--grey-medium);">Not returned</em>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($borrow['status'] === 'returned'): ?>
                                <span class="badge-success badge-custom"><i class="fas fa-check-circle"></i>Returned</span>
                            <?php elseif ($borrow['status'] === 'overdue'): ?>
                                <span class="badge-danger badge-custom"><i class="fas fa-exclamation-circle"></i>Overdue</span>
                            <?php else: ?>
                                <span class="badge-warning badge-custom"><i class="fas fa-clock"></i>Borrowed</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <p class="mb-0">No borrow history available for this book.</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../app/views/shared/layout-footer.php'; ?>
<?php include '../app/views/shared/footer.php'; ?>
