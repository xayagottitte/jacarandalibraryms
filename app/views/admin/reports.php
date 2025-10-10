<?php 
$title = "Advanced Reports - Multi-Library System";
include '../app/views/shared/header.php'; 
include '../app/views/shared/admin-sidebar.php'; 
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Advanced Reports & Analytics</h1>
    </div>

    <!-- Advanced Report Generator -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Generate Advanced Report</h5>
        </div>
        <div class="card-body">
            <form id="advancedReportForm">
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="report_type" class="form-label">Report Type</label>
                            <select class="form-select" id="report_type" name="report_type" required>
                                <option value="">Select Report Type</option>
                                <option value="comprehensive">Comprehensive Library Report</option>
                                <option value="analytics">Library Analytics</option>
                                <option value="performance">Performance Trends</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="library_id" class="form-label">Library</label>
                            <select class="form-select" id="library_id" name="library_id">
                                <option value="">All Libraries</option>
                                <?php foreach ($libraries as $library): ?>
                                    <option value="<?= $library['id'] ?>"><?= htmlspecialchars($library['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="report_title" class="form-label">Report Title</label>
                            <input type="text" class="form-control" id="report_title" name="report_title" required>
                        </div>
                    </div>
                </div>

                <!-- Dynamic Filters -->
                <div id="advancedFilters" class="row mb-3" style="display: none;">
                    <!-- Filters will be loaded here based on report type -->
                </div>

                <button type="submit" class="btn btn-primary" id="generateAdvancedReport">
                    <i class="fas fa-chart-bar"></i> Generate Advanced Report
                </button>
            </form>
        </div>
    </div>

    <!-- Advanced Report Results -->
    <div class="card mb-4" id="advancedReportResults" style="display: none;">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0" id="resultsTitle">Report Results</h5>
            <div>
                <button class="btn btn-sm btn-success" id="exportCSVAdvanced">
                    <i class="fas fa-file-csv"></i> CSV
                </button>
                <button class="btn btn-sm btn-danger" id="exportPDFAdvanced">
                    <i class="fas fa-file-pdf"></i> PDF
                </button>
                <button class="btn btn-sm btn-warning" id="exportExcelAdvanced">
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

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Total Reports</h6>
                            <h4><?= count($saved_reports) ?></h4>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-chart-bar fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">This Month</h6>
                            <h4><?= count(array_filter($saved_reports, function($r) {
                                return date('Y-m') === date('Y-m', strtotime($r['created_at']));
                            })) ?></h4>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-calendar fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Most Active</h6>
                            <h4>
                                <?php
                                $types = array_count_values(array_column($saved_reports, 'type'));
                                arsort($types);
                                echo ucfirst(key($types) ?: 'N/A');
                                ?>
                            </h4>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-star fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Your Reports</h6>
                            <h4><?= count(array_filter($saved_reports, function($r) {
                                return $r['generated_by'] == $_SESSION['user_id'];
                            })) ?></h4>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-user fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Saved Reports -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Saved Reports</h5>
            <button class="btn btn-sm btn-outline-danger" id="cleanupReports">
                <i class="fas fa-trash"></i> Cleanup Old Reports
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Library</th>
                            <th>Generated By</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($saved_reports as $report): ?>
                            <tr>
                                <td><?= htmlspecialchars($report['title']) ?></td>
                                <td>
                                    <span class="badge bg-<?= 
                                        $report['type'] === 'comprehensive' ? 'primary' : 
                                        ($report['type'] === 'analytics' ? 'info' : 'success')
                                    ?>">
                                        <?= ucfirst($report['type']) ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($report['library_name'] ?? 'All Libraries') ?></td>
                                <td><?= htmlspecialchars($report['username'] ?? 'System') ?></td>
                                <td><?= date('M j, Y g:i A', strtotime($report['created_at'])) ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="/report/view/<?= $report['id'] ?>" class="btn btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form method="POST" action="/report/delete" class="d-inline">
                                            <input type="hidden" name="report_id" value="<?= $report['id'] ?>">
                                            <button type="submit" class="btn btn-outline-danger" 
                                                    onclick="return confirm('Delete this report?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const reportType = document.getElementById('report_type');
    const filtersDiv = document.getElementById('advancedFilters');
    const reportForm = document.getElementById('advancedReportForm');
    const reportResults = document.getElementById('advancedReportResults');
    const resultsTitle = document.getElementById('resultsTitle');
    const resultsSummary = document.getElementById('resultsSummary');
    const resultsCharts = document.getElementById('resultsCharts');
    const resultsData = document.getElementById('resultsData');

    // Load filters based on report type
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
                        <div class="row">
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
        }

        filtersDiv.innerHTML = filtersHTML;
        filtersDiv.style.display = 'block';
    });

    // Generate advanced report
    reportForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const filters = {};
        
        // Collect filter data
        formData.forEach((value, key) => {
            if (key.startsWith('filters[') && value) {
                const filterKey = key.match(/filters\[(.*?)\]/)[1];
                filters[filterKey] = value;
            }
        });

        const payload = {
            report_type: formData.get('report_type'),
            library_id: formData.get('library_id'),
            report_title: formData.get('report_title'),
            filters: JSON.stringify(filters)
        };

        fetch('/report/generate-advanced', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams(payload)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayAdvancedResults(data.data, data.summary, formData.get('report_title'));
                reportResults.style.display = 'block';
                
                // Store data for export
                window.currentReportData = data.data;
                window.currentReportType = formData.get('report_type');
                window.currentReportTitle = formData.get('report_title');
            } else {
                alert('Error generating report: ' + (data.error || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error generating report.');
        });
    });

    function displayAdvancedResults(data, summary, title) {
        // Clear previous results
        resultsSummary.innerHTML = '';
        resultsCharts.innerHTML = '';
        resultsData.innerHTML = '';
        resultsTitle.textContent = title;

        // Display summary
        if (summary) {
            let summaryHTML = '<div class="alert alert-info"><h5>Summary</h5><div class="row">';
            for (const [key, value] of Object.entries(summary)) {
                summaryHTML += `<div class="col-md-3"><strong>${key.replace(/_/g, ' ')}:</strong> ${value}</div>`;
            }
            summaryHTML += '</div></div>';
            resultsSummary.innerHTML = summaryHTML;
        }

        // Display charts and data based on report type
        if (window.currentReportType === 'analytics') {
            displayAnalyticsCharts(data);
        } else if (window.currentReportType === 'performance') {
            displayPerformanceCharts(data);
        }

        // Display data tables
        displayDataTables(data);
    }

    function displayAnalyticsCharts(data) {
        if (data.analytics) {
            // Borrowing trend chart
            const dates = data.analytics.map(item => item.date).reverse();
            const dailyBorrows = data.analytics.map(item => item.daily_borrows).reverse();
            
            resultsCharts.innerHTML += `
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5>Daily Borrowing Trend</h5>
                            <canvas id="borrowingChart" height="250"></canvas>
                        </div>
                    </div>
                </div>
            `;

            // Popular books chart
            if (data.popular_books) {
                const bookTitles = data.popular_books.map(item => item.title.substring(0, 20) + '...');
                const borrowCounts = data.popular_books.map(item => item.borrow_count);
                
                resultsCharts.innerHTML += `
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5>Most Popular Books</h5>
                                <canvas id="popularBooksChart" height="250"></canvas>
                            </div>
                        </div>
                    </div>
                `;

                // Render charts after DOM update
                setTimeout(() => {
                    renderBorrowingChart(dates, dailyBorrows);
                    renderPopularBooksChart(bookTitles, borrowCounts);
                }, 100);
            }
        }
    }

    function renderBorrowingChart(dates, dailyBorrows) {
        const ctx = document.getElementById('borrowingChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: dates,
                datasets: [{
                    label: 'Daily Borrows',
                    data: dailyBorrows,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });
    }

    function renderPopularBooksChart(bookTitles, borrowCounts) {
        const ctx = document.getElementById('popularBooksChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: bookTitles,
                datasets: [{
                    label: 'Borrow Count',
                    data: borrowCounts,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });
    }

    function displayPerformanceCharts(data) {
        // Implementation for performance charts
        // Similar to analytics charts but for performance data
    }

    function displayDataTables(data) {
        let tablesHTML = '';
        
        for (const [section, sectionData] of Object.entries(data)) {
            if (sectionData && sectionData.length > 0 && section !== 'summary') {
                tablesHTML += `
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">${ucfirst(section)}</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-sm">
                                    <thead>
                                        <tr>
                                            ${Object.keys(sectionData[0]).map(key => 
                                                `<th>${ucfirst(key.replace(/_/g, ' '))}</th>`
                                            ).join('')}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${sectionData.map(row => `
                                            <tr>
                                                ${Object.values(row).map(value => 
                                                    `<td>${value}</td>`
                                                ).join('')}
                                            </tr>
                                        `).join('')}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                `;
            }
        }
        
        resultsData.innerHTML = tablesHTML;
    }

    function ucfirst(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    // Export functionality
    document.getElementById('exportCSVAdvanced').addEventListener('click', function() {
        exportAdvancedReport('csv');
    });

    document.getElementById('exportPDFAdvanced').addEventListener('click', function() {
        exportAdvancedReport('pdf');
    });

    document.getElementById('exportExcelAdvanced').addEventListener('click', function() {
        exportAdvancedReport('excel');
    });

    function exportAdvancedReport(type) {
        if (window.currentReportData) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/report/export';

            const dataInput = document.createElement('input');
            dataInput.type = 'hidden';
            dataInput.name = 'report_data';
            dataInput.value = JSON.stringify(window.currentReportData);
            form.appendChild(dataInput);

            const typeInput = document.createElement('input');
            typeInput.type = 'hidden';
            typeInput.name = 'export_type';
            typeInput.value = type;
            form.appendChild(typeInput);

            const reportTypeInput = document.createElement('input');
            reportTypeInput.type = 'hidden';
            reportTypeInput.name = 'report_type';
            reportTypeInput.value = window.currentReportType;
            form.appendChild(reportTypeInput);

            const filenameInput = document.createElement('input');
            filenameInput.type = 'hidden';
            filenameInput.name = 'filename';
            filenameInput.value = window.currentReportTitle;
            form.appendChild(filenameInput);

            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        }
    }

    // Cleanup reports
    document.getElementById('cleanupReports').addEventListener('click', function() {
        if (confirm('Clean up reports older than 30 days?')) {
            fetch('/system/maintenance', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=cleanup_reports'
            }).then(() => location.reload());
        }
    });
});
</script>

<?php include '../app/views/shared/footer.php'; ?>