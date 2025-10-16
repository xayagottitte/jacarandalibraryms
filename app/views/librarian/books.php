<?php 
$title = "Book Management - Multi-Library System";
include '../app/views/shared/header.php'; 
include '../app/views/shared/layout-header.php'; 
?>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="mb-0">Book Inventory</h2>
            <small id="bookCount" class="text-muted">Total: <?= count($books) ?> books</small>
        </div>
        <div class="d-flex gap-2 align-items-center">
            <span id="searchStatus" class="text-muted small" style="display: none;"></span>
            <input type="text" id="bookSearch" class="form-control form-control-sm" placeholder="Search by title, author, ISBN, category, or class" style="width: 350px;">
            <button class="btn btn-outline-secondary btn-sm" id="clearSearch" title="Clear search">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Modern Books Table -->
    <div class="modern-table-container">
        <div class="table-responsive">
            <table class="modern-table">
                    <thead>
                        <tr class="table-header">
                            <th>TITLE</th>
                            <th>AUTHOR</th>
                            <th>ISBN</th>
                            <th>CATEGORY</th>
                            <th>CLASS LEVEL</th>
                            <th>AVAILABLE</th>
                            <th>TOTAL</th>
                            <th>STATUS</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($books as $book): ?>
                            <tr class="table-row">
                                <td class="book-title"><?= htmlspecialchars($book['title']) ?></td>
                                <td><?= htmlspecialchars($book['author']) ?></td>
                                <td class="isbn-code"><?= htmlspecialchars($book['isbn'] ?? '') ?></td>
                                <td><?= htmlspecialchars($book['category'] ?? '') ?></td>
                                <td class="text-center">
                                    <?php if (!empty($book['class_level'])): ?>
                                        <span class="class-badge"><?= htmlspecialchars($book['class_level']) ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center"><span class="availability-number"><?= $book['available_copies'] ?></span></td>
                                <td class="text-center"><?= $book['total_copies'] ?></td>
                                <td>
                                    <?php if ($book['available_copies'] > 0): ?>
                                        <span class="status-badge available">Available</span>
                                    <?php else: ?>
                                        <span class="status-badge unavailable">Unavailable</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="action-btn view-btn" title="View" data-book-id="<?= $book['id'] ?>">VIEW</button>
                                        <button class="action-btn edit-btn" title="Edit" data-book-id="<?= $book['id'] ?>">EDIT</button>
                                        <?php if ($book['available_copies'] == $book['total_copies']): ?>
                                            <button class="action-btn delete-btn" title="Delete" 
                                                    onclick="confirmDelete('Are you sure you want to delete this book?', <?= $book['id'] ?>)">DELETE</button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <?php if (empty($books)): ?>
                    <div class="empty-state">
                        <i class="fas fa-book fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No books found. <a href="/librarian/create-book">Add your first book</a></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

<style>
/* Modern Table Styling */
.modern-table-container {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    overflow: hidden;
    margin: 0 auto;
    width: 90%;
    min-width: 1000px;
}

.table-responsive {
    border-radius: 12px;
    overflow: hidden;
}

.modern-table {
    width: 100%;
    margin-bottom: 0;
    border-collapse: collapse;
    font-size: 14px;
}

/* Table Header */
.table-header {
    background: linear-gradient(135deg, #663399 0%, #8a4baf 100%);
    color: white;
}

.table-header th {
    padding: 18px 16px;
    font-weight: 600;
    font-size: 12px;
    letter-spacing: 0.5px;
    text-align: left;
    border: none;
    white-space: nowrap;
}

/* Table Rows */
.table-row {
    transition: background-color 0.2s ease;
    border-bottom: 1px solid #f0f0f0;
}

.table-row:hover {
    background-color: #fafbfc;
}

.table-row:last-child {
    border-bottom: none;
}

.table-row td {
    padding: 16px;
    vertical-align: middle;
    border: none;
    color: #333;
}

/* Book Title Styling */
.book-title {
    font-weight: 500;
    color: #2d3748;
}

/* ISBN Code Styling */
.isbn-code {
    font-family: 'Courier New', monospace;
    background: #f7fafc;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 13px;
}

/* Availability Number */
.availability-number {
    font-weight: 600;
    color: #663399;
    font-size: 16px;
}

/* Status Badge */
.status-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}

.status-badge.available {
    background-color: #10b981;
    color: white;
}

.status-badge.unavailable {
    background-color: #ef4444;
    color: white;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 8px;
    justify-content: flex-start;
}

.action-btn {
    padding: 8px 16px;
    border: none;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    cursor: pointer;
    transition: all 0.2s ease;
    letter-spacing: 0.5px;
}

.view-btn {
    background-color: #0ea5e9;
    color: white;
}

.view-btn:hover {
    background-color: #0284c7;
    transform: translateY(-1px);
}

.edit-btn {
    background-color: #8b5cf6;
    color: white;
}

.edit-btn:hover {
    background-color: #7c3aed;
    transform: translateY(-1px);
}

.delete-btn {
    background-color: #ef4444;
    color: white;
}

.delete-btn:hover {
    background-color: #dc2626;
    transform: translateY(-1px);
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #6b7280;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .modern-table-container {
        width: 95%;
        min-width: auto;
    }
}

@media (max-width: 768px) {
    .modern-table-container {
        width: 100%;
        margin: 0;
        border-radius: 8px;
    }
    
    .table-header th,
    .table-row td {
        padding: 12px 8px;
        font-size: 12px;
    }
    
    .action-buttons {
        flex-direction: column;
        gap: 4px;
    }
    
    .action-btn {
        padding: 6px 12px;
        font-size: 10px;
    }
}

/* Ensure 90% browser width utilization */
body .main-content {
    max-width: none;
    width: 100%;
}

.container-fluid {
    padding-left: 2rem;
    padding-right: 2rem;
}

@media (min-width: 1400px) {
    .container-fluid {
        padding-left: 5%;
        padding-right: 5%;
    }
}

/* Search Functionality Styles */
#bookSearch {
    transition: all 0.3s ease;
    border: 2px solid #e9ecef;
}

#bookSearch:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    transform: scale(1.02);
}

#clearSearch {
    transition: all 0.2s ease;
    border: 2px solid #e9ecef;
}

#clearSearch:hover {
    background-color: #dc3545;
    border-color: #dc3545;
    color: white;
}

.table-row {
    transition: opacity 0.3s ease;
}

.table-row[style*="display: none"] {
    opacity: 0;
}

/* No results message styling */
#no-search-results {
    text-align: center;
    padding: 3rem;
    background: #f8f9fa;
    border-radius: 8px;
    margin: 2rem 0;
}

/* Search highlighting effect */
.search-highlight {
    background-color: #fff3cd;
    padding: 2px 4px;
    border-radius: 3px;
    font-weight: 600;
}

/* Class Level Badge */
.class-badge {
    display: inline-block;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    text-align: center;
    min-width: 30px;
    box-shadow: 0 2px 4px rgba(102, 126, 234, 0.3);
    transition: all 0.2s ease;
}

.class-badge:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(102, 126, 234, 0.4);
}
</style>

<script>
// Helper functions for search highlighting
function highlightText(element, searchTerm) {
    if (!element || !searchTerm) return;
    
    const originalText = element.textContent;
    const regex = new RegExp(`(${escapeRegex(searchTerm)})`, 'gi');
    const highlightedText = originalText.replace(regex, '<span class="search-highlight">$1</span>');
    
    if (highlightedText !== originalText) {
        element.innerHTML = highlightedText;
    }
}

function removeHighlight(element) {
    if (!element) return;
    
    const originalText = element.textContent;
    element.innerHTML = originalText;
}

function escapeRegex(string) {
    return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
}

function confirmDelete(message, bookId) {
    if (confirm(message)) {
        // Create a form to submit the delete request
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/jacarandalibraryms/librarian/delete-book';
        
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'id';
        input.value = bookId;
        
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }
}

// Add click handlers for view and edit buttons
document.addEventListener('DOMContentLoaded', function() {
    // View buttons
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const bookId = this.getAttribute('data-book-id');
            if (bookId) {
                window.location.href = '/jacarandalibraryms/librarian/view-book?id=' + bookId;
            }
        });
    });
    
    // Edit buttons  
    document.querySelectorAll('.edit-btn').forEach((btn) => {
        btn.addEventListener('click', function() {
            const bookId = this.getAttribute('data-book-id');
            if (bookId) {
                window.location.href = '/jacarandalibraryms/librarian/edit-book?id=' + bookId;
            }
        });
    });

    // Live Search Functionality
    const searchInput = document.getElementById('bookSearch');
    const clearButton = document.getElementById('clearSearch');
    const tableRows = document.querySelectorAll('.modern-table tbody .table-row');
    const emptyState = document.querySelector('.empty-state');
    
    if (searchInput && tableRows.length > 0) {
        // Search as you type
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            let visibleRows = 0;
            
            tableRows.forEach(row => {
                // Get original text content (remove any existing highlights)
                const titleCell = row.querySelector('.book-title');
                const authorCell = row.cells[1];
                const isbnCell = row.cells[2];
                const categoryCell = row.cells[3];
                
                const titleText = titleCell.textContent.toLowerCase();
                const authorText = authorCell.textContent.toLowerCase();
                const isbnText = isbnCell.textContent.toLowerCase();
                const categoryText = categoryCell.textContent.toLowerCase();
                const classLevelCell = row.cells[4];
                const classLevelText = classLevelCell.textContent.toLowerCase();
                
                const isMatch = titleText.includes(searchTerm) || 
                               authorText.includes(searchTerm) || 
                               isbnText.includes(searchTerm) || 
                               categoryText.includes(searchTerm) ||
                               classLevelText.includes(searchTerm);
                
                if (isMatch || searchTerm === '') {
                    row.style.display = '';
                    visibleRows++;
                    
                    // Add highlighting if there's a search term
                    if (searchTerm !== '') {
                        highlightText(titleCell, searchTerm);
                        highlightText(authorCell, searchTerm);
                        highlightText(isbnCell, searchTerm);
                        highlightText(categoryCell, searchTerm);
                        highlightText(classLevelCell, searchTerm);
                    } else {
                        // Remove highlighting when search is cleared
                        removeHighlight(titleCell);
                        removeHighlight(authorCell);
                        removeHighlight(isbnCell);
                        removeHighlight(categoryCell);
                        removeHighlight(classLevelCell);
                    }
                } else {
                    row.style.display = 'none';
                }
            });
            
            // Update search status
            const searchStatus = document.getElementById('searchStatus');
            const bookCount = document.getElementById('bookCount');
            
            if (searchTerm !== '') {
                if (visibleRows === 0) {
                    searchStatus.textContent = 'No results found';
                    searchStatus.style.color = '#dc3545';
                } else {
                    searchStatus.textContent = `${visibleRows} result${visibleRows !== 1 ? 's' : ''} found`;
                    searchStatus.style.color = '#28a745';
                }
                searchStatus.style.display = 'inline';
                bookCount.style.display = 'none';
            } else {
                searchStatus.style.display = 'none';
                bookCount.style.display = 'inline';
            }
            
            // Show/hide empty state message
            const tableContainer = document.querySelector('.modern-table-container');
            let noResultsMsg = document.getElementById('no-search-results');
            
            if (visibleRows === 0 && searchTerm !== '') {
                if (!noResultsMsg) {
                    noResultsMsg = document.createElement('div');
                    noResultsMsg.id = 'no-search-results';
                    noResultsMsg.className = 'empty-state';
                    noResultsMsg.innerHTML = `
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No books found matching "<strong>${searchTerm}</strong>"</p>
                        <button class="btn btn-outline-primary" onclick="document.getElementById('bookSearch').value=''; document.getElementById('bookSearch').dispatchEvent(new Event('input'));">Clear Search</button>
                    `;
                    tableContainer.appendChild(noResultsMsg);
                }
                noResultsMsg.style.display = 'block';
            } else if (noResultsMsg) {
                noResultsMsg.style.display = 'none';
            }
            
            // Update clear button visibility
            clearButton.style.display = searchTerm ? 'block' : 'none';
        });
        
        // Clear search functionality
        clearButton.addEventListener('click', function() {
            searchInput.value = '';
            searchInput.dispatchEvent(new Event('input'));
            searchInput.focus();
        });
        
        // Keyboard shortcuts
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                this.value = '';
                this.dispatchEvent(new Event('input'));
                this.blur();
            }
        });
        
        // Initially hide clear button
        clearButton.style.display = 'none';
        
        // Focus search input when user starts typing (anywhere on page)
        document.addEventListener('keydown', function(e) {
            // Only if not already focused on an input and it's a letter/number
            if (!['INPUT', 'TEXTAREA'].includes(document.activeElement.tagName) && 
                e.key.match(/[a-zA-Z0-9]/)) {
                searchInput.focus();
            }
        });
    }
});
</script>

<?php include '../app/views/shared/layout-footer.php'; ?>
<?php include '../app/views/shared/footer.php'; ?>