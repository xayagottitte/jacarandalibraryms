<?php
$title = "Edit Book - Multi-Library System";
include __DIR__ . '/../shared/header.php';
include __DIR__ . '/../shared/navbar.php';

// Data is passed from the controller
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4><i class="fas fa-edit text-jacaranda"></i> Edit Book</h4>
                <a href="<?php echo BASE_PATH; ?>/librarian/books" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Books
                </a>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow border-0">
                        <div class="card-header bg-white py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Book Information</h6>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="<?php echo BASE_PATH; ?>/librarian/edit-book/<?php echo $book['book_id']; ?>">
                                <input type="hidden" name="id" value="<?php echo $book['book_id']; ?>">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label for="title" class="form-label">Title *</label>
                                            <input type="text" class="form-control" id="title" name="title" 
                                                   value="<?php echo htmlspecialchars($book['title']); ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="class_level" class="form-label">Class Level</label>
                                            <select class="form-select" id="class_level" name="class_level">
                                                <option value="">Select Level (Optional)</option>
                                                <?php if (isset($class_levels)): ?>
                                                    <?php foreach ($class_levels as $level): ?>
                                                        <option value="<?php echo $level; ?>" <?php echo ($book['class_level'] == $level) ? 'selected' : ''; ?>>
                                                            <?php echo $level; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="author" class="form-label">Author *</label>
                                            <input type="text" class="form-control" id="author" name="author" 
                                                   value="<?php echo htmlspecialchars($book['author']); ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="publisher" class="form-label">Publisher</label>
                                            <input type="text" class="form-control" id="publisher" name="publisher" 
                                                   value="<?php echo htmlspecialchars($book['publisher'] ?? ''); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="isbn" class="form-label">ISBN</label>
                                            <input type="text" class="form-control" id="isbn" name="isbn" 
                                                   value="<?php echo htmlspecialchars($book['isbn'] ?? ''); ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="category" class="form-label">Category *</label>
                                            <select class="form-select" id="category" name="category" required>
                                                <option value="">Select Category</option>
                                                <option value="Fiction" <?php echo ($book['category'] == 'Fiction') ? 'selected' : ''; ?>>Fiction</option>
                                                <option value="Non-Fiction" <?php echo ($book['category'] == 'Non-Fiction') ? 'selected' : ''; ?>>Non-Fiction</option>
                                                <option value="Science" <?php echo ($book['category'] == 'Science') ? 'selected' : ''; ?>>Science</option>
                                                <option value="Mathematics" <?php echo ($book['category'] == 'Mathematics') ? 'selected' : ''; ?>>Mathematics</option>
                                                <option value="History" <?php echo ($book['category'] == 'History') ? 'selected' : ''; ?>>History</option>
                                                <option value="Literature" <?php echo ($book['category'] == 'Literature') ? 'selected' : ''; ?>>Literature</option>
                                                <option value="Reference" <?php echo ($book['category'] == 'Reference') ? 'selected' : ''; ?>>Reference</option>
                                                <option value="Children" <?php echo ($book['category'] == 'Children') ? 'selected' : ''; ?>>Children</option>
                                                <option value="Educational" <?php echo ($book['category'] == 'Educational') ? 'selected' : ''; ?>>Educational</option>
                                                <option value="Other" <?php echo ($book['category'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="publication_year" class="form-label">Publication Year</label>
                                            <input type="number" class="form-control" id="publication_year" name="publication_year" 
                                                   min="1800" max="<?php echo date('Y'); ?>" 
                                                   value="<?php echo $book['publication_year'] ?? ''; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="total_copies" class="form-label">Total Copies *</label>
                                            <input type="number" class="form-control" id="total_copies" name="total_copies" 
                                                   min="1" value="<?php echo $book['total_copies']; ?>" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="4"><?php echo htmlspecialchars($book['description'] ?? ''); ?></textarea>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Update Book
                                    </button>
                                    <a href="<?php echo BASE_PATH; ?>/librarian/books" class="btn btn-outline-secondary">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.text-jacaranda {
    color: var(--jacaranda-primary, #663399);
}
</style>

<?php include __DIR__ . '/../shared/footer.php'; ?>