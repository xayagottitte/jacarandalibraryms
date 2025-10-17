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

    <!-- Charts Row 1 -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-fire text-danger"></i> Most Popular Books</h5>
                </div>
                <div class="card-body">
                    <canvas id="popularBooksChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-bed text-muted"></i> Underutilized Educational Books</h5>
                </div>
                <div class="card-body">
                    <canvas id="underutilizedBooksChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 2 -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-users text-primary"></i> Most Active Classes</h5>
                </div>
                <div class="card-body">
                    <canvas id="classActivityChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-line text-success"></i> Borrowing Trends (Last 30 Days)</h5>
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
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-exclamation-circle text-warning"></i> At-Risk Students</h5>
                    <span class="badge bg-warning text-dark"><?= count($at_risk_students) ?></span>
                </div>
                <div class="card-body">
                    <?php if (!empty($at_risk_students)): ?>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
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
                                            <td><?= htmlspecialchars($student['student_id']) ?></td>
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
                                                    <span class="text-danger">MK <?= number_format($student['unpaid_fines']) ?></span>
                                                <?php else: ?>
                                                    <span class="text-muted">MK 0</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($student['overdue_count'] > 0): ?>
                                                    <span class="badge bg-danger">Overdue</span>
                                                <?php elseif ($student['total_borrows'] == 0): ?>
                                                    <span class="badge bg-warning text-dark">No Activity</span>
                                                <?php else: ?>
                                                    <span class="badge bg-info">Unpaid Fines</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-success text-center"><i class="fas fa-check-circle"></i> No at-risk students found!</p>
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
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)'
            ],
            borderWidth: 1
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
            plugins: {
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: 'Top 5 Most Borrowed Books'
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
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
            backgroundColor: [
                'rgba(255, 159, 64, 0.2)',
                'rgba(255, 205, 86, 0.2)',
                'rgba(201, 203, 207, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(153, 102, 255, 0.2)'
            ],
            borderColor: [
                'rgba(255, 159, 64, 1)',
                'rgba(255, 205, 86, 1)',
                'rgba(201, 203, 207, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(153, 102, 255, 1)'
            ],
            borderWidth: 1
        }]
    };

    // Underutilized Books Chart
    const underutilizedBooksCtx = document.getElementById('underutilizedBooksChart').getContext('2d');
    new Chart(underutilizedBooksCtx, {
        type: 'bar',
        data: underutilizedBooksData,
        options: {
            indexAxis: 'y', // Horizontal bar chart
            responsive: true,
            plugins: {
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: '5 Least Borrowed Books'
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
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
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2
            }]
        };

        const classActivityCtx = document.getElementById('classActivityChart').getContext('2d');
        new Chart(classActivityCtx, {
            type: 'bar',
            data: classActivityData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Book Borrows by Class Level'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
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
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Daily Borrowing Activity'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    }
});
</script>

<?php include '../app/views/shared/layout-footer.php'; ?>