<?php 
$title = "Reports - Jacaranda Libraries";
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
    --red-gradient-start: #ef4444;
    --red-gradient-end: #dc2626;
    --warning-gradient-start: #f59e0b;
    --warning-gradient-end: #d97706;
    --info-gradient-start: #3b82f6;
    --info-gradient-end: #2563eb;
}

.reports-container {
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

.page-header h1 {
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.header-actions {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.btn-header {
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
    padding: 0.6rem 1.25rem;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    font-size: 0.875rem;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-header:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-2px);
    color: white;
    box-shadow: 0 6px 20px rgba(255, 255, 255, 0.2);
}

/* Statistics Cards */
.stat-card {
    background: white;
    border-radius: 20px;
    padding: 1.75rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s;
    height: 100%;
    border-left: 5px solid transparent;
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 100px;
    height: 100px;
    border-radius: 50%;
    opacity: 0.1;
    transition: all 0.3s;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
}

.stat-card.purple {
    border-left-color: var(--primary-purple);
}

.stat-card.purple::before {
    background: var(--primary-purple);
}

.stat-card.green {
    border-left-color: var(--success-gradient-start);
}

.stat-card.green::before {
    background: var(--success-gradient-start);
}

.stat-card.blue {
    border-left-color: var(--info-gradient-start);
}

.stat-card.blue::before {
    background: var(--info-gradient-start);
}

.stat-card.orange {
    border-left-color: var(--warning-gradient-start);
}

.stat-card.orange::before {
    background: var(--warning-gradient-start);
}

.stat-label {
    font-size: 0.75rem;
    text-transform: uppercase;
    font-weight: 700;
    letter-spacing: 0.5px;
    margin-bottom: 0.75rem;
    color: var(--grey-medium);
}

.stat-value {
    font-size: 2rem;
    font-weight: 800;
    color: var(--grey-dark);
    margin: 0;
}

.stat-icon {
    font-size: 2.5rem;
    opacity: 0.3;
}

/* Chart Cards */
.chart-card {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    margin-bottom: 2rem;
    transition: all 0.3s;
}

.chart-card:hover {
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
}

.chart-header {
    background: linear-gradient(135deg, var(--grey-lighter) 0%, white 100%);
    border-radius: 12px;
    padding: 1rem 1.5rem;
    margin-bottom: 1.5rem;
    border-left: 4px solid var(--primary-purple);
}

.chart-header h6 {
    margin: 0;
    font-weight: 700;
    font-size: 1rem;
    color: var(--grey-dark);
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.chart-header.danger {
    border-left-color: var(--red-gradient-start);
}

.chart-header.success {
    border-left-color: var(--success-gradient-start);
}

.chart-header.info {
    border-left-color: var(--info-gradient-start);
}

/* Form Card */
.form-card {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    margin-bottom: 2rem;
}

.form-card label {
    font-weight: 600;
    color: var(--grey-dark);
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
}

.form-card .form-control,
.form-card .form-select {
    border: 2px solid var(--grey-light);
    border-radius: 12px;
    padding: 0.75rem 1rem;
    font-size: 0.95rem;
    transition: all 0.3s;
}

.form-card .form-control:focus,
.form-card .form-select:focus {
    border-color: var(--primary-purple);
    box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.25);
    outline: none;
}

.btn-submit {
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--dark-purple) 100%);
    border: none;
    color: white;
    padding: 0.75rem 2rem;
    border-radius: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.875rem;
    transition: all 0.3s;
}

.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(99, 102, 241, 0.3);
    color: white;
}

.btn-reset {
    background: linear-gradient(135deg, var(--grey-medium) 0%, var(--grey-dark) 100%);
    border: none;
    color: white;
    padding: 0.75rem 2rem;
    border-radius: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.875rem;
    transition: all 0.3s;
}

.btn-reset:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(107, 114, 128, 0.3);
    color: white;
}

/* Saved Reports Card */
.reports-list-card {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    margin-bottom: 2rem;
}

.report-item {
    background: var(--grey-lighter);
    border-radius: 12px;
    padding: 1.25rem;
    margin-bottom: 1rem;
    border-left: 4px solid var(--primary-purple);
    transition: all 0.3s;
}

.report-item:hover {
    background: #e9ecef;
    transform: translateX(5px);
}

.report-item h6 {
    font-weight: 700;
    color: var(--grey-dark);
    margin-bottom: 0.5rem;
}

.report-item p {
    color: var(--grey-medium);
    font-size: 0.875rem;
    margin-bottom: 1rem;
}

.btn-view {
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--dark-purple) 100%);
    border: none;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.75rem;
    transition: all 0.3s;
    margin-right: 0.5rem;
}

.btn-view:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
    color: white;
}

.btn-download {
    background: linear-gradient(135deg, var(--grey-medium) 0%, var(--grey-dark) 100%);
    border: none;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.75rem;
    transition: all 0.3s;
}

.btn-download:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(107, 114, 128, 0.3);
    color: white;
}

.empty-state {
    text-align: center;
    padding: 3rem 1rem;
}

.empty-state i {
    color: var(--grey-medium);
    font-size: 3rem;
    margin-bottom: 1rem;
}

.empty-state p {
    color: var(--grey-medium);
    font-size: 1rem;
}

/* Quick Actions */
.btn-quick-action {
    background: white;
    border: 2px solid var(--grey-light);
    color: var(--grey-dark);
    padding: 0.75rem 1rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.875rem;
    transition: all 0.3s;
    width: 100%;
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.btn-quick-action:hover {
    border-color: var(--primary-purple);
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--dark-purple) 100%);
    color: white;
    transform: translateX(5px);
}

.btn-quick-action i {
    font-size: 1.125rem;
}
</style>

<div class="container-fluid reports-container">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <h1><i class="fas fa-chart-bar"></i>Library Reports</h1>
            <div class="header-actions">
                <button type="button" class="btn-header" onclick="exportReport('pdf')">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </button>
                <button type="button" class="btn-header" onclick="exportReport('excel')">
                    <i class="fas fa-file-excel"></i> Export Excel
                </button>
                <a href="<?= BASE_PATH ?>/librarian/dashboard" class="btn-header">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Overview -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card purple">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label">Total Books</div>
                        <div class="stat-value"><?= $book_stats['total_books'] ?? 0 ?></div>
                    </div>
                    <i class="fas fa-book stat-icon" style="color: var(--primary-purple);"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card green">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label">Available Copies</div>
                        <div class="stat-value"><?= $book_stats['available_copies'] ?? 0 ?></div>
                    </div>
                    <i class="fas fa-check-circle stat-icon" style="color: var(--success-gradient-start);"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card blue">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label">Borrowed Books</div>
                        <div class="stat-value"><?= $book_stats['borrowed_books'] ?? 0 ?></div>
                    </div>
                    <i class="fas fa-hand-holding stat-icon" style="color: var(--info-gradient-start);"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card orange">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label">Overdue Books</div>
                        <div class="stat-value"><?= $book_stats['overdue_books'] ?? 0 ?></div>
                    </div>
                    <i class="fas fa-exclamation-triangle stat-icon" style="color: var(--warning-gradient-start);"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Charts Row 1 -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="chart-card">
                <div class="chart-header">
                    <h6><i class="fas fa-chart-pie"></i>Category Popularity</h6>
                </div>
                <div class="chart-body">
                    <canvas id="categoryPopularityChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="chart-card">
                <div class="chart-header danger">
                    <h6><i class="fas fa-exclamation-triangle"></i>Overdue Hotspots</h6>
                </div>
                <div class="chart-body">
                    <canvas id="overdueHotspotsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Charts Row 2 -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="chart-card">
                <div class="chart-header success">
                    <h6><i class="fas fa-dollar-sign"></i>Financial Performance (Last 30 Days)</h6>
                </div>
                <div class="chart-body">
                    <canvas id="financialChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="chart-card">
                <div class="chart-header info">
                    <h6><i class="fas fa-percentage"></i>Book Utilization Rate</h6>
                </div>
                <div class="chart-body">
                    <canvas id="utilizationChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Generation -->
    <div class="row">
        <div class="col-lg-8">
            <div class="form-card">
                <div class="chart-header mb-4">
                    <h6><i class="fas fa-chart-line"></i>Generate Reports</h6>
                </div>
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
                                            <option value="<?= htmlspecialchars($category['name']) ?>">
                                                <?= htmlspecialchars($category['name']) ?>
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

                        <div class="d-flex gap-3 justify-content-end mt-4">
                            <button type="button" class="btn-reset" onclick="resetForm()">
                                <i class="fas fa-undo me-1"></i> Reset
                            </button>
                            <button type="submit" class="btn-submit">
                                <i class="fas fa-chart-bar me-1"></i> Generate Report
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">


            <!-- Quick Actions -->
            <div class="reports-list-card">
                <div class="chart-header mb-4">
                    <h6><i class="fas fa-bolt"></i>Quick Actions</h6>
                </div>
                <div>
                    <button type="button" class="btn-quick-action" onclick="generateQuickReport('overdue')">
                        <i class="fas fa-exclamation-triangle"></i> Overdue Books
                    </button>
                    <button type="button" class="btn-quick-action" onclick="generateQuickReport('popular')">
                        <i class="fas fa-star"></i> Popular Books
                    </button>
                    <button type="button" class="btn-quick-action" onclick="generateQuickReport('monthly')">
                        <i class="fas fa-calendar-alt"></i> Monthly Summary
                    </button>
                    <button type="button" class="btn-quick-action" onclick="generateQuickReport('inventory')">
                        <i class="fas fa-boxes"></i> Inventory Status
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

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
    const form = document.getElementById('reportForm');
    const formData = new FormData(form);
    formData.append('export_format', format);

    // AJAX check for empty report before download
    fetch('<?= BASE_PATH ?>/librarian/generate-report', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (!data || !Array.isArray(data) || data.length === 0) {
            alert('No data available for this report. Please adjust your filters or date range.');
        } else {
            // Proceed with download
            const params = new URLSearchParams(formData);
            const url = `<?= BASE_PATH ?>/librarian/export-report?${params.toString()}`;
            const link = document.createElement('a');
            link.href = url;
            link.download = `library-report-${Date.now()}.${format === 'pdf' ? 'pdf' : 'xlsx'}`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    })
    .catch(() => {
        alert('Failed to generate report. Please try again.');
    });
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
                        'rgba(99, 102, 241, 0.85)',    // Purple
                        'rgba(16, 185, 129, 0.85)',    // Green
                        'rgba(245, 158, 11, 0.85)',    // Orange
                        'rgba(239, 68, 68, 0.85)',     // Red
                        'rgba(59, 130, 246, 0.85)',    // Blue
                        'rgba(167, 139, 250, 0.85)',   // Light Purple
                        'rgba(5, 150, 105, 0.85)',     // Dark Green
                        'rgba(217, 119, 6, 0.85)',     // Dark Orange
                        'rgba(129, 140, 248, 0.85)',   // Very Light Purple
                        'rgba(16, 185, 129, 0.6)'      // Lighter Green
                    ],
                    borderColor: [
                        'rgba(99, 102, 241, 1)',
                        'rgba(16, 185, 129, 1)',
                        'rgba(245, 158, 11, 1)',
                        'rgba(239, 68, 68, 1)',
                        'rgba(59, 130, 246, 1)',
                        'rgba(167, 139, 250, 1)',
                        'rgba(5, 150, 105, 1)',
                        'rgba(217, 119, 6, 1)',
                        'rgba(129, 140, 248, 1)',
                        'rgba(16, 185, 129, 1)'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            font: {
                                size: 12,
                                weight: '600'
                            },
                            padding: 15,
                            usePointStyle: true
                        }
                    },
                    title: {
                        display: true,
                        text: 'Book Borrows by Category',
                        font: {
                            size: 16,
                            weight: '700'
                        },
                        color: '#374151'
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
                        },
                        backgroundColor: 'rgba(55, 65, 81, 0.95)',
                        padding: 12,
                        titleFont: {
                            size: 14,
                            weight: '700'
                        },
                        bodyFont: {
                            size: 13
                        },
                        borderColor: '#663399',
                        borderWidth: 2
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
                    backgroundColor: 'rgba(239, 68, 68, 0.7)',
                    borderColor: 'rgba(239, 68, 68, 1)',
                    borderWidth: 2,
                    borderRadius: 8
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
                        text: 'Top 10 Most Frequently Overdue Books',
                        font: {
                            size: 16,
                            weight: '700'
                        },
                        color: '#374151'
                    },
                    tooltip: {
                        callbacks: {
                            afterLabel: function(context) {
                                const index = context.dataIndex;
                                const avgDays = overdueData[index].avg_days_overdue;
                                return 'Avg ' + parseFloat(avgDays).toFixed(1) + ' days overdue';
                            }
                        },
                        backgroundColor: 'rgba(55, 65, 81, 0.95)',
                        padding: 12,
                        titleFont: {
                            size: 14,
                            weight: '700'
                        },
                        bodyFont: {
                            size: 13
                        },
                        borderColor: '#ef4444',
                        borderWidth: 2
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            font: {
                                weight: '600'
                            }
                        },
                        grid: {
                            color: 'rgba(229, 231, 235, 0.5)'
                        }
                    },
                    y: {
                        ticks: {
                            font: {
                                weight: '600',
                                size: 11
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
                    borderColor: 'rgba(16, 185, 129, 1)',
                    backgroundColor: 'rgba(16, 185, 129, 0.2)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: 'rgba(16, 185, 129, 1)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            font: {
                                size: 13,
                                weight: '600'
                            },
                            padding: 15,
                            usePointStyle: true
                        }
                    },
                    title: {
                        display: true,
                        text: 'Daily Fine Collections',
                        font: {
                            size: 16,
                            weight: '700'
                        },
                        color: '#374151'
                    },
                    tooltip: {
                        backgroundColor: 'rgba(55, 65, 81, 0.95)',
                        padding: 12,
                        titleFont: {
                            size: 14,
                            weight: '700'
                        },
                        bodyFont: {
                            size: 13
                        },
                        borderColor: '#10b981',
                        borderWidth: 2
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'MK ' + value.toLocaleString();
                            },
                            font: {
                                weight: '600'
                            }
                        },
                        grid: {
                            color: 'rgba(229, 231, 235, 0.5)'
                        }
                    },
                    x: {
                        ticks: {
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
                        if (value >= 80) return 'rgba(239, 68, 68, 0.7)'; // Red - need more copies
                        if (value >= 50) return 'rgba(245, 158, 11, 0.7)'; // Orange - moderate
                        return 'rgba(16, 185, 129, 0.7)'; // Green - adequate
                    }),
                    borderColor: utilizationValues.map(value => {
                        if (value >= 80) return 'rgba(239, 68, 68, 1)';
                        if (value >= 50) return 'rgba(245, 158, 11, 1)';
                        return 'rgba(16, 185, 129, 1)';
                    }),
                    borderWidth: 2,
                    borderRadius: 8
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
                        text: 'Top 10 Books by Utilization Rate',
                        font: {
                            size: 16,
                            weight: '700'
                        },
                        color: '#374151'
                    },
                    tooltip: {
                        callbacks: {
                            afterLabel: function(context) {
                                const index = context.dataIndex;
                                const copies = utilizationData[index].total_copies;
                                const borrowed = utilizationData[index].borrowed_copies;
                                return borrowed + ' of ' + copies + ' copies in use';
                            }
                        },
                        backgroundColor: 'rgba(55, 65, 81, 0.95)',
                        padding: 12,
                        titleFont: {
                            size: 14,
                            weight: '700'
                        },
                        bodyFont: {
                            size: 13
                        },
                        borderColor: '#663399',
                        borderWidth: 2
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            },
                            font: {
                                weight: '600'
                            }
                        },
                        grid: {
                            color: 'rgba(229, 231, 235, 0.5)'
                        }
                    },
                    y: {
                        ticks: {
                            font: {
                                weight: '600',
                                size: 11
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