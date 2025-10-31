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
                        <i class="fas fa-book-medical me-2"></i>Add New Book
                    </h1>
                    <p class="mb-0 text-muted">Add a new book to the selected library</p>
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
                        <form method="POST" id="createBookForm">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="library_id" class="form-label">Library *</label>
                                    <select name="library_id" id="library_id" class="form-select" required onchange="updateClassLevels()">
                                        <option value="">Select Library</option>
                                        <?php foreach ($libraries as $library): ?>
                                            <option value="<?php echo $library['id']; ?>" data-type="<?php echo $library['type']; ?>">
                                                <?php echo htmlspecialchars($library['name']); ?> (<?php echo ucfirst($library['type']); ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="class_level" class="form-label">Class Level <small class="text-muted">(Optional)</small></label>
                                    <select name="class_level" id="class_level" class="form-select">
                                        <option value="">All Classes</option>
                                    </select>
                                    <div class="form-text">Select a specific class or leave blank for all classes</div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-8">
                                    <label for="title" class="form-label">Book Title *</label>
                                    <input type="text" name="title" id="title" class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="isbn" class="form-label">ISBN</label>
                                    <input type="text" name="isbn" id="isbn" class="form-control" placeholder="978-0-123456-78-9">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="author" class="form-label">Author *</label>
                                    <input type="text" name="author" id="author" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="publisher" class="form-label">Publisher</label>
                                    <input type="text" name="publisher" id="publisher" class="form-control">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="category" class="form-label">Category</label>
                                    <input type="text" name="category" id="category" class="form-control" placeholder="e.g., Fiction, Science, History">
                                </div>
                                <div class="col-md-4">
                                    <label for="publication_year" class="form-label">Publication Year</label>
                                    <input type="number" name="publication_year" id="publication_year" class="form-control" 
                                           min="1800" max="<?php echo date('Y'); ?>" placeholder="<?php echo date('Y'); ?>">
                                </div>
                                <div class="col-md-4">
                                    <label for="total_copies" class="form-label">Total Copies *</label>
                                    <input type="number" name="total_copies" id="total_copies" class="form-control" 
                                           min="1" value="1" required>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="<?php echo BASE_PATH; ?>/admin/books" class="btn btn-outline-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Add Book
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
                            <i class="fas fa-info-circle me-2"></i>Guidelines
                        </h6>
                    </div>
                    <div class="card-body">
                        <h6>Class Level Information:</h6>
                        <ul class="list-unstyled">
                            <li><strong>Primary Schools:</strong> Classes 1-8</li>
                            <li><strong>Secondary Schools:</strong> Forms 1-4</li>
                            <li><strong>All Classes:</strong> Book available to all students</li>
                        </ul>

                        <hr>
                        
                        <h6>Required Fields:</h6>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-asterisk text-danger" style="font-size: 8px;"></i> Library</li>
                            <li><i class="fas fa-asterisk text-danger" style="font-size: 8px;"></i> Title</li>
                            <li><i class="fas fa-asterisk text-danger" style="font-size: 8px;"></i> Author</li>
                            <li><i class="fas fa-asterisk text-danger" style="font-size: 8px;"></i> Total Copies</li>
                        </ul>

                        <hr>

                        <h6>Tips:</h6>
                        <ul class="small">
                            <li>ISBN helps prevent duplicate entries</li>
                            <li>Category helps in book organization</li>
                            <li>Class level restricts book visibility</li>
                            <li>Leave class level blank for general access</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateClassLevels() {
    const librarySelect = document.getElementById('library_id');
    const classSelect = document.getElementById('class_level');
    const selectedOption = librarySelect.options[librarySelect.selectedIndex];
    
    // Clear existing options except "All Classes"
    classSelect.innerHTML = '<option value="">All Classes</option>';
    
    if (selectedOption.value) {
        const libraryType = selectedOption.getAttribute('data-type');
        const maxClass = libraryType === 'primary' ? 8 : 4;
        const classLabel = libraryType === 'primary' ? 'Class' : 'Form';
        
        for (let i = 1; i <= maxClass; i++) {
            const option = document.createElement('option');
            option.value = i;
            option.textContent = `${classLabel} ${i}`;
            classSelect.appendChild(option);
        }
    }
}

// Form validation
document.getElementById('createBookForm').addEventListener('submit', function(e) {
    const libraryId = document.getElementById('library_id').value;
    const title = document.getElementById('title').value.trim();
    const author = document.getElementById('author').value.trim();
    const totalCopies = document.getElementById('total_copies').value;
    
    if (!libraryId) {
        alert('Please select a library.');
        e.preventDefault();
        return;
    }
    
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
});
</script>

<?php include '../app/views/shared/footer.php'; ?>