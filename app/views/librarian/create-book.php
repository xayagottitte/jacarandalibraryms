<?php 
$title = "Add Book - Multi-Library System";
include '../app/views/shared/header.php'; 
include '../app/views/shared/layout-header.php'; 
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
    --red-gradient-start: #ef4444;
    --red-gradient-end: #dc2626;
}

.create-book-container {
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

.page-header h1 {
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.btn-back {
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
    padding: 0.6rem 1.25rem;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    font-size: 0.875rem;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-back:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-2px);
    color: white;
    box-shadow: 0 6px 20px rgba(255, 255, 255, 0.2);
}

/* Alert Messages */
.alert-danger-modern {
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(220, 38, 38, 0.1) 100%);
    border-left: 4px solid var(--red-gradient-start);
    border-radius: 12px;
    padding: 1rem 1.5rem;
    margin-bottom: 2rem;
    color: var(--grey-dark);
    display: flex;
    align-items: center;
    gap: 1rem;
}

/* Form Card */
.form-card {
    background: white;
    border-radius: 20px;
    padding: 2.5rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.form-section {
    margin-bottom: 2rem;
}

.form-section-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: var(--grey-dark);
    margin-bottom: 1.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid var(--grey-light);
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.form-section-title i {
    color: var(--primary-purple);
    font-size: 1.25rem;
}

.form-card label {
    font-weight: 700;
    color: var(--grey-dark);
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-card label i {
    color: var(--primary-purple);
    font-size: 1rem;
}

.form-card .form-control,
.form-card .form-select {
    border: 2px solid var(--grey-light);
    border-radius: 12px;
    padding: 0.875rem 1.25rem;
    font-size: 1rem;
    transition: all 0.3s;
    font-weight: 500;
    background-color: white;
}

.form-card .form-control:focus,
.form-card .form-select:focus {
    border-color: var(--primary-purple);
    box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.25);
    outline: none;
    background-color: white;
}

.form-card .form-control::placeholder {
    color: var(--grey-medium);
    opacity: 0.6;
}

.form-text {
    color: var(--grey-medium);
    font-size: 0.8rem;
    font-weight: 500;
    margin-top: 0.5rem;
}

.text-danger {
    color: var(--red-gradient-start) !important;
}

.text-muted {
    color: var(--grey-medium) !important;
    font-weight: 500;
}

/* Action Buttons */
.btn-cancel {
    background: linear-gradient(135deg, var(--grey-medium) 0%, var(--grey-dark) 100%);
    border: none;
    color: white;
    padding: 0.875rem 2rem;
    border-radius: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.875rem;
    transition: all 0.3s;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-cancel:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(107, 114, 128, 0.3);
    color: white;
}

.btn-submit {
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--dark-purple) 100%);
    border: none;
    color: white;
    padding: 0.875rem 2.5rem;
    border-radius: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.875rem;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(99, 102, 241, 0.35);
    color: white;
}

.btn-submit i {
    font-size: 1rem;
}

/* Input Icons */
.input-with-icon {
    position: relative;
}

.input-with-icon i {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--grey-medium);
    font-size: 1rem;
    z-index: 1;
    pointer-events: none;
}

.input-with-icon .form-control,
.input-with-icon .form-select {
    padding-left: 2.75rem;
    position: relative;
    z-index: 2;
    background: white;
}

/* Info Box */
.info-box {
    background: linear-gradient(135deg, rgba(99, 102, 241, 0.1) 0%, rgba(79, 70, 229, 0.1) 100%);
    border-left: 4px solid var(--primary-purple);
    border-radius: 12px;
    padding: 1.25rem 1.5rem;
    margin-top: 2rem;
    margin-bottom: 2rem;
}

.info-box i {
    color: var(--primary-purple);
    font-size: 1.5rem;
    margin-right: 1rem;
}

.info-box p {
    margin: 0;
    color: var(--grey-dark);
    font-weight: 500;
    line-height: 1.6;
}

.info-box strong {
    font-weight: 700;
}
</style>

<div class="container-fluid create-book-container">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <h1><i class="fas fa-book-medical"></i>Add New Book</h1>
            <a href="/librarian/books" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back to Books
            </a>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert-danger-modern alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i>
            <span><?= $_SESSION['error']; unset($_SESSION['error']); ?></span>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="form-card">
        <form method="POST" action="/librarian/create-book">
            <!-- Basic Information Section -->
            <div class="form-section">
                <h3 class="form-section-title">
                    <i class="fas fa-book"></i> Basic Information
                </h3>
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="title" class="form-label">
                                <i class="fas fa-heading"></i> Book Title <span class="text-danger">*</span>
                            </label>
                            <div class="input-with-icon">
                                <i class="fas fa-book"></i>
                                <input type="text" class="form-control" id="title" name="title" 
                                       placeholder="Enter book title" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="author" class="form-label">
                                <i class="fas fa-user-edit"></i> Author <span class="text-danger">*</span>
                            </label>
                            <div class="input-with-icon">
                                <i class="fas fa-pen-fancy"></i>
                                <input type="text" class="form-control" id="author" name="author" 
                                       placeholder="Enter author name" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="isbn" class="form-label">
                                <i class="fas fa-barcode"></i> ISBN
                            </label>
                            <div class="input-with-icon">
                                <i class="fas fa-hashtag"></i>
                                <input type="text" class="form-control" id="isbn" name="isbn" 
                                       placeholder="Enter ISBN number">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="publisher" class="form-label">
                                <i class="fas fa-building"></i> Publisher
                            </label>
                            <div class="input-with-icon">
                                <i class="fas fa-print"></i>
                                <input type="text" class="form-control" id="publisher" name="publisher" 
                                       placeholder="Enter publisher name">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Classification Section -->
            <div class="form-section">
                <h3 class="form-section-title">
                    <i class="fas fa-tags"></i> Classification & Copies
                    <a href="<?= BASE_PATH ?>/librarian/categories" class="btn btn-sm btn-outline-primary ms-3">Manage Categories</a>
                </h3>
                <div class="row g-4">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="publication_year" class="form-label">
                                <i class="fas fa-calendar-alt"></i> Publication Year
                            </label>
                            <div class="input-with-icon">
                                <i class="fas fa-calendar"></i>
                                <input type="number" class="form-control" id="publication_year" name="publication_year" 
                                       min="1900" max="<?= date('Y') ?>" value="<?= date('Y') ?>" 
                                       placeholder="<?= date('Y') ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="category" class="form-label">
                                <i class="fas fa-list"></i> Category
                            </label>
                            <div class="input-with-icon">
                                <i class="fas fa-folder"></i>
                                <select class="form-select" id="category" name="category">
                                    <option value="">Select Category</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= htmlspecialchars($category['name'] ?? $category['category']) ?>">
                                            <?= htmlspecialchars($category['name'] ?? $category['category']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="class_level" class="form-label">
                                <i class="fas fa-graduation-cap"></i> Class Level <small class="text-muted">(Optional)</small>
                            </label>
                            <div class="input-with-icon">
                                <i class="fas fa-chalkboard"></i>
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
                            </div>
                            <div class="form-text">Select specific class or leave blank for all</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="total_copies" class="form-label">
                                <i class="fas fa-copy"></i> Total Copies <span class="text-danger">*</span>
                            </label>
                            <div class="input-with-icon">
                                <i class="fas fa-sort-numeric-up"></i>
                                <input type="number" class="form-control" id="total_copies" name="total_copies" 
                                       value="1" min="1" placeholder="1" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Box -->
            <div class="info-box">
                <div class="d-flex align-items-start">
                    <i class="fas fa-info-circle"></i>
                    <div>
                        <p><strong>Note:</strong> All fields marked with <span class="text-danger">*</span> are required. Make sure to enter accurate information as this will be used for cataloging and tracking the book throughout its lifecycle in the library system.</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex gap-3 justify-content-end mt-4">
                <a href="/librarian/books" class="btn-cancel">
                    <i class="fas fa-times"></i> Cancel
                </a>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-plus-circle"></i> Add Book
                </button>
            </div>
            </form>
        </div>
    </div>
</div>

<?php include '../app/views/shared/footer.php'; ?>