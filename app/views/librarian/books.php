<?php 
$title = "Book Management - Multi-Library System";
include '../app/views/shared/header.php'; 
include '../app/views/shared/layout-header.php'; 
?>

<style>
/* Modern Dashboard Color Variables */
:root {
    --primary-purple: #6366f1;
    --dark-purple: #4f46e5;
    --light-purple: #818cf8;
    --accent-purple: #a78bfa;
    --grey-dark: #374151;
    --grey-medium: #6b7280;
    --grey-light: #e5e7eb;
    --grey-lighter: #f3f4f6;
    --white: #ffffff;
    --red-gradient-start: #ef4444;
    --red-gradient-end: #dc2626;
    --success-gradient-start: #10b981;
    --success-gradient-end: #059669;
}

.books-container {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    min-height: 100vh;
    padding: 2rem 0;
}

.page-header {
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--dark-purple) 100%);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 10px 40px rgba(99, 102, 241, 0.2);
    color: white;
}

.page-header h2 {
    font-weight: 700;
    font-size: 2rem;
    margin: 0;
    color: white;
}

.page-header small {
    color: rgba(255, 255, 255, 0.85);
    font-size: 1rem;
}

.search-section {
    display: flex;
    gap: 0.75rem;
    align-items: center;
}

.search-input {
    border: 2px solid rgba(255, 255, 255, 0.3);
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    color: white;
    padding: 0.75rem 1.25rem;
    border-radius: 50px;
    width: 350px;
    font-size: 0.95rem;
}

.search-input::placeholder {
    color: rgba(255, 255, 255, 0.7);
}

.search-input:focus {
    background: rgba(255, 255, 255, 0.25);
    border-color: rgba(255, 255, 255, 0.5);
    outline: none;
    color: white;
}

.btn-clear {
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
    padding: 0.75rem 1rem;
    border-radius: 50px;
    transition: all 0.3s;
}

.btn-clear:hover {
    background: rgba(255, 255, 255, 0.3);
    color: white;
}

.search-status {
    color: rgba(255, 255, 255, 0.85);
    font-size: 0.875rem;
}

/* Modern Alert Messages */
.alert-modern {
    border-radius: 15px;
    border: none;
    padding: 1.25rem 1.5rem;
    font-weight: 500;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    margin-bottom: 1.5rem;
}

.alert-success-modern {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    color: #065f46;
}

.alert-danger-modern {
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    color: #991b1b;
}

/* Modern Table Container */
.modern-table-container {
    background: white;
    border-radius: 20px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
}

.table-responsive {
    border-radius: 20px;
    overflow-x: auto;
}

.modern-table {
    width: 100%;
    margin-bottom: 0;
    border-collapse: collapse;
    font-size: 0.9rem;
}

/* Table Header */
.table-header {
    background: linear-gradient(135deg, #7c3aed 0%, #6366f1 100%);
    border-bottom: none;
}

.table-header th {
    padding: 1rem;
    font-weight: 600;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
    text-align: left;
    border: none;
    white-space: nowrap;
    color: white;
    text-transform: uppercase;
}

/* Table Rows */
.table-row {
    transition: all 0.2s ease;
    border-bottom: 1px solid var(--grey-light);
}

.table-row:hover {
    background: var(--grey-lighter);
    transform: scale(1.005);
}

.table-row:last-child {
    border-bottom: none;
}

.table-row td {
    padding: 1.25rem 1rem;
    vertical-align: middle;
    border: none;
    color: var(--grey-medium);
    font-weight: 500;
}

/* Book Title Styling */
.book-title {
    font-weight: 600;
    color: var(--grey-dark);
    font-size: 1rem;
}

/* ISBN Code Styling */
.isbn-code {
    font-family: 'Courier New', monospace;
    background: var(--grey-lighter);
    padding: 0.5rem 0.75rem;
    border-radius: 8px;
    font-size: 0.85rem;
    color: var(--grey-dark);
    font-weight: 600;
}

/* Class Badge */
.class-badge {
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--dark-purple) 100%);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: inline-block;
}

/* Availability Number */
.availability-number {
    font-weight: 700;
    color: var(--primary-purple);
    font-size: 1.25rem;
}

/* Status Badge */
.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: inline-block;
}

.status-badge.available {
    background: linear-gradient(135deg, var(--success-gradient-start) 0%, var(--success-gradient-end) 100%);
    color: white;
}

.status-badge.unavailable {
    background: linear-gradient(135deg, var(--red-gradient-start) 0%, var(--red-gradient-end) 100%);
    color: white;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    flex-direction: row;
    gap: 0.5rem;
    flex-wrap: nowrap;
    align-items: center;
}

.action-btn {
    padding: 0.5rem 1rem;
    border-radius: 8px;
    border: none;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    cursor: pointer;
    transition: all 0.3s;
    white-space: nowrap;
    flex-shrink: 0;
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.view-btn {
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--dark-purple) 100%);
    color: white;
}

.edit-btn {
    background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
    color: white;
}

.delete-btn {
    background: linear-gradient(135deg, var(--red-gradient-start) 0%, var(--red-gradient-end) 100%);
    color: white;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
}

.empty-state i {
    color: var(--grey-medium);
}

.empty-state p {
    color: var(--grey-medium);
    font-size: 1.125rem;
    margin-top: 1rem;
}

.empty-state a {
    color: var(--primary-purple);
    font-weight: 600;
    text-decoration: none;
}

.empty-state a:hover {
    text-decoration: underline;
}
</style>

<div class="container-fluid books-container">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2><i class="fas fa-book me-3"></i>Book Inventory</h2>
                <small id="bookCount">Total: <?= count($books) ?> books</small>
            </div>
            <div class="d-flex gap-3 align-items-center flex-wrap">
                <div class="search-section">
                    <span id="searchStatus" class="search-status" style="display: none;"></span>
                    <input type="text" id="bookSearch" class="search-input" placeholder="Search by title, author, ISBN, category, or class">
                    <button class="btn btn-clear" id="clearSearch" title="Clear search">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <a href="<?= BASE_PATH ?>/librarian/create-book" class="btn btn-light px-4 py-2" style="border-radius: 50px; font-weight: 600;">
                    <i class="fas fa-plus me-2"></i>Add Book
                </a>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success-modern alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i><?= $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger-modern alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><?= $_SESSION['error']; unset($_SESSION['error']); ?>
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
                            <th class="text-center">AVAILABLE</th>
                            <th class="text-center">TOTAL</th>
                            <th>STATUS</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($books as $book): ?>
                            <tr class="table-row">
                                <td class="book-title"><?= htmlspecialchars($book['title']) ?></td>
                                <td><?= htmlspecialchars($book['author']) ?></td>
                                <td><span class="isbn-code"><?= htmlspecialchars($book['isbn'] ?? '') ?></span></td>
                                <td><?= htmlspecialchars($book['category'] ?? '') ?></td>
                                <td class="text-center">
                                    <?php if (!empty($book['class_level'])): ?>
                                        <span class="class-badge"><?= htmlspecialchars($book['class_level']) ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center"><span class="availability-number"><?= $book['available_copies'] ?></span></td>
                                <td class="text-center"><strong><?= $book['total_copies'] ?></strong></td>
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
                        <i class="fas fa-book fa-3x"></i>
                        <p>No books found. <a href="<?= BASE_PATH ?>/librarian/create-book">Add your first book</a></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
</div>

<style>
/* Additional Table Styles */
.modern-table-container {
    width: 98%;
    min-width: 1200px;
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
    table-layout: auto;
}

/* Table Header */
.table-header {
    background: linear-gradient(135deg, #663399 0%, #8a4baf 100%);
    color: white;
}

.table-header th {
    padding: 1rem;
    font-weight: 600;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
    text-align: left;
    border: none;
    white-space: nowrap;
}

/* Specific column widths */
.table-header th:nth-child(1) { width: auto; } /* TITLE */
.table-header th:nth-child(2) { width: auto; } /* AUTHOR */
.table-header th:nth-child(3) { width: 120px; } /* ISBN */
.table-header th:nth-child(4) { width: 110px; } /* CATEGORY */
.table-header th:nth-child(5) { width: 100px; } /* CLASS LEVEL */
.table-header th:nth-child(6) { width: 90px; text-align: center; } /* AVAILABLE */
.table-header th:nth-child(7) { width: 80px; text-align: center; } /* TOTAL */
.table-header th:nth-child(8) { width: 120px; } /* STATUS */
.table-header th:nth-child(9) { width: 280px; } /* ACTIONS - increased width */

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
    flex-direction: row;
    gap: 8px;
    justify-content: flex-start;
    align-items: center;
    flex-wrap: nowrap;
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
    white-space: nowrap;
    flex-shrink: 0;
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