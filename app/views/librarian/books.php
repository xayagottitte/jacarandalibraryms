<?php 
$title = "Book Management - Multi-Library System";
include '../app/views/shared/header.php'; 
include '../app/views/shared/layout-header.php'; 
?>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Book Inventory</h2>
        <div class="d-flex gap-2">
            <input type="text" class="form-control form-control-sm" placeholder="Search by title, author, ISBN, or category" style="width: 300px;">
            <button class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-search"></i>
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
                                        <button class="action-btn view-btn" title="View">VIEW</button>
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
</style>

<script>
function confirmDelete(message, bookId) {
    if (confirm(message)) {
        // Create a form to submit the delete request
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/librarian/delete-book';
        
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
            // Add view functionality
            alert('View functionality to be implemented');
        });
    });
    
    // Edit buttons  
    document.querySelectorAll('.edit-btn').forEach((btn) => {
        btn.addEventListener('click', function() {
            const bookId = this.getAttribute('data-book-id');
            window.location.href = '<?= BASE_PATH ?>/librarian/edit-book?id=' + bookId;
        });
    });
});
</script>

<?php include '../app/views/shared/layout-footer.php'; ?>
<?php include '../app/views/shared/footer.php'; ?>