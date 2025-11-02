
<?php 
$title = "Manage Categories - Multi-Library System";
include '../app/views/shared/header.php'; 
include '../app/views/shared/layout-header.php'; 
?>

<style>
    .category-card {
        border-radius: 18px;
        box-shadow: 0 4px 24px rgba(99,102,241,0.08);
        background: #fff;
        margin-bottom: 2rem;
        max-width: 900px;
        width: 100%;
        margin-left: auto;
        margin-right: auto;
    }
    .category-header {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
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
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        color: white;
        font-weight: 600;
        border-radius: 10px;
        padding: 0.3rem 1rem;
        font-size: 0.95rem;
        box-shadow: 0 2px 8px rgba(99,102,241,0.12);
        transition: background 0.2s;
    }
    .btn-add-category:hover {
        background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
        color: #fff;
    }
    .btn-delete-category {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        font-weight: 600;
        border-radius: 8px;
        padding: 0.25rem 0.8rem;
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

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="category-card">
                <div class="category-header">
                    <h4><i class="fas fa-list me-2"></i>Categories</h4>
                    <a href="<?= BASE_PATH ?>/librarian/add-category" class="btn btn-add-category"><i class="fas fa-plus"></i> Add Category</a>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success"> <?= $_SESSION['success']; unset($_SESSION['success']); ?> </div>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger"> <?= $_SESSION['error']; unset($_SESSION['error']); ?> </div>
                    <?php endif; ?>
                    <table class="table category-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th style="width: 120px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categories as $category): ?>
                                <tr>
                                    <td><?= htmlspecialchars($category['name']) ?></td>
                                    <td>
                                        <form method="POST" action="<?= BASE_PATH ?>/librarian/delete-category" style="display:inline-block;">
                                            <input type="hidden" name="id" value="<?= $category['id'] ?>">
                                            <button type="submit" class="btn btn-delete-category" onclick="return confirm('Delete this category?')"><i class="fas fa-trash"></i> Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include '../app/views/shared/footer.php'; ?>
