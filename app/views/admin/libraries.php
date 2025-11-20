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
        background: linear-gradient(135deg, #663399 0%, #8a4baf 100%);
    }
    
    .stat-card-gradient.success {
        background: linear-gradient(135deg, #8a4baf 0%, #a47cc6 100%);
    }
    
    .stat-card-gradient.warning {
        background: linear-gradient(135deg, #a47cc6 0%, #c9a0dc 100%);
    }
    
    .stat-card-gradient.info {
        background: linear-gradient(135deg, #9b7bb3 0%, #b399cc 100%);
    }
    
    .stat-card-gradient.secondary {
        background: linear-gradient(135deg, #7b5a9e 0%, #9b7bb3 100%);
    }
    
    .stat-card-gradient.teal {
        background: linear-gradient(135deg, #8a4baf 0%, #b399cc 100%);
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
                <h1 class="h3 mb-2">Library Management</h1>
                <p class="mb-0 text-muted"><i class="fas fa-building me-2" style="color: var(--jacaranda-primary);"></i>Manage libraries, locations, and librarian assignments</p>
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

        <?php if (isset($_SESSION['info'])): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="fas fa-info-circle me-2"></i><?= $_SESSION['info']; unset($_SESSION['info']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- PIN Confirmation Section -->
        <?php if (isset($_SESSION['awaiting_pin_confirmation']) && $_SESSION['awaiting_pin_confirmation'] === true): ?>
            <div class="card mb-4 border-0 shadow-lg" style="background: linear-gradient(135deg, #fff5f5 0%, #ffe0e0 100%);">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <div style="font-size: 4rem; color: #dc2626;">🔒</div>
                        <h4 class="fw-bold mt-3" style="color: #dc2626;">
                            <i class="fas fa-shield-alt me-2"></i>Library Deletion Confirmation Required
                        </h4>
                        <p class="text-muted mb-0">
                            A 6-digit confirmation PIN has been sent to your email address.
                            <br>Please enter it below to complete the library deletion.
                        </p>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <form method="POST" action="<?= BASE_PATH ?>/admin/confirm-library-deletion">
                                <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                                
                                <div class="mb-3">
                                    <label for="confirmation_pin" class="form-label fw-bold">
                                        <i class="fas fa-key me-2"></i>Confirmation PIN
                                    </label>
                                    <input type="text" 
                                           class="form-control form-control-lg text-center" 
                                           id="confirmation_pin" 
                                           name="confirmation_pin" 
                                           placeholder="Enter 6-digit PIN" 
                                           maxlength="6" 
                                           pattern="[0-9]{6}"
                                           style="letter-spacing: 0.5rem; font-size: 1.5rem; font-family: 'Courier New', monospace;"
                                           required 
                                           autofocus>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>This PIN expires in 10 minutes
                                    </small>
                                </div>

                                <div class="alert alert-warning border-0 shadow-sm" role="alert">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>Warning:</strong> This action cannot be undone. All library data, books, and borrowing history will be permanently deleted.
                                </div>

                                <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                                    <button type="submit" class="btn btn-danger btn-lg px-5" style="background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%); border: none;">
                                        <i class="fas fa-trash-alt me-2"></i>Confirm Deletion
                                    </button>
                                    <a href="<?= BASE_PATH ?>/admin/cancel-library-deletion" class="btn btn-secondary btn-lg px-5">
                                        <i class="fas fa-times me-2"></i>Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <p class="text-muted mb-0" style="font-size: 0.9rem;">
                            <i class="fas fa-envelope me-1"></i>Didn't receive the PIN? Check your spam folder or contact the system administrator.
                        </p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Library Statistics Cards -->
        <div class="row mb-4">
            <?php 
            $totalLibraries = count($libraries);
            $primaryLibraries = count(array_filter($libraries, function($l) { return $l['type'] === 'primary'; }));
            $secondaryLibraries = count(array_filter($libraries, function($l) { return $l['type'] === 'secondary'; }));
            $totalBooks = array_sum(array_column($libraries, 'total_books'));
            $totalLibrarians = array_sum(array_column($libraries, 'total_librarians'));

            $primaryCopies = 0;
            $primaryAvailableCopies = 0;
            $secondaryCopies = 0;
            $secondaryAvailableCopies = 0;
            foreach ($libraries as $lib) {
                if ($lib['type'] === 'primary') {
                    $primaryCopies += (int)$lib['total_copies'];
                    $primaryAvailableCopies += (int)$lib['available_copies'];
                } elseif ($lib['type'] === 'secondary') {
                    $secondaryCopies += (int)$lib['total_copies'];
                    $secondaryAvailableCopies += (int)$lib['available_copies'];
                }
            }
            ?>
            
            <div class="col-xl-2 col-md-4 mb-4">
                <div class="card stat-card-gradient primary h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1" style="opacity: 0.9;">Libraries</div>
                                <div class="h5 mb-0 font-weight-bold"><?php echo $totalLibraries; ?></div>
                            </div>
                        </div>
                        <i class="fas fa-building icon-bg"></i>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 mb-4">
                <div class="card stat-card-gradient success h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1" style="opacity: 0.9;">Books</div>
                                <div class="h5 mb-0 font-weight-bold"><?php echo $totalBooks; ?></div>
                            </div>
                        </div>
                        <i class="fas fa-book icon-bg"></i>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 mb-4">
                <div class="card stat-card-gradient warning h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1" style="opacity: 0.9;">Primary Copies</div>
                                <div class="h6 mb-0 font-weight-bold">
                                    <span class="badge bg-primary">Total: <?php echo $primaryCopies; ?></span><br>
                                    <span class="badge bg-success mt-1">Available: <?php echo $primaryAvailableCopies; ?></span>
                                </div>
                            </div>
                        </div>
                        <i class="fas fa-layer-group icon-bg"></i>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 mb-4">
                <div class="card stat-card-gradient info h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1" style="opacity: 0.9;">Secondary Copies</div>
                                <div class="h6 mb-0 font-weight-bold">
                                    <span class="badge bg-primary">Total: <?php echo $secondaryCopies; ?></span><br>
                                    <span class="badge bg-success mt-1">Available: <?php echo $secondaryAvailableCopies; ?></span>
                                </div>
                            </div>
                        </div>
                        <i class="fas fa-school icon-bg"></i>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 mb-4">
                <div class="card stat-card-gradient secondary h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1" style="opacity: 0.9;">Secondary</div>
                                <div class="h5 mb-0 font-weight-bold"><?php echo $secondaryLibraries; ?></div>
                            </div>
                        </div>
                        <i class="fas fa-graduation-cap icon-bg"></i>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 mb-4">
                <div class="card stat-card-gradient teal h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1" style="opacity: 0.9;">Librarians</div>
                                <div class="h5 mb-0 font-weight-bold"><?php echo $totalLibrarians; ?></div>
                            </div>
                        </div>
                        <i class="fas fa-user-tie icon-bg"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter -->
        <div class="card shadow mb-4">
            <div class="card-header py-3" style="background: linear-gradient(135deg, var(--jacaranda-primary) 0%, var(--jacaranda-secondary) 100%);">
                <h6 class="m-0 font-weight-bold text-white">
                    <i class="fas fa-search me-2"></i>Search & Filter Libraries
                </h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="liveSearch" class="form-label">
                            <i class="fas fa-search me-1"></i>Search (Live)
                        </label>
                        <input type="text" id="liveSearch" class="form-control" 
                               placeholder="Type to search library name or address..." 
                               autocomplete="off">
                    </div>
                    
                    <div class="col-md-3">
                        <label for="typeFilter" class="form-label">
                            <i class="fas fa-filter me-1"></i>Type
                        </label>
                        <select id="typeFilter" class="form-select">
                            <option value="">All Types</option>
                            <option value="primary">Primary</option>
                            <option value="secondary">Secondary</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label for="librarianFilter" class="form-label">
                            <i class="fas fa-user-tie me-1"></i>Librarian Status
                        </label>
                        <select id="librarianFilter" class="form-select">
                            <option value="">All Libraries</option>
                            <option value="assigned">Has Librarian</option>
                            <option value="unassigned">No Librarian</option>
                        </select>
                    </div>
                    
                    <div class="col-12">
                        <button type="button" id="clearFilters" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Clear Filters
                        </button>
                        <span class="ms-3 text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Showing <span id="libraryCount"><?php echo count($libraries); ?></span> of <?php echo count($libraries); ?> libraries
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Libraries Table -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, var(--jacaranda-primary) 0%, var(--jacaranda-secondary) 100%);">
                <h6 class="m-0 font-weight-bold text-white">System Libraries (<span id="totalCount"><?php echo count($libraries); ?></span> libraries)</h6>
                <button class="btn btn-sm btn-light" onclick="toggleLibrarianAssign()">
                    <i class="fas fa-user-plus me-1"></i>Assign Librarian
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead style="background: linear-gradient(135deg, var(--jacaranda-primary) 0%, var(--jacaranda-secondary) 100%); color: white;">
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
                        <tbody id="librariesTableBody">
                            <?php if (!empty($libraries)): ?>
                                <?php foreach ($libraries as $library): ?>
                                <tr class="library-row" 
                                    data-name="<?php echo strtolower(htmlspecialchars($library['name'])); ?>"
                                    data-address="<?php echo strtolower(htmlspecialchars($library['address'])); ?>"
                                    data-type="<?php echo $library['type']; ?>"
                                    data-has-librarian="<?php echo $library['total_librarians'] > 0 ? 'yes' : 'no'; ?>">
                                    <td><strong><?php echo htmlspecialchars($library['name']); ?></strong></td>
                                    <td>
                                        <span class="badge badge-purple">
                                            <?php echo ucfirst($library['type']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($library['address']); ?></td>
                                    <td>
                                        <span class="badge badge-purple-light">
                                            <?php echo $library['loan_period_days'] ?? 5; ?> days
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-<?php echo $library['total_librarians'] > 0 ? 'purple' : 'gray'; ?>">
                                            <?php echo $library['total_librarians']; ?> Librarians
                                        </span>
                                    </td>
                                    <td><?php echo $library['total_books']; ?></td>
                                    <td>
                                        <span class="badge badge-purple">Total: <?php echo $library['total_copies']; ?></span><br>
                                        <span class="badge badge-purple-light mt-1">Available: <?php echo $library['available_copies']; ?></span>
                                    </td>
                                    <td><?php echo $library['total_students']; ?></td>
                                    <td><?php echo date('M j, Y', strtotime($library['created_at'])); ?></td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="<?php echo BASE_PATH; ?>/admin/edit-library/<?php echo $library['id']; ?>" 
                                               class="btn btn-outline-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-outline-danger delete-library-btn" 
                                                    data-library-id="<?php echo $library['id']; ?>"
                                                    data-library-name="<?php echo htmlspecialchars($library['name']); ?>"
                                                    data-total-librarians="<?php echo $library['total_librarians']; ?>"
                                                    data-total-books="<?php echo $library['total_books']; ?>"
                                                    data-total-students="<?php echo $library['total_students']; ?>"
                                                    title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
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
        <div class="card shadow mb-4" id="librarianAssignCard" style="display: none; border: 2px solid var(--jacaranda-primary);">
            <div class="card-header py-3" style="background: linear-gradient(135deg, var(--jacaranda-primary) 0%, var(--jacaranda-secondary) 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-user-plus me-2"></i>Assign Librarian to Library
                    </h6>
                    <button type="button" class="btn btn-sm btn-light" onclick="toggleLibrarianAssign()">
                        <i class="fas fa-times me-1"></i>Close
                    </button>
                </div>
            </div>
            <div class="card-body">
                <?php if (!empty($available_librarians)): ?>
                    <div class="alert alert-info mb-3">
                        <i class="fas fa-info-circle me-2"></i>
                        You have <strong><?php echo count($available_librarians); ?></strong> librarian(s) available for assignment.
                    </div>
                    <form method="POST" action="<?php echo BASE_PATH; ?>/admin/assign-librarian" id="assignLibrarianForm">
                        <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="assign_library_id" class="form-label">
                                    <i class="fas fa-building me-1"></i>Select Library <span class="text-danger">*</span>
                                </label>
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
                                <label for="assign_librarian_id" class="form-label">
                                    <i class="fas fa-user-tie me-1"></i>Select Librarian <span class="text-danger">*</span>
                                </label>
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
                            <div class="col-md-4 d-flex align-items-end gap-2">
                                <button type="submit" class="btn" style="background: linear-gradient(135deg, var(--jacaranda-primary) 0%, var(--jacaranda-secondary) 100%); color: white;">
                                    <i class="fas fa-check me-1"></i>Assign Librarian
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="toggleLibrarianAssign()">
                                    <i class="fas fa-times me-1"></i>Cancel
                                </button>
                            </div>
                        </div>
                    </form>
                <?php else: ?>
                    <div class="alert alert-warning mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>
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
        <div class="modal-content" style="border: none; border-radius: 12px; overflow: hidden;">
            <div class="modal-header" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; border: none;">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>Delete Library
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding: 1.5rem;">
                <div id="deleteWarningContent">
                    <!-- Will be populated by JavaScript -->
                </div>
                
                <!-- Password Authentication Section -->
                <div id="passwordSection" style="display: none;">
                    <div class="alert alert-warning" style="background: linear-gradient(135deg, #fff4e6 0%, #ffe5b4 100%); border: none; border-radius: 8px;">
                        <i class="fas fa-shield-alt me-2"></i>
                        <strong>Authentication Required</strong><br>
                        <small>Please enter your password to confirm this critical action.</small>
                    </div>
                    <form method="POST" action="<?php echo BASE_PATH; ?>/admin/delete-library" id="deleteLibraryForm">
                        <input type="hidden" name="csrf_token" value="<?php echo Security::generateCSRFToken(); ?>">
                        <input type="hidden" name="id" id="deleteLibraryId">
                        
                        <div class="mb-3">
                            <label for="admin_password" class="form-label" style="font-weight: 600; color: #663399;">
                                <i class="fas fa-lock me-1"></i>Your Password
                            </label>
                            <input type="password" 
                                   class="form-control" 
                                   id="admin_password" 
                                   name="admin_password" 
                                   required
                                   placeholder="Enter your password"
                                   style="border-radius: 8px; border: 2px solid #e3e6f0; padding: 0.75rem;">
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer" style="border: none; background: #f8f9fc; padding: 1rem 1.5rem;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 8px; padding: 0.5rem 1.5rem;">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <button type="button" 
                        class="btn btn-danger" 
                        id="confirmDeleteBtn" 
                        style="display: none; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border: none; border-radius: 8px; padding: 0.5rem 1.5rem;">
                    <i class="fas fa-trash me-1"></i>Delete Library
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Live Search and Filter Functionality
document.addEventListener('DOMContentLoaded', function() {
    const liveSearch = document.getElementById('liveSearch');
    const typeFilter = document.getElementById('typeFilter');
    const librarianFilter = document.getElementById('librarianFilter');
    const clearFilters = document.getElementById('clearFilters');
    const libraryRows = document.querySelectorAll('.library-row');
    const libraryCount = document.getElementById('libraryCount');
    const totalCount = document.getElementById('totalCount');
    
    // Live search function
    function filterLibraries() {
        const searchTerm = liveSearch.value.toLowerCase();
        const typeValue = typeFilter.value;
        const librarianValue = librarianFilter.value;
        
        let visible = 0;
        
        libraryRows.forEach(row => {
            const name = row.dataset.name || '';
            const address = row.dataset.address || '';
            const type = row.dataset.type || '';
            const hasLibrarian = row.dataset.hasLibrarian || '';
            
            let matchesSearch = true;
            let matchesType = true;
            let matchesLibrarian = true;
            
            // Check search term
            if (searchTerm) {
                matchesSearch = name.includes(searchTerm) || address.includes(searchTerm);
            }
            
            // Check type filter
            if (typeValue) {
                matchesType = type === typeValue;
            }
            
            // Check librarian filter
            if (librarianValue === 'assigned') {
                matchesLibrarian = hasLibrarian === 'yes';
            } else if (librarianValue === 'unassigned') {
                matchesLibrarian = hasLibrarian === 'no';
            }
            
            // Show/hide row based on all filters
            if (matchesSearch && matchesType && matchesLibrarian) {
                row.style.display = '';
                visible++;
            } else {
                row.style.display = 'none';
            }
        });
        
        libraryCount.textContent = visible;
        totalCount.textContent = visible;
    }
    
    // Attach event listeners
    liveSearch.addEventListener('input', filterLibraries);
    typeFilter.addEventListener('change', filterLibraries);
    librarianFilter.addEventListener('change', filterLibraries);
    
    // Clear all filters
    clearFilters.addEventListener('click', function() {
        liveSearch.value = '';
        typeFilter.value = '';
        librarianFilter.value = '';
        filterLibraries();
    });
    
    // Attach delete button listeners
    document.querySelectorAll('.delete-library-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const libraryId = parseInt(this.dataset.libraryId);
            const libraryName = this.dataset.libraryName;
            const totalLibrarians = parseInt(this.dataset.totalLibrarians);
            const totalBooks = parseInt(this.dataset.totalBooks);
            const totalStudents = parseInt(this.dataset.totalStudents);
            
            confirmLibraryDelete(libraryId, libraryName, totalLibrarians, totalBooks, totalStudents);
            
            return false;
        });
    });
});


function toggleLibrarianAssign() {
    const card = document.getElementById('librarianAssignCard');
    if (card.style.display === 'none' || card.style.display === '') {
        card.style.display = 'block';
        card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    } else {
        card.style.display = 'none';
    }
}

function viewLibrary(library) {
    const content = `
        <div class="row">
            <div class="col-md-6">
                <h6 style="color: #663399; font-weight: 600;"><i class="fas fa-building me-2"></i>Basic Information</h6>
                <table class="table table-sm">
                    <tr><td><strong>Name:</strong></td><td>${library.name}</td></tr>
                    <tr><td><strong>Type:</strong></td><td><span class="badge badge-purple">${library.type.charAt(0).toUpperCase() + library.type.slice(1)} School</span></td></tr>
                    <tr><td><strong>Address:</strong></td><td>${library.address}</td></tr>
                    ${library.phone ? `<tr><td><strong>Phone:</strong></td><td>${library.phone}</td></tr>` : ''}
                    ${library.email ? `<tr><td><strong>Email:</strong></td><td>${library.email}</td></tr>` : ''}
                    <tr><td><strong>Created:</strong></td><td>${new Date(library.created_at).toLocaleDateString()}</td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6 style="color: #663399; font-weight: 600;"><i class="fas fa-chart-bar me-2"></i>Statistics</h6>
                <table class="table table-sm">
                    <tr><td><strong>Librarians:</strong></td><td><span class="badge badge-${library.total_librarians > 0 ? 'purple' : 'gray'}">${library.total_librarians}</span></td></tr>
                    <tr><td><strong>Books:</strong></td><td><span class="badge badge-purple-light">${library.total_books}</span></td></tr>
                    <tr><td><strong>Students:</strong></td><td><span class="badge badge-purple-light">${library.total_students}</span></td></tr>
                </table>
            </div>
        </div>
        ${library.description ? `
        <div class="row mt-3">
            <div class="col-12">
                <h6 style="color: #663399; font-weight: 600;"><i class="fas fa-info-circle me-2"></i>Description</h6>
                <p class="text-muted">${library.description}</p>
            </div>
        </div>` : ''}
    `;
    
    document.getElementById('libraryDetailsContent').innerHTML = content;
    new bootstrap.Modal(document.getElementById('viewLibraryModal')).show();
}

function confirmLibraryDelete(libraryId, libraryName, totalLibrarians, totalBooks, totalStudents) {
    try {
        const deleteIdInput = document.getElementById('deleteLibraryId');
        if (!deleteIdInput) {
            return;
        }
        deleteIdInput.value = libraryId;
        
        // Build warning message based on what will be deleted
        let warningContent = '';
        let itemsList = [];
        
        if (totalLibrarians > 0) itemsList.push(`${totalLibrarians} librarian(s)`);
        if (totalBooks > 0) itemsList.push(`${totalBooks} book(s)`);
        if (totalStudents > 0) itemsList.push(`${totalStudents} student(s)`);
        
        if (itemsList.length > 0) {
            warningContent = `
                <div class="alert alert-danger" style="background: linear-gradient(135deg, #ffe5e5 0%, #ffcccc 100%); border: none; border-radius: 8px; margin-bottom: 1rem;">
                    <h6 style="color: #c0392b; font-weight: 600; margin-bottom: 0.5rem;">
                        <i class="fas fa-exclamation-triangle me-2"></i>Cascade Deletion Warning
                    </h6>
                    <p style="margin-bottom: 0.5rem; color: #555;">
                        Deleting library <strong>"${libraryName}"</strong> will also permanently delete:
                    </p>
                    <ul style="margin: 0; padding-left: 1.5rem; color: #555;">
                        ${itemsList.map(item => `<li><i class="fas fa-times-circle me-1" style="color: #c0392b;"></i>${item}</li>`).join('')}
                    </ul>
                    <p style="margin-top: 0.75rem; margin-bottom: 0; color: #c0392b; font-weight: 600;">
                        <i class="fas fa-exclamation-circle me-1"></i>This action cannot be undone!
                    </p>
                </div>
            `;
        } else {
            warningContent = `
                <div class="alert alert-warning" style="background: linear-gradient(135deg, #fff4e6 0%, #ffe5b4 100%); border: none; border-radius: 8px; margin-bottom: 1rem;">
                    <h6 style="color: #c0392b; font-weight: 600; margin-bottom: 0.5rem;">
                        <i class="fas fa-exclamation-circle me-2"></i>Permanent Deletion Warning
                    </h6>
                    <p style="margin: 0; color: #555;">
                        You are about to permanently delete library <strong>"${libraryName}"</strong>. 
                        This action cannot be undone.
                    </p>
                </div>
            `;
        }
        
        // Always show password section and delete button
        document.getElementById('passwordSection').style.display = 'block';
        document.getElementById('confirmDeleteBtn').style.display = 'inline-block';
        document.getElementById('confirmDeleteBtn').onclick = function() {
            const password = document.getElementById('admin_password').value;
            if (!password) {
                const passwordInput = document.getElementById('admin_password');
                passwordInput.classList.add('is-invalid');
                passwordInput.focus();
                return;
            }
            document.getElementById('deleteLibraryForm').submit();
        };
    
        document.getElementById('deleteWarningContent').innerHTML = warningContent;
        const modalElement = document.getElementById('deleteModal');
        if (modalElement) {
            new bootstrap.Modal(modalElement).show();
        }
    } catch (error) {
        // Silently fail
    }
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
.border-left-primary {
    border-left: 0.25rem solid #663399 !important;
}

.border-left-success {
    border-left: 0.25rem solid #8a4baf !important;
}

.border-left-info {
    border-left: 0.25rem solid #a47cc6 !important;
}

.border-left-warning {
    border-left: 0.25rem solid #c9a0dc !important;
}

.border-left-secondary {
    border-left: 0.25rem solid #9b7bb3 !important;
}

.border-left-dark {
    border-left: 0.25rem solid #553377 !important;
}

/* Enhanced Table Styling */
.table {
    border-collapse: separate !important;
    border-spacing: 0 !important;
    border-radius: 12px !important;
    overflow: hidden !important;
}

.table thead th {
    border: none !important;
    padding: 1rem !important;
    font-weight: 600 !important;
    text-transform: uppercase !important;
    font-size: 0.85rem !important;
    letter-spacing: 0.5px !important;
}

.table tbody td {
    border: none !important;
    border-top: 1px solid #f0f0f0 !important;
    padding: 1rem !important;
    vertical-align: middle !important;
    transition: all 0.3s ease !important;
}

.table-hover tbody tr {
    transition: all 0.3s ease !important;
}

.table-hover tbody tr:hover {
    background: linear-gradient(135deg, #f8f4fc 0%, #f0ebf7 100%) !important;
    transform: scale(1.005) !important;
    box-shadow: 0 4px 12px rgba(102, 51, 153, 0.08) !important;
}

/* Custom Badge Colors - Purple Theme */
.badge-purple {
    background: linear-gradient(135deg, #663399 0%, #8a4baf 100%) !important;
    color: white !important;
    padding: 0.4rem 0.8rem !important;
    border-radius: 6px !important;
    font-weight: 500 !important;
}

.badge-purple-light {
    background: linear-gradient(135deg, #a47cc6 0%, #c9a0dc 100%) !important;
    color: white !important;
    padding: 0.4rem 0.8rem !important;
    border-radius: 6px !important;
    font-weight: 500 !important;
}

.badge-gray {
    background: linear-gradient(135deg, #858796 0%, #9a9aad 100%) !important;
    color: white !important;
    padding: 0.4rem 0.8rem !important;
    border-radius: 6px !important;
    font-weight: 500 !important;
}

.badge {
    font-size: 0.75em;
}

.badge.fs-6 {
    font-size: 0.875rem !important;
}

/* Button Group Enhancements */
.btn-group .btn {
    margin-right: 2px;
    border-radius: 8px !important;
    transition: all 0.3s ease !important;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

.btn-group .btn:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
}

.btn-outline-primary {
    color: #663399 !important;
    border-color: #663399 !important;
}

.btn-outline-primary:hover {
    background: linear-gradient(135deg, #663399 0%, #8a4baf 100%) !important;
    border-color: #663399 !important;
    color: white !important;
}

.btn-outline-danger {
    color: #e74c3c !important;
    border-color: #e74c3c !important;
}

.btn-outline-danger:hover {
    background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%) !important;
    border-color: #e74c3c !important;
    color: white !important;
}

/* Card Enhancements */
.card {
    border: none !important;
    border-radius: 12px !important;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08) !important;
    overflow: hidden !important;
}

.card-header {
    border-radius: 12px 12px 0 0 !important;
}

.card-body {
    padding: 1.5rem !important;
}

.main-content {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%) !important;
    min-height: calc(100vh - 60px);
}

/* Table Responsive Container */
.table-responsive {
    border-radius: 12px !important;
    overflow: hidden !important;
}
</style>

<?php include '../app/views/shared/footer.php'; ?>