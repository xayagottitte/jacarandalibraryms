<?php include '../app/views/shared/header.php'; ?>
<?php include '../app/views/shared/navbar.php'; ?>
<?php include '../app/views/shared/layout-header.php'; ?>

<style>
    :root {
        --jacaranda-primary: #663399;
        --jacaranda-secondary: #8a4baf;
    }
    .page-header-modern {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        border-left: 5px solid var(--jacaranda-primary);
    }
    .page-header-modern h1 {
        background: linear-gradient(135deg, var(--jacaranda-primary) 0%, var(--jacaranda-secondary) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-weight: 600;
        margin: 0;
    }
    .modern-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }
    .modern-card:hover {
        box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    }
    .modern-card .card-header {
        background: linear-gradient(135deg, var(--jacaranda-primary) 0%, var(--jacaranda-secondary) 100%);
        color: white;
        border-radius: 15px 15px 0 0;
        border: none;
    }
    .form-label {
        font-weight: 600;
        color: #4a5568;
        margin-bottom: 0.5rem;
    }
    .form-control, .form-select {
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.65rem 1rem;
        transition: all 0.3s ease;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--jacaranda-primary);
        box-shadow: 0 0 0 3px rgba(102, 51, 153, 0.1);
    }
    .btn-primary {
        background: linear-gradient(135deg, var(--jacaranda-primary) 0%, var(--jacaranda-secondary) 100%);
        border: none;
        padding: 0.65rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(102, 51, 153, 0.3);
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 51, 153, 0.4);
    }
    .btn-outline-secondary {
        border: 2px solid #e2e8f0;
        color: #4a5568;
        padding: 0.65rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .btn-outline-secondary:hover {
        border-color: var(--jacaranda-primary);
        color: var(--jacaranda-primary);
        background: #f7fafc;
    }
    .btn-danger {
        border-radius: 10px;
        padding: 0.65rem 1.5rem;
        font-weight: 600;
    }
    .text-primary {
        color: var(--jacaranda-primary) !important;
    }
</style>

<div class="main-content">
    <div class="container-fluid px-4">
        <div class="page-header-modern">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-book-open me-2"></i>Edit Book
                    </h1>
                    <p class="mb-0 text-muted">Update book information</p>
                </div>
                <a href="<?php echo BASE_PATH; ?>/admin/books" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Books
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card modern-card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fas fa-book me-2"></i>Book Information
                        </h6>
                    </div>
                    <div class="card-body">
                        <form method="POST" id="editBookForm" enctype="multipart/form-data">
                            <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                            <input type="hidden" name="id" value="<?php echo $book['id']; ?>">
                            <input type="hidden" name="library_id" value="<?php echo $book['library_id']; ?>">
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="library_name" class="form-label">Library</label>
                                    <input type="text" id="library_name" class="form-control" 
                                           value="<?php echo htmlspecialchars($book['library_name']); ?>" readonly>
                                    <div class="form-text">Library cannot be changed when editing</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="class_level" class="form-label">Class Level <small class="text-muted">(Optional)</small></label>
                                    <select name="class_level" id="class_level" class="form-select">
                                        <option value="">All Classes</option>
                                        <?php
                                        $maxClass = $book['library_type'] === 'primary' ? 8 : 4;
                                        $classLabel = $book['library_type'] === 'primary' ? 'Class' : 'Form';
                                        for ($i = 1; $i <= $maxClass; $i++): ?>
                                            <option value="<?php echo $i; ?>" 
                                                    <?php echo ($book['class_level'] == $i) ? 'selected' : ''; ?>>
                                                <?php echo $classLabel; ?> <?php echo $i; ?>
                                            </option>
                                        <?php endfor; ?>
                                    </select>
                                    <div class="form-text">Select a specific class or leave blank for all classes</div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-8">
                                    <label for="title" class="form-label">Book Title *</label>
                                    <input type="text" name="title" id="title" class="form-control" 
                                           value="<?php echo htmlspecialchars($book['title']); ?>" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="isbn" class="form-label">ISBN</label>
                                    <input type="text" name="isbn" id="isbn" class="form-control" 
                                           value="<?php echo htmlspecialchars($book['isbn'] ?? ''); ?>" 
                                           placeholder="978-0-123456-78-9">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="author" class="form-label">Author *</label>
                                    <input type="text" name="author" id="author" class="form-control" 
                                           value="<?php echo htmlspecialchars($book['author']); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="publisher" class="form-label">Publisher</label>
                                    <input type="text" name="publisher" id="publisher" class="form-control" 
                                           value="<?php echo htmlspecialchars($book['publisher'] ?? ''); ?>">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="category_id" class="form-label">Category</label>
                                    <select class="form-select" id="category_id" name="category_id">
                                        <option value="">Select Category</option>
                                        <?php if (isset($categories) && is_array($categories)): ?>
                                            <?php foreach ($categories as $category): ?>
                                                <option value="<?= $category['id'] ?>" 
                                                    <?= ($book['category_id'] == $category['id']) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($category['name']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="publication_year" class="form-label">Publication Year</label>
                                    <input type="number" name="publication_year" id="publication_year" class="form-control" 
                                           value="<?php echo $book['publication_year'] ?? ''; ?>"
                                           min="1800" max="<?php echo date('Y'); ?>" placeholder="<?php echo date('Y'); ?>">
                                </div>
                                <div class="col-md-4">
                                    <label for="total_copies" class="form-label">Total Copies *</label>
                                    <input type="number" name="total_copies" id="total_copies" class="form-control" 
                                           value="<?php echo $book['total_copies']; ?>" min="1" required>
                                    <div class="form-text">Available copies will be calculated automatically</div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="cover_image" class="form-label">Book Cover Image</label>
                                    <input type="file" name="cover_image" id="cover_image" class="form-control" 
                                           accept="image/jpeg,image/png,image/gif,image/webp">
                                    <div class="form-text">Upload a new cover image (JPEG, PNG, GIF, WebP - max 2MB). Leave empty to keep current.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Current/New Cover</label>
                                    <div id="coverPreview" style="border: 2px dashed #e0e0e0; border-radius: 8px; padding: 20px; text-align: center; min-height: 120px; display: flex; align-items: center; justify-content: center; background: #fafafa;">
                                        <?php 
                                        $bookModel = new Book();
                                        $coverUrl = BASE_PATH . $bookModel->getBookCoverUrl($book['cover_image'] ?? null);
                                        ?>
                                        <img src="<?= $coverUrl ?>" alt="Current Cover" 
                                             style="max-width: 100%; max-height: 120px; border-radius: 4px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="<?php echo BASE_PATH; ?>/admin/books" class="btn btn-outline-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Update Book
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-chart-bar me-2"></i>Book Statistics
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="border-end">
                                    <h4 class="text-primary"><?php echo $book['total_copies']; ?></h4>
                                    <small class="text-muted">Total Copies</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <h4 class="text-success"><?php echo $book['available_copies']; ?></h4>
                                <small class="text-muted">Available</small>
                            </div>
                        </div>
                        
                        <?php if ($book['total_copies'] > $book['available_copies']): ?>
                        <hr>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong><?php echo $book['total_copies'] - $book['available_copies']; ?></strong> 
                            cop<?php echo ($book['total_copies'] - $book['available_copies']) === 1 ? 'y' : 'ies'; ?> 
                            currently borrowed
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-info-circle me-2"></i>Information
                        </h6>
                    </div>
                    <div class="card-body">
                        <h6>Library Information:</h6>
                        <ul class="list-unstyled">
                            <li><strong>Name:</strong> <?php echo htmlspecialchars($book['library_name']); ?></li>
                            <li><strong>Type:</strong> <?php echo ucfirst($book['library_type']); ?> School</li>
                        </ul>

                        <hr>

                        <h6>Class Level:</h6>
                        <?php if ($book['class_level']): ?>
                            <span class="badge bg-info">
                                <?php echo $book['library_type'] === 'primary' ? 'Class' : 'Form'; ?> <?php echo $book['class_level']; ?>
                            </span>
                        <?php else: ?>
                            <span class="text-muted">Available to all classes</span>
                        <?php endif; ?>

                        <hr>

                        <h6>Important Notes:</h6>
                        <ul class="small">
                            <li>Available copies are calculated automatically</li>
                            <li>Cannot reduce total copies below borrowed copies</li>
                            <li>Library assignment cannot be changed</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Form validation
document.getElementById('editBookForm').addEventListener('submit', function(e) {
    const title = document.getElementById('title').value.trim();
    const author = document.getElementById('author').value.trim();
    const totalCopies = parseInt(document.getElementById('total_copies').value);
    const currentlyBorrowed = <?php echo $book['total_copies'] - $book['available_copies']; ?>;
    
    if (!title) {
        alert('Please enter a book title.');
        e.preventDefault();
        return;
    }
    
    if (!author) {
        alert('Please enter an author name.');
        e.preventDefault();
        return;
    }
    
    if (!totalCopies || totalCopies < 1) {
        alert('Please enter a valid number of copies (at least 1).');
        e.preventDefault();
        return;
    }
    
    if (totalCopies < currentlyBorrowed) {
        alert(`Cannot set total copies to ${totalCopies}. There are currently ${currentlyBorrowed} copies borrowed. Total copies must be at least ${currentlyBorrowed}.`);
        e.preventDefault();
        return;
    }
});

// Update total copies validation message
document.getElementById('total_copies').addEventListener('input', function() {
    const totalCopies = parseInt(this.value);
    const currentlyBorrowed = <?php echo $book['total_copies'] - $book['available_copies']; ?>;
    
    if (totalCopies < currentlyBorrowed) {
        this.setCustomValidity(`Total copies must be at least ${currentlyBorrowed} (currently borrowed copies)`);
    } else {
        this.setCustomValidity('');
    }
});

// Book cover preview functionality
document.getElementById('cover_image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('coverPreview');
    
    if (file) {
        // Validate file type
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!allowedTypes.includes(file.type)) {
            alert('Please select a valid image file (JPEG, PNG, GIF, or WebP)');
            e.target.value = '';
            return;
        }
        
        // Validate file size (2MB max)
        if (file.size > 2 * 1024 * 1024) {
            alert('File size must be less than 2MB');
            e.target.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `
                <img src="${e.target.result}" alt="New Cover Preview" 
                     style="max-width: 100%; max-height: 120px; border-radius: 4px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            `;
        };
        reader.readAsDataURL(file);
    }
});
</script>

<?php include '../app/views/shared/footer.php'; ?>