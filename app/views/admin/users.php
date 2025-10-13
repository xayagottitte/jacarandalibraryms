<?php include '../app/views/shared/header.php'; ?>
<?php include '../app/views/shared/navbar.php'; ?>
<?php include '../app/views/shared/layout-header.php'; ?>

<div class="main-content">
    <div class="container-fluid px-4" style="max-width: 80%;">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">User Management</h1>
                <p class="mb-0 text-muted">Manage system users, roles, and permissions</p>
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
            ?>
            
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Users</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalUsers; ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
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
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Active Users</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $activeUsers; ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-check fa-2x text-gray-300"></i>
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
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $pendingUsers; ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-clock fa-2x text-gray-300"></i>
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
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Librarians</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $librarians; ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-tie fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Search & Filter Users</h6>
            </div>
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" name="search" id="search" class="form-control" 
                               placeholder="Username, name, or email..." 
                               value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                    </div>
                    
                    <div class="col-md-2">
                        <label for="role" class="form-label">Role</label>
                        <select name="role" id="role" class="form-select">
                            <option value="">All Roles</option>
                            <option value="super_admin" <?php echo ($_GET['role'] ?? '') === 'super_admin' ? 'selected' : ''; ?>>Super Admin</option>
                            <option value="librarian" <?php echo ($_GET['role'] ?? '') === 'librarian' ? 'selected' : ''; ?>>Librarian</option>
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="active" <?php echo ($_GET['status'] ?? '') === 'active' ? 'selected' : ''; ?>>Active</option>
                            <option value="pending" <?php echo ($_GET['status'] ?? '') === 'pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="inactive" <?php echo ($_GET['status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <label for="library" class="form-label">Library</label>
                        <select name="library" id="library" class="form-select">
                            <option value="">All Libraries</option>
                            <?php foreach ($libraries as $library): ?>
                                <option value="<?php echo $library['id']; ?>" 
                                        <?php echo ($_GET['library'] ?? '') == $library['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($library['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-2 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i>Filter
                        </button>
                        <a href="<?php echo BASE_PATH; ?>/admin/users" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Clear
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Users Table -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">System Users (<?php echo count($users); ?> users)</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead class="table-light">
                            <tr>
                                <th>Username</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Library</th>
                                <th>Status</th>
                                <th>Registered</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($users)): ?>
                                <?php foreach ($users as $user): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars($user['username']); ?></strong>
                                    </td>
                                    <td><?php echo htmlspecialchars($user['full_name'] ?? 'N/A'); ?></td>
                                    <td>
                                        <a href="mailto:<?php echo htmlspecialchars($user['email']); ?>" class="text-decoration-none">
                                            <?php echo htmlspecialchars($user['email']); ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?php if ($user['role'] === 'super_admin'): ?>
                                            <span class="badge bg-danger">Super Admin</span>
                                        <?php elseif ($user['role'] === 'librarian'): ?>
                                            <span class="badge bg-primary">Librarian</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary"><?php echo ucfirst($user['role']); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($user['library_name']): ?>
                                            <span class="badge bg-info"><?php echo htmlspecialchars($user['library_name']); ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">Not Assigned</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($user['status'] === 'active'): ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php elseif ($user['status'] === 'pending'): ?>
                                            <span class="badge bg-warning">Pending</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo date('M j, Y', strtotime($user['created_at'])); ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
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
                                                <!-- Edit Button -->
                                                <a href="<?php echo BASE_PATH; ?>/admin/edit-user/<?php echo $user['id']; ?>" 
                                                   class="btn btn-sm btn-outline-primary" title="Edit User">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                <?php if ($user['role'] !== 'super_admin'): ?>
                                                    <!-- Assign Library Button (if librarian without library) -->
                                                    <?php if ($user['role'] === 'librarian' && empty($user['library_id'])): ?>
                                                        <button type="button" class="btn btn-sm btn-outline-info" 
                                                                onclick="assignLibrary(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars($user['username']); ?>')" 
                                                                title="Assign Library">
                                                            <i class="fas fa-building"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                    
                                                    <!-- Status Toggle Button -->
                                                    <?php if ($user['status'] === 'active'): ?>
                                                        <button type="button" class="btn btn-sm btn-outline-warning" 
                                                                onclick="confirmDeactivate(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars($user['username']); ?>')" 
                                                                title="Deactivate User">
                                                            <i class="fas fa-user-slash"></i>
                                                        </button>
                                                    <?php elseif ($user['status'] === 'inactive'): ?>
                                                        <form method="POST" action="<?php echo BASE_PATH; ?>/admin/activate-user" class="d-inline">
                                                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                            <button type="submit" class="btn btn-sm btn-outline-success" title="Activate User">
                                                                <i class="fas fa-user-check"></i>
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                    
                                                    <!-- Delete Button -->
                                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                                            onclick="confirmDelete(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars($user['username']); ?>')" 
                                                            title="Delete User">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="fas fa-users fa-3x mb-3 text-gray-300"></i>
                                    <p>No users found matching your criteria.</p>
                                    <a href="<?php echo BASE_PATH; ?>/admin/create-user" class="btn btn-primary">
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
            <div class="modal-header">
                <h5 class="modal-title">Deactivate User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to deactivate user "<span id="deactivateUsername"></span>"?</p>
                <p class="text-warning"><i class="fas fa-info-circle"></i> The user will not be able to log in until reactivated.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="<?php echo BASE_PATH; ?>/admin/deactivate-user" style="display: inline;">
                    <input type="hidden" name="user_id" id="deactivateUserId">
                    <button type="submit" class="btn btn-warning">Deactivate</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete User Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to permanently delete user "<span id="deleteUsername"></span>"?</p>
                <p class="text-danger"><i class="fas fa-exclamation-triangle"></i> This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="<?php echo BASE_PATH; ?>/admin/delete-user" style="display: inline;">
                    <input type="hidden" name="user_id" id="deleteUserId">
                    <button type="submit" class="btn btn-danger">Delete User</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Assign Library Modal -->
<div class="modal fade" id="assignLibraryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assign Library</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Assign library to user "<span id="assignUsername"></span>":</p>
                <form method="POST" action="<?php echo BASE_PATH; ?>/admin/assign-library" id="assignLibraryForm">
                    <input type="hidden" name="user_id" id="assignUserId">
                    <div class="mb-3">
                        <label for="library_id" class="form-label">Select Library</label>
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
                <button type="submit" form="assignLibraryForm" class="btn btn-primary">Assign Library</button>
            </div>
        </div>
    </div>
</div>

<script>
function confirmReject(userId, username) {
    document.getElementById('rejectUserId').value = userId;
    document.getElementById('rejectUsername').textContent = username;
    new bootstrap.Modal(document.getElementById('rejectModal')).show();
}

function confirmDeactivate(userId, username) {
    document.getElementById('deactivateUserId').value = userId;
    document.getElementById('deactivateUsername').textContent = username;
    new bootstrap.Modal(document.getElementById('deactivateModal')).show();
}

function confirmDelete(userId, username) {
    document.getElementById('deleteUserId').value = userId;
    document.getElementById('deleteUsername').textContent = username;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

function assignLibrary(userId, username) {
    document.getElementById('assignUserId').value = userId;
    document.getElementById('assignUsername').textContent = username;
    new bootstrap.Modal(document.getElementById('assignLibraryModal')).show();
}
</script>

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

.table-hover tbody tr:hover {
    background-color: rgba(0,0,0,.025);
}

.badge {
    font-size: 0.75em;
}

.btn-group .btn {
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

.main-content {
    background: #f8f9fc;
    min-height: calc(100vh - 60px);
}
</style>

<?php include '../app/views/shared/footer.php'; ?>