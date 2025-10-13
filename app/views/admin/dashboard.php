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
        padding: 0.75rem 1rem;
        font-size: 0.85rem;
    }

    .modern-table td {
        padding: 0.75rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f0f0f0;
        font-size: 0.85rem;
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

<script>
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
</style>

<?php include '../app/views/shared/footer.php'; ?>