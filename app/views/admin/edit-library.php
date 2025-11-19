<?php 
$title = "Edit Library - Jacaranda Libraries";
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

    .info-box-modern {
        background: linear-gradient(135deg, #e0f2fe 0%, #bfdbfe 100%);
        border: none;
        border-radius: 12px;
        padding: 1.25rem;
        border-left: 4px solid #3b82f6;
    }

    .info-box-modern i {
        color: #3b82f6;
        font-size: 1.2rem;
    }
</style>

    <div class="page-header-modern">
        <div class="d-flex justify-content-between align-items-center">
            <h3>
                <i class="fas fa-edit me-2"></i>Edit Library
            </h3>
            <a href="<?= BASE_PATH ?>/admin/libraries" class="btn btn-outline-modern">
                <i class="fas fa-arrow-left me-2"></i>Back to Libraries
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
                    <form method="POST" action="<?= BASE_PATH ?>/admin/edit-library">
                        <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                        <input type="hidden" name="id" value="<?= $library['id'] ?>">
                        
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="name" class="form-label-modern">
                                    <i class="fas fa-building"></i> Library Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control form-control-modern" id="name" name="name" 
                                       value="<?= htmlspecialchars($library['name']) ?>" required 
                                       placeholder="Enter library name">
                            </div>
                            <div class="col-md-6">
                                <label for="type" class="form-label-modern">
                                    <i class="fas fa-school"></i> Library Type <span class="text-danger">*</span>
                                </label>
                                <select class="form-select form-select-modern" id="type" name="type" required>
                                    <option value="">Select Type</option>
                                    <option value="primary" <?= $library['type'] === 'primary' ? 'selected' : '' ?>>Primary School</option>
                                    <option value="secondary" <?= $library['type'] === 'secondary' ? 'selected' : '' ?>>Secondary School</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <label for="address" class="form-label-modern">
                                <i class="fas fa-map-marker-alt"></i> Address <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control form-control-modern" id="address" name="address" rows="3" required 
                                      placeholder="Enter library address"><?= htmlspecialchars($library['address']) ?></textarea>
                        </div>

                        <div class="mt-4">
                            <label for="loan_period_days" class="form-label-modern">
                                <i class="fas fa-calendar-alt"></i> Loan Period (Days) <span class="text-danger">*</span>
                            </label>
                            <input type="number" class="form-control form-control-modern" id="loan_period_days" 
                                   name="loan_period_days" min="1" max="90" 
                                   value="<?= htmlspecialchars($library['loan_period_days'] ?? 5) ?>" required
                                   placeholder="Enter number of days">
                            <small class="text-muted mt-2 d-block">
                                <i class="fas fa-info-circle"></i> Number of days students can borrow books from this library (Default: 5 days)
                            </small>
                        </div>

                        <div class="info-box-modern mt-4">
                            <div>
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Note:</strong> Changing the loan period will only affect new book borrowings. 
                                Existing borrowed books will keep their original due dates.
                            </div>
                        </div>

                        <div class="d-flex gap-3 justify-content-end mt-4 pt-3 border-top">
                            <a href="<?= BASE_PATH ?>/admin/libraries" class="btn btn-outline-modern">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-gradient-primary">
                                <i class="fas fa-save me-2"></i>Update Library
                            </button>
                        </div>
                    </form>
                </div>
            </div>

<?php include '../app/views/shared/layout-footer.php'; ?>
<?php include '../app/views/shared/footer.php'; ?>
