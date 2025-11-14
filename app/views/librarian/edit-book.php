<?php
$title = "Edit Book - Multi-Library System";
include __DIR__ . '/../shared/header.php';
include __DIR__ . '/../shared/navbar.php';

// Data is passed from the controller
?>

<style>
:root {
    --primary-purple: #6366f1;
    --dark-purple: #4f46e5;
    --light-purple: #818cf8;
    --accent-purple: #a78bfa;
    --grey-dark: #374151;
    --grey-medium: #6b7280;
    --grey-light: #e5e7eb;
    --grey-lighter: #f3f4f6;
    --success-gradient-start: #10b981;
    --success-gradient-end: #059669;
    --warning-gradient-start: #f59e0b;
    --warning-gradient-end: #d97706;
}

.edit-book-container {
    padding: 2rem;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    min-height: 100vh;
}

/* Page Header */
.page-header {
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--dark-purple) 100%);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 10px 30px rgba(99, 102, 241, 0.25);
    color: white;
}

.page-header h2 {
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.page-header h2 i {
    font-size: 2rem;
}

.btn-back {
    background: rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    font-size: 0.95rem;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-back:hover {
    background: rgba(255, 255, 255, 0.35);
    transform: translateY(-2px);
    color: white;
    box-shadow: 0 6px 20px rgba(255, 255, 255, 0.2);
}

/* Form Card */
.form-card {
    background: white;
    border-radius: 20px;
    padding: 2.5rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    margin-bottom: 2rem;
}

.section-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--grey-dark);
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid var(--grey-light);
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.section-title i {
    font-size: 1.5rem;
    color: var(--primary-purple);
}

/* Form Groups */
.form-group-custom {
    margin-bottom: 1.75rem;
}

.form-label-custom {
    font-weight: 600;
    color: var(--grey-dark);
    margin-bottom: 0.75rem;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-label-custom i {
    color: var(--primary-purple);
    font-size: 1rem;
}

.form-label-custom .required {
    color: #ef4444;
    margin-left: 0.25rem;
}

.form-control-custom,
.form-select-custom {
    border: 2px solid var(--grey-light);
    border-radius: 12px;
    padding: 0.875rem 1.25rem;
    font-size: 1rem;
    transition: all 0.3s;
    background: white;
}

.form-control-custom:focus,
.form-select-custom:focus {
    border-color: var(--primary-purple);
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    outline: none;
}

.form-control-custom:hover,
.form-select-custom:hover {
    border-color: var(--light-purple);
}

textarea.form-control-custom {
    resize: vertical;
    min-height: 120px;
}

/* Action Buttons */
.btn-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 2px solid var(--grey-light);
}

.btn-primary-custom {
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--dark-purple) 100%);
    border: none;
    color: white;
    padding: 1rem 2rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    cursor: pointer;
    box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
}

.btn-primary-custom:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 25px rgba(99, 102, 241, 0.4);
}

.btn-secondary-custom {
    background: white;
    border: 2px solid var(--grey-light);
    color: var(--grey-dark);
    padding: 1rem 2rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    text-decoration: none;
}

.btn-secondary-custom:hover {
    background: var(--grey-lighter);
    border-color: var(--grey-medium);
    color: var(--grey-dark);
    transform: translateY(-2px);
}

/* Info Box */
.info-box {
    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
    border-left: 4px solid var(--primary-purple);
    border-radius: 12px;
    padding: 1.25rem;
    margin-bottom: 2rem;
    display: flex;
    align-items: start;
    gap: 1rem;
}

.info-box i {
    color: var(--primary-purple);
    font-size: 1.5rem;
    margin-top: 0.25rem;
}

.info-box-content h6 {
    color: var(--grey-dark);
    font-weight: 700;
    margin-bottom: 0.5rem;
    font-size: 1rem;
}

.info-box-content p {
    color: var(--grey-medium);
    margin: 0;
    font-size: 0.9rem;
    line-height: 1.6;
}

/* Responsive */
@media (max-width: 768px) {
    .edit-book-container {
        padding: 1rem;
    }
    
    .page-header {
        padding: 1.5rem;
    }
    
    .page-header h2 {
        font-size: 1.5rem;
    }
    
    .form-card {
        padding: 1.5rem;
    }
    
    .btn-actions {
        flex-direction: column;
    }
    
    .btn-primary-custom,
    .btn-secondary-custom {
        width: 100%;
        justify-content: center;
    }
}
</style>

<div class="container-fluid edit-book-container">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h2><i class="fas fa-edit"></i>Edit Book</h2>
            <a href="<?php echo BASE_PATH; ?>/librarian/books" class="btn-back">
                <i class="fas fa-arrow-left"></i>Back to Books
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="info-box">
                <i class="fas fa-info-circle"></i>
                <div class="info-box-content">
                    <h6>Editing Book Information</h6>
                    <p>Update the details for this book. Fields marked with <span style="color: #ef4444;">*</span> are required. Changes will affect all copies of this book in the library.</p>
                </div>
            </div>

            <div class="form-card">
                <form method="POST" action="<?php echo BASE_PATH; ?>/librarian/edit-book" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $book['id']; ?>">
                    
                    <!-- Basic Information Section -->
                    <div class="section-title">
                        <i class="fas fa-book"></i>
                        <span>Basic Information</span>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group-custom">
                                <label for="title" class="form-label-custom">
                                    <i class="fas fa-heading"></i>
                                    Book Title<span class="required">*</span>
                                </label>
                                <input type="text" class="form-control form-control-custom" id="title" name="title" 
                                       value="<?php echo htmlspecialchars($book['title']); ?>" 
                                       placeholder="Enter book title" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-custom">
                                <label for="class_level" class="form-label-custom">
                                    <i class="fas fa-graduation-cap"></i>
                                    Class Level
                                </label>
                                <select class="form-select form-select-custom" id="class_level" name="class_level">
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
                            <div class="form-group-custom">
                                <label for="author" class="form-label-custom">
                                    <i class="fas fa-user-edit"></i>
                                    Author<span class="required">*</span>
                                </label>
                                <input type="text" class="form-control form-control-custom" id="author" name="author" 
                                       value="<?php echo htmlspecialchars($book['author']); ?>" 
                                       placeholder="Enter author name" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-custom">
                                <label for="publisher" class="form-label-custom">
                                    <i class="fas fa-building"></i>
                                    Publisher
                                </label>
                                <input type="text" class="form-control form-control-custom" id="publisher" name="publisher" 
                                       value="<?php echo htmlspecialchars($book['publisher'] ?? ''); ?>"
                                       placeholder="Enter publisher name">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-custom">
                                <label for="isbn" class="form-label-custom">
                                    <i class="fas fa-barcode"></i>
                                    ISBN
                                </label>
                                <input type="text" class="form-control form-control-custom" id="isbn" name="isbn" 
                                       value="<?php echo htmlspecialchars($book['isbn'] ?? ''); ?>"
                                       placeholder="Enter ISBN number">
                            </div>
                        </div>
                    </div>

                    <!-- Classification Section -->
                    <div class="section-title mt-4">
                        <i class="fas fa-tags"></i>
                        <span>Classification & Copies</span>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group-custom">
                                <label for="category_id" class="form-label-custom">
                                    <i class="fas fa-tag"></i>
                                    Category<span class="required">*</span>
                                </label>
                                <select class="form-select form-select-custom" id="category_id" name="category_id" required>
                                    <option value="">Select Category</option>
                                    <?php if (isset($categories) && is_array($categories)): ?>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?= htmlspecialchars($category['id']) ?>" 
                                                <?= ($book['category_id'] == $category['id']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($category['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-custom">
                                <label for="publication_year" class="form-label-custom">
                                    <i class="fas fa-calendar-alt"></i>
                                    Publication Year
                                </label>
                                <input type="number" class="form-control form-control-custom" id="publication_year" name="publication_year" 
                                       min="1800" max="<?php echo date('Y'); ?>" 
                                       value="<?php echo $book['publication_year'] ?? ''; ?>"
                                       placeholder="Enter publication year">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-custom">
                                <label for="total_copies" class="form-label-custom">
                                    <i class="fas fa-copy"></i>
                                    Total Copies<span class="required">*</span>
                                </label>
                                <input type="number" class="form-control form-control-custom" id="total_copies" name="total_copies" 
                                       min="1" value="<?php echo $book['total_copies']; ?>" 
                                       placeholder="Enter number of copies" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group-custom">
                                <label for="cover_image" class="form-label-custom">
                                    <i class="fas fa-image"></i>
                                    Book Cover Image
                                </label>
                                <input type="file" class="form-control form-control-custom" id="cover_image" name="cover_image" 
                                       accept="image/jpeg,image/png,image/gif,image/webp">
                                <small class="text-muted">Upload new cover (JPEG, PNG, GIF, WebP - max 2MB). Leave empty to keep current.</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group-custom">
                                <label class="form-label-custom">Current/New Cover</label>
                                <div id="coverPreview" style="border: 2px dashed #e0e0e0; border-radius: 12px; padding: 20px; text-align: center; min-height: 140px; display: flex; align-items: center; justify-content: center; background: #fafafa;">
                                    <?php 
                                    $bookModel = new Book();
                                    $coverUrl = BASE_PATH . $bookModel->getBookCoverUrl($book['cover_image'] ?? null);
                                    ?>
                                    <img src="<?= $coverUrl ?>" alt="Current Cover" 
                                         style="max-width: 100%; max-height: 140px; border-radius: 8px; box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group-custom">
                        <label for="description" class="form-label-custom">
                            <i class="fas fa-align-left"></i>
                            Description
                        </label>
                        <textarea class="form-control form-control-custom" id="description" name="description" 
                                  rows="4" placeholder="Enter book description (optional)"><?php echo htmlspecialchars($book['description'] ?? ''); ?></textarea>
                    </div>

                    <div class="btn-actions">
                        <button type="submit" class="btn-primary-custom">
                            <i class="fas fa-save"></i>
                            Update Book
                        </button>
                        <a href="<?php echo BASE_PATH; ?>/librarian/books" class="btn-secondary-custom">
                            <i class="fas fa-times"></i>
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
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
                     style="max-width: 100%; max-height: 140px; border-radius: 8px; box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);">
            `;
        };
        reader.readAsDataURL(file);
    }
});
</script>

<?php include __DIR__ . '/../shared/footer.php'; ?>