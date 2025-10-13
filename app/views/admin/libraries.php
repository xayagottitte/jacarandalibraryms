<?php include '../app/views/shared/header.php'; ?>
<?php include '../app/views/shared/navbar.php'; ?>
<?php include '../app/views/shared/layout-header.php'; ?>

<div class="main-content">
    <div class="container-fluid px-4" style="max-width: 80%;">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">Library Management</h1>
                <p class="mb-0 text-muted">Manage libraries, locations, and librarian assignments</p>
            </div>
            <a href="<?php echo BASE_PATH; ?>/admin/create-library" class="btn btn-primary">
                <i class="fas fa-building me-2"></i>Add New Library
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

        <!-- Library Statistics Cards -->
        <div class="row mb-4">
            <?php 
            $totalLibraries = count($libraries);
            $primaryLibraries = count(array_filter($libraries, function($l) { return $l['type'] === 'primary'; }));
            $secondaryLibraries = count(array_filter($libraries, function($l) { return $l['type'] === 'secondary'; }));
            $totalBooks = array_sum(array_column($libraries, 'total_books'));
            $totalCopies = array_sum(array_column($libraries, 'total_copies'));
            $totalLibrarians = array_sum(array_column($libraries, 'total_librarians'));
            ?>
            
            <div class="col-xl-2 col-md-4 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Libraries</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalLibraries; ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-building fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Books</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalBooks; ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-book fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Copies</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalCopies; ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-layer-group fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Primary</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $primaryLibraries; ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-school fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 mb-4">
                <div class="card border-left-secondary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Secondary</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $secondaryLibraries; ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-graduation-cap fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 mb-4">
                <div class="card border-left-dark shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">Librarians</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalLibrarians; ?></div>
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
                <h6 class="m-0 font-weight-bold text-primary">Search & Filter Libraries</h6>
            </div>
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" name="search" id="search" class="form-control" 
                               placeholder="Library name or address..." 
                               value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                    </div>
                    
                    <div class="col-md-2">
                        <label for="type" class="form-label">Type</label>
                        <select name="type" id="type" class="form-select">
                            <option value="">All Types</option>
                            <option value="primary" <?php echo ($_GET['type'] ?? '') === 'primary' ? 'selected' : ''; ?>>Primary</option>
                            <option value="secondary" <?php echo ($_GET['type'] ?? '') === 'secondary' ? 'selected' : ''; ?>>Secondary</option>
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <label for="librarian_status" class="form-label">Librarian Status</label>
                        <select name="librarian_status" id="librarian_status" class="form-select">
                            <option value="">All Libraries</option>
                            <option value="assigned" <?php echo ($_GET['librarian_status'] ?? '') === 'assigned' ? 'selected' : ''; ?>>Has Librarian</option>
                            <option value="unassigned" <?php echo ($_GET['librarian_status'] ?? '') === 'unassigned' ? 'selected' : ''; ?>>No Librarian</option>
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <label for="sort_by" class="form-label">Sort By</label>
                        <select name="sort_by" id="sort_by" class="form-select">
                            <option value="name" <?php echo ($_GET['sort_by'] ?? 'name') === 'name' ? 'selected' : ''; ?>>Name</option>
                            <option value="type" <?php echo ($_GET['sort_by'] ?? '') === 'type' ? 'selected' : ''; ?>>Type</option>
                            <option value="created_at" <?php echo ($_GET['sort_by'] ?? '') === 'created_at' ? 'selected' : ''; ?>>Date Created</option>
                            <option value="total_books" <?php echo ($_GET['sort_by'] ?? '') === 'total_books' ? 'selected' : ''; ?>>Books Count</option>
                        </select>
                    </div>
                    
                    <div class="col-md-2 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i>Filter
                        </button>
                        <a href="<?php echo BASE_PATH; ?>/admin/libraries" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Clear
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Libraries Table -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">System Libraries (<?php echo count($libraries); ?> libraries)</h6>
                <button class="btn btn-sm btn-outline-primary" onclick="toggleLibrarianAssign()">
                    <i class="fas fa-user-plus me-1"></i>Assign Librarian
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Address</th>
                                <th>Loan Period</th>
                                <th>Librarians</th>
                                <th>Books</th>
                                <th>Copies</th>
                                <th>Students</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($libraries)): ?>
                                <?php foreach ($libraries as $library): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($library['name']); ?></strong></td>
                                    <td>
                                        <span class="badge bg-<?php echo $library['type'] === 'primary' ? 'primary' : 'success'; ?>">
                                            <?php echo ucfirst($library['type']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($library['address']); ?></td>
                                    <td>
                                        <span class="badge bg-info">
                                            <?php echo $library['loan_period_days'] ?? 5; ?> days
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php echo $library['total_librarians'] > 0 ? 'success' : 'warning'; ?>">
                                            <?php echo $library['total_librarians']; ?> Librarians
                                        </span>
                                    </td>
                                    <td><?php echo $library['total_books']; ?></td>
                                    <td><?php echo $library['total_copies']; ?></td>
                                    <td><?php echo $library['total_students']; ?></td>
                                    <td><?php echo date('M j, Y', strtotime($library['created_at'])); ?></td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="<?php echo BASE_PATH; ?>/admin/edit-library/<?php echo $library['id']; ?>" 
                                               class="btn btn-outline-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <?php if ($library['total_librarians'] == 0 && $library['total_books'] == 0 && $library['total_students'] == 0): ?>
                                                <button type="button" class="btn btn-outline-danger" 
                                                        onclick="confirmDelete(<?php echo $library['id']; ?>, '<?php echo htmlspecialchars($library['name']); ?>')" 
                                                        title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">
                                    <i class="fas fa-building fa-3x mb-3 text-gray-300"></i>
                                    <p>No libraries found matching your criteria.</p>
                                    <a href="<?php echo BASE_PATH; ?>/admin/create-library" class="btn btn-primary">
                                        <i class="fas fa-building me-2"></i>Add First Library
                                    </a>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Assign Librarian Section (Hidden by default) -->
        <div class="card shadow mb-4" id="librarianAssignCard" style="display: none;">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-user-plus me-2"></i>Assign Librarian to Library
                </h6>
            </div>
            <div class="card-body">
                <?php if (!empty($available_librarians)): ?>
                    <form method="POST" action="<?php echo BASE_PATH; ?>/admin/assign-librarian">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="library_id" class="form-label">Select Library</label>
                                <select class="form-select" id="assign_library_id" name="library_id" required>
                                    <option value="">Choose a library...</option>
                                    <?php foreach ($libraries as $library): ?>
                                        <option value="<?php echo $library['id']; ?>">
                                            <?php echo htmlspecialchars($library['name']); ?> 
                                            (<?php echo $library['total_librarians']; ?> librarian<?php echo $library['total_librarians'] != 1 ? 's' : ''; ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="librarian_id" class="form-label">Select Librarian</label>
                                <select class="form-select" id="assign_librarian_id" name="librarian_id" required>
                                    <option value="">Choose a librarian...</option>
                                    <?php foreach ($available_librarians as $librarian): ?>
                                        <option value="<?php echo $librarian['id']; ?>">
                                            <?php echo htmlspecialchars($librarian['full_name'] ?? $librarian['username']); ?> 
                                            (<?php echo htmlspecialchars($librarian['email']); ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-success me-2">
                                    <i class="fas fa-user-plus me-1"></i>Assign Librarian
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="toggleLibrarianAssign()">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </form>
                <?php else: ?>
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>No Available Librarians</strong><br>
                        All active librarians have been assigned to libraries. 
                        <a href="<?php echo BASE_PATH; ?>/admin/users" class="alert-link">Manage users</a> to create new librarians or unassign existing ones.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- View Library Details Modal -->
<div class="modal fade" id="viewLibraryModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-building me-2"></i>Library Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="libraryDetailsContent">
                    <!-- Content will be populated by JavaScript -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Library Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Library</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to permanently delete library "<span id="deleteLibraryName"></span>"?</p>
                <p class="text-danger"><i class="fas fa-exclamation-triangle"></i> This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="<?php echo BASE_PATH; ?>/admin/delete-library" style="display: inline;">
                    <input type="hidden" name="id" id="deleteLibraryId">
                    <button type="submit" class="btn btn-danger">Delete Library</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function toggleLibrarianAssign() {
    const card = document.getElementById('librarianAssignCard');
    if (card.style.display === 'none') {
        card.style.display = 'block';
        card.scrollIntoView({ behavior: 'smooth' });
    } else {
        card.style.display = 'none';
    }
}

function viewLibrary(library) {
    const content = `
        <div class="row">
            <div class="col-md-6">
                <h6 class="text-primary"><i class="fas fa-building me-2"></i>Basic Information</h6>
                <table class="table table-sm">
                    <tr><td><strong>Name:</strong></td><td>${library.name}</td></tr>
                    <tr><td><strong>Type:</strong></td><td><span class="badge bg-${library.type === 'primary' ? 'primary' : 'success'}">${library.type.charAt(0).toUpperCase() + library.type.slice(1)} School</span></td></tr>
                    <tr><td><strong>Address:</strong></td><td>${library.address}</td></tr>
                    ${library.phone ? `<tr><td><strong>Phone:</strong></td><td>${library.phone}</td></tr>` : ''}
                    ${library.email ? `<tr><td><strong>Email:</strong></td><td>${library.email}</td></tr>` : ''}
                    <tr><td><strong>Created:</strong></td><td>${new Date(library.created_at).toLocaleDateString()}</td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6 class="text-primary"><i class="fas fa-chart-bar me-2"></i>Statistics</h6>
                <table class="table table-sm">
                    <tr><td><strong>Librarians:</strong></td><td><span class="badge bg-${library.total_librarians > 0 ? 'success' : 'warning'}">${library.total_librarians}</span></td></tr>
                    <tr><td><strong>Books:</strong></td><td><span class="badge bg-info">${library.total_books}</span></td></tr>
                    <tr><td><strong>Students:</strong></td><td><span class="badge bg-secondary">${library.total_students}</span></td></tr>
                </table>
            </div>
        </div>
        ${library.description ? `
        <div class="row mt-3">
            <div class="col-12">
                <h6 class="text-primary"><i class="fas fa-info-circle me-2"></i>Description</h6>
                <p class="text-muted">${library.description}</p>
            </div>
        </div>` : ''}
    `;
    
    document.getElementById('libraryDetailsContent').innerHTML = content;
    new bootstrap.Modal(document.getElementById('viewLibraryModal')).show();
}

function confirmDelete(libraryId, libraryName) {
    document.getElementById('deleteLibraryId').value = libraryId;
    document.getElementById('deleteLibraryName').textContent = libraryName;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
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

.border-left-secondary {
    border-left: 0.25rem solid #858796 !important;
}

.border-left-secondary {
    border-left: 0.25rem solid #6c757d !important;
}

.border-left-dark {
    border-left: 0.25rem solid #343a40 !important;
}

.table-hover tbody tr:hover {
    background-color: rgba(0,0,0,.025);
}

.badge {
    font-size: 0.75em;
}

.badge.fs-6 {
    font-size: 0.875rem !important;
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



.card {
    border: 1px solid #e3e6f0;
}
</style>

<?php include '../app/views/shared/footer.php'; ?>