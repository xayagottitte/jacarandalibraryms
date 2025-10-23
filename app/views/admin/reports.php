<?php 
$title = "Advanced Reports - Multi-Library System";
include '../app/views/shared/header.php'; 
include '../app/views/shared/navbar.php';
include '../app/views/shared/layout-header.php'; 
?>

<div class="main-content">
    <div class="container-fluid px-4" style="max-width: 95%;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">Advanced Reports & Analytics</h1>
                <p class="mb-0 text-muted">Generate comprehensive reports and view library insights</p>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="row mb-4 g-3">
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Reports</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= count($saved_reports) ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-chart-bar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">This Month</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= count(array_filter($saved_reports, function($r) {
                                    return date('Y-m') === date('Y-m', strtotime($r['created_at']));
                                })) ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Most Active Type</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?php
                                    $types = array_count_values(array_column($saved_reports, 'type'));
                                    arsort($types);
                                    echo ucfirst(key($types) ?: 'N/A');
                                    ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-star fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Your Reports</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= count(array_filter($saved_reports, function($r) {
                                    return $r['generated_by'] == $_SESSION['user_id'];
                                })) ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Visual Insights -->
        <div class="row mb-4 g-3">
            <div class="col-lg-8">
                <div class="card shadow h-100">
                    <div class="card-header bg-gradient-primary text-white">
                        <h6 class="mb-0"><i class="fas fa-chart-line me-2"></i>Reports Generated Over Time</h6>
                    </div>
                    <div class="card-body" id="timelineChartContainer">
                        <canvas id="reportsTimelineChart" height="100"></canvas>
                        <div id="timelineNoData" class="text-center py-5" style="display: none;">
                            <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No report data available yet</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card shadow h-100">
                    <div class="card-header bg-gradient-primary text-white">
                        <h6 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Reports by Type</h6>
                    </div>
                    <div class="card-body" id="typeChartContainer">
                        <canvas id="reportTypeChart" height="200"></canvas>
                        <div id="typeNoData" class="text-center py-5" style="display: none;">
                            <i class="fas fa-chart-pie fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No report data available yet</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4 g-3">
            <div class="col-lg-6">
                <div class="card shadow h-100">
                    <div class="card-header bg-gradient-primary text-white">
                        <h6 class="mb-0"><i class="fas fa-building me-2"></i>Reports by Library</h6>
                    </div>
                    <div class="card-body" id="libraryChartContainer">
                        <canvas id="libraryReportsChart" height="150"></canvas>
                        <div id="libraryNoData" class="text-center py-5" style="display: none;">
                            <i class="fas fa-building fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No report data available yet</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card shadow h-100">
                    <div class="card-header bg-gradient-primary text-white">
                        <h6 class="mb-0"><i class="fas fa-users me-2"></i>Top Report Generators</h6>
                    </div>
                    <div class="card-body" id="generatorsChartContainer">
                        <canvas id="topGeneratorsChart" height="150"></canvas>
                        <div id="generatorsNoData" class="text-center py-5" style="display: none;">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No report data available yet</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Report Generator -->
        <div class="card mb-4 shadow">
            <div class="card-header bg-gradient-primary text-white">
                <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Generate New Report</h5>
            </div>
            <div class="card-body">
                <form id="advancedReportForm">
                    <div class="row g-3">
                        <div class="col-lg-3 col-md-6">
                            <label for="report_type" class="form-label">Report Type</label>
                            <select class="form-select" id="report_type" name="report_type" required>
                                <option value="">Select Type</option>
                                <option value="comprehensive">Comprehensive Library Report</option>
                                <option value="analytics">Library Analytics</option>
                                <option value="performance">Performance Trends</option>
                                <option value="borrowing">Borrowing Statistics</option>
                                <option value="inventory">Inventory Status</option>
                                <option value="student">Student Activity</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label for="library_id" class="form-label">Library</label>
                            <select class="form-select" id="library_id" name="library_id">
                                <option value="">All Libraries</option>
                                <?php foreach ($libraries as $library): ?>
                                    <option value="<?= $library['id'] ?>"><?= htmlspecialchars($library['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-8">
                            <label for="report_title" class="form-label">Report Title</label>
                            <input type="text" class="form-control" id="report_title" name="report_title" 
                                   placeholder="Enter custom report title" required>
                        </div>
                        <div class="col-lg-2 col-md-4">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-chart-bar me-1"></i> Generate
                            </button>
                        </div>
                    </div>

                    <!-- Dynamic Filters -->
                    <div id="advancedFilters" class="row g-3 mt-2" style="display: none;">
                        <!-- Filters will be loaded here based on report type -->
                    </div>
                </form>
            </div>
        </div>

        <!-- Advanced Report Results -->
        <div class="card mb-4 shadow" id="advancedReportResults" style="display: none;">
            <div class="card-header bg-gradient-success text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0" id="resultsTitle">Report Results</h5>
                <div>
                    <button class="btn btn-sm btn-light" id="exportCSVAdvanced">
                        <i class="fas fa-file-csv"></i> CSV
                    </button>
                    <button class="btn btn-sm btn-light" id="exportPDFAdvanced">
                        <i class="fas fa-file-pdf"></i> PDF
                    </button>
                    <button class="btn btn-sm btn-light" id="exportExcelAdvanced">
                        <i class="fas fa-file-excel"></i> Excel
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Summary Section -->
                <div id="resultsSummary" class="mb-4"></div>
                
                <!-- Charts Section -->
                <div id="resultsCharts" class="row mb-4"></div>
                
                <!-- Data Tables -->
                <div id="resultsData"></div>
            </div>
        </div>

    <!-- Saved Reports -->
    <div class="card shadow">
        <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0"><i class="fas fa-folder-open me-2"></i>Saved Reports</h5>
                <small class="text-white-50">Showing <span id="reportCount"><?= count($saved_reports) ?></span> of <?= count($saved_reports) ?> reports</small>
            </div>
            <div>
                <input type="text" class="form-control form-control-sm" id="searchReports" 
                       placeholder="Search reports..." style="width: 250px;">
            </div>
        </div>
        <div class="card-body">
            <?php if (empty($saved_reports)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No saved reports yet. Generate your first report above!</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Library</th>
                                <th>Generated By</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="reportsTableBody">
                            <?php foreach ($saved_reports as $report): ?>
                                <tr data-title="<?= strtolower(htmlspecialchars($report['title'])) ?>"
                                    data-type="<?= strtolower($report['type']) ?>"
                                    data-library="<?= strtolower(htmlspecialchars($report['library_name'] ?? 'all libraries')) ?>">
                                    <td>
                                        <strong><?= htmlspecialchars($report['title']) ?></strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= 
                                            $report['type'] === 'comprehensive' ? 'primary' : 
                                            ($report['type'] === 'analytics' ? 'info' : 
                                            ($report['type'] === 'performance' ? 'success' : 
                                            ($report['type'] === 'borrowing' ? 'warning' : 
                                            ($report['type'] === 'inventory' ? 'secondary' : 'dark'))))
                                        ?>">
                                            <?= ucfirst($report['type']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <i class="fas fa-building text-muted me-1"></i>
                                        <?= htmlspecialchars($report['library_name'] ?? 'All Libraries') ?>
                                    </td>
                                    <td>
                                        <i class="fas fa-user text-muted me-1"></i>
                                        <?= htmlspecialchars($report['username'] ?? 'System') ?>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar text-muted me-1"></i>
                                            <?= date('M j, Y', strtotime($report['created_at'])) ?>
                                        </small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="/report/view/<?= $report['id'] ?>" 
                                               class="btn btn-outline-primary btn-sm"
                                               title="View Report"
                                               target="_blank">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-outline-danger btn-sm btn-delete-report"
                                                    data-report-id="<?= $report['id'] ?>"
                                                    data-report-title="<?= htmlspecialchars($report['title']) ?>"
                                                    title="Delete Report">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
        <div class="card-footer bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    Reports are automatically cleaned up after 90 days
                </small>
                <button class="btn btn-sm btn-outline-danger" id="cleanupReports">
                    <i class="fas fa-broom me-1"></i> Cleanup Old Reports
                </button>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Delete Report Modal -->
<div class="modal fade" id="deleteReportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Confirm Delete</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this report?</p>
                <p class="mb-0"><strong id="deleteReportTitle"></strong></p>
                <small class="text-muted">This action cannot be undone.</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="/report/delete" id="deleteReportForm">
                    <input type="hidden" name="report_id" id="deleteReportId">
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i> Delete Report
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Prepare reports data for charts
    const reportsData = <?= json_encode($saved_reports) ?>;
    
    // Show/hide no data messages
    if (!reportsData || reportsData.length === 0) {
        document.getElementById('reportsTimelineChart').style.display = 'none';
        document.getElementById('timelineNoData').style.display = 'block';
        
        document.getElementById('reportTypeChart').style.display = 'none';
        document.getElementById('typeNoData').style.display = 'block';
        
        document.getElementById('libraryReportsChart').style.display = 'none';
        document.getElementById('libraryNoData').style.display = 'block';
        
        document.getElementById('topGeneratorsChart').style.display = 'none';
        document.getElementById('generatorsNoData').style.display = 'block';
    }
    
    // ========== CHART 1: Reports Timeline ==========
    const timelineCtx = document.getElementById('reportsTimelineChart');
    if (timelineCtx && reportsData.length > 0) {
        // Group reports by month
        const monthlyData = {};
        reportsData.forEach(report => {
            const month = new Date(report.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short' });
            monthlyData[month] = (monthlyData[month] || 0) + 1;
        });
        
        new Chart(timelineCtx, {
            type: 'line',
            data: {
                labels: Object.keys(monthlyData),
                datasets: [{
                    label: 'Reports Generated',
                    data: Object.values(monthlyData),
                    borderColor: '#7c3aed',
                    backgroundColor: 'rgba(124, 58, 237, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Reports: ' + context.parsed.y;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    }
    
    // ========== CHART 2: Reports by Type ==========
    const typeCtx = document.getElementById('reportTypeChart');
    if (typeCtx && reportsData.length > 0) {
        const typeCounts = {};
        reportsData.forEach(report => {
            const type = report.type.charAt(0).toUpperCase() + report.type.slice(1);
            typeCounts[type] = (typeCounts[type] || 0) + 1;
        });
        
        new Chart(typeCtx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(typeCounts),
                datasets: [{
                    data: Object.values(typeCounts),
                    backgroundColor: [
                        '#7c3aed',
                        '#6366f1',
                        '#10b981',
                        '#f59e0b',
                        '#64748b',
                        '#1e293b'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
    
    // ========== CHART 3: Reports by Library ==========
    const libraryCtx = document.getElementById('libraryReportsChart');
    if (libraryCtx && reportsData.length > 0) {
        const libraryCounts = {};
        reportsData.forEach(report => {
            const lib = report.library_name || 'All Libraries';
            libraryCounts[lib] = (libraryCounts[lib] || 0) + 1;
        });
        
        new Chart(libraryCtx, {
            type: 'bar',
            data: {
                labels: Object.keys(libraryCounts),
                datasets: [{
                    label: 'Reports',
                    data: Object.values(libraryCounts),
                    backgroundColor: '#7c3aed'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    }
    
    // ========== CHART 4: Top Report Generators ==========
    const generatorsCtx = document.getElementById('topGeneratorsChart');
    if (generatorsCtx && reportsData.length > 0) {
        const userCounts = {};
        reportsData.forEach(report => {
            const user = report.username || 'System';
            userCounts[user] = (userCounts[user] || 0) + 1;
        });
        
        // Sort and get top 5
        const sortedUsers = Object.entries(userCounts)
            .sort((a, b) => b[1] - a[1])
            .slice(0, 5);
        
        new Chart(generatorsCtx, {
            type: 'bar',
            data: {
                labels: sortedUsers.map(u => u[0]),
                datasets: [{
                    label: 'Reports Generated',
                    data: sortedUsers.map(u => u[1]),
                    backgroundColor: '#6366f1'
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    }
    
    // ========== LIVE SEARCH FOR REPORTS ==========
    const searchInput = document.getElementById('searchReports');
    const reportRows = document.querySelectorAll('#reportsTableBody tr');
    const reportCount = document.getElementById('reportCount');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            let visibleCount = 0;
            
            reportRows.forEach(row => {
                const title = row.dataset.title || '';
                const type = row.dataset.type || '';
                const library = row.dataset.library || '';
                
                if (title.includes(searchTerm) || type.includes(searchTerm) || library.includes(searchTerm)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });
            
            if (reportCount) {
                reportCount.textContent = visibleCount;
            }
        });
    }
    
    // ========== DELETE REPORT ==========
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteReportModal'));
    const deleteButtons = document.querySelectorAll('.btn-delete-report');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const reportId = this.dataset.reportId;
            const reportTitle = this.dataset.reportTitle;
            
            document.getElementById('deleteReportId').value = reportId;
            document.getElementById('deleteReportTitle').textContent = reportTitle;
            
            deleteModal.show();
        });
    });
    
    // ========== CLEANUP OLD REPORTS ==========
    const cleanupBtn = document.getElementById('cleanupReports');
    if (cleanupBtn) {
        cleanupBtn.addEventListener('click', function() {
            if (confirm('This will delete all reports older than 90 days. Continue?')) {
                window.location.href = '/report/cleanup';
            }
        });
    }
    
    // ========== ADVANCED REPORT FORM ==========
    const reportType = document.getElementById('report_type');
    const filtersDiv = document.getElementById('advancedFilters');
    const reportForm = document.getElementById('advancedReportForm');
    const reportResults = document.getElementById('advancedReportResults');
    
    // Load filters based on report type
    if (reportType) {
        reportType.addEventListener('change', function() {
            const type = this.value;
            if (!type) {
                filtersDiv.style.display = 'none';
                return;
            }

            let filtersHTML = '';
            switch(type) {
                case 'comprehensive':
                    filtersHTML = `
                        <div class="col-md-6">
                            <label class="form-label">Date Range</label>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <input type="date" class="form-control" name="filters[start_date]" placeholder="Start Date">
                                </div>
                                <div class="col-md-6">
                                    <input type="date" class="form-control" name="filters[end_date]" placeholder="End Date">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Book Category</label>
                            <input type="text" class="form-control" name="filters[category]" placeholder="Filter by category">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Student Class</label>
                            <input type="text" class="form-control" name="filters[class]" placeholder="Filter by class">
                        </div>
                    `;
                    break;
                case 'analytics':
                    filtersHTML = `
                        <div class="col-md-6">
                            <label class="form-label">Analysis Period</label>
                            <select class="form-select" name="filters[period]">
                                <option value="7">Last 7 Days</option>
                                <option value="30" selected>Last 30 Days</option>
                                <option value="90">Last 90 Days</option>
                                <option value="365">Last Year</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Top Items Limit</label>
                            <input type="number" class="form-control" name="filters[limit]" value="10" min="5" max="50">
                        </div>
                    `;
                    break;
                case 'performance':
                    filtersHTML = `
                        <div class="col-md-6">
                            <label class="form-label">Trend Period (Days)</label>
                            <input type="number" class="form-control" name="filters[trend_days]" value="90" min="30" max="365">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Year</label>
                            <select class="form-select" name="filters[year]">
                                <option value="2024">2024</option>
                                <option value="2023">2023</option>
                            </select>
                        </div>
                    `;
                    break;
                case 'borrowing':
                    filtersHTML = `
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="filters[status]">
                                <option value="">All Statuses</option>
                                <option value="active">Active</option>
                                <option value="overdue">Overdue</option>
                                <option value="returned">Returned</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date Range</label>
                            <input type="date" class="form-control" name="filters[date_from]">
                        </div>
                    `;
                    break;
                case 'inventory':
                    filtersHTML = `
                        <div class="col-md-6">
                            <label class="form-label">Availability</label>
                            <select class="form-select" name="filters[availability]">
                                <option value="">All Books</option>
                                <option value="available">Available Only</option>
                                <option value="borrowed">Borrowed Only</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Minimum Copies</label>
                            <input type="number" class="form-control" name="filters[min_copies]" value="1" min="0">
                        </div>
                    `;
                    break;
                case 'student':
                    filtersHTML = `
                        <div class="col-md-6">
                            <label class="form-label">Activity Type</label>
                            <select class="form-select" name="filters[activity]">
                                <option value="all">All Activity</option>
                                <option value="borrowing">Borrowing History</option>
                                <option value="returns">Returns Only</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Class Filter</label>
                            <input type="text" class="form-control" name="filters[class]" placeholder="e.g., Grade 10">
                        </div>
                    `;
                    break;
            }

            filtersDiv.innerHTML = filtersHTML;
            filtersDiv.style.display = 'block';
        });
    }
    
    // Generate advanced report
    if (reportForm) {
        reportForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Generating...';
            
            fetch('/admin/generateReport', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show results
                    document.getElementById('resultsTitle').textContent = data.title;
                    document.getElementById('resultsSummary').innerHTML = data.summary;
                    document.getElementById('resultsCharts').innerHTML = data.charts || '';
                    document.getElementById('resultsData').innerHTML = data.data;
                    reportResults.style.display = 'block';
                    
                    // Scroll to results
                    reportResults.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'start' 
                    });
                    
                    // Reload page to show new report in saved reports
                    setTimeout(() => location.reload(), 2000);
                } else {
                    alert('Error generating report: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to generate report. Please try again.');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });
    }
    
    // ========== EXPORT FUNCTIONS ==========
    function exportReport(format) {
        const reportData = document.getElementById('resultsData').innerHTML;
        const reportTitle = document.getElementById('resultsTitle').textContent;
        
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/report/export';
        
        const formatInput = document.createElement('input');
        formatInput.type = 'hidden';
        formatInput.name = 'format';
        formatInput.value = format;
        
        const dataInput = document.createElement('input');
        dataInput.type = 'hidden';
        dataInput.name = 'data';
        dataInput.value = reportData;
        
        const titleInput = document.createElement('input');
        titleInput.type = 'hidden';
        titleInput.name = 'title';
        titleInput.value = reportTitle;
        
        form.appendChild(formatInput);
        form.appendChild(dataInput);
        form.appendChild(titleInput);
        
        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
    }
    
    document.getElementById('exportCSVAdvanced')?.addEventListener('click', () => exportReport('csv'));
    document.getElementById('exportPDFAdvanced')?.addEventListener('click', () => exportReport('pdf'));
    document.getElementById('exportExcelAdvanced')?.addEventListener('click', () => exportReport('excel'));
    
    // ========== AUTO-DISMISS ALERTS ==========
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
    
});
</script>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #7c3aed 0%, #6366f1 100%);
}

.bg-gradient-success {
    background: linear-gradient(135deg, #10b981 0%, #14b8a6 100%);
}

.border-left-info {
    border-left: 4px solid #3b82f6;
}

.border-left-success {
    border-left: 4px solid #10b981;
}

.border-left-warning {
    border-left: 4px solid #f59e0b;
}

.border-left-primary {
    border-left: 4px solid #7c3aed;
}

.table-hover tbody tr:hover {
    background-color: rgba(124, 58, 237, 0.05);
}

.btn-group-sm > .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

#searchReports:focus {
    border-color: #7c3aed;
    box-shadow: 0 0 0 0.2rem rgba(124, 58, 237, 0.25);
}

.card {
    border: none;
}

.card-header {
    border-bottom: 2px solid rgba(0,0,0,0.1);
}

.badge {
    font-weight: 500;
    padding: 0.35em 0.65em;
}
</style>

<?php 
include '../app/views/shared/layout-footer.php';
include '../app/views/shared/footer.php'; 
?>
