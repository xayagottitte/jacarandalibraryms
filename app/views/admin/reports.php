<?php 
$title = "Advanced Reports - Jacaranda Libraries";
include '../app/views/shared/header.php'; 
include '../app/views/shared/navbar.php';
include '../app/views/shared/layout-header.php'; 
?>

<style>
    :root {
        --jacaranda-primary: #663399;
        --jacaranda-secondary: #8a4baf;
        --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --gradient-success: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        --gradient-warning: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        --gradient-info: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        --shadow-light: 0 4px 20px rgba(0,0,0,0.1);
        --shadow-hover: 0 8px 30px rgba(0,0,0,0.15);
    }

    .modern-dashboard {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
        padding: 1.5rem 0;
    }

    .page-header {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: var(--shadow-light);
        border-left: 4px solid var(--jacaranda-primary);
    }

    .page-header h1 {
        background: var(--jacaranda-primary);
        background: linear-gradient(135deg, var(--jacaranda-primary) 0%, var(--jacaranda-secondary) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-weight: 600;
        margin: 0;
    }

    .stat-card-gradient {
        border: none;
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: var(--shadow-light);
        color: white;
    }
    
    .stat-card-gradient:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-hover);
    }
    
    .stat-card-gradient.primary {
        background: var(--gradient-primary);
    }
    
    .stat-card-gradient.success {
        background: var(--gradient-success);
    }
    
    .stat-card-gradient.warning {
        background: var(--gradient-warning);
    }
    
    .stat-card-gradient.info {
        background: var(--gradient-info);
    }
    
    .stat-card-gradient .card-body {
        position: relative;
        overflow: hidden;
    }
    
    .stat-card-gradient .icon-bg {
        position: absolute;
        right: -10px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 4rem;
        opacity: 0.2;
    }

    .modern-card .card-header {
        background: linear-gradient(135deg, var(--jacaranda-primary) 0%, var(--jacaranda-secondary) 100%) !important;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--jacaranda-primary) 0%, var(--jacaranda-secondary) 100%);
        border: none;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 51, 153, 0.3);
    }
</style>

<div class="main-content modern-dashboard">
    <div class="container-fluid px-4">
        <div class="page-header d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-2">Multi-Library Analytics & Reporting</h1>
                <p class="mb-0 text-muted"><i class="fas fa-chart-line me-2" style="color: var(--jacaranda-primary);"></i>Comprehensive insights across <?= $stats['libraries_count'] ?> libraries for strategic decision-making</p>
            </div>
            <div>
                <select id="libraryFilterMain" class="form-select" style="min-width: 200px;">
                    <option value="">All Libraries</option>
                    <?php foreach ($libraries as $lib): ?>
                        <option value="<?= $lib['id'] ?>"><?= htmlspecialchars($lib['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- Key Performance Indicators for Administrative Decision Making -->
        <div class="row mb-4 g-3">
            <!-- Total Collection Size -->
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card stat-card-gradient primary shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Total Collection</div>
                                <div class="h4 mb-0 font-weight-bold"><?= number_format($stats['total_books']) ?></div>
                                <div class="text-xs mt-1">Books across all libraries</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-book-open icon-bg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Collection Utilization -->
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card stat-card-gradient success shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Utilization Rate</div>
                                <div class="h4 mb-0 font-weight-bold"><?= $stats['utilization_rate'] ?>%</div>
                                <div class="text-xs mt-1">Books currently in circulation</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-chart-pie icon-bg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Student Reach -->
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card stat-card-gradient info shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Student Reach</div>
                                <div class="h4 mb-0 font-weight-bold"><?= number_format($stats['total_students']) ?></div>
                                <div class="text-xs mt-1"><?= $stats['student_engagement'] ?>% actively engaged</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users icon-bg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Impact Metric -->
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card stat-card-gradient warning shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Total Transactions</div>
                                <div class="h4 mb-0 font-weight-bold"><?= number_format($stats['total_borrows']) ?></div>
                                <div class="text-xs mt-1">Lifetime borrowing activity</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-exchange-alt icon-bg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Operational Health Metrics -->
        <div class="row mb-4 g-3">
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card shadow h-100" style="border-left: 4px solid #dc3545;">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Overdue Items</div>
                                <div class="h4 mb-0 font-weight-bold text-dark"><?= number_format($stats['overdue_books']) ?></div>
                                <div class="text-xs mt-1 text-muted">Require follow-up action</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card shadow h-100" style="border-left: 4px solid #28a745;">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Available Stock</div>
                                <div class="h4 mb-0 font-weight-bold text-dark"><?= number_format($stats['total_available']) ?></div>
                                <div class="text-xs mt-1 text-muted">Ready for borrowing</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card shadow h-100" style="border-left: 4px solid #17a2b8;">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Active Borrowers</div>
                                <div class="h4 mb-0 font-weight-bold text-dark"><?= number_format($stats['active_students']) ?></div>
                                <div class="text-xs mt-1 text-muted">Current library users</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-check fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card shadow h-100" style="border-left: 4px solid #6f42c1;">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #6f42c1;">Network Size</div>
                                <div class="h4 mb-0 font-weight-bold text-dark"><?= $stats['libraries_count'] ?></div>
                                <div class="text-xs mt-1 text-muted">Libraries managed</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-building fa-2x" style="color: #6f42c1;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Library Performance Comparison -->
        <div class="card shadow mb-4">
            <div class="card-header py-3" style="background: linear-gradient(135deg, var(--jacaranda-primary) 0%, var(--jacaranda-secondary) 100%);">
                <h6 class="m-0 font-weight-bold text-white">
                    <i class="fas fa-building me-2"></i>Library Performance Overview
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead style="background-color: #f8f9fc;">
                            <tr>
                                <th>Library</th>
                                <th class="text-center">Books</th>
                                <th class="text-center">Students</th>
                                <th class="text-center">Active Borrows</th>
                                <th class="text-center">Utilization</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($libraries as $library): 
                                $lib_utilization = $library['total_copies'] > 0 
                                    ? round((($library['total_copies'] - $library['available_copies']) / $library['total_copies']) * 100, 1) 
                                    : 0;
                            ?>
                            <tr class="library-performance-row" data-library-id="<?= $library['id'] ?>">
                                <td>
                                    <strong><?= htmlspecialchars($library['name']) ?></strong>
                                    <br><small class="text-muted"><?= htmlspecialchars($library['type']) ?></small>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary"><?= number_format($library['total_books'] ?? 0) ?></span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info"><?= number_format($library['total_students'] ?? 0) ?></span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-warning text-dark"><?= number_format($library['active_borrows'] ?? 0) ?></span>
                                </td>
                                <td class="text-center">
                                    <div class="progress" style="height: 20px; min-width: 100px;">
                                        <div class="progress-bar <?= $lib_utilization > 70 ? 'bg-success' : ($lib_utilization > 40 ? 'bg-warning' : 'bg-danger') ?>" 
                                             role="progressbar" 
                                             style="width: <?= $lib_utilization ?>%"
                                             aria-valuenow="<?= $lib_utilization ?>" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                            <?= $lib_utilization ?>%
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <?php if ($lib_utilization > 70): ?>
                                        <span class="badge bg-success"><i class="fas fa-check-circle"></i> Excellent</span>
                                    <?php elseif ($lib_utilization > 40): ?>
                                        <span class="badge bg-warning text-dark"><i class="fas fa-chart-line"></i> Moderate</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger"><i class="fas fa-exclamation-circle"></i> Needs Attention</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
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

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // ========== LIBRARY FILTER ==========
    const libraryFilter = document.getElementById('libraryFilterMain');
    if (libraryFilter) {
        libraryFilter.addEventListener('change', function() {
            const selectedLibrary = this.value;
            const libraryRows = document.querySelectorAll('.library-performance-row');
            
            libraryRows.forEach(row => {
                if (!selectedLibrary || row.dataset.libraryId === selectedLibrary) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
    
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
                    borderColor: '#663399',
                    backgroundColor: 'rgba(102, 51, 153, 0.1)',
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
                        '#663399',
                        '#8a4baf',
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
                    backgroundColor: '#663399'
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
                    backgroundColor: '#8a4baf'
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
    
    // ========== CLEANUP OLD REPORTS ==========
    const cleanupBtn = document.getElementById('cleanupReports');
    if (cleanupBtn) {
        cleanupBtn.addEventListener('click', function() {
            if (confirm('This will delete all reports older than 90 days. Continue?')) {
                window.location.href = '<?= BASE_PATH ?>/report/cleanup';
            }
        });
    }

    
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
    background: linear-gradient(135deg, #663399 0%, #8a4baf 100%);
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
    border-left: 4px solid #663399;
}

.table-hover tbody tr:hover {
    background-color: rgba(102, 51, 153, 0.05);
}

.btn-group-sm > .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

#searchReports:focus {
    border-color: #663399;
    box-shadow: 0 0 0 0.2rem rgba(102, 51, 153, 0.25);
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

<?php include '../app/views/shared/layout-footer.php'; ?>
