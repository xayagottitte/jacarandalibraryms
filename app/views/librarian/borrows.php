<?php 
$title = "Borrow Management - Multi-Library System";
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
    --warning-gradient-start: #f59e0b;
    --warning-gradient-end: #d97706;
}

.borrows-container {
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

.page-header h1 {
    font-weight: 700;
    font-size: 2rem;
    margin: 0;
    color: white;
}

.header-actions {
    display: flex;
    gap: 0.75rem;
}

.btn-header {
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
    padding: 0.75rem 1.5rem;
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

.btn-header:hover {
    background: rgba(255, 255, 255, 0.3);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}

.btn-header.success {
    background: linear-gradient(135deg, var(--success-gradient-start) 0%, var(--success-gradient-end) 100%);
    border: none;
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

/* Statistics Cards */
.stat-card-modern {
    border-radius: 20px;
    border: none;
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    height: 100%;
}

.stat-card-modern:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
}

.stat-card-modern .card-body {
    padding: 2rem;
}

.stat-card-modern .stat-icon {
    width: 70px;
    height: 70px;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    margin-bottom: 1.5rem;
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
}

.stat-card-modern h5 {
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    opacity: 0.9;
    margin-bottom: 0.5rem;
}

.stat-card-modern h2 {
    font-size: 2.5rem;
    font-weight: 700;
    margin: 0;
}

.card-purple {
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--dark-purple) 100%);
    color: white;
}

.card-warning {
    background: linear-gradient(135deg, var(--warning-gradient-start) 0%, var(--warning-gradient-end) 100%);
    color: white;
}

.card-danger {
    background: linear-gradient(135deg, var(--red-gradient-start) 0%, var(--red-gradient-end) 100%);
    color: white;
}

.card-success {
    background: linear-gradient(135deg, var(--success-gradient-start) 0%, var(--success-gradient-end) 100%);
    color: white;
}

/* Search Card */
.search-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    padding: 1.5rem 2rem;
    margin-bottom: 1.5rem;
}

.search-input-group {
    position: relative;
}

.search-input-group .input-group-text {
    background: var(--grey-lighter);
    border: 2px solid var(--grey-light);
    border-right: none;
    border-radius: 12px 0 0 12px;
    color: var(--primary-purple);
}

.search-input-group input {
    border: 2px solid var(--grey-light);
    border-left: none;
    border-radius: 0 12px 12px 0;
    padding: 0.75rem 1rem;
    font-size: 0.95rem;
    transition: all 0.3s;
}

.search-input-group input:focus {
    border-color: var(--primary-purple);
    box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.25);
    outline: none;
}

#searchResults {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    color: var(--grey-medium);
    font-weight: 600;
}

/* Filter Card */
.filter-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    padding: 2rem;
    margin-bottom: 2rem;
}

.filter-card label {
    font-weight: 600;
    color: var(--grey-dark);
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.5rem;
}

.filter-card .form-control,
.filter-card .form-select {
    border: 2px solid var(--grey-light);
    border-radius: 12px;
    padding: 0.75rem 1rem;
    font-size: 0.95rem;
    transition: all 0.3s;
}

.filter-card .form-control:focus,
.filter-card .form-select:focus {
    border-color: var(--primary-purple);
    box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.25);
    outline: none;
}

.btn-filter {
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--dark-purple) 100%);
    border: none;
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.875rem;
    transition: all 0.3s;
}

.btn-filter:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(99, 102, 241, 0.3);
    color: white;
}

/* Table Card */
.table-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
}

.modern-borrows-table {
    width: 100%;
    margin-bottom: 0;
    border-collapse: collapse;
    font-size: 0.9rem;
}

.modern-borrows-table thead th {
    background: linear-gradient(135deg, #7c3aed 0%, #6366f1 100%);
    border-bottom: none;
    padding: 1.25rem 1rem;
    font-weight: 700;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
    text-align: left;
    color: white;
    text-transform: uppercase;
}

.modern-borrows-table tbody tr {
    transition: all 0.2s ease;
    border-bottom: 1px solid var(--grey-light);
}

.modern-borrows-table tbody tr:hover {
    background: var(--grey-lighter);
    transform: scale(1.005);
}

.modern-borrows-table tbody td {
    padding: 1.25rem 1rem;
    vertical-align: middle;
    color: var(--grey-medium);
    font-weight: 500;
}

.student-info strong {
    color: var(--grey-dark);
    font-weight: 700;
    font-family: 'Courier New', monospace;
}

.book-info strong {
    color: var(--grey-dark);
    font-weight: 600;
}

/* Status Badges */
.badge-modern {
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: inline-block;
}

.badge-borrowed {
    background: linear-gradient(135deg, var(--warning-gradient-start) 0%, var(--warning-gradient-end) 100%);
    color: white;
}

.badge-returned {
    background: linear-gradient(135deg, var(--success-gradient-start) 0%, var(--success-gradient-end) 100%);
    color: white;
}

.badge-overdue {
    background: linear-gradient(135deg, var(--red-gradient-start) 0%, var(--red-gradient-end) 100%);
    color: white;
}

.badge-danger-small {
    background: linear-gradient(135deg, var(--red-gradient-start) 0%, var(--red-gradient-end) 100%);
    color: white;
    padding: 0.35rem 0.75rem;
    border-radius: 50px;
    font-size: 0.7rem;
    font-weight: 600;
}

/* Fine Badges */
.badge-fine {
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 700;
    display: inline-block;
    letter-spacing: 0.3px;
}

.badge-danger {
    background: linear-gradient(135deg, var(--red-gradient-start) 0%, var(--red-gradient-end) 100%);
    color: white;
}

.badge-success {
    background: linear-gradient(135deg, var(--success-gradient-start) 0%, var(--success-gradient-end) 100%);
    color: white;
}

.fine-info {
    display: flex;
    flex-direction: column;
    gap: 0.35rem;
}

.paid-info {
    color: var(--success-gradient-start);
    font-weight: 600;
    font-size: 0.75rem;
}

/* Action Buttons */
.btn-action {
    border: none;
    padding: 0.6rem 1.25rem;
    border-radius: 8px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.75rem;
    transition: all 0.3s;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-return {
    background: linear-gradient(135deg, var(--success-gradient-start) 0%, var(--success-gradient-end) 100%);
    color: white;
}

.btn-return:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(16, 185, 129, 0.35);
    color: white;
}

.returned-label {
    color: var(--success-gradient-start);
    font-weight: 600;
    font-size: 0.875rem;
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
}

.returned-label i {
    font-size: 1rem;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: linear-gradient(135deg, var(--grey-lighter) 0%, white 100%);
    border-radius: 12px;
    margin: 2rem;
}

.empty-state i {
    color: var(--grey-medium);
    font-size: 4rem;
    margin-bottom: 1.5rem;
}

.empty-state p {
    color: var(--grey-dark);
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.empty-state small {
    color: var(--grey-medium);
    font-size: 0.95rem;
}
</style>

<div class="container-fluid borrows-container">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <h1><i class="fas fa-exchange-alt me-3"></i>Borrow Management</h1>
            <div class="header-actions">
                <a href="<?= BASE_PATH ?>/librarian/borrow-book" class="btn-header">
                    <i class="fas fa-book"></i> Borrow Book
                </a>
                <a href="<?= BASE_PATH ?>/librarian/quick-borrow" class="btn-header success">
                    <i class="fas fa-bolt"></i> Quick Borrow
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

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card stat-card-modern card-purple">
                <div class="card-body">
                    <div class="stat-icon">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <h5>Total Borrows</h5>
                    <h2><?= $borrow_stats['total_borrows'] ?? 0 ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card-modern card-warning">
                <div class="card-body">
                    <div class="stat-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <h5>Current Borrows</h5>
                    <h2><?= $borrow_stats['current_borrows'] ?? 0 ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card-modern card-danger">
                <div class="card-body">
                    <div class="stat-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h5>Overdue Books</h5>
                    <h2><?= $borrow_stats['overdue_books'] ?? 0 ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card-modern card-success">
                <div class="card-body">
                    <div class="stat-icon">
                        <i class="fas fa-money-bill"></i>
                    </div>
                    <h5>Total Fines</h5>
                    <h2>MK <?= number_format($borrow_stats['total_fines'] ?? 0) ?></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Live Search -->
    <div class="search-card">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="search-input-group input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" id="liveSearch" class="form-control" placeholder="Search borrows (student, book, ID, status...)">
                </div>
            </div>
            <div class="col-md-6">
                <div id="searchResults">
                    <small>Showing <span id="visibleCount">0</span> of <span id="totalCount">0</span> records</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Form -->
    <div class="filter-card">
        <form method="POST" action="<?= BASE_PATH ?>/librarian/borrows">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All Status</option>
                        <option value="borrowed" <?= ($filters['status'] ?? '') === 'borrowed' ? 'selected' : '' ?>>Borrowed</option>
                        <option value="returned" <?= ($filters['status'] ?? '') === 'returned' ? 'selected' : '' ?>>Returned</option>
                        <option value="overdue" <?= ($filters['status'] ?? '') === 'overdue' ? 'selected' : '' ?>>Overdue</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="student_id" class="form-label">Student ID</label>
                    <input type="text" class="form-control" id="student_id" name="student_id" 
                           value="<?= htmlspecialchars($filters['student_id'] ?? '') ?>" 
                           placeholder="Student ID">
                </div>
                <div class="col-md-3">
                    <label for="book_title" class="form-label">Book Title</label>
                    <input type="text" class="form-control" id="book_title" name="book_title" 
                           value="<?= htmlspecialchars($filters['book_title'] ?? '') ?>" 
                           placeholder="Book Title">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-filter w-100">Filter</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Borrows Table -->
    <div class="table-card">
        <div class="table-responsive">
            <table id="borrowsTable" class="modern-borrows-table">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Book</th>
                        <th>Borrowed Date</th>
                        <th>Due Date</th>
                        <th>Returned Date</th>
                        <th>Status</th>
                        <th>Fine</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($borrows as $borrow): ?>
                        <tr class="borrow-row">
                            <td class="student-info">
                                <strong><?= htmlspecialchars($borrow['student_id']) ?></strong><br>
                                <small><?= htmlspecialchars($borrow['student_name']) ?> - Class <?= htmlspecialchars($borrow['class']) ?></small>
                            </td>
                            <td class="book-info">
                                <strong><?= htmlspecialchars($borrow['title']) ?></strong><br>
                                <small>by <?= htmlspecialchars($borrow['author']) ?></small>
                            </td>
                            <td><?= date('M j, Y', strtotime($borrow['borrowed_date'])) ?></td>
                            <td>
                                <?= date('M j, Y', strtotime($borrow['due_date'])) ?>
                                <?php if ($borrow['status'] === 'borrowed' && $borrow['days_overdue_calc'] > 0): ?>
                                    <br><span class="badge-danger-small">Overdue <?= $borrow['days_overdue_calc'] ?> days</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($borrow['returned_date']): ?>
                                    <?= date('M j, Y', strtotime($borrow['returned_date'])) ?>
                                <?php else: ?>
                                    <span class="text-muted">Not returned</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge-modern badge-<?= 
                                    $borrow['status'] === 'borrowed' ? 'borrowed' : 
                                    ($borrow['status'] === 'overdue' ? 'overdue' : 'returned')
                                ?>">
                                    <?= ucfirst($borrow['status']) ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($borrow['fine_amount'] > 0): ?>
                                    <div class="fine-info">
                                        <span class="badge-fine badge-danger">MK <?= number_format($borrow['fine_amount'], 2) ?></span>
                                        <?php if ($borrow['paid_amount'] > 0): ?>
                                            <br><small class="paid-info">Paid: MK <?= number_format($borrow['paid_amount'], 2) ?></small>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <span class="badge-fine badge-success">MK 0.00</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($borrow['status'] !== 'returned'): ?>
                                    <form method="POST" action="<?= BASE_PATH ?>/librarian/return-book" class="d-inline">
                                        <input type="hidden" name="borrow_id" value="<?= $borrow['id'] ?>">
                                        <button type="submit" class="btn-action btn-return" title="Return Book">
                                            <i class="fas fa-undo me-1"></i> Return
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <span class="returned-label">
                                        <i class="fas fa-check-circle me-1"></i> Returned
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?php if (empty($borrows)): ?>
                <div class="empty-state">
                    <i class="fas fa-exchange-alt"></i>
                    <p>No borrowing records found</p>
                    <small>Try adjusting your filters or search terms</small>
                </div>
            <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('liveSearch');
    const table = document.getElementById('borrowsTable');
    const tbody = table.querySelector('tbody');
    const rows = tbody.querySelectorAll('tr');
    const visibleCountSpan = document.getElementById('visibleCount');
    const totalCountSpan = document.getElementById('totalCount');

    // Initialize counts
    let totalRows = rows.length;
    totalCountSpan.textContent = totalRows;
    visibleCountSpan.textContent = totalRows;

    // Function to highlight matching text
    function highlightText(text, searchTerm) {
        if (!searchTerm) return text;
        const regex = new RegExp(`(${searchTerm})`, 'gi');
        return text.replace(regex, '<mark>$1</mark>');
    }

    // Function to remove existing highlights
    function removeHighlights(element) {
        element.innerHTML = element.innerHTML.replace(/<mark>/g, '').replace(/<\/mark>/g, '');
    }

    // Live search function
    function performSearch() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        let visibleRows = 0;

        rows.forEach(row => {
            // Remove existing highlights
            const cells = row.querySelectorAll('td');
            cells.forEach(cell => removeHighlights(cell));

            if (!searchTerm) {
                row.style.display = '';
                visibleRows++;
                return;
            }

            // Get text content from all cells
            const rowText = Array.from(cells).map(cell => cell.textContent.toLowerCase()).join(' ');

            if (rowText.includes(searchTerm)) {
                row.style.display = '';
                visibleRows++;

                // Highlight matching terms
                cells.forEach(cell => {
                    const originalText = cell.textContent;
                    if (originalText.toLowerCase().includes(searchTerm)) {
                        cell.innerHTML = highlightText(originalText, searchTerm);
                    }
                });
            } else {
                row.style.display = 'none';
            }
        });

        visibleCountSpan.textContent = visibleRows;

        // Show message if no results
        const noResultsRow = tbody.querySelector('.no-results-row');
        if (visibleRows === 0 && searchTerm) {
            if (!noResultsRow) {
                const newRow = document.createElement('tr');
                newRow.className = 'no-results-row';
                newRow.innerHTML = `
                    <td colspan="7" class="text-center py-4">
                        <i class="fas fa-search fa-2x text-muted mb-2"></i>
                        <p class="text-muted">No borrows found matching "${searchInput.value}"</p>
                    </td>
                `;
                tbody.appendChild(newRow);
            }
        } else if (noResultsRow) {
            noResultsRow.remove();
        }
    }

    // Add event listener for real-time search
    searchInput.addEventListener('input', performSearch);

    // Clear search on escape key
    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            this.value = '';
            performSearch();
        }
    });
});
</script>

<?php include '../app/views/shared/footer.php'; ?>