<?php 
$title = "Librarian Dashboard - Multi-Library System";
include '../app/views/shared/header.php'; 
include '../app/views/shared/layout-header.php'; 
?>

<style>
/* Modern Dashboard Styles */
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

.dashboard-container {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    min-height: 100vh;
    padding: 2rem 0;
}

.dashboard-header {
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--dark-purple) 100%);
    border-radius: 16px;
    padding: 0.75rem 1.25rem;
    margin-bottom: 1.25rem;
    box-shadow: 0 4px 20px rgba(99, 102, 241, 0.12);
    color: white;
}

.dashboard-header h1 {
    font-weight: 700;
    font-size: 2rem;
    margin: 0;
    color: white;
}

.library-badge {
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    padding: 0.75rem 1.5rem;
    border-radius: 50px;
    font-size: 1rem;
    font-weight: 600;
    color: white;
}

/* Modern Stat Cards */
.stat-card-modern {
    border-radius: 20px;
    border: none;
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    min-height: 80px;
    height: auto;
    position: relative;
}

.stat-card-modern:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
}

.stat-card-modern .card-body {
    padding: 0.5rem 0.75rem;
    position: relative;
    z-index: 2;
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
    margin-bottom: 0.25rem;
}

.stat-card-modern small {
    font-size: 0.875rem;
    opacity: 0.85;
}

/* Gradient Cards */
.card-purple {
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--dark-purple) 100%);
    color: white;
}

.card-success {
    background: linear-gradient(135deg, var(--success-gradient-start) 0%, var(--success-gradient-end) 100%);
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

/* Modern Content Cards */
.content-card {
    border-radius: 20px;
    border: none;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    background: white;
    margin-bottom: 2rem;
}

.content-card .card-header {
    background: linear-gradient(135deg, var(--grey-lighter) 0%, white 100%);
    border: none;
    padding: 1.5rem 2rem;
    border-bottom: 1px solid var(--grey-light);
}

.content-card .card-header h5 {
    font-weight: 700;
    color: var(--grey-dark);
    margin: 0;
    font-size: 1.125rem;
}

.content-card .card-body {
    padding: 2rem;
}

/* Modern Table */
.table-modern {
    margin: 0;
}

.table-modern thead th {
    background: var(--grey-lighter);
    color: var(--grey-dark);
    font-weight: 700;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 1rem;
    border: none;
}

.table-modern tbody tr {
    border-bottom: 1px solid var(--grey-light);
    transition: all 0.2s;
}

.table-modern tbody tr:hover {
    background: var(--grey-lighter);
    transform: scale(1.01);
}

.table-modern tbody td {
    padding: 1rem;
    vertical-align: middle;
    color: var(--grey-medium);
    font-weight: 500;
}

/* Modern Badges */
.badge-modern {
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge-purple {
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--dark-purple) 100%);
    color: white;
}

.badge-success-modern {
    background: linear-gradient(135deg, var(--success-gradient-start) 0%, var(--success-gradient-end) 100%);
    color: white;
}

.badge-warning-modern {
    background: linear-gradient(135deg, var(--warning-gradient-start) 0%, var(--warning-gradient-end) 100%);
    color: white;
}

.badge-danger-modern {
    background: linear-gradient(135deg, var(--red-gradient-start) 0%, var(--red-gradient-end) 100%);
    color: white;
}

/* Modern Buttons */
.btn-modern {
    border-radius: 12px;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    border: none;
    transition: all 0.3s;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.875rem;
}

.btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}

.btn-modern-purple {
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--dark-purple) 100%);
    color: white;
}

.btn-modern-purple:hover {
    background: linear-gradient(135deg, var(--dark-purple) 0%, var(--primary-purple) 100%);
    color: white;
}

/* Quick Action Cards */
.quick-action-card {
    border-radius: 20px;
    border: 2px solid var(--grey-light);
    background: white;
    padding: 2rem;
    text-align: center;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
    text-decoration: none;
    display: block;
    height: 100%;
}

.quick-action-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(99, 102, 241, 0.2);
    border-color: var(--primary-purple);
    text-decoration: none;
}

.quick-action-card .icon-wrapper {
    width: 80px;
    height: 80px;
    border-radius: 20px;
    margin: 0 auto 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    color: white;
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--dark-purple) 100%);
}

.quick-action-card.success .icon-wrapper {
    background: linear-gradient(135deg, var(--success-gradient-start) 0%, var(--success-gradient-end) 100%);
}

.quick-action-card.warning .icon-wrapper {
    background: linear-gradient(135deg, var(--warning-gradient-start) 0%, var(--warning-gradient-end) 100%);
}

.quick-action-card.info .icon-wrapper {
    background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
}

.quick-action-card h5 {
    font-weight: 700;
    color: var(--grey-dark);
    margin: 0;
    font-size: 1.125rem;
}

/* Alert Messages */
.alert-modern {
    border-radius: 15px;
    border: none;
    padding: 1.25rem 1.5rem;
    font-weight: 500;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.alert-success-modern {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    color: #065f46;
}

.alert-danger-modern {
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    color: #991b1b;
}
</style>

<div class="container-fluid dashboard-container">
    <div class="dashboard-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <h1><i class="fas fa-chart-line me-3"></i>Librarian Dashboard</h1>
            <div class="library-badge">
                <i class="fas fa-library me-2"></i><?= htmlspecialchars($library['name']) ?>
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
    <div class="row row-cols-1 row-cols-lg-5 g-4 mb-4">
        <div class="col">
            <div class="card stat-card-modern card-purple">
                <div class="card-body">
                    <div class="stat-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <h5>Total Books</h5>
                    <h2><?= $book_stats['total_books'] ?? 0 ?></h2>
                    <small><?= $book_stats['total_copies'] ?? 0 ?> copies</small>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card stat-card-modern card-success">
                <div class="card-body">
                    <div class="stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h5>Available Books</h5>
                    <h2><?= $book_stats['available_copies'] ?? 0 ?></h2>
                    <small>Ready to borrow</small>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card stat-card-modern card-warning">
                <div class="card-body">
                    <div class="stat-icon">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <h5>Borrowed Books</h5>
                    <h2><?= $book_stats['borrowed_books'] ?? 0 ?></h2>
                    <small>Currently issued</small>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card stat-card-modern card-danger">
                <div class="card-body">
                    <div class="stat-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h5>Overdue Books</h5>
                    <h2><?= $book_stats['overdue_books'] ?? 0 ?></h2>
                    <small>Need attention</small>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card stat-card-modern card-danger">
                <div class="card-body">
                    <div class="stat-icon">
                        <i class="fas fa-book-dead"></i>
                    </div>
                    <h5>Lost Books</h5>
                    <h2><?= $lost_count ?? 0 ?></h2>
                    <small>Overdue > 30 days</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Recent Borrowings -->
        <div class="col-lg-6">
            <div class="card content-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5><i class="fas fa-history me-2"></i>Recent Borrowings</h5>
                    <a href="<?= BASE_PATH ?>/librarian/borrows" class="btn btn-modern btn-modern-purple btn-sm">View All</a>
                </div>
                <div class="card-body">
                    <?php if (!empty($recent_borrows)): ?>
                        <div class="table-responsive">
                            <table class="table table-modern">
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
                                            <td><strong><?= htmlspecialchars($borrow['student_name']) ?></strong></td>
                                            <td><?= htmlspecialchars($borrow['title']) ?></td>
                                            <td><?= date('M j, Y', strtotime($borrow['due_date'])) ?></td>
                                            <td>
                                                <span class="badge badge-modern <?= 
                                                    $borrow['status'] === 'borrowed' ? 'badge-warning-modern' : 
                                                    ($borrow['status'] === 'overdue' ? 'badge-danger-modern' : 'badge-success-modern')
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
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No recent borrowings</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Overdue Books -->
        <div class="col-lg-6">
            <div class="card content-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5><i class="fas fa-exclamation-circle me-2"></i>Overdue Books</h5>
                    <span class="badge badge-modern badge-danger-modern"><?= count($overdue_books) ?></span>
                </div>
                <div class="card-body">
                    <?php if (!empty($overdue_books)): ?>
                        <div class="table-responsive">
                            <table class="table table-modern">
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
                                            <td><strong><?= htmlspecialchars($borrow['student_name']) ?></strong></td>
                                            <td><?= htmlspecialchars($borrow['title']) ?></td>
                                            <td>
                                                <span class="badge badge-modern badge-danger-modern"><?= $borrow['days_overdue'] ?> days</span>
                                            </td>
                                            <td><?= date('M j, Y', strtotime($borrow['due_date'])) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <p class="text-success fw-bold">No overdue books</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Lost Books (Overdue > 30 days) -->
        <div class="col-lg-6">
            <div class="card content-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5><i class="fas fa-book-dead me-2"></i>Lost Books (Overdue > 30 days)</h5>
                    <span class="badge badge-modern badge-danger-modern"><?= $lost_count ?? 0 ?></span>
                </div>
                <div class="card-body">
                    <?php if (!empty($lost_books)): ?>
                        <div class="table-responsive">
                            <table class="table table-modern">
                                <thead>
                                    <tr>
                                        <th>Student</th>
                                        <th>Book</th>
                                        <th>Days Overdue</th>
                                        <th>Due Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($lost_books as $lost): ?>
                                        <tr>
                                            <td><strong><?= htmlspecialchars($lost['student_name']) ?></strong></td>
                                            <td><?= htmlspecialchars($lost['title']) ?></td>
                                            <td><span class="badge badge-modern badge-danger-modern"><?= (int)$lost['days_overdue'] ?> days</span></td>
                                            <td><?= date('M j, Y', strtotime($lost['due_date'])) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <p class="text-success fw-bold">No lost books detected</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card content-card">
                <div class="card-header">
                    <h5><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-3">
                            <a href="<?= BASE_PATH ?>/librarian/quick-borrow" class="quick-action-card success">
                                <div class="icon-wrapper">
                                    <i class="fas fa-bolt"></i>
                                </div>
                                <h5>Quick Borrow</h5>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="<?= BASE_PATH ?>/librarian/create-book" class="quick-action-card">
                                <div class="icon-wrapper">
                                    <i class="fas fa-plus"></i>
                                </div>
                                <h5>Add Book</h5>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="<?= BASE_PATH ?>/librarian/create-student" class="quick-action-card info">
                                <div class="icon-wrapper">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <h5>Add Student</h5>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="<?= BASE_PATH ?>/librarian/reports" class="quick-action-card warning">
                                <div class="icon-wrapper">
                                    <i class="fas fa-chart-bar"></i>
                                </div>
                                <h5>View Reports</h5>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 1 -->
    <div class="row g-4 mt-4">
        <div class="col-lg-6">
            <div class="card content-card">
                <div class="card-header">
                    <h5><i class="fas fa-fire me-2 text-danger"></i>Most Popular Books</h5>
                </div>
                <div class="card-body">
                    <canvas id="popularBooksChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card content-card">
                <div class="card-header">
                    <h5><i class="fas fa-bed me-2 text-muted"></i>Underutilized Educational Books</h5>
                </div>
                <div class="card-body">
                    <canvas id="underutilizedBooksChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 2 -->
    <div class="row g-4 mt-4">
        <div class="col-lg-6">
            <div class="card content-card">
                <div class="card-header">
                    <h5><i class="fas fa-users me-2" style="color: var(--primary-purple);"></i>Most Active Classes</h5>
                </div>
                <div class="card-body">
                    <canvas id="classActivityChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card content-card">
                <div class="card-header">
                    <h5><i class="fas fa-chart-line me-2 text-success"></i>Borrowing Trends (Last 30 Days)</h5>
                </div>
                <div class="card-body">
                    <canvas id="borrowingTrendsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- At-Risk Students Table -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card content-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5><i class="fas fa-exclamation-circle me-2" style="color: var(--warning-gradient-start);"></i>At-Risk Students</h5>
                    <span class="badge badge-modern badge-warning-modern"><?= count($at_risk_students) ?></span>
                </div>
                <div class="card-body">
                    <?php if (!empty($at_risk_students)): ?>
                        <div class="table-responsive">
                            <table class="table table-modern">
                                <thead>
                                    <tr>
                                        <th>Student ID</th>
                                        <th>Name</th>
                                        <th>Class</th>
                                        <th>Total Borrows</th>
                                        <th>Overdue Books</th>
                                        <th>Unpaid Fines</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($at_risk_students as $student): ?>
                                        <tr>
                                            <td><strong><?= htmlspecialchars($student['student_id']) ?></strong></td>
                                            <td><?= htmlspecialchars($student['full_name']) ?></td>
                                            <td>Class <?= htmlspecialchars($student['class']) ?></td>
                                            <td><?= $student['total_borrows'] ?></td>
                                            <td>
                                                <?php if ($student['overdue_count'] > 0): ?>
                                                    <span class="badge bg-danger"><?= $student['overdue_count'] ?></span>
                                                <?php else: ?>
                                                    <span class="text-muted">0</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($student['unpaid_fines'] > 0): ?>
                                                    <span class="text-danger fw-bold">MK <?= number_format($student['unpaid_fines']) ?></span>
                                                <?php else: ?>
                                                    <span class="text-muted">MK 0</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($student['overdue_count'] > 0): ?>
                                                    <span class="badge badge-modern badge-danger-modern">Overdue</span>
                                                <?php elseif ($student['total_borrows'] == 0): ?>
                                                    <span class="badge badge-modern badge-warning-modern">No Activity</span>
                                                <?php else: ?>
                                                    <span class="badge badge-modern badge-purple">Unpaid Fines</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <p class="text-success fw-bold">No at-risk students found!</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Data for Popular Books Chart
    const popularBooksData = {
        labels: <?= json_encode(array_map(function($book) {
            return $book['title'] . ' (Class ' . $book['class_level'] . ')';
        }, $popular_books)) ?>,
        datasets: [{
            label: 'Number of Borrows',
            data: <?= json_encode(array_column($popular_books, 'borrow_count')) ?>,
            backgroundColor: [
                'rgba(99, 102, 241, 0.8)',
                'rgba(139, 92, 246, 0.8)',
                'rgba(79, 70, 229, 0.8)',
                'rgba(129, 140, 248, 0.8)',
                'rgba(167, 139, 250, 0.8)'
            ],
            borderColor: [
                'rgba(99, 102, 241, 1)',
                'rgba(139, 92, 246, 1)',
                'rgba(79, 70, 229, 1)',
                'rgba(129, 140, 248, 1)',
                'rgba(167, 139, 250, 1)'
            ],
            borderWidth: 2,
            borderRadius: 8
        }]
    };

    // Popular Books Chart
    const popularBooksCtx = document.getElementById('popularBooksChart').getContext('2d');
    new Chart(popularBooksCtx, {
        type: 'bar',
        data: popularBooksData,
        options: {
            indexAxis: 'y', // Horizontal bar chart
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                },
                title: {
                    display: false
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0,
                        color: '#6b7280',
                        font: {
                            weight: '600'
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                y: {
                    ticks: {
                        color: '#374151',
                        font: {
                            weight: '600'
                        }
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Data for Underutilized Books Chart
    const underutilizedBooksData = {
        labels: <?= json_encode(array_map(function($book) {
            return $book['title'] . ' (Class ' . $book['class_level'] . ')';
        }, $underutilized_books)) ?>,
        datasets: [{
            label: 'Number of Borrows',
            data: <?= json_encode(array_column($underutilized_books, 'borrow_count')) ?>,
            backgroundColor: function(context) {
                const value = context.parsed.x;
                if (value === 0) return 'rgba(239, 68, 68, 0.8)'; // Red for 0
                if (value <= 2) return 'rgba(245, 158, 11, 0.8)'; // Orange for 1-2
                return 'rgba(156, 163, 175, 0.8)'; // Grey for 3+
            },
            borderColor: function(context) {
                const value = context.parsed.x;
                if (value === 0) return 'rgba(239, 68, 68, 1)';
                if (value <= 2) return 'rgba(245, 158, 11, 1)';
                return 'rgba(156, 163, 175, 1)';
            },
            borderWidth: 2,
            borderRadius: 8
        }]
    };

    // Underutilized Books Chart
    const underutilizedBooksCtx = document.getElementById('underutilizedBooksChart').getContext('2d');
    new Chart(underutilizedBooksCtx, {
        type: 'bar',
        data: underutilizedBooksData,
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                },
                title: {
                    display: false
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0,
                        color: '#6b7280',
                        font: {
                            weight: '600'
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                y: {
                    ticks: {
                        color: '#374151',
                        font: {
                            weight: '600'
                        }
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Class Activity Chart
    const classData = <?= json_encode($class_borrow_stats) ?>;
    
    if (classData && classData.length > 0) {
        const classLabels = classData.map(item => 'Class ' + item.class);
        const classBorrows = classData.map(item => parseInt(item.borrow_count));
        
        const classActivityData = {
            labels: classLabels,
            datasets: [{
                label: 'Total Borrows',
                data: classBorrows,
                backgroundColor: 'rgba(99, 102, 241, 0.8)',
                borderColor: 'rgba(99, 102, 241, 1)',
                borderWidth: 2,
                borderRadius: 8
            }]
        };

        const classActivityCtx = document.getElementById('classActivityChart').getContext('2d');
        new Chart(classActivityCtx, {
            type: 'bar',
            data: classActivityData,
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            color: '#6b7280',
                            font: {
                                weight: '600'
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        ticks: {
                            color: '#374151',
                            font: {
                                weight: '600'
                            }
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    // Borrowing Trends Chart
    const trendsData = <?= json_encode($borrowing_trends) ?>;
    
    if (trendsData && trendsData.length > 0) {
        const trendLabels = trendsData.map(item => {
            const date = new Date(item.borrow_date);
            return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        });
        const trendValues = trendsData.map(item => parseInt(item.borrow_count));
        
        const trendsCtx = document.getElementById('borrowingTrendsChart').getContext('2d');
        new Chart(trendsCtx, {
            type: 'line',
            data: {
                labels: trendLabels,
                datasets: [{
                    label: 'Books Borrowed',
                    data: trendValues,
                    borderColor: 'rgba(16, 185, 129, 1)',
                    backgroundColor: 'rgba(16, 185, 129, 0.2)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointBackgroundColor: 'rgba(16, 185, 129, 1)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            color: '#6b7280',
                            font: {
                                weight: '600'
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        ticks: {
                            color: '#374151',
                            font: {
                                weight: '600'
                            }
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }
});
</script>

<?php include '../app/views/shared/layout-footer.php'; ?>