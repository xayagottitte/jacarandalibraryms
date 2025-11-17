<?php 
$pageTitle = "Activity Logs";
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

    .page-header h2 {
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
    
    .stat-card-gradient.danger {
        background: linear-gradient(135deg, #f85032 0%, #e73827 100%);
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
        <div class="page-header">
            <h2><i class="fas fa-history me-2"></i>Activity Logs</h2>
            <p class="mb-0 text-muted mt-2"><i class="fas fa-chart-line me-2" style="color: var(--jacaranda-primary);"></i>Monitor system activities and security events</p>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= htmlspecialchars($_SESSION['success'], ENT_QUOTES, 'UTF-8'); unset($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?= htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8'); unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <?php
            $statsByCategory = [];
            $statsBySeverity = [];
            foreach ($stats as $stat) {
                $key = $stat['event_category'] . '_' . $stat['severity'];
                if (!isset($statsByCategory[$stat['event_category']])) {
                    $statsByCategory[$stat['event_category']] = 0;
                }
                if (!isset($statsBySeverity[$stat['severity']])) {
                    $statsBySeverity[$stat['severity']] = 0;
                }
                $statsByCategory[$stat['event_category']] += $stat['count'];
                $statsBySeverity[$stat['severity']] += $stat['count'];
            }
            ?>
            <div class="col-md-3">
                <div class="card stat-card-gradient primary h-100 py-2">
                    <div class="card-body">
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="opacity: 0.9;">Authentication</div>
                        <div class="h3 mb-0 font-weight-bold"><?= $statsByCategory['authentication'] ?? 0 ?></div>
                        <small style="opacity: 0.8;">Last 7 days</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card-gradient success h-100 py-2">
                    <div class="card-body">
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="opacity: 0.9;">Profile Changes</div>
                        <div class="h3 mb-0 font-weight-bold"><?= $statsByCategory['profile'] ?? 0 ?></div>
                        <small style="opacity: 0.8;">Last 7 days</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card-gradient warning h-100 py-2">
                    <div class="card-body">
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="opacity: 0.9;">Security Events</div>
                        <div class="h3 mb-0 font-weight-bold"><?= $statsByCategory['security'] ?? 0 ?></div>
                        <small style="opacity: 0.8;">Last 7 days</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card-gradient danger h-100 py-2">
                    <div class="card-body">
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="opacity: 0.9;">Critical Events</div>
                        <div class="h3 mb-0 font-weight-bold"><?= $statsBySeverity['critical'] ?? 0 ?></div>
                        <small style="opacity: 0.8;">Last 7 days</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card mb-4 shadow">
            <div class="card-header" style="background: linear-gradient(135deg, var(--jacaranda-primary) 0%, var(--jacaranda-secondary) 100%);">
                <h5 class="mb-0 text-white"><i class="fas fa-filter me-2"></i>Filters</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="<?= BASE_PATH ?>/admin/activity-logs" class="row g-3" id="filterForm">
                    <div class="col-md-3">
                        <label class="form-label">User</label>
                        <select name="user_id" class="form-select filter-input">
                            <option value="">All Users</option>
                            <?php foreach ($users as $user): ?>
                                <option value="<?= $user['id'] ?>" <?= ($filters['user_id'] == $user['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') ?> (<?= $user['role'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Category</label>
                        <select name="event_category" class="form-select filter-input">
                            <option value="">All</option>
                            <option value="authentication" <?= ($filters['event_category'] == 'authentication') ? 'selected' : '' ?>>Authentication</option>
                            <option value="profile" <?= ($filters['event_category'] == 'profile') ? 'selected' : '' ?>>Profile</option>
                            <option value="security" <?= ($filters['event_category'] == 'security') ? 'selected' : '' ?>>Security</option>
                            <option value="data" <?= ($filters['event_category'] == 'data') ? 'selected' : '' ?>>Data</option>
                            <option value="system" <?= ($filters['event_category'] == 'system') ? 'selected' : '' ?>>System</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Severity</label>
                        <select name="severity" class="form-select filter-input">
                            <option value="">All</option>
                            <option value="info" <?= ($filters['severity'] == 'info') ? 'selected' : '' ?>>Info</option>
                            <option value="warning" <?= ($filters['severity'] == 'warning') ? 'selected' : '' ?>>Warning</option>
                            <option value="critical" <?= ($filters['severity'] == 'critical') ? 'selected' : '' ?>>Critical</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">From Date</label>
                        <input type="date" name="date_from" class="form-control filter-input" value="<?= htmlspecialchars($filters['date_from'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">To Date</label>
                        <input type="date" name="date_to" class="form-control filter-input" value="<?= htmlspecialchars($filters['date_to'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" id="clearFilters" class="btn btn-secondary w-100" title="Clear Filters">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Activity Logs Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>Activity Records
                    <span class="badge bg-secondary ms-2"><?= $pagination['totalRecords'] ?> total</span>
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Timestamp</th>
                                <th>User</th>
                                <th>Event</th>
                                <th>Category</th>
                                <th>Description</th>
                                <th>IP Address</th>
                                <th>Severity</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($logs)): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                        No activity logs found
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($logs as $log): ?>
                                    <tr>
                                        <td>
                                            <small class="text-muted">
                                                <?= date('M d, Y', strtotime($log['created_at'])) ?><br>
                                                <?= date('h:i:s A', strtotime($log['created_at'])) ?>
                                            </small>
                                        </td>
                                        <td>
                                            <?php if ($log['username']): ?>
                                                <strong><?= htmlspecialchars($log['username'], ENT_QUOTES, 'UTF-8') ?></strong><br>
                                                <small class="text-muted"><?= htmlspecialchars($log['email'] ?? '', ENT_QUOTES, 'UTF-8') ?></small>
                                            <?php else: ?>
                                                <span class="text-muted">Anonymous</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <code><?= htmlspecialchars($log['event_type'], ENT_QUOTES, 'UTF-8') ?></code>
                                        </td>
                                        <td>
                                            <?php
                                            $categoryColors = [
                                                'authentication' => 'primary',
                                                'profile' => 'info',
                                                'security' => 'warning',
                                                'data' => 'success',
                                                'system' => 'secondary'
                                            ];
                                            $color = $categoryColors[$log['event_category']] ?? 'secondary';
                                            ?>
                                            <span class="badge bg-<?= $color ?>">
                                                <?= ucfirst($log['event_category']) ?>
                                            </span>
                                        </td>
                                        <td><?= htmlspecialchars($log['description'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><small><?= htmlspecialchars($log['ip_address'], ENT_QUOTES, 'UTF-8') ?></small></td>
                                        <td>
                                            <?php
                                            $severityColors = [
                                                'info' => 'secondary',
                                                'warning' => 'warning',
                                                'critical' => 'danger'
                                            ];
                                            $severityColor = $severityColors[$log['severity']] ?? 'secondary';
                                            ?>
                                            <span class="badge bg-<?= $severityColor ?>">
                                                <?= ucfirst($log['severity']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <?php if (isset($pagination['total']) && $pagination['total'] > 1): ?>
                <div class="card-footer">
                    <nav>
                        <ul class="pagination justify-content-center mb-0">
                            <?php
                            $queryString = $_GET;
                            unset($queryString['page']);
                            $baseQuery = http_build_query($queryString);
                            $separator = $baseQuery ? '&' : '';
                            ?>
                            
                            <!-- Previous -->
                            <li class="page-item <?= ($pagination['current'] <= 1) ? 'disabled' : '' ?>">
                                <a class="page-link" href="?<?= $baseQuery ?><?= $separator ?>page=<?= $pagination['current'] - 1 ?>">Previous</a>
                            </li>

                            <!-- Page Numbers -->
                            <?php
                            $start = max(1, $pagination['current'] - 2);
                            $end = min($pagination['total'], $pagination['current'] + 2);
                            
                            for ($i = $start; $i <= $end; $i++):
                            ?>
                                <li class="page-item <?= ($i == $pagination['current']) ? 'active' : '' ?>">
                                    <a class="page-link" href="?<?= $baseQuery ?><?= $separator ?>page=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>

                            <!-- Next -->
                            <li class="page-item <?= ($pagination['current'] >= $pagination['total']) ? 'disabled' : '' ?>">
                                <a class="page-link" href="?<?= $baseQuery ?><?= $separator ?>page=<?= $pagination['current'] + 1 ?>">Next</a>
                            </li>
                        </ul>
                    </nav>
                    <div class="text-center text-muted">
                        <small>
                            Showing page <?= $pagination['current'] ?> of <?= $pagination['total'] ?>
                            (<?= $pagination['totalRecords'] ?> total records)
                        </small>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterInputs = document.querySelectorAll('.filter-input');
    const clearFiltersBtn = document.getElementById('clearFilters');
    const logsTableBody = document.querySelector('.table tbody');
    
    // Apply filters on any input change
    filterInputs.forEach(input => {
        input.addEventListener('change', function() {
            applyFilters();
        });
    });
    
    // Clear filters button
    clearFiltersBtn.addEventListener('click', function() {
        filterInputs.forEach(input => {
            if (input.tagName === 'SELECT') {
                input.selectedIndex = 0;
            } else {
                input.value = '';
            }
        });
        applyFilters();
    });
    
    function applyFilters(page = 1) {
        // Show loading state
        if (logsTableBody) {
            logsTableBody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted">Loading activity logs...</p>
                    </td>
                </tr>
            `;
        }
        
        // Build query string
        const formData = new FormData(document.getElementById('filterForm'));
        const params = new URLSearchParams();
        
        formData.forEach((value, key) => {
            if (value) {
                params.append(key, value);
            }
        });
        
        if (page > 1) {
            params.append('page', page);
        }
        
        // Fetch filtered data
        fetch('<?= BASE_PATH ?>/admin/activity-logs?' + params.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            // Parse the HTML response
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            
            // Update table body
            const newTableBody = doc.querySelector('.table tbody');
            if (newTableBody && logsTableBody) {
                logsTableBody.innerHTML = newTableBody.innerHTML;
            }
            
            // Update pagination
            const newPagination = doc.querySelector('.pagination');
            const currentPagination = document.querySelector('.pagination');
            if (newPagination && currentPagination) {
                currentPagination.parentElement.innerHTML = newPagination.parentElement.innerHTML;
                attachPaginationListeners();
            }
            
            // Update URL without page reload
            const newUrl = '<?= BASE_PATH ?>/admin/activity-logs' + (params.toString() ? '?' + params.toString() : '');
            window.history.pushState({}, '', newUrl);
        })
        .catch(error => {
            console.error('Error:', error);
            if (logsTableBody) {
                logsTableBody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center py-5 text-danger">
                            <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                            <p>Error loading activity logs. Please try again.</p>
                        </td>
                    </tr>
                `;
            }
        });
    }
    
    function attachPaginationListeners() {
        const paginationLinks = document.querySelectorAll('.pagination .page-link');
        paginationLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const url = new URL(this.href);
                const page = url.searchParams.get('page') || 1;
                applyFilters(page);
            });
        });
    }
    
    // Attach pagination listeners on initial load
    attachPaginationListeners();
});
</script>

<?php include '../app/views/shared/footer.php'; ?>
