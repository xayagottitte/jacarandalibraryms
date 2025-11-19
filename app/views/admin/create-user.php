<?php 
$title = "Create User - Jacaranda Libraries";
include '../app/views/shared/header.php'; 
include '../app/views/shared/layout-header.php'; 
?>

<style>
    :root {
        --jacaranda-primary: #663399;
        --jacaranda-secondary: #8a4baf;
        --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .page-header-modern {
        background: white;
        border-radius: 15px;
        padding: 1.75rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        border-left: 5px solid var(--jacaranda-primary);
    }

    .page-header-modern h3 {
        background: linear-gradient(135deg, var(--jacaranda-primary) 0%, var(--jacaranda-secondary) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-weight: 600;
        margin: 0;
        font-size: 1.75rem;
    }

    .modern-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .modern-card:hover {
        box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    }

    .form-label-modern {
        font-weight: 600;
        color: #4a5568;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .form-control-modern, .form-select-modern {
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.65rem 1rem;
        transition: all 0.3s ease;
        font-size: 0.95rem;
    }

    .form-control-modern:focus, .form-select-modern:focus {
        border-color: var(--jacaranda-primary);
        box-shadow: 0 0 0 3px rgba(102, 51, 153, 0.1);
        outline: none;
    }

    .btn-gradient-primary {
        background: linear-gradient(135deg, var(--jacaranda-primary) 0%, var(--jacaranda-secondary) 100%);
        border: none;
        color: white;
        padding: 0.65rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(102, 51, 153, 0.3);
    }

    .btn-gradient-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 51, 153, 0.4);
        color: white;
    }

    .btn-outline-modern {
        border: 2px solid #e2e8f0;
        color: #4a5568;
        padding: 0.65rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
        background: white;
    }

    .btn-outline-modern:hover {
        border-color: var(--jacaranda-primary);
        color: var(--jacaranda-primary);
        background: #f7fafc;
    }

    .alert-modern {
        border: none;
        border-radius: 12px;
        padding: 1rem 1.25rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .alert-modern i {
        font-size: 1.25rem;
    }

    .form-section-title {
        color: var(--jacaranda-primary);
        font-weight: 600;
        font-size: 1.1rem;
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
</style>

    <div class="page-header-modern">
        <div class="d-flex justify-content-between align-items-center">
            <h3>
                <i class="fas fa-user-plus me-2"></i>Create New User
            </h3>
            <a href="<?= BASE_PATH ?>/admin/users" class="btn btn-outline-modern">
                <i class="fas fa-arrow-left me-2"></i>Back to Users
            </a>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-modern alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i>
            <span><?= $_SESSION['error']; unset($_SESSION['error']); ?></span>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-modern alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i>
            <span><?= $_SESSION['success']; unset($_SESSION['success']); ?></span>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="modern-card">
        <div class="card-body p-4">
            <form method="POST" action="<?= BASE_PATH ?>/admin/create-user">
                <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                <!-- Personal Information -->
                <div class="form-section-title">
                    <i class="fas fa-user"></i> Personal Information
                </div>
                
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label for="username" class="form-label-modern">
                            <i class="fas fa-user-circle"></i> Username <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control form-control-modern" id="username" name="username" required
                               placeholder="Enter username">
                        <small class="text-muted mt-1 d-block"><i class="fas fa-info-circle"></i> Must be unique</small>
                    </div>
                    <div class="col-md-6">
                        <label for="full_name" class="form-label-modern">
                            <i class="fas fa-id-card"></i> Full Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control form-control-modern" id="full_name" name="full_name" required
                               placeholder="Enter full name">
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="form-section-title">
                    <i class="fas fa-address-book"></i> Contact Information
                </div>
                
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label for="email" class="form-label-modern">
                            <i class="fas fa-envelope"></i> Email Address <span class="text-danger">*</span>
                        </label>
                        <input type="email" class="form-control form-control-modern" id="email" name="email" required
                               placeholder="Enter email address">
                        <small class="text-muted mt-1 d-block"><i class="fas fa-info-circle"></i> Must be unique</small>
                    </div>
                    <div class="col-md-6">
                        <label for="phone" class="form-label-modern">
                            <i class="fas fa-phone"></i> Phone Number
                        </label>
                        <input type="tel" class="form-control form-control-modern" id="phone" name="phone"
                               placeholder="Enter phone number">
                    </div>
                </div>

                <!-- Security -->
                <div class="form-section-title">
                    <i class="fas fa-lock"></i> Security & Access
                </div>
                
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label for="password" class="form-label-modern">
                            <i class="fas fa-key"></i> Password <span class="text-danger">*</span>
                        </label>
                        <input type="password" class="form-control form-control-modern" id="password" name="password" required minlength="6"
                               placeholder="Enter password">
                        <small class="text-muted mt-1 d-block"><i class="fas fa-info-circle"></i> Minimum 6 characters</small>
                    </div>
                    <div class="col-md-6">
                        <label for="confirm_password" class="form-label-modern">
                            <i class="fas fa-check-double"></i> Confirm Password <span class="text-danger">*</span>
                        </label>
                        <input type="password" class="form-control form-control-modern" id="confirm_password" name="confirm_password" required minlength="6"
                               placeholder="Confirm password">
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label for="role" class="form-label-modern">
                            <i class="fas fa-user-tag"></i> Role <span class="text-danger">*</span>
                        </label>
                        <select class="form-select form-select-modern" id="role" name="role" required>
                            <option value="">Select Role</option>
                            <option value="super_admin">Super Admin</option>
                            <option value="librarian">Librarian</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex gap-3 justify-content-end mt-4 pt-3 border-top">
                    <a href="<?= BASE_PATH ?>/admin/users" class="btn btn-outline-modern">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-gradient-primary">
                        <i class="fas fa-user-plus me-2"></i>Create User
                    </button>
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

<?php include '../app/views/shared/layout-footer.php'; ?>
<?php include '../app/views/shared/footer.php'; ?>