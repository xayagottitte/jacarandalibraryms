<?php 
$title = "Create User - Multi-Library System";
include '../app/views/shared/header.php'; 
include '../app/views/shared/layout-header.php'; 
?>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="mb-0">Create New User</h3>
                <a href="<?= BASE_PATH ?>/admin/users" class="btn btn-secondary btn-sm">Back to Users</a>
            </div>

    <!-- Flash Messages -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

            <div class="card shadow-sm">
                <div class="card-body p-3">
                    <form method="POST" action="<?= BASE_PATH ?>/admin/create-user">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="username" class="form-label form-label-sm">Username <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm" id="username" name="username" required>
                                    <small class="form-text text-muted">Must be unique</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="full_name" class="form-label form-label-sm">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm" id="full_name" name="full_name" required>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="email" class="form-label form-label-sm">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control form-control-sm" id="email" name="email" required>
                                    <small class="form-text text-muted">Must be unique</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="phone" class="form-label form-label-sm">Phone Number</label>
                                    <input type="tel" class="form-control form-control-sm" id="phone" name="phone">
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="password" class="form-label form-label-sm">Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control form-control-sm" id="password" name="password" required minlength="6">
                                    <small class="form-text text-muted">Min 6 characters</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="confirm_password" class="form-label form-label-sm">Confirm Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control form-control-sm" id="confirm_password" name="confirm_password" required minlength="6">
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="role" class="form-label form-label-sm">Role <span class="text-danger">*</span></label>
                                    <select class="form-select form-select-sm" id="role" name="role" required>
                                        <option value="">Select Role</option>
                                        <option value="super_admin">Super Admin</option>
                                        <option value="librarian">Librarian</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 justify-content-end mt-3">
                            <a href="<?= BASE_PATH ?>/admin/users" class="btn btn-secondary btn-sm">Cancel</a>
                            <button type="submit" class="btn btn-primary btn-sm">Create User</button>
                        </div>
                    </form>
                </div>
            </div>
</main>

<script>
// Password confirmation validation
document.getElementById('confirm_password').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmPassword = this.value;
    
    if (password !== confirmPassword) {
        this.setCustomValidity('Passwords do not match');
    } else {
        this.setCustomValidity('');
    }
});

document.getElementById('password').addEventListener('input', function() {
    const confirmPassword = document.getElementById('confirm_password');
    if (confirmPassword.value) {
        confirmPassword.dispatchEvent(new Event('input'));
    }
});
</script>

<style>
.form-label-sm {
    font-size: 0.875rem;
    font-weight: 500;
    margin-bottom: 0.25rem;
}

.form-control-sm, .form-select-sm {
    font-size: 0.875rem;
    padding: 0.375rem 0.75rem;
}

.card {
    border: 1px solid #e3e6f0;
    border-radius: 0.35rem;
}

.modern-dashboard {
    background: #f8f9fc;
    min-height: calc(100vh - 60px);
}
</style>

<?php include '../app/views/shared/layout-footer.php'; ?>
<?php include '../app/views/shared/footer.php'; ?>