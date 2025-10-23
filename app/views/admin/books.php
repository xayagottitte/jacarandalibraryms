<?php include '../app/views/shared/header.php'; ?>
<?php include '../app/views/shared/navbar.php'; ?>
<?php include '../app/views/shared/layout-header.php'; ?>

<style>
    :root {
        --jacaranda-primary: #663399;
        --jacaranda-secondary: #8a4baf;
        --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --gradient-success: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        --gradient-warning: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        --gradient-info: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        --shadow-light: 0 4px 20px rgba(0,0,0,0.1);
        --shadow-hover: 0 8px 30px rgba(0,0,0,0.15);
    }

    .modern-dashboard {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
        padding: 1.5rem 0;
    }

    .page-header {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: var(--shadow-light);
        border-left: 4px solid var(--jacaranda-primary);
    }

    .page-header h1 {
        background: var(--jacaranda-primary);
        background: linear-gradient(135deg, var(--jacaranda-primary) 0%, var(--jacaranda-secondary) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-weight: 600;
        margin: 0;
    }

    .stat-card-gradient {
        border: none;
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: var(--shadow-light);
        color: white;
    }
    
    .stat-card-gradient:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-hover);
    }
    
    .stat-card-gradient.primary {
        background: var(--gradient-primary);
    }
    
    .stat-card-gradient.success {
        background: var(--gradient-success);
    }
    
    .stat-card-gradient.warning {
        background: var(--gradient-warning);
    }
    
    .stat-card-gradient.info {
        background: var(--gradient-info);
    }
    
    .stat-card-gradient .card-body {
        position: relative;
        overflow: hidden;
    }
    
    .stat-card-gradient .icon-bg {
        position: absolute;
        right: -10px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 4rem;
        opacity: 0.2;
    }

    .modern-card .card-header {
        background: linear-gradient(135deg, var(--jacaranda-primary) 0%, var(--jacaranda-secondary) 100%) !important;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--jacaranda-primary) 0%, var(--jacaranda-secondary) 100%);
        border: none;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 51, 153, 0.3);
    }
</style>

<div class="main-content modern-dashboard">
    <div class="container-fluid px-4">
        <div class="page-header d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-2">Book Management</h1>
                <?php if ($selected_library_id): ?>
                    <?php 
                    $selectedLibrary = array_filter($libraries, function($lib) use ($selected_library_id) {
                        return $lib['id'] == $selected_library_id;
                    });
                    $selectedLibrary = reset($selectedLibrary);
                    ?>
                    <p class="mb-0 text-muted"><i class="fas fa-book me-2" style="color: var(--jacaranda-primary);"></i>Showing books for: <strong><?php echo htmlspecialchars($selectedLibrary['name']); ?></strong></p>
                <?php else: ?>
                    <p class="mb-0 text-muted"><i class="fas fa-book me-2" style="color: var(--jacaranda-primary);"></i>Showing books from all libraries</p>
                <?php endif; ?>
            </div>
            <a href="<?php echo BASE_PATH; ?>/admin/create-book" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add New Book
            </a>
        </div>

        <!-- Search and Filters -->
        <div class="card shadow mb-4">
            <div class="card-header py-3" style="background: linear-gradient(135deg, var(--jacaranda-primary) 0%, var(--jacaranda-secondary) 100%);">
                <h6 class="m-0 font-weight-bold text-white">
                    <i class="fas fa-search me-2"></i>Search & Filter Books
                </h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="liveSearch" class="form-label">
                            <i class="fas fa-search me-1"></i>Search (Live)
                        </label>
                        <input type="text" id="liveSearch" class="form-control" 
                               placeholder="Type to search title, author, ISBN..." 
                               autocomplete="off">
                    </div>
                    
                    <div class="col-md-2">
                        <label for="categoryFilter" class="form-label">
                            <i class="fas fa-tag me-1"></i>Category
                        </label>
                        <select id="categoryFilter" class="form-select">
                            <option value="">All Categories</option>
                            <?php 
                            // Get all unique categories from all books
                            $allCategories = array_unique(array_filter(array_column($all_books, 'category')));
                            foreach ($allCategories as $category): ?>
                                <option value="<?php echo htmlspecialchars($category); ?>">
                                    <?php echo htmlspecialchars($category); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <label for="libraryFilter" class="form-label">
                            <i class="fas fa-building me-1"></i>Library
                        </label>
                        <select id="libraryFilter" class="form-select">
                            <option value="">All Libraries</option>
                            <?php foreach ($libraries as $library): ?>
                                <option value="<?php echo $library['id']; ?>">
                                    <?php echo htmlspecialchars($library['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <label for="statusFilter" class="form-label">
                            <i class="fas fa-check-circle me-1"></i>Availability
                        </label>
                        <select id="statusFilter" class="form-select">
                            <option value="">All Books</option>
                            <option value="available">Available</option>
                            <option value="unavailable">Unavailable</option>
                        </select>
                    </div>
                    
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" id="clearFilters" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-times me-1"></i>Clear Filters
                        </button>
                    </div>
                    
                    <div class="col-12">
                        <span class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Showing <span id="bookCount"><?php echo count($all_books); ?></span> of <?php echo count($all_books); ?> books
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Books Overview & Statistics -->
        <div class="row mb-4">
            <!-- Statistics Cards -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card-gradient primary h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1" style="opacity: 0.9;">
                                    Total Books<?php echo $selected_library_id ? ' (Selected Library)' : ' (All Libraries)'; ?>
                                </div>
                                <div class="h5 mb-0 font-weight-bold">
                                    <?php echo $book_stats['total_books'] ?? 0; ?>
                                </div>
                            </div>
                        </div>
                        <i class="fas fa-book icon-bg"></i>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card-gradient success h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1" style="opacity: 0.9;">
                                    Total Copies<?php echo $selected_library_id ? ' (Selected Library)' : ' (All Libraries)'; ?>
                                </div>
                                <div class="h5 mb-0 font-weight-bold">
                                    <?php echo $book_stats['total_copies'] ?? 0; ?>
                                </div>
                            </div>
                        </div>
                        <i class="fas fa-layer-group icon-bg"></i>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card-gradient info h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1" style="opacity: 0.9;">
                                    Available<?php echo $selected_library_id ? ' (Selected Library)' : ' (All Libraries)'; ?>
                                </div>
                                <div class="h5 mb-0 font-weight-bold">
                                    <?php echo $book_stats['available_copies'] ?? 0; ?>
                                </div>
                            </div>
                        </div>
                        <i class="fas fa-check-circle icon-bg"></i>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card-gradient warning h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1" style="opacity: 0.9;">
                                    Borrowed<?php echo $selected_library_id ? ' (Selected Library)' : ' (All Libraries)'; ?>
                                </div>
                                <div class="h5 mb-0 font-weight-bold">
                                    <?php echo $book_stats['borrowed_copies'] ?? 0; ?>
                                </div>
                            </div>
                        </div>
                        <i class="fas fa-hand-holding icon-bg"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- All Books Table -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, var(--jacaranda-primary) 0%, var(--jacaranda-secondary) 100%);">
                <h6 class="m-0 font-weight-bold text-white">
                    <?php if ($selected_library_id): ?>
                        <?php 
                        $selectedLibrary = array_filter($libraries, function($lib) use ($selected_library_id) {
                            return $lib['id'] == $selected_library_id;
                        });
                        $selectedLibrary = reset($selectedLibrary);
                        ?>
                        <i class="fas fa-book me-2"></i><?php echo htmlspecialchars($selectedLibrary['name']); ?> Books (<span id="totalCount"><?php echo count($all_books); ?></span> books)
                    <?php else: ?>
                        <i class="fas fa-book me-2"></i>All Books Overview (<span id="totalCount"><?php echo count($all_books); ?></span> books)
                    <?php endif; ?>
                </h6>
                <div class="btn-group">
                    <button class="btn btn-sm btn-light" onclick="toggleCategoryStats()">
                        <i class="fas fa-chart-pie me-1"></i>Category Stats
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead style="background: linear-gradient(135deg, var(--jacaranda-primary) 0%, var(--jacaranda-secondary) 100%); color: white;">
                            <tr>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Category</th>
                                <th>Class Level</th>
                                <th>Library</th>
                                <th>Copies</th>
                                <th>Available</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="booksTableBody">
                            <?php if (!empty($all_books)): ?>
                                <?php foreach ($all_books as $book): ?>
                                <tr class="book-row"
                                    data-title="<?php echo strtolower(htmlspecialchars($book['title'])); ?>"
                                    data-author="<?php echo strtolower(htmlspecialchars($book['author'])); ?>"
                                    data-isbn="<?php echo strtolower(htmlspecialchars($book['isbn'] ?? '')); ?>"
                                    data-category="<?php echo strtolower(htmlspecialchars($book['category'] ?? '')); ?>"
                                    data-library-id="<?php echo $book['library_id']; ?>"
                                    data-available="<?php echo $book['available_copies'] > 0 ? 'yes' : 'no'; ?>">
                                    <td><strong><?php echo htmlspecialchars($book['title']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($book['author']); ?></td>
                                    <td>
                                        <?php if ($book['category']): ?>
                                            <span class="badge bg-info"><?php echo htmlspecialchars($book['category']); ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">Uncategorized</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($book['class_level']): ?>
                                            <span class="badge bg-success"><?php echo htmlspecialchars($book['class_level']); ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php echo $book['library_type'] === 'primary' ? 'primary' : 'secondary'; ?>">
                                            <?php echo htmlspecialchars($book['library_name']); ?>
                                        </span>
                                    </td>
                                    <td class="text-center"><?php echo $book['total_copies']; ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-<?php echo $book['available_copies'] > 0 ? 'success' : 'danger'; ?>">
                                            <?php echo $book['available_copies']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($book['available_copies'] > 0): ?>
                                            <span class="badge bg-success">Available</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning">All Borrowed</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?php echo BASE_PATH; ?>/admin/edit-book/<?php echo $book['id']; ?>" 
                                               class="btn btn-sm btn-outline-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger btn-delete-book" 
                                                    data-book-id="<?php echo $book['id']; ?>"
                                                    data-book-title="<?php echo htmlspecialchars($book['title']); ?>"
                                                    title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="fas fa-book fa-3x mb-3 text-gray-300"></i>
                                    <?php if ($selected_library_id): ?>
                                        <p>No books found in the selected library matching your criteria.</p>
                                    <?php else: ?>
                                        <p>No books found matching your criteria.</p>
                                    <?php endif; ?>
                                    <div class="mt-3">
                                        <a href="<?php echo BASE_PATH; ?>/admin/create-book" class="btn btn-primary me-2">
                                            <i class="fas fa-plus me-2"></i>Add New Book
                                        </a>
                                        <?php if ($filters['search'] || $filters['category'] || $filters['library_filter'] || $filters['status']): ?>
                                            <a href="<?php echo BASE_PATH; ?>/admin/books" class="btn btn-outline-secondary">
                                                <i class="fas fa-times me-2"></i>Clear Filters
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Category Statistics (Hidden by default) -->
        <div class="card shadow mb-4" id="categoryStats" style="display: none;">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Books by Category</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead class="table-light">
                            <tr>
                                <th>Category</th>
                                <th>Library</th>
                                <th>Books</th>
                                <th>Total Copies</th>
                                <th>Available</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($category_stats)): ?>
                                <?php foreach ($category_stats as $stat): ?>
                                <tr>
                                    <td><span class="badge bg-info"><?php echo htmlspecialchars($stat['category']); ?></span></td>
                                    <td><?php echo htmlspecialchars($stat['library_name']); ?></td>
                                    <td class="text-center"><?php echo $stat['book_count']; ?></td>
                                    <td class="text-center"><?php echo $stat['total_copies']; ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-success"><?php echo $stat['available_copies']; ?></span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted">No categorized books found.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete "<span id="deleteBookTitle"></span>"?</p>
                <p class="text-danger"><i class="fas fa-exclamation-triangle"></i> This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="<?php echo BASE_PATH; ?>/admin/delete-book" style="display: inline;">
                    <input type="hidden" name="id" id="deleteBookId">
                    <button type="submit" class="btn btn-danger">Delete Book</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Live Search and Filter Functionality
document.addEventListener('DOMContentLoaded', function() {
    const liveSearch = document.getElementById('liveSearch');
    const categoryFilter = document.getElementById('categoryFilter');
    const libraryFilter = document.getElementById('libraryFilter');
    const statusFilter = document.getElementById('statusFilter');
    const clearFilters = document.getElementById('clearFilters');
    const bookRows = document.querySelectorAll('.book-row');
    const bookCount = document.getElementById('bookCount');
    const totalCount = document.getElementById('totalCount');
    
    // Live search function
    function filterBooks() {
        const searchTerm = liveSearch.value.toLowerCase();
        const categoryValue = categoryFilter.value.toLowerCase();
        const libraryValue = libraryFilter.value;
        const statusValue = statusFilter.value;
        
        let visible = 0;
        
        bookRows.forEach(row => {
            const title = row.dataset.title || '';
            const author = row.dataset.author || '';
            const isbn = row.dataset.isbn || '';
            const category = row.dataset.category || '';
            const libraryId = row.dataset.libraryId || '';
            const available = row.dataset.available || '';
            
            let matchesSearch = true;
            let matchesCategory = true;
            let matchesLibrary = true;
            let matchesStatus = true;
            
            // Check search term (title, author, or ISBN)
            if (searchTerm) {
                matchesSearch = title.includes(searchTerm) || 
                               author.includes(searchTerm) || 
                               isbn.includes(searchTerm);
            }
            
            // Check category filter
            if (categoryValue) {
                matchesCategory = category === categoryValue;
            }
            
            // Check library filter
            if (libraryValue) {
                matchesLibrary = libraryId === libraryValue;
            }
            
            // Check status filter
            if (statusValue === 'available') {
                matchesStatus = available === 'yes';
            } else if (statusValue === 'unavailable') {
                matchesStatus = available === 'no';
            }
            
            // Show/hide row based on all filters
            if (matchesSearch && matchesCategory && matchesLibrary && matchesStatus) {
                row.style.display = '';
                visible++;
            } else {
                row.style.display = 'none';
            }
        });
        
        bookCount.textContent = visible;
        totalCount.textContent = visible;
    }
    
    // Attach event listeners
    liveSearch.addEventListener('input', filterBooks);
    categoryFilter.addEventListener('change', filterBooks);
    libraryFilter.addEventListener('change', filterBooks);
    statusFilter.addEventListener('change', filterBooks);
    
    // Clear all filters
    clearFilters.addEventListener('click', function() {
        liveSearch.value = '';
        categoryFilter.value = '';
        libraryFilter.value = '';
        statusFilter.value = '';
        filterBooks();
    });
    
    // Delete book event listeners
    const deleteButtons = document.querySelectorAll('.btn-delete-book');
    deleteButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const bookId = this.dataset.bookId;
            const bookTitle = this.dataset.bookTitle;
            if (bookId && bookTitle) {
                confirmDeleteBook(bookId, bookTitle);
            }
        });
    });
});

function confirmDeleteBook(bookId, bookTitle) {
    document.getElementById('deleteBookId').value = bookId;
    document.getElementById('deleteBookTitle').textContent = bookTitle;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

function toggleCategoryStats() {
    const categoryStatsDiv = document.getElementById('categoryStats');
    if (categoryStatsDiv.style.display === 'none' || categoryStatsDiv.style.display === '') {
        categoryStatsDiv.style.display = 'block';
        categoryStatsDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    } else {
        categoryStatsDiv.style.display = 'none';
    }
}

// Auto-dismiss alerts after 5 seconds
setTimeout(function() {
    var alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach(function(alert) {
        var bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
    });
}, 5000);
</script>

<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.table-hover tbody tr:hover {
    background-color: rgba(0,0,0,.025);
}

.badge {
    font-size: 0.75em;
}
</style>

<?php include '../app/views/shared/footer.php'; ?>