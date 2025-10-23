<?php include '../app/views/shared/header.php'; ?>
<?php include '../app/views/shared/navbar.php'; ?>
<?php include '../app/views/shared/layout-header.php'; ?>

<div class="main-content">
    <div class="container-fluid px-4" style="max-width: 900px;">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-user-edit me-2"></i>Edit User
                </h1>
                <p class="mb-0 text-muted">Update user information and permissions</p>
            </div>
            <a href="<?= BASE_PATH ?>/admin/users" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Users
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

        <!-- Edit User Form -->
        <div class="card shadow">
            <div class="card-header py-3" style="background: linear-gradient(135deg, #7c3aed 0%, #6366f1 100%); color: white;">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-user me-2"></i>User Information
                </h6>
            </div>
            <div class="card-body">
                <form method="POST" class="needs-validation" novalidate>
                    <!-- Account Information Section -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-id-card me-2"></i>Account Information
                        </h6>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="username" class="form-label">
                                    <i class="fas fa-user me-1"></i>Username <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="username" name="username" 
                                       value="<?= htmlspecialchars($user['username']) ?>" required>
                                <div class="invalid-feedback">Please enter a username.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="full_name" class="form-label">
                                    <i class="fas fa-id-badge me-1"></i>Full Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="full_name" name="full_name" 
                                       value="<?= htmlspecialchars($user['full_name']) ?>" required>
                                <div class="invalid-feedback">Please enter full name.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-1"></i>Email Address <span class="text-danger">*</span>
                                </label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?= htmlspecialchars($user['email']) ?>" required>
                                <div class="invalid-feedback">Please enter a valid email.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="role" class="form-label">
                                    <i class="fas fa-user-tag me-1"></i>Role <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="role" name="role" required onchange="toggleLibraryField()">
                                    <option value="">Select Role</option>
                                    <option value="super_admin" <?= $user['role'] === 'super_admin' ? 'selected' : '' ?>>Super Admin</option>
                                    <option value="librarian" <?= $user['role'] === 'librarian' ? 'selected' : '' ?>>Librarian</option>
                                </select>
                                <div class="invalid-feedback">Please select a role.</div>
                            </div>

                            <div class="col-md-6" id="libraryField" style="<?= $user['role'] !== 'librarian' ? 'display:none;' : '' ?>">
                                <label for="library_id" class="form-label">
                                    <i class="fas fa-building me-1"></i>Assigned Library
                                </label>
                                <select class="form-select" id="library_id" name="library_id">
                                    <option value="">Not Assigned</option>
                                    <?php foreach ($libraries as $library): ?>
                                        <option value="<?= $library['id'] ?>" <?= $user['library_id'] == $library['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($library['name']) ?> (<?= ucfirst($library['type']) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="status" class="form-label">
                                    <i class="fas fa-toggle-on me-1"></i>Status
                                </label>
                                <div class="form-control-plaintext">
                                    <?php if ($user['status'] === 'active'): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php elseif ($user['status'] === 'pending'): ?>
                                        <span class="badge bg-warning">Pending</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Inactive</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Password Update Section -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-lock me-2"></i>Change Password (Optional)
                        </h6>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Leave password fields empty to keep the current password.
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">
                                    <i class="fas fa-key me-1"></i>New Password
                                </label>
                                <input type="password" class="form-control" id="password" name="password" 
                                       minlength="6" placeholder="Enter new password">
                                <small class="text-muted">Minimum 6 characters</small>
                            </div>

                            <div class="col-md-6">
                                <label for="confirm_password" class="form-label">
                                    <i class="fas fa-key me-1"></i>Confirm New Password
                                </label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                                       minlength="6" placeholder="Confirm new password">
                            </div>
                        </div>
                    </div>

                    <!-- User Metadata -->
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">
                            <i class="fas fa-info-circle me-2"></i>Account Metadata
                        </h6>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-2">
                                    <strong>User ID:</strong> #<?= $user['id'] ?>
                                </p>
                                <p class="mb-2">
                                    <strong>Created:</strong> <?= date('F j, Y g:i A', strtotime($user['created_at'])) ?>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <?php if ($user['approved_by']): ?>
                                    <p class="mb-2">
                                        <strong>Approved By:</strong> User #<?= $user['approved_by'] ?>
                                    </p>
                                    <p class="mb-2">
                                        <strong>Approved At:</strong> <?= $user['approved_at'] ? date('F j, Y g:i A', strtotime($user['approved_at'])) : 'N/A' ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="d-flex justify-content-between">
                        <a href="<?= BASE_PATH ?>/admin/users" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Bootstrap form validation
(function() {
    'use strict';
    var forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();

// Toggle library field based on role
function toggleLibraryField() {
    const role = document.getElementById('role').value;
    const libraryField = document.getElementById('libraryField');
    
    if (role === 'librarian') {
        libraryField.style.display = 'block';
    } else {
        libraryField.style.display = 'none';
        document.getElementById('library_id').value = '';
    }
}

// Password match validation
document.getElementById('confirm_password').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmPassword = this.value;
    
    if (password !== confirmPassword) {
        this.setCustomValidity('Passwords do not match');
    } else {
        this.setCustomValidity('');
    }
});

// Auto-dismiss alerts after 5 seconds
setTimeout(function() {
    var alerts = document.querySelectorAll('.alert');
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

.card {
    border: none;
    border-radius: 12px;
}

.form-label {
    font-weight: 600;
    color: #374151;
    font-size: 0.9rem;
}

.form-control, .form-select {
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    padding: 0.6rem 0.75rem;
}

.form-control:focus, .form-select:focus {
    border-color: #7c3aed;
    box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
}

.btn {
    padding: 0.6rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
}

.alert {
    border-radius: 8px;
    border: none;
}
</style>

<?php include '../app/views/shared/footer.php'; ?>
