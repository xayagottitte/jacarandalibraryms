<?php include '../app/views/shared/header.php'; ?>
<?php include '../app/views/shared/navbar.php'; ?>
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
        <!-- Page Header -->
        <div class="page-header d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-2">User Management</h1>
                <p class="mb-0 text-muted"><i class="fas fa-users-cog me-2" style="color: var(--jacaranda-primary);"></i>Manage system users, roles, and permissions</p>
            </div>
            <a href="<?php echo BASE_PATH; ?>/admin/create-user" class="btn btn-primary">
                <i class="fas fa-user-plus me-2"></i>Add New User
            </a>
        </div>

        <!-- Flash Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i><?= $_SESSION['success']; unset($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i><?= $_SESSION['error']; unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- User Statistics Cards -->
        <div class="row mb-4">
            <?php 
            $totalUsers = count($users);
            $activeUsers = count(array_filter($users, function($u) { return $u['status'] === 'active'; }));
            $pendingUsers = count(array_filter($users, function($u) { return $u['status'] === 'pending'; }));
            $librarians = count(array_filter($users, function($u) { return $u['role'] === 'librarian'; }));
            $unassignedLibrarians = count(array_filter($users, function($u) { 
                return $u['role'] === 'librarian' && $u['status'] === 'active' && empty($u['library_id']); 
            }));
            ?>
            
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card-gradient primary h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1" style="opacity: 0.9;">Total Users</div>
                                <div class="h5 mb-0 font-weight-bold"><?php echo $totalUsers; ?></div>
                            </div>
                        </div>
                        <i class="fas fa-users icon-bg"></i>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card-gradient success h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1" style="opacity: 0.9;">Active Users</div>
                                <div class="h5 mb-0 font-weight-bold"><?php echo $activeUsers; ?></div>
                            </div>
                        </div>
                        <i class="fas fa-user-check icon-bg"></i>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card-gradient warning h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1" style="opacity: 0.9;">Pending</div>
                                <div class="h5 mb-0 font-weight-bold"><?php echo $pendingUsers; ?></div>
                            </div>
                        </div>
                        <i class="fas fa-user-clock icon-bg"></i>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card-gradient info h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1" style="opacity: 0.9;">Librarians</div>
                                <div class="h5 mb-0 font-weight-bold"><?php echo $librarians; ?></div>
                            </div>
                        </div>
                        <i class="fas fa-user-tie icon-bg"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Warning Alert for Unassigned Librarians -->
        <?php if ($unassignedLibrarians > 0): ?>
            <div class="alert alert-warning alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                <div>
                    <strong>Action Required!</strong> You have <strong><?php echo $unassignedLibrarians; ?></strong> active librarian(s) without an assigned library.
                    <br>
                    <small>Librarians cannot login without a library assignment. Please use the pulsing <i class="fas fa-building text-warning"></i> button to assign libraries.</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Search and Filter -->
        <div class="modern-card shadow mb-4">
            <div class="card-header py-3 d-flex align-items-center" style="background: linear-gradient(135deg, var(--jacaranda-primary) 0%, var(--jacaranda-secondary) 100%);">
                <i class="fas fa-search me-2 text-white"></i>
                <h6 class="m-0 font-weight-bold text-white">Search & Filter Users</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="liveSearch" class="form-label">
                            <i class="fas fa-search me-1"></i>Search (Live)
                        </label>
                        <input type="text" id="liveSearch" class="form-control modern-input" 
                               placeholder="Type to search username, name, or email..." 
                               autocomplete="off">
                        <small class="text-muted">Search updates as you type</small>
                    </div>
                    
                    <div class="col-md-2">
                        <label for="roleFilter" class="form-label">
                            <i class="fas fa-user-tag me-1"></i>Role
                        </label>
                        <select id="roleFilter" class="form-select modern-select">
                            <option value="">All Roles</option>
                            <option value="super_admin">Super Admin</option>
                            <option value="librarian">Librarian</option>
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <label for="statusFilter" class="form-label">
                            <i class="fas fa-toggle-on me-1"></i>Status
                        </label>
                        <select id="statusFilter" class="form-select modern-select">
                            <option value="">All Status</option>
                            <option value="active">Active</option>
                            <option value="pending">Pending</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <label for="libraryFilter" class="form-label">
                            <i class="fas fa-building me-1"></i>Library
                        </label>
                        <select id="libraryFilter" class="form-select modern-select">
                            <option value="">All Libraries</option>
                            <?php foreach ($libraries as $library): ?>
                                <option value="<?php echo $library['id']; ?>">
                                    <?php echo htmlspecialchars($library['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" id="clearFilters" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-times me-1"></i>Clear All
                        </button>
                    </div>
                </div>
                
                <div id="searchResultsCount" class="mt-3 text-muted small" style="display: none;">
                    <i class="fas fa-info-circle me-1"></i>
                    Showing <strong><span id="visibleCount">0</span></strong> of <strong><span id="totalCount">0</span></strong> users
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="modern-card shadow mb-4">
            <div class="card-header py-3 d-flex align-items-center" style="background: linear-gradient(135deg, var(--jacaranda-primary) 0%, var(--jacaranda-secondary) 100%);">
                <i class="fas fa-users me-2 text-white"></i>
                <h6 class="m-0 font-weight-bold text-white">System Users (<span id="userCount"><?php echo count($users); ?></span> users)</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table modern-table" width="100%" cellspacing="0">
                        <thead style="background: linear-gradient(135deg, var(--jacaranda-primary) 0%, var(--jacaranda-secondary) 100%);">
                            <tr>
                                <th><i class="fas fa-user me-2"></i>Username</th>
                                <th><i class="fas fa-id-badge me-2"></i>Full Name</th>
                                <th><i class="fas fa-envelope me-2"></i>Email</th>
                                <th><i class="fas fa-user-tag me-2"></i>Role</th>
                                <th><i class="fas fa-building me-2"></i>Library</th>
                                <th><i class="fas fa-toggle-on me-2"></i>Status</th>
                                <th><i class="fas fa-calendar me-2"></i>Registered</th>
                                <th><i class="fas fa-cogs me-2"></i>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="usersTableBody">
                            <?php if (!empty($users)): ?>
                                <?php foreach ($users as $user): ?>
                                <tr class="user-row" 
                                    data-username="<?php echo strtolower(htmlspecialchars($user['username'])); ?>"
                                    data-fullname="<?php echo strtolower(htmlspecialchars($user['full_name'] ?? '')); ?>"
                                    data-email="<?php echo strtolower(htmlspecialchars($user['email'])); ?>"
                                    data-role="<?php echo $user['role']; ?>"
                                    data-status="<?php echo $user['status']; ?>"
                                    data-library="<?php echo $user['library_id'] ?? ''; ?>">
                                    <td>
                                        <strong><?php echo htmlspecialchars($user['username']); ?></strong>
                                        <?php if ($user['id'] == $_SESSION['user_id']): ?>
                                            <span class="badge ms-1" style="background: var(--jacaranda-primary); color: white;">You</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($user['full_name'] ?? 'N/A'); ?></td>
                                    <td>
                                        <a href="mailto:<?php echo htmlspecialchars($user['email']); ?>" class="text-decoration-none">
                                            <?php echo htmlspecialchars($user['email']); ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?php if ($user['role'] === 'super_admin'): ?>
                                            <span class="badge badge-modern bg-danger">
                                                <i class="fas fa-crown me-1"></i>Super Admin
                                            </span>
                                        <?php elseif ($user['role'] === 'librarian'): ?>
                                            <span class="badge badge-modern" style="background: var(--jacaranda-primary); color: white;">
                                                <i class="fas fa-user-tie me-1"></i>Librarian
                                            </span>
                                        <?php else: ?>
                                            <span class="badge badge-modern bg-secondary"><?php echo ucfirst($user['role']); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($user['library_name']): ?>
                                            <span class="badge badge-modern" style="background: var(--gradient-info); color: white;">
                                                <i class="fas fa-building me-1"></i><?php echo htmlspecialchars($user['library_name']); ?>
                                            </span>
                                        <?php else: ?>
                                            <?php if ($user['role'] === 'librarian' && $user['status'] === 'active'): ?>
                                                <span class="badge badge-modern bg-warning">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>Not Assigned
                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted"><i class="fas fa-minus"></i></span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($user['status'] === 'active'): ?>
                                            <span class="badge badge-modern bg-success">
                                                <i class="fas fa-check-circle me-1"></i>Active
                                            </span>
                                        <?php elseif ($user['status'] === 'pending'): ?>
                                            <span class="badge badge-modern bg-warning">
                                                <i class="fas fa-clock me-1"></i>Pending
                                            </span>
                                        <?php else: ?>
                                            <span class="badge badge-modern bg-danger">
                                                <i class="fas fa-ban me-1"></i>Inactive
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo date('M j, Y', strtotime($user['created_at'])); ?></td>
                                    <td>
                                        <div class="action-buttons d-flex gap-1 flex-wrap" style="min-width: 200px;">
                                            <?php if ($user['status'] === 'pending' && $user['role'] === 'librarian'): ?>
                                                <!-- Approve Button -->
                                                <form method="POST" action="<?php echo BASE_PATH; ?>/admin/approve-user" class="d-inline">
                                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                    <button type="submit" class="btn btn-sm btn-success" title="Approve User">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                <!-- Reject Button -->
                                                <button type="button" class="btn btn-sm btn-danger" 
                                                        onclick="confirmReject(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars($user['username']); ?>')" 
                                                        title="Reject User">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            <?php else: ?>
                                                <!-- Edit Button - Available for ALL users including super admins -->
                                                <a href="<?php echo BASE_PATH; ?>/admin/edit-user/<?php echo $user['id']; ?>" 
                                                   class="btn btn-sm modern-btn" style="background: var(--jacaranda-primary); color: white;" title="Edit User">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                <!-- Assign Library Button (if librarian without library) -->
                                                <?php if ($user['role'] === 'librarian' && empty($user['library_id'])): ?>
                                                    <button type="button" class="btn btn-sm btn-warning pulse-button" 
                                                            onclick="assignLibrary(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars($user['username']); ?>')" 
                                                            title="⚠️ ASSIGN LIBRARY REQUIRED - User cannot login without a library">
                                                        <i class="fas fa-building"></i>
                                                    </button>
                                                <?php endif; ?>
                                                
                                                <!-- Status Toggle Button - Available for ALL users including super admins -->
                                                <?php if ($user['status'] === 'active'): ?>
                                                    <button type="button" class="btn btn-sm btn-warning btn-deactivate" 
                                                            data-user-id="<?php echo $user['id']; ?>"
                                                            data-username="<?php echo htmlspecialchars($user['username']); ?>"
                                                            data-role="<?php echo $user['role']; ?>"
                                                            title="Deactivate User">
                                                        <i class="fas fa-user-slash"></i>
                                                    </button>
                                                <?php elseif ($user['status'] === 'inactive'): ?>
                                                    <form method="POST" action="<?php echo BASE_PATH; ?>/admin/activate-user" class="d-inline">
                                                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                        <button type="submit" class="btn btn-sm btn-success" title="Activate User">
                                                            <i class="fas fa-user-check"></i>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                                
                                                <!-- Delete Button - Available for ALL users including super admins -->
                                                <button type="button" class="btn btn-sm btn-danger btn-delete" 
                                                        data-user-id="<?php echo $user['id']; ?>"
                                                        data-username="<?php echo htmlspecialchars($user['username']); ?>"
                                                        data-role="<?php echo $user['role']; ?>"
                                                        title="Delete User">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                            <tr id="noUsersRow">
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="fas fa-users fa-3x mb-3 text-gray-300"></i>
                                    <p>No users found matching your criteria.</p>
                                    <a href="<?php echo BASE_PATH; ?>/admin/create-user" class="btn btn-primary modern-btn">
                                        <i class="fas fa-user-plus me-2"></i>Add First User
                                    </a>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject User Confirmation Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to reject user "<span id="rejectUsername"></span>"?</p>
                <p class="text-danger"><i class="fas fa-exclamation-triangle"></i> This action will delete the user account.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="<?php echo BASE_PATH; ?>/admin/reject-user" style="display: inline;">
                    <input type="hidden" name="user_id" id="rejectUserId">
                    <button type="submit" class="btn btn-danger">Reject User</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Deactivate User Confirmation Modal -->
<div class="modal fade" id="deactivateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title">
                    <i class="fas fa-user-slash me-2"></i>Deactivate User
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to deactivate <strong><span id="deactivateUsername"></span></strong>?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>This user will not be able to log in until reactivated.</strong>
                    <div id="superAdminWarning" style="display: none;" class="mt-2">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        This is a Super Admin account. Deactivating will remove their administrative access.
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="<?php echo BASE_PATH; ?>/admin/deactivate-user" id="deactivateForm" style="display: inline;">
                    <input type="hidden" name="user_id" id="deactivateUserId">
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-user-slash me-2"></i>Deactivate User
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete User Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-trash me-2"></i>Delete User
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to permanently delete <strong><span id="deleteUsername"></span></strong>?</p>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>This action cannot be undone!</strong>
                    <div id="deleteSuperAdminWarning" style="display: none;" class="mt-2">
                        <i class="fas fa-crown me-2"></i>
                        This is a Super Admin account. Deleting will permanently remove their administrative access and all associated data.
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="<?php echo BASE_PATH; ?>/admin/delete-user" id="deleteForm" style="display: inline;">
                    <input type="hidden" name="user_id" id="deleteUserId">
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Delete Permanently
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Assign Library Modal -->
<div class="modal fade" id="assignLibraryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--jacaranda-primary) 0%, var(--jacaranda-secondary) 100%); color: white;">
                <h5 class="modal-title">
                    <i class="fas fa-building me-2"></i>Assign Library
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Important:</strong> Librarians cannot login without an assigned library. Please assign one now.
                </div>
                <p>Assign library to user <strong><span id="assignUsername"></span></strong>:</p>
                <form method="POST" action="<?php echo BASE_PATH; ?>/admin/assign-library" id="assignLibraryForm">
                    <input type="hidden" name="user_id" id="assignUserId">
                    <div class="mb-3">
                        <label for="library_id" class="form-label">
                            <i class="fas fa-building me-1"></i>Select Library <span class="text-danger">*</span>
                        </label>
                        <select name="library_id" id="library_id" class="form-select" required>
                            <option value="">Choose a library...</option>
                            <?php foreach ($libraries as $library): ?>
                                <option value="<?php echo $library['id']; ?>">
                                    <?php echo htmlspecialchars($library['name']); ?> (<?php echo ucfirst($library['type']); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="assignLibraryForm" class="btn" style="background: linear-gradient(135deg, var(--jacaranda-primary) 0%, var(--jacaranda-secondary) 100%); color: white;">
                    <i class="fas fa-check me-1"></i>Assign Library
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Live Search and Filter Functionality
document.addEventListener('DOMContentLoaded', function() {
    const liveSearch = document.getElementById('liveSearch');
    const roleFilter = document.getElementById('roleFilter');
    const statusFilter = document.getElementById('statusFilter');
    const libraryFilter = document.getElementById('libraryFilter');
    const clearFilters = document.getElementById('clearFilters');
    const userRows = document.querySelectorAll('.user-row');
    const searchResultsCount = document.getElementById('searchResultsCount');
    const visibleCount = document.getElementById('visibleCount');
    const totalCount = document.getElementById('totalCount');
    const userCount = document.getElementById('userCount');
    
    totalCount.textContent = userRows.length;
    
    // Deactivate button event listeners
    const deactivateButtons = document.querySelectorAll('.btn-deactivate');
    deactivateButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const userId = this.dataset.userId;
            const username = this.dataset.username;
            const role = this.dataset.role;
            confirmDeactivate(userId, username, role);
        });
    });
    
    // Delete button event listeners
    const deleteButtons = document.querySelectorAll('.btn-delete');
    deleteButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const userId = this.dataset.userId;
            const username = this.dataset.username;
            const role = this.dataset.role;
            if (userId && username && role) {
                confirmDeleteUser(userId, username, role);
            }
        });
    });
    
    // Live search as you type
    function filterUsers() {
        const searchTerm = liveSearch.value.toLowerCase();
        const roleValue = roleFilter.value;
        const statusValue = statusFilter.value;
        const libraryValue = libraryFilter.value;
        
        let visible = 0;
        
        userRows.forEach(row => {
            const username = row.dataset.username || '';
            const fullname = row.dataset.fullname || '';
            const email = row.dataset.email || '';
            const role = row.dataset.role || '';
            const status = row.dataset.status || '';
            const library = row.dataset.library || '';
            
            // Check search term
            const matchesSearch = !searchTerm || 
                username.includes(searchTerm) || 
                fullname.includes(searchTerm) || 
                email.includes(searchTerm);
            
            // Check role filter
            const matchesRole = !roleValue || role === roleValue;
            
            // Check status filter
            const matchesStatus = !statusValue || status === statusValue;
            
            // Check library filter
            const matchesLibrary = !libraryValue || library === libraryValue;
            
            // Show/hide row
            if (matchesSearch && matchesRole && matchesStatus && matchesLibrary) {
                row.style.display = '';
                visible++;
            } else {
                row.style.display = 'none';
            }
        });
        
        // Update counts
        visibleCount.textContent = visible;
        userCount.textContent = visible;
        
        // Show/hide results count
        if (searchTerm || roleValue || statusValue || libraryValue) {
            searchResultsCount.style.display = 'block';
        } else {
            searchResultsCount.style.display = 'none';
        }
        
        // Show no results message
        const noUsersRow = document.getElementById('noUsersRow');
        if (visible === 0 && userRows.length > 0) {
            if (!document.getElementById('noResultsRow')) {
                const tbody = document.getElementById('usersTableBody');
                const noResultsRow = document.createElement('tr');
                noResultsRow.id = 'noResultsRow';
                noResultsRow.innerHTML = `
                    <td colspan="8" class="text-center text-muted py-4">
                        <i class="fas fa-search fa-3x mb-3 text-gray-300"></i>
                        <p>No users match your search criteria.</p>
                        <button class="btn btn-outline-secondary" onclick="document.getElementById('clearFilters').click()">
                            <i class="fas fa-times me-2"></i>Clear Filters
                        </button>
                    </td>
                `;
                tbody.appendChild(noResultsRow);
            }
        } else {
            const noResultsRow = document.getElementById('noResultsRow');
            if (noResultsRow) {
                noResultsRow.remove();
            }
        }
    }
    
    // Attach event listeners
    liveSearch.addEventListener('input', filterUsers);
    roleFilter.addEventListener('change', filterUsers);
    statusFilter.addEventListener('change', filterUsers);
    libraryFilter.addEventListener('change', filterUsers);
    
    // Clear all filters
    clearFilters.addEventListener('click', function() {
        liveSearch.value = '';
        roleFilter.value = '';
        statusFilter.value = '';
        libraryFilter.value = '';
        filterUsers();
    });
});

// Modal functions
function confirmReject(userId, username) {
    document.getElementById('rejectUserId').value = userId;
    document.getElementById('rejectUsername').textContent = username;
    new bootstrap.Modal(document.getElementById('rejectModal')).show();
}

function confirmDeactivate(userId, username, role) {
    try {
        const userIdInput = document.getElementById('deactivateUserId');
        const usernameSpan = document.getElementById('deactivateUsername');
        const superAdminWarning = document.getElementById('superAdminWarning');
        const modalElement = document.getElementById('deactivateModal');
        
        if (!userIdInput || !usernameSpan || !modalElement) {
            return;
        }
        
        userIdInput.value = userId;
        usernameSpan.textContent = username;
        
        // Show warning for super admin
        if (superAdminWarning) {
            superAdminWarning.style.display = (role === 'super_admin') ? 'block' : 'none';
        }
        
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
    } catch (error) {
        console.error('Error in confirmDeactivate:', error);
    }
}

function confirmDeleteUser(userId, username, role) {
    try {
        const userIdInput = document.getElementById('deleteUserId');
        const usernameSpan = document.getElementById('deleteUsername');
        const deleteSuperAdminWarning = document.getElementById('deleteSuperAdminWarning');
        const modalElement = document.getElementById('deleteModal');
        
        if (!userIdInput || !usernameSpan || !modalElement) {
            alert('Error: Modal elements not found. Please refresh the page.');
            return;
        }
        
        userIdInput.value = userId;
        usernameSpan.textContent = username;
        
        // Show warning for super admin
        if (deleteSuperAdminWarning) {
            deleteSuperAdminWarning.style.display = (role === 'super_admin') ? 'block' : 'none';
        }
        
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
    } catch (error) {
        console.error('Error in confirmDeleteUser:', error);
        alert('Error: ' + error.message);
    }
}

function assignLibrary(userId, username) {
    document.getElementById('assignUserId').value = userId;
    document.getElementById('assignUsername').textContent = username;
    new bootstrap.Modal(document.getElementById('assignLibraryModal')).show();
}

// Auto-dismiss alerts after 5 seconds
setTimeout(function() {
    var alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach(function(alert) {
        var bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
    });
}, 5000);
</script>

<style>
.main-content {
    background: #f8f9fc;
    min-height: calc(100vh - 60px);
    padding: 2rem 0;
}

/* Modern Card Styling */
.modern-card {
    border: none;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

.modern-card:hover {
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
}

.modern-card .card-header {
    border-radius: 16px 16px 0 0;
    border: none;
    padding: 1.25rem 1.5rem;
}

/* Modern Input Styling */
.modern-input, .modern-select {
    border: 2px solid #e5e7eb;
    border-radius: 10px;
    padding: 0.6rem 1rem;
    transition: all 0.3s ease;
    font-weight: 500;
}

.modern-input:focus, .modern-select:focus {
    border-color: var(--jacaranda-primary);
    box-shadow: 0 0 0 4px rgba(124, 58, 237, 0.1);
    outline: none;
}

/* Modern Table Styling */
.modern-table {
    border-collapse: separate;
    border-spacing: 0;
}

.modern-table thead th {
    color: white;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
    padding: 1rem;
    border: none;
}

.modern-table thead tr th:first-child {
    border-radius: 0;
}

.modern-table thead tr th:last-child {
    border-radius: 0;
}

.modern-table tbody tr {
    transition: all 0.2s ease;
    border-bottom: 1px solid #f3f4f6;
}

.modern-table tbody tr:hover {
    background: linear-gradient(to right, rgba(124, 58, 237, 0.03), rgba(99, 102, 241, 0.03));
    transform: translateX(2px);
}

.modern-table tbody td {
    padding: 1rem;
    vertical-align: middle;
    border: none;
}

/* Badge Styling */
.badge-modern {
    padding: 0.5rem 0.75rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.75rem;
    display: inline-flex;
    align-items: center;
}

/* Button Styling */
.modern-btn {
    border-radius: 8px;
    padding: 0.5rem 1rem;
    font-weight: 600;
    transition: all 0.3s ease;
    border: none;
}

.modern-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.action-buttons .btn {
    border-radius: 6px;
    transition: all 0.2s ease;
}

.action-buttons .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

/* Statistics Cards */
.border-left-primary {
    border-left: 4px solid var(--jacaranda-primary) !important;
    border-radius: 12px;
}

.border-left-success {
    border-left: 4px solid #10b981 !important;
    border-radius: 12px;
}

.border-left-info {
    border-left: 4px solid #06b6d4 !important;
    border-radius: 12px;
}

.border-left-warning {
    border-left: 4px solid #f59e0b !important;
    border-radius: 12px;
}

.card.shadow {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08) !important;
}

/* Modal Styling */
.modal-content {
    border-radius: 16px;
    border: none;
}

.modal-header {
    border-radius: 16px 16px 0 0;
    border-bottom: none;
    padding: 1.5rem;
}

.modal-footer {
    border-top: none;
    padding: 1.5rem;
}

/* Form Labels */
.form-label {
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
}

/* Alert Styling */
.alert {
    border-radius: 12px;
    border: none;
    padding: 1rem 1.25rem;
}

/* Empty State */
.text-gray-300 {
    color: #d1d5db;
}

/* Pulse animation for assign library button */
.pulse-button {
    animation: pulse 2s infinite;
    box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.7);
}

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.7);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(255, 193, 7, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(255, 193, 7, 0);
    }
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .action-buttons {
        flex-direction: column;
    }
    
    .action-buttons .btn {
        width: 100%;
        margin-bottom: 0.25rem;
    }
}
</style>

<?php include '../app/views/shared/footer.php'; ?>