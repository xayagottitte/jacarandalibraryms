<?php 
$title = "Add Category - Jacaranda Libraries";
include '../app/views/shared/header.php'; 
include '../app/views/shared/layout-header.php'; 
?>

<style>
    .add-category-card {
        border-radius: 18px;
        box-shadow: 0 4px 24px rgba(102, 51, 153, 0.08);
        background: #fff;
        margin-bottom: 2rem;
    }
    .add-category-header {
        background: linear-gradient(135deg, #663399 0%, #522d7a 100%);
        color: white;
        border-radius: 18px 18px 0 0;
        padding: 1.5rem 2rem;
    }
    .btn-submit-category {
        background: linear-gradient(135deg, #663399 0%, #522d7a 100%);
        color: white;
        font-weight: 600;
        border-radius: 12px;
        padding: 0.6rem 1.8rem;
        box-shadow: 0 2px 8px rgba(102, 51, 153, 0.12);
        transition: background 0.2s;
        border: none;
    }
    .btn-submit-category:hover {
        background: linear-gradient(135deg, #522d7a 0%, #663399 100%);
        color: #fff;
    }
    .btn-back-category {
        background: #f3f4f6;
        color: #663399;
        font-weight: 600;
        border-radius: 12px;
        padding: 0.6rem 1.8rem;
        margin-left: 1rem;
        border: none;
    }
    .btn-back-category:hover {
        background: #e5e7eb;
        color: #522d7a;
    }
</style>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="add-category-card">
                <div class="add-category-header">
                    <h4><i class="fas fa-plus-circle me-2"></i>Add New Category</h4>
                </div>
                <div class="card-body p-4">
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show"> 
                            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="<?= BASE_PATH ?>/admin/add-category">
                        <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                        
                        <div class="mb-4">
                            <label for="library_id" class="form-label fw-bold">
                                <i class="fas fa-university me-1"></i>Library
                            </label>
                            <select class="form-select" id="library_id" name="library_id" required>
                                <option value="">Select Library</option>
                                <?php foreach ($libraries as $library): ?>
                                    <option value="<?= $library['id'] ?>">
                                        <?= htmlspecialchars($library['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-muted">Select the library this category belongs to</small>
                        </div>

                        <div class="mb-4">
                            <label for="name" class="form-label fw-bold">
                                <i class="fas fa-tag me-1"></i>Category Name
                            </label>
                            <input type="text" class="form-control" id="name" name="name" required 
                                   placeholder="e.g., Fiction, Science, History">
                            <small class="text-muted">Enter a unique category name</small>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <a href="<?= BASE_PATH ?>/admin/categories" class="btn btn-back-category">
                                <i class="fas fa-arrow-left me-1"></i> Back
                            </a>
                            <button type="submit" class="btn btn-submit-category ms-2">
                                <i class="fas fa-save me-1"></i> Add Category
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include '../app/views/shared/footer.php'; ?>
