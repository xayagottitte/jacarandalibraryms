<?php 
$title = "Reports - Multi-Library System";
include '../app/views/shared/header.php'; 
include '../app/views/shared/layout-header.php'; 
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">
            <i class="fas fa-chart-bar me-2 text-primary"></i>Library Reports
        </h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="exportReport('pdf')">
                    <i class="fas fa-file-pdf me-1"></i> Export PDF
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="exportReport('excel')">
                    <i class="fas fa-file-excel me-1"></i> Export Excel
                </button>
            </div>
            <a href="<?= BASE_PATH ?>/librarian/dashboard" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Statistics Overview -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Books</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $book_stats['total_books'] ?? 0 ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Available Copies</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $book_stats['available_copies'] ?? 0 ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Borrowed Books</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $book_stats['borrowed_books'] ?? 0 ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hand-holding fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Overdue Books</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $book_stats['overdue_books'] ?? 0 ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Charts Row 1 -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-pie me-2"></i>Category Popularity
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="categoryPopularityChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>Overdue Hotspots
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="overdueHotspotsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Charts Row 2 -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-dollar-sign me-2"></i>Financial Performance (Last 30 Days)
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="financialChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-percentage me-2"></i>Book Utilization Rate
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="utilizationChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Generation -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-line me-2"></i>Generate Reports
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= BASE_PATH ?>/librarian/generate-report" id="reportForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="reportType" class="form-label">Report Type</label>
                                <select class="form-select" name="report_type" id="reportType" required>
                                    <option value="">Select Report Type</option>
                                    <option value="books">Books Report</option>
                                    <option value="students">Students Report</option>
                                    <option value="borrowing">Borrowing Report</option>
                                    <option value="overdue">Overdue Books Report</option>
                                    <option value="financial">Financial Report</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="dateRange" class="form-label">Date Range</label>
                                <select class="form-select" name="date_range" id="dateRange">
                                    <option value="7">Last 7 Days</option>
                                    <option value="30" selected>Last 30 Days</option>
                                    <option value="90">Last 90 Days</option>
                                    <option value="365">Last Year</option>
                                    <option value="custom">Custom Range</option>
                                </select>
                            </div>
                        </div>

                        <div class="row" id="customDateRange" style="display: none;">
                            <div class="col-md-6 mb-3">
                                <label for="startDate" class="form-label">Start Date</label>
                                <input type="date" class="form-control" name="start_date" id="startDate">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="endDate" class="form-label">End Date</label>
                                <input type="date" class="form-control" name="end_date" id="endDate">
                            </div>
                        </div>

                        <!-- Conditional Filters -->
                        <div class="row" id="bookFilters" style="display: none;">
                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label">Category</label>
                                <select class="form-select" name="category">
                                    <option value="">All Categories</option>
                                    <?php if (!empty($categories)): ?>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?= htmlspecialchars($category['category']) ?>">
                                                <?= htmlspecialchars($category['category']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="bookStatus" class="form-label">Status</label>
                                <select class="form-select" name="status">
                                    <option value="">All Status</option>
                                    <option value="available">Available</option>
                                    <option value="unavailable">Unavailable</option>
                                </select>
                            </div>
                        </div>

                        <div class="row" id="studentFilters" style="display: none;">
                            <div class="col-md-6 mb-3">
                                <label for="class" class="form-label">Class</label>
                                <select class="form-select" name="class">
                                    <option value="">All Classes</option>
                                    <?php if (!empty($classes)): ?>
                                        <?php foreach ($classes as $class): ?>
                                            <option value="<?= htmlspecialchars($class['class']) ?>">
                                                <?= htmlspecialchars($class['class']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="studentStatus" class="form-label">Student Status</label>
                                <select class="form-select" name="student_status">
                                    <option value="">All Status</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="button" class="btn btn-secondary me-md-2" onclick="resetForm()">
                                <i class="fas fa-undo me-1"></i> Reset
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-chart-bar me-1"></i> Generate Report
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-save me-2"></i>Saved Reports
                    </h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($saved_reports)): ?>
                        <div class="list-group">
                            <?php foreach ($saved_reports as $report): ?>
                                <div class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1"><?= htmlspecialchars($report['title']) ?></h6>
                                        <small><?= date('M j, Y', strtotime($report['created_at'])) ?></small>
                                    </div>
                                    <p class="mb-1"><?= ucfirst($report['type']) ?> Report</p>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-outline-primary btn-sm" 
                                                onclick="viewReport(<?= $report['id'] ?>)">
                                            <i class="fas fa-eye"></i> View
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm"
                                                onclick="downloadReport(<?= $report['id'] ?>)">
                                            <i class="fas fa-download"></i> Download
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No saved reports yet. Generate your first report above!</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-primary btn-sm" 
                                onclick="generateQuickReport('overdue')">
                            <i class="fas fa-exclamation-triangle me-1"></i> Overdue Books
                        </button>
                        <button type="button" class="btn btn-outline-success btn-sm"
                                onclick="generateQuickReport('popular')">
                            <i class="fas fa-star me-1"></i> Popular Books
                        </button>
                        <button type="button" class="btn btn-outline-info btn-sm"
                                onclick="generateQuickReport('monthly')">
                            <i class="fas fa-calendar-alt me-1"></i> Monthly Summary
                        </button>
                        <button type="button" class="btn btn-outline-warning btn-sm"
                                onclick="generateQuickReport('inventory')">
                            <i class="fas fa-boxes me-1"></i> Inventory Status
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.text-gray-800 {
    color: #5a5c69 !important;
}

.text-gray-300 {
    color: #dddfeb !important;
}

.shadow {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
}

.card {
    border: 1px solid #e3e6f0;
}

.card-header {
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
}

.list-group-item {
    border: 1px solid #e3e6f0;
}

.btn-outline-primary:hover {
    background-color: #663399;
    border-color: #663399;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const reportType = document.getElementById('reportType');
    const dateRange = document.getElementById('dateRange');
    const customDateRange = document.getElementById('customDateRange');
    const bookFilters = document.getElementById('bookFilters');
    const studentFilters = document.getElementById('studentFilters');

    // Show/hide custom date range
    dateRange.addEventListener('change', function() {
        if (this.value === 'custom') {
            customDateRange.style.display = 'block';
        } else {
            customDateRange.style.display = 'none';
        }
    });

    // Show/hide filters based on report type
    reportType.addEventListener('change', function() {
        // Hide all filters first
        bookFilters.style.display = 'none';
        studentFilters.style.display = 'none';

        // Show relevant filters
        if (this.value === 'books' || this.value === 'borrowing') {
            bookFilters.style.display = 'block';
        } else if (this.value === 'students') {
            studentFilters.style.display = 'block';
        }
    });
});

function exportReport(format) {
    // Get current form data
    const form = document.getElementById('reportForm');
    const formData = new FormData(form);
    formData.append('export_format', format);
    
    // Create download link
    const params = new URLSearchParams(formData);
    const url = `<?= BASE_PATH ?>/librarian/export-report?${params.toString()}`;
    
    // Trigger download
    const link = document.createElement('a');
    link.href = url;
    link.download = `library-report-${Date.now()}.${format === 'pdf' ? 'pdf' : 'xlsx'}`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function resetForm() {
    document.getElementById('reportForm').reset();
    document.getElementById('customDateRange').style.display = 'none';
    document.getElementById('bookFilters').style.display = 'none';
    document.getElementById('studentFilters').style.display = 'none';
}

function generateQuickReport(type) {
    const form = document.getElementById('reportForm');
    const reportType = document.getElementById('reportType');
    
    // Set report type based on quick action
    switch(type) {
        case 'overdue':
            reportType.value = 'borrowing';
            // Add overdue filter logic
            break;
        case 'popular':
            reportType.value = 'books';
            break;
        case 'monthly':
            reportType.value = 'borrowing';
            document.getElementById('dateRange').value = '30';
            break;
        case 'inventory':
            reportType.value = 'books';
            break;
    }
    
    // Trigger change event to show relevant filters
    reportType.dispatchEvent(new Event('change'));
    
    // Submit form
    form.submit();
}

function viewReport(reportId) {
    window.open(`<?= BASE_PATH ?>/librarian/view-report/${reportId}`, '_blank');
}

function downloadReport(reportId) {
    window.location.href = `<?= BASE_PATH ?>/librarian/download-report/${reportId}`;
}
</script>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Category Popularity Pie Chart
    const categoryData = <?= json_encode($category_stats) ?>;
    
    if (categoryData && categoryData.length > 0) {
        const categoryLabels = categoryData.map(item => item.category);
        const categoryValues = categoryData.map(item => parseInt(item.borrow_count));
        
        const categoryCtx = document.getElementById('categoryPopularityChart').getContext('2d');
        new Chart(categoryCtx, {
            type: 'pie',
            data: {
                labels: categoryLabels,
                datasets: [{
                    label: 'Borrows by Category',
                    data: categoryValues,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.8)',
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(255, 206, 86, 0.8)',
                        'rgba(75, 192, 192, 0.8)',
                        'rgba(153, 102, 255, 0.8)',
                        'rgba(255, 159, 64, 0.8)',
                        'rgba(201, 203, 207, 0.8)',
                        'rgba(255, 99, 71, 0.8)',
                        'rgba(144, 238, 144, 0.8)',
                        'rgba(173, 216, 230, 0.8)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(201, 203, 207, 1)',
                        'rgba(255, 99, 71, 1)',
                        'rgba(144, 238, 144, 1)',
                        'rgba(173, 216, 230, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    title: {
                        display: true,
                        text: 'Book Borrows by Category'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                return `${label}: ${value} borrows (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    // Overdue Hotspots Chart
    const overdueData = <?= json_encode($overdue_stats) ?>;
    
    if (overdueData && overdueData.length > 0) {
        const overdueLabels = overdueData.map(item => item.title.substring(0, 30) + (item.title.length > 30 ? '...' : ''));
        const overdueValues = overdueData.map(item => parseInt(item.overdue_count));
        
        const overdueCtx = document.getElementById('overdueHotspotsChart').getContext('2d');
        new Chart(overdueCtx, {
            type: 'bar',
            data: {
                labels: overdueLabels,
                datasets: [{
                    label: 'Times Overdue',
                    data: overdueValues,
                    backgroundColor: 'rgba(220, 53, 69, 0.6)',
                    borderColor: 'rgba(220, 53, 69, 1)',
                    borderWidth: 2
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Top 10 Most Frequently Overdue Books'
                    },
                    tooltip: {
                        callbacks: {
                            afterLabel: function(context) {
                                const index = context.dataIndex;
                                const avgDays = overdueData[index].avg_days_overdue;
                                return 'Avg ' + parseFloat(avgDays).toFixed(1) + ' days overdue';
                            }
                        }
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
    }

    // Financial Performance Chart
    const financialData = <?= json_encode($financial_stats) ?>;
    
    if (financialData && financialData.length > 0) {
        const financialLabels = financialData.map(item => {
            const date = new Date(item.return_date);
            return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        });
        const fineValues = financialData.map(item => parseFloat(item.total_fines || 0));
        
        const financialCtx = document.getElementById('financialChart').getContext('2d');
        new Chart(financialCtx, {
            type: 'line',
            data: {
                labels: financialLabels,
                datasets: [{
                    label: 'Fines Collected (MK)',
                    data: fineValues,
                    borderColor: 'rgba(40, 167, 69, 1)',
                    backgroundColor: 'rgba(40, 167, 69, 0.2)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true
                    },
                    title: {
                        display: true,
                        text: 'Daily Fine Collections'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'MK ' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    }

    // Book Utilization Chart
    const utilizationData = <?= json_encode($utilization_stats) ?>;
    
    if (utilizationData && utilizationData.length > 0) {
        const utilizationLabels = utilizationData.map(item => 
            item.title.substring(0, 25) + (item.title.length > 25 ? '...' : '') + ' (C' + item.class_level + ')'
        );
        const utilizationValues = utilizationData.map(item => parseFloat(item.utilization_rate));
        
        const utilizationCtx = document.getElementById('utilizationChart').getContext('2d');
        new Chart(utilizationCtx, {
            type: 'bar',
            data: {
                labels: utilizationLabels,
                datasets: [{
                    label: 'Utilization Rate (%)',
                    data: utilizationValues,
                    backgroundColor: utilizationValues.map(value => {
                        if (value >= 80) return 'rgba(220, 53, 69, 0.6)'; // Red - need more copies
                        if (value >= 50) return 'rgba(255, 193, 7, 0.6)'; // Yellow - moderate
                        return 'rgba(40, 167, 69, 0.6)'; // Green - adequate
                    }),
                    borderColor: utilizationValues.map(value => {
                        if (value >= 80) return 'rgba(220, 53, 69, 1)';
                        if (value >= 50) return 'rgba(255, 193, 7, 1)';
                        return 'rgba(40, 167, 69, 1)';
                    }),
                    borderWidth: 2
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Top 10 Books by Utilization Rate'
                    },
                    tooltip: {
                        callbacks: {
                            afterLabel: function(context) {
                                const index = context.dataIndex;
                                const copies = utilizationData[index].total_copies;
                                const borrowed = utilizationData[index].borrowed_copies;
                                return borrowed + ' of ' + copies + ' copies in use';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>

<?php include '../app/views/shared/layout-footer.php'; ?>