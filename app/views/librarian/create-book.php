<?php 
$title = "Add Book - Multi-Library System";
include '../app/views/shared/header.php'; 
include '../app/views/shared/layout-header.php'; 
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Add New Book</h1>
        <a href="/librarian/books" class="btn btn-secondary">Back to Books</a>
    </div>

    <!-- Flash Messages -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="/librarian/create-book">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="title" class="form-label">Book Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="author" class="form-label">Author <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="author" name="author" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="isbn" class="form-label">ISBN</label>
                            <input type="text" class="form-control" id="isbn" name="isbn">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="publisher" class="form-label">Publisher</label>
                            <input type="text" class="form-control" id="publisher" name="publisher">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="publication_year" class="form-label">Publication Year</label>
                            <input type="number" class="form-control" id="publication_year" name="publication_year" 
                                   min="1900" max="<?= date('Y') ?>" value="<?= date('Y') ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-select" id="category" name="category">
                                <option value="">Select Category</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= htmlspecialchars($category['category']) ?>">
                                        <?= htmlspecialchars($category['category']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="class_level" class="form-label">Class Level <small class="text-muted">(Optional)</small></label>
                            <select class="form-select" id="class_level" name="class_level">
                                <option value="">All Classes</option>
                                <?php if (isset($class_levels)): ?>
                                    <?php foreach ($class_levels as $level): ?>
                                        <option value="<?= $level ?>">
                                            <?= $library['type'] === 'primary' ? 'Class' : 'Form' ?> <?= $level ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <div class="form-text">Select specific class or leave blank for all</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="total_copies" class="form-label">Total Copies <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="total_copies" name="total_copies" value="1" min="1" required>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="/librarian/books" class="btn btn-secondary me-md-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">Add Book</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../app/views/shared/footer.php'; ?>