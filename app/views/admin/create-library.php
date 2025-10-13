<?php 
$title = "Create Library - Multi-Library System";
include '../app/views/shared/header.php'; 
include '../app/views/shared/layout-header.php'; 
?>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="mb-0">Create New Library</h3>
                <a href="<?= BASE_PATH ?>/admin/libraries" class="btn btn-secondary btn-sm">Back to Libraries</a>
            </div>

    <!-- Flash Messages -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

            <div class="card shadow-sm">
                <div class="card-body p-3">
                    <form method="POST" action="<?= BASE_PATH ?>/admin/create-library">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="name" class="form-label form-label-sm">Library Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm" id="name" name="name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="type" class="form-label form-label-sm">Library Type <span class="text-danger">*</span></label>
                                    <select class="form-select form-select-sm" id="type" name="type" required>
                                        <option value="">Select Type</option>
                                        <option value="primary">Primary School</option>
                                        <option value="secondary">Secondary School</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="address" class="form-label form-label-sm">Address <span class="text-danger">*</span></label>
                            <textarea class="form-control form-control-sm" id="address" name="address" rows="2" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="loan_period_days" class="form-label form-label-sm">
                                Loan Period (Days) <span class="text-danger">*</span>
                            </label>
                            <input type="number" class="form-control form-control-sm" id="loan_period_days" 
                                   name="loan_period_days" min="1" max="90" value="5" required>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> Number of days students can borrow books from this library (Default: 5 days)
                            </small>
                        </div>

                        <div class="d-flex gap-2 justify-content-end mt-3">
                            <a href="<?= BASE_PATH ?>/admin/libraries" class="btn btn-secondary btn-sm">Cancel</a>
                            <button type="submit" class="btn btn-primary btn-sm">Create Library</button>
                        </div>
                    </form>
                </div>
            </div>

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