
<?php 
$title = "Add Category - Jacaranda Libraries";
include '../app/views/shared/header.php'; 
include '../app/views/shared/layout-header.php'; 
?>

<style>
    .add-category-card {
        border-radius: 18px;
        box-shadow: 0 4px 24px rgba(99,102,241,0.08);
        background: #fff;
        margin-bottom: 2rem;
    }
    .add-category-header {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        color: white;
        border-radius: 18px 18px 0 0;
        padding: 1.5rem 2rem;
    }
    .btn-submit-category {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        color: white;
        font-weight: 600;
        border-radius: 12px;
        padding: 0.5rem 1.5rem;
        box-shadow: 0 2px 8px rgba(99,102,241,0.12);
        transition: background 0.2s;
        border: none;
    }
    .btn-submit-category:hover {
        background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
        color: #fff;
    }
    .btn-back-category {
        background: #f3f4f6;
        color: #6366f1;
        font-weight: 600;
        border-radius: 12px;
        padding: 0.5rem 1.5rem;
        margin-left: 1rem;
        border: none;
    }
    .btn-back-category:hover {
        background: #e5e7eb;
        color: #4f46e5;
    }
</style>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="add-category-card">
                <div class="add-category-header">
                    <h4><i class="fas fa-plus me-2"></i>Add Category</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger"> <?= $_SESSION['error']; unset($_SESSION['error']); ?> </div>
                    <?php endif; ?>
                    <form method="POST" action="<?= BASE_PATH ?>/librarian/add-category">
                        <div class="mb-3">
                            <label for="name" class="form-label">Category Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <button type="submit" class="btn btn-submit-category">Add Category</button>
                        <a href="<?= BASE_PATH ?>/librarian/categories" class="btn btn-back-category">Back</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include '../app/views/shared/footer.php'; ?>
