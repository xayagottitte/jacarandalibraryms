<?php 
$title = "Manage Categories - Jacaranda Libraries";
include '../app/views/shared/header.php'; 
include '../app/views/shared/layout-header.php'; 
?>

<style>
    .category-card {
        border-radius: 18px;
        box-shadow: 0 4px 24px rgba(102, 51, 153, 0.08);
        background: #fff;
        margin-bottom: 2rem;
    }
    .category-header {
        background: linear-gradient(135deg, #663399 0%, #522d7a 100%);
        color: white;
        border-radius: 18px 18px 0 0;
        padding: 1.5rem 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .category-table th, .category-table td {
        padding: 1rem;
        font-size: 1rem;
        vertical-align: middle;
    }
    .category-table th {
        background: #f3f4f6;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .category-table tr {
        transition: background 0.2s;
    }
    .category-table tr:hover {
        background: #f8fafc;
    }
    .btn-add-category {
        background: linear-gradient(135deg, #663399 0%, #522d7a 100%);
        color: white;
        font-weight: 600;
        border-radius: 10px;
        padding: 0.5rem 1.2rem;
        font-size: 0.95rem;
        box-shadow: 0 2px 8px rgba(102, 51, 153, 0.12);
        transition: background 0.2s;
        border: none;
    }
    .btn-add-category:hover {
        background: linear-gradient(135deg, #522d7a 0%, #663399 100%);
        color: #fff;
    }
    .btn-delete-category {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        font-weight: 600;
        border-radius: 8px;
        padding: 0.4rem 1rem;
        font-size: 0.92rem;
        box-shadow: 0 2px 8px rgba(239,68,68,0.12);
        transition: background 0.2s;
        border: none;
    }
    .btn-delete-category:hover {
        background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
        color: #fff;
    }
</style>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="category-card">
                <div class="category-header">
                    <h4><i class="fas fa-tags me-2"></i>Manage Categories</h4>
                    <a href="<?= BASE_PATH ?>/admin/add-category" class="btn btn-add-category">
                        <i class="fas fa-plus me-1"></i> Add Category
                    </a>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show"> 
                            <?= $_SESSION['success']; unset($_SESSION['success']); ?> 
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show"> 
                            <?= $_SESSION['error']; unset($_SESSION['error']); ?> 
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (empty($categories)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                            <h5>No Categories Found</h5>
                            <p class="text-muted">Start by adding a new category.</p>
                            <a href="<?= BASE_PATH ?>/admin/add-category" class="btn btn-add-category">
                                <i class="fas fa-plus me-1"></i> Add First Category
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table category-table">
                                <thead>
                                    <tr>
                                        <th><i class="fas fa-tag me-2"></i>Name</th>
                                        <th><i class="fas fa-university me-2"></i>Library</th>
                                        <th style="width: 120px;"><i class="fas fa-cogs me-2"></i>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($categories as $category): ?>
                                        <tr>
                                            <td><strong><?= htmlspecialchars($category['name']) ?></strong></td>
                                            <td><?= htmlspecialchars($category['library_name'] ?? 'All Libraries') ?></td>
                                            <td>
                                                <form method="POST" action="<?= BASE_PATH ?>/admin/delete-category" style="display:inline-block;">
                                                    <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                                                    <input type="hidden" name="id" value="<?= $category['id'] ?>">
                                                    <button type="submit" class="btn btn-delete-category btn-sm" onclick="return confirm('Are you sure you want to delete this category? Books using this category will need to be reassigned.')">
                                                        <i class="fas fa-trash me-1"></i> Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include '../app/views/shared/footer.php'; ?>
