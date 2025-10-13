<?php include '../app/views/shared/header.php'; ?>
<?php include '../app/views/shared/navbar.php'; ?>
<?php include '../app/views/shared/layout-header.php'; ?>

<div class="main-content">
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">Book Management</h1>
                <?php if ($selected_library_id): ?>
                    <?php 
                    $selectedLibrary = array_filter($libraries, function($lib) use ($selected_library_id) {
                        return $lib['id'] == $selected_library_id;
                    });
                    $selectedLibrary = reset($selectedLibrary);
                    ?>
                    <p class="mb-0 text-muted">Showing books for: <strong><?php echo htmlspecialchars($selectedLibrary['name']); ?></strong></p>
                <?php else: ?>
                    <p class="mb-0 text-muted">Showing books from all libraries</p>
                <?php endif; ?>
            </div>
            <a href="<?php echo BASE_PATH; ?>/admin/create-book" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add New Book
            </a>
        </div>

        <!-- Search and Filters -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Search & Filter Books</h6>
            </div>
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" name="search" id="search" class="form-control" 
                               placeholder="Title, Author, ISBN..." 
                               value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                    </div>
                    
                    <div class="col-md-3">
                        <label for="category" class="form-label">Category</label>
                        <select name="category" id="category" class="form-select">
                            <option value="">All Categories</option>
                            <?php 
                            // Get all unique categories from all books
                            $allCategories = array_unique(array_filter(array_column($all_books, 'category')));
                            foreach ($allCategories as $category): ?>
                                <option value="<?php echo htmlspecialchars($category); ?>" 
                                        <?php echo ($_GET['category'] ?? '') === $category ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($category); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <label for="library_filter" class="form-label">Library</label>
                        <select name="library_filter" id="library_filter" class="form-select">
                            <option value="">All Libraries</option>
                            <?php foreach ($libraries as $library): ?>
                                <option value="<?php echo $library['id']; ?>" 
                                        <?php echo ($_GET['library_filter'] ?? '') == $library['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($library['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <label for="status" class="form-label">Availability</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">All Books</option>
                            <option value="available" <?php echo ($_GET['status'] ?? '') === 'available' ? 'selected' : ''; ?>>Available</option>
                            <option value="unavailable" <?php echo ($_GET['status'] ?? '') === 'unavailable' ? 'selected' : ''; ?>>Unavailable</option>
                        </select>
                    </div>
                    
                    <div class="col-md-2 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i>Filter
                        </button>
                        <a href="<?php echo BASE_PATH; ?>/admin/books" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Clear
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Books Overview & Statistics -->
        <div class="row mb-4">
            <!-- Statistics Cards -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Books<?php echo $selected_library_id ? ' (Selected Library)' : ' (All Libraries)'; ?>
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?php echo $book_stats['total_books'] ?? 0; ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-book fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Total Copies<?php echo $selected_library_id ? ' (Selected Library)' : ' (All Libraries)'; ?>
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?php echo $book_stats['total_copies'] ?? 0; ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-layer-group fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Available<?php echo $selected_library_id ? ' (Selected Library)' : ' (All Libraries)'; ?>
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?php echo $book_stats['available_copies'] ?? 0; ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Borrowed<?php echo $selected_library_id ? ' (Selected Library)' : ' (All Libraries)'; ?>
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?php echo $book_stats['borrowed_copies'] ?? 0; ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-hand-holding fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- All Books Table -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <?php if ($selected_library_id): ?>
                        <?php 
                        $selectedLibrary = array_filter($libraries, function($lib) use ($selected_library_id) {
                            return $lib['id'] == $selected_library_id;
                        });
                        $selectedLibrary = reset($selectedLibrary);
                        ?>
                        <?php echo htmlspecialchars($selectedLibrary['name']); ?> Books (<?php echo count($all_books); ?> books)
                    <?php else: ?>
                        All Books Overview (<?php echo count($all_books); ?> books)
                    <?php endif; ?>
                </h6>
                <div class="btn-group">
                    <button class="btn btn-sm btn-outline-primary" onclick="toggleCategoryStats()">
                        <i class="fas fa-chart-pie me-1"></i>Category Stats
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead class="table-light">
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
                        <tbody>
                            <?php if (!empty($all_books)): ?>
                                <?php foreach ($all_books as $book): ?>
                                <tr>
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
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="confirmDelete(<?php echo $book['id']; ?>, '<?php echo htmlspecialchars($book['title']); ?>')" 
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
function confirmDelete(bookId, bookTitle) {
    document.getElementById('deleteBookId').value = bookId;
    document.getElementById('deleteBookTitle').textContent = bookTitle;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

function toggleCategoryStats() {
    const categoryStatsDiv = document.getElementById('categoryStats');
    if (categoryStatsDiv.style.display === 'none') {
        categoryStatsDiv.style.display = 'block';
    } else {
        categoryStatsDiv.style.display = 'none';
    }
}
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