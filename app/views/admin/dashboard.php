<?php include '../app/views/shared/header.php'; ?>
<?php include '../app/views/shared/layout-header.php'; ?>

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
        transition: all 0.3s ease;
    }

    .dashboard-header {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: var(--shadow-light);
        border-left: 4px solid var(--jacaranda-primary);
    }

    .dashboard-header h1 {
        background: var(--jacaranda-primary);
        background: linear-gradient(135deg, var(--jacaranda-primary) 0%, var(--jacaranda-secondary) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-weight: 600;
        font-size: 2rem;
        margin: 0;
    }

    .dashboard-header .text-muted {
        font-size: 1.1rem;
        font-weight: 500;
        color: #6c757d !important;
    }

    .dashboard-header .text-muted i {
        color: var(--jacaranda-primary);
    }

    .stat-card-modern {
        border: none;
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: var(--shadow-light);
        backdrop-filter: blur(10px);
        margin-bottom: 1.5rem;
        height: 120px;
    }

    .stat-card-modern:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-hover);
    }

    .stat-card-modern.primary { background: var(--gradient-primary); }
    .stat-card-modern.success { background: var(--gradient-success); }
    .stat-card-modern.warning { background: var(--gradient-warning); }
    .stat-card-modern.info { background: var(--gradient-info); }

    .stat-card-modern .card-body {
        padding: 1.5rem;
        position: relative;
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .stat-card-modern .card-body::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 50px;
        height: 50px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
        transform: translate(15px, -15px);
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: white;
        margin: 0;
        text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        line-height: 1;
    }

    .stat-label {
        color: rgba(255,255,255,0.9);
        font-weight: 500;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
        line-height: 1.2;
    }

    .stat-icon {
        font-size: 1.8rem;
        color: rgba(255,255,255,0.7);
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
    }

    .modern-card {
        border: none;
        border-radius: 15px;
        box-shadow: var(--shadow-light);
        overflow: hidden;
        transition: all 0.3s ease;
        background: white;
        margin-bottom: 1.5rem;
    }

    .modern-card:hover {
        box-shadow: var(--shadow-hover);
    }

    .modern-card .card-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: none;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }

    .modern-card .card-header h5 {
        color: #333;
        font-weight: 600;
        font-size: 1.1rem;
        margin: 0;
    }

    .modern-card .card-body {
        padding: 1.5rem;
    }

    .modern-table {
        border-radius: 10px;
        overflow: hidden;
    }

    .modern-table th {
        background: var(--jacaranda-primary);
        color: white;
        font-weight: 600;
        border: none;
        padding: 1rem 1.25rem;
        font-size: 0.95rem;
    }
    
    .modern-table td {
        padding: 1rem 1.25rem;
        vertical-align: middle;
        border-bottom: 1px solid #f0f0f0;
        font-size: 0.95rem;
    }

    .modern-table tbody tr:hover {
        background: rgba(102, 51, 153, 0.05);
        transition: all 0.2s ease;
    }

    .modern-btn {
        border-radius: 12px;
        padding: 0.5rem 1rem;
        font-weight: 500;
        transition: all 0.3s ease;
        border: none;
        font-size: 0.85rem;
    }

    .modern-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }

    .badge-modern {
        border-radius: 10px;
        padding: 0.4rem 0.8rem;
        font-weight: 500;
        font-size: 0.75rem;
    }

    .empty-state {
        text-align: center;
        padding: 2rem 1.5rem;
        color: #6c757d;
    }

    .empty-state i {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .empty-state h5 {
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        font-size: 0.9rem;
    }

    @media (max-width: 768px) {
        .dashboard-header {
            padding: 1rem;
            margin: 1rem 0.5rem;
        }
        
        .dashboard-header h1 {
            font-size: 1.5rem;
        }
        
        .stat-number {
            font-size: 2rem;
        }
        
        .stat-card-modern {
            height: 100px;
        }
        
        .stat-card-modern .card-body {
            padding: 1rem;
        }
        
        .stat-icon {
            font-size: 1.4rem;
        }
        
        .modern-card .card-header {
            padding: 0.75rem 1rem;
        }
        
        .modern-card .card-body {
            padding: 1rem;
        }
        
        .modern-table th,
        .modern-table td {
            padding: 0.5rem 0.75rem;
            font-size: 0.75rem;
        }
    }

    /* Responsive improvements when sidebar is collapsed */
    .sidebar-collapsed .modern-dashboard {
        transition: all 0.3s ease;
    }

    .sidebar-collapsed .row {
        transition: all 0.3s ease;
    }

    .sidebar-collapsed .col-xl-3 {
        transition: all 0.3s ease;
    }

    /* Improve spacing when sidebar is collapsed */
    .sidebar-collapsed .container-fluid {
        padding-left: 1.5rem;
        padding-right: 1.5rem;
    }
</style>

            <div class="modern-dashboard">
            <div class="container-fluid">
        <div class="dashboard-header">
            <div class="d-flex justify-content-between align-items-center">
                <h1>Admin Dashboard</h1>
                <div class="text-muted">
                    <i class="fas fa-calendar-day me-2"></i>
                    <?= date('F j, Y') ?>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="card stat-card-modern primary">
                    <div class="card-body">
                        <div class="stat-label">Total Libraries</div>
                        <div class="stat-number" data-count="<?= $stats['total_libraries'] ?? 0 ?>">0</div>
                        <i class="fas fa-building stat-icon"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card stat-card-modern success">
                    <div class="card-body">
                        <div class="stat-label">Active Librarians</div>
                        <div class="stat-number" data-count="<?= $stats['total_librarians'] ?? 0 ?>">0</div>
                        <i class="fas fa-users stat-icon"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card stat-card-modern warning">
                    <div class="card-body">
                        <div class="stat-label">Pending Approvals</div>
                        <div class="stat-number" data-count="<?= $stats['pending_librarians'] ?? 0 ?>">0</div>
                        <i class="fas fa-clock stat-icon"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card stat-card-modern info">
                    <div class="card-body">
                        <div class="stat-label">Super Admins</div>
                        <div class="stat-number" data-count="<?= $stats['total_super_admins'] ?? 0 ?>">0</div>
                        <i class="fas fa-user-shield stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lost Books Overview -->
        <div class="row">
            <div class="col-12">
                <div class="modern-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5><i class="fas fa-book-dead me-2"></i>Lost Books (Overdue > 30 days)</h5>
                        <span class="badge badge-modern bg-danger"><?= (int)($lost_count ?? 0) ?> total</span>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($lost_books)): ?>
                            <div class="table-responsive">
                                <table class="table modern-table">
                                    <thead>
                                        <tr>
                                            <th>Library</th>
                                            <th>Book</th>
                                            <th>Borrower</th>
                                            <th>Student ID</th>
                                            <th>Days Overdue</th>
                                            <th>Due Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($lost_books as $row): ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="library-icon me-2">
                                                            <i class="fas fa-university"></i>
                                                        </div>
                                                        <strong><?= htmlspecialchars($row['library_name']) ?></strong>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <strong><?= htmlspecialchars($row['title']) ?></strong>
                                                        <small class="text-muted">ISBN: <?= htmlspecialchars($row['isbn']) ?></small>
                                                    </div>
                                                </td>
                                                <td><?= htmlspecialchars($row['student_name']) ?></td>
                                                <td><?= htmlspecialchars($row['student_code']) ?></td>
                                                <td>
                                                    <span class="badge badge-modern bg-danger"><?= (int)$row['days_overdue'] ?> days</span>
                                                </td>
                                                <td><?= date('M j, Y', strtotime($row['due_date'])) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="empty-state">
                                <i class="fas fa-book"></i>
                                <h5>No Lost Books</h5>
                                <p class="text-muted">No books appear lost based on overdue > 30 days.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Pending Approvals -->
            <div class="col-lg-6">
                <div class="modern-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5><i class="fas fa-user-check me-2"></i>Pending Approvals</h5>
                        <a href="<?= BASE_PATH ?>/admin/users" class="btn btn-primary modern-btn btn-sm">View All</a>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($pending_users)): ?>
                            <div class="table-responsive">
                                <table class="table modern-table">
                                    <thead>
                                        <tr>
                                            <th><i class="fas fa-user me-2"></i>User</th>
                                            <th><i class="fas fa-envelope me-2"></i>Email</th>
                                            <th><i class="fas fa-calendar me-2"></i>Date</th>
                                            <th><i class="fas fa-cogs me-2"></i>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($pending_users as $user): ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-circle me-3">
                                                            <i class="fas fa-user"></i>
                                                        </div>
                                                        <div>
                                                            <strong><?= htmlspecialchars($user['username']) ?></strong>
                                                            <br>
                                                            <small class="text-muted"><?= htmlspecialchars($user['full_name'] ?? '') ?></small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><?= htmlspecialchars($user['email']) ?></td>
                                                <td>
                                                    <span class="badge badge-modern bg-light text-dark">
                                                        <?= date('M j, Y', strtotime($user['created_at'])) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <form method="POST" action="<?= BASE_PATH ?>/admin/approve-user" class="d-inline">
                                                            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                                            <button type="submit" class="btn btn-success btn-sm modern-btn">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        </form>
                                                        <form method="POST" action="<?= BASE_PATH ?>/admin/reject-user" class="d-inline">
                                                            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                                            <button type="submit" class="btn btn-danger btn-sm modern-btn">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="empty-state">
                                <i class="fas fa-check-circle"></i>
                                <h5>All Caught Up!</h5>
                                <p class="text-muted">No pending user approvals at the moment.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Libraries Overview -->
            <div class="col-lg-6">
                <div class="modern-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5><i class="fas fa-building me-2"></i>Libraries Overview</h5>
                        <a href="<?= BASE_PATH ?>/admin/libraries" class="btn btn-primary modern-btn btn-sm">Manage</a>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($libraries)): ?>
                            <div class="table-responsive">
                                <table class="table modern-table">
                                    <thead>
                                        <tr>
                                            <th><i class="fas fa-bookmark me-2"></i>Library</th>
                                            <th><i class="fas fa-tag me-2"></i>Type</th>
                                            <th><i class="fas fa-users me-2"></i>Staff</th>
                                            <th><i class="fas fa-signal me-2"></i>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($libraries as $library): ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="library-icon me-3">
                                                            <i class="fas fa-<?= $library['type'] === 'primary' ? 'school' : 'graduation-cap' ?>"></i>
                                                        </div>
                                                        <strong><?= htmlspecialchars($library['name']) ?></strong>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge badge-modern bg-<?= $library['type'] === 'primary' ? 'primary' : 'success' ?>">
                                                        <i class="fas fa-<?= $library['type'] === 'primary' ? 'child' : 'user-graduate' ?> me-1"></i>
                                                        <?= ucfirst($library['type']) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="fw-bold"><?= $library['total_librarians'] ?></span>
                                                    <small class="text-muted">librarian(s)</small>
                                                </td>
                                                <td>
                                                    <?php if ($library['total_librarians'] > 0): ?>
                                                        <span class="badge badge-modern bg-success">
                                                            <i class="fas fa-check-circle me-1"></i>Active
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="badge badge-modern bg-warning">
                                                            <i class="fas fa-exclamation-triangle me-1"></i>No Librarian
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="empty-state">
                                <i class="fas fa-building"></i>
                                <h5>No Libraries Yet</h5>
                                <p class="text-muted">Create your first library to get started.</p>
                                <a href="<?= BASE_PATH ?>/admin/create-library" class="btn btn-primary modern-btn">
                                    <i class="fas fa-plus me-2"></i>Create Library
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Library Analytics Section -->
        <?php if (!empty($libraries)): ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="modern-card">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                        <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Library Analytics & Insights</h5>
                        <div class="d-flex align-items-center gap-2">
                            <label class="mb-0 me-2 text-muted small">Filter by Library:</label>
                            <select id="libraryFilter" class="form-select form-select-sm modern-select" style="width: 250px;" onchange="filterByLibrary(this.value)">
                                <option value="0" <?= $selected_library == 0 ? 'selected' : '' ?>>All Libraries</option>
                                <?php foreach ($libraries as $lib): ?>
                                    <option value="<?= $lib['id'] ?>" <?= $selected_library == $lib['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($lib['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if ($selected_library > 0): ?>
                            <!-- Charts Row 1 -->
                            <div class="row g-4 mb-4">
                                <!-- Popular Books Chart -->
                                <div class="col-lg-6">
                                    <div class="chart-card">
                                        <div class="chart-header">
                                            <h6><i class="fas fa-fire me-2 text-danger"></i>Most Popular Books</h6>
                                            <small class="text-muted">Top 5 most borrowed books</small>
                                        </div>
                                        <div class="chart-body">
                                            <?php if (!empty($popular_books)): ?>
                                                <canvas id="popularBooksChart" height="280"></canvas>
                                            <?php else: ?>
                                                <div class="empty-chart-state">
                                                    <i class="fas fa-book fa-3x text-muted mb-3"></i>
                                                    <p class="text-muted">No borrowing data available yet</p>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Underutilized Books Chart -->
                                <div class="col-lg-6">
                                    <div class="chart-card">
                                        <div class="chart-header">
                                            <h6><i class="fas fa-exclamation-triangle me-2 text-warning"></i>Underutilized Educational Books</h6>
                                            <small class="text-muted">Books with fewer than 3 borrows</small>
                                        </div>
                                        <div class="chart-body">
                                            <?php if (!empty($underutilized_books)): ?>
                                                <canvas id="underutilizedBooksChart" height="280"></canvas>
                                            <?php else: ?>
                                                <div class="empty-chart-state">
                                                    <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                                                    <p class="text-muted">No underutilized books found</p>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Charts Row 2 -->
                            <div class="row g-4">
                                <!-- Class Activity Chart -->
                                <div class="col-lg-6">
                                    <div class="chart-card">
                                        <div class="chart-header">
                                            <h6><i class="fas fa-users me-2 text-primary"></i>Most Active Classes</h6>
                                            <small class="text-muted">Borrowing activity by class</small>
                                        </div>
                                        <div class="chart-body">
                                            <?php if (!empty($class_borrow_stats)): ?>
                                                <canvas id="classActivityChart" height="280"></canvas>
                                            <?php else: ?>
                                                <div class="empty-chart-state">
                                                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                                    <p class="text-muted">No class activity data available</p>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Borrowing Trends Chart -->
                                <div class="col-lg-6">
                                    <div class="chart-card">
                                        <div class="chart-header">
                                            <h6><i class="fas fa-chart-area me-2 text-success"></i>Borrowing Trends</h6>
                                            <small class="text-muted">Last 30 days</small>
                                        </div>
                                        <div class="chart-body">
                                            <?php if (!empty($borrowing_trends)): ?>
                                                <canvas id="borrowingTrendsChart" height="280"></canvas>
                                            <?php else: ?>
                                                <div class="empty-chart-state">
                                                    <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                                                    <p class="text-muted">No trend data available</p>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <!-- All Libraries View - Show instruction -->
                            <div class="text-center py-5">
                                <i class="fas fa-chart-bar fa-4x mb-3" style="color: var(--jacaranda-primary); opacity: 0.3;"></i>
                                <h5 class="mb-2">Select a Library to View Analytics</h5>
                                <p class="text-muted">Choose a specific library from the dropdown above to view detailed charts and insights.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Quick Access Configuration Cards -->
        <div class="row mt-4">
            <div class="col-12">
                <h5 class="mb-3"><i class="fas fa-cogs me-2"></i>Quick Configuration</h5>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="card modern-card border-primary">
                    <div class="card-body text-center py-4">
                        <div class="mb-3">
                            <i class="fas fa-calendar-alt fa-3x text-primary"></i>
                        </div>
                        <h5 class="card-title">Loan Period Settings</h5>
                        <p class="text-muted small mb-3">
                            Configure system-wide default loan period and per-library settings
                        </p>
                        <a href="<?= BASE_PATH ?>/admin/settings" class="btn btn-primary modern-btn btn-sm me-2">
                            <i class="fas fa-cog me-1"></i>System Settings
                        </a>
                        <a href="<?= BASE_PATH ?>/admin/libraries" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-building me-1"></i>Per-Library
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="card modern-card border-success">
                    <div class="card-body text-center py-4">
                        <div class="mb-3">
                            <i class="fas fa-user-shield fa-3x text-success"></i>
                        </div>
                        <h5 class="card-title">User Management</h5>
                        <p class="text-muted small mb-3">
                            Manage users, approve registrations, and assign librarians
                        </p>
                        <a href="<?= BASE_PATH ?>/admin/users" class="btn btn-success modern-btn btn-sm">
                            <i class="fas fa-users me-1"></i>Manage Users
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="card modern-card border-info">
                    <div class="card-body text-center py-4">
                        <div class="mb-3">
                            <i class="fas fa-building fa-3x text-info"></i>
                        </div>
                        <h5 class="card-title">Libraries & Books</h5>
                        <p class="text-muted small mb-3">
                            Manage libraries, configure settings, and view book inventory
                        </p>
                        <a href="<?= BASE_PATH ?>/admin/libraries" class="btn btn-info modern-btn btn-sm me-2">
                            <i class="fas fa-building me-1"></i>Libraries
                        </a>
                        <a href="<?= BASE_PATH ?>/admin/books" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-book me-1"></i>Books
                        </a>
                    </div>
                </div>
            </div>
        </div>
            </div>
            </div>

<?php include '../app/views/shared/layout-footer.php'; ?>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Library filter function
function filterByLibrary(libraryId) {
    window.location.href = '<?= BASE_PATH ?>/admin/dashboard?library=' + libraryId;
}

// Counter animation for statistics
document.addEventListener('DOMContentLoaded', function() {
    const counters = document.querySelectorAll('.stat-number');
    
    counters.forEach(counter => {
        const target = parseInt(counter.getAttribute('data-count'));
        const duration = 2000;
        const start = 0;
        const increment = target / (duration / 16);
        let current = start;
        
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            counter.textContent = Math.floor(current);
        }, 16);
    });

    <?php if ($selected_library > 0): ?>
    // Initialize Charts only if a library is selected
    
    // Popular Books Chart
    <?php if (!empty($popular_books)): ?>
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

    const popularBooksCtx = document.getElementById('popularBooksChart').getContext('2d');
    new Chart(popularBooksCtx, {
        type: 'bar',
        data: popularBooksData,
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
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
    <?php endif; ?>

    // Underutilized Books Chart
    <?php if (!empty($underutilized_books)): ?>
    const underutilizedBooksData = {
        labels: <?= json_encode(array_map(function($book) {
            return $book['title'] . ' (Class ' . $book['class_level'] . ')';
        }, $underutilized_books)) ?>,
        datasets: [{
            label: 'Number of Borrows',
            data: <?= json_encode(array_column($underutilized_books, 'borrow_count')) ?>,
            backgroundColor: function(context) {
                const value = context.parsed.x;
                if (value === 0) return 'rgba(239, 68, 68, 0.8)';
                if (value <= 2) return 'rgba(245, 158, 11, 0.8)';
                return 'rgba(156, 163, 175, 0.8)';
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

    const underutilizedBooksCtx = document.getElementById('underutilizedBooksChart').getContext('2d');
    new Chart(underutilizedBooksCtx, {
        type: 'bar',
        data: underutilizedBooksData,
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
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
    <?php endif; ?>

    // Class Activity Chart
    <?php if (!empty($class_borrow_stats)): ?>
    const classData = <?= json_encode($class_borrow_stats) ?>;
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
            maintainAspectRatio: false,
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
    <?php endif; ?>

    // Borrowing Trends Chart
    <?php if (!empty($borrowing_trends)): ?>
    const trendsData = <?= json_encode($borrowing_trends) ?>;
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
                pointHoverRadius: 7,
                pointHoverBackgroundColor: 'rgba(16, 185, 129, 1)',
                pointHoverBorderColor: '#fff',
                pointHoverBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
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
                        },
                        maxRotation: 45,
                        minRotation: 45
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
    <?php endif; ?>
    
    <?php endif; ?>
});
</script>

<style>
.avatar-circle {
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background: var(--jacaranda-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.375rem;
}

.library-icon {
    width: 16px;
    height: 16px;
    border-radius: 4px;
    background: linear-gradient(135deg, var(--jacaranda-primary), var(--jacaranda-secondary));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.5rem;
}

/* Modern Select Styling */
.modern-select {
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    padding: 0.5rem 2.5rem 0.5rem 1rem;
    font-weight: 600;
    color: #374151;
    background-color: white;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236366f1' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 1rem center;
    background-size: 12px;
    transition: all 0.3s ease;
    cursor: pointer;
}

.modern-select:focus {
    outline: none;
    border-color: var(--jacaranda-primary);
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
}

.modern-select:hover {
    border-color: var(--jacaranda-primary);
}

/* Chart Card Styling */
.chart-card {
    background: white;
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    height: 100%;
}

.chart-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
    transform: translateY(-2px);
}

.chart-header {
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #f3f4f6;
}

.chart-header h6 {
    margin: 0;
    font-size: 1rem;
    font-weight: 700;
    color: #1f2937;
    display: flex;
    align-items: center;
}

.chart-header small {
    display: block;
    margin-top: 0.25rem;
    font-size: 0.75rem;
}

.chart-body {
    position: relative;
    height: 280px;
}

.empty-chart-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    text-align: center;
}

.empty-chart-state i {
    opacity: 0.3;
}

.empty-chart-state p {
    margin: 0;
    font-size: 0.875rem;
}
</style>

<?php include '../app/views/shared/footer.php'; ?>