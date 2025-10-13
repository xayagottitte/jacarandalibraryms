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

<?php include '../app/views/shared/layout-footer.php'; ?>