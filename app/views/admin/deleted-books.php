<?php include '../app/views/shared/header.php'; ?>
<?php include '../app/views/shared/layout-header.php'; ?>

<div class="main-content">
    <div class="page-header">
        <div class="header-content">
            <h1><i class="fas fa-trash-restore"></i> Deleted Books</h1>
            <p>View and restore deleted books from all libraries</p>
        </div>
        <div class="header-actions">
            <a href="<?= BASE_PATH ?>/admin/books" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Books
            </a>
        </div>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <?= $_SESSION['success'] ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <?= $_SESSION['error'] ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="card">
        <div class="table-responsive">
            <table class="modern-table">
                <thead class="table-header">
                    <tr>
                        <th>Book Title</th>
                        <th>Author</th>
                        <th>ISBN</th>
                        <th>Category</th>
                        <th>Library</th>
                        <th>Deleted By</th>
                        <th>Deleted At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($books)): ?>
                        <tr>
                            <td colspan="8" class="text-center">
                                <div class="empty-state">
                                    <i class="fas fa-trash fa-3x"></i>
                                    <p>No deleted books found.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($books as $book): ?>
                            <tr class="table-row">
                                <td class="book-title"><?= htmlspecialchars($book['title']) ?></td>
                                <td><?= htmlspecialchars($book['author']) ?></td>
                                <td><span class="isbn-code"><?= htmlspecialchars($book['isbn'] ?? 'N/A') ?></span></td>
                                <td><?= htmlspecialchars($book['category_name'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($book['library_name']) ?></td>
                                <td><?= htmlspecialchars($book['deleted_by_name'] ?? 'Unknown') ?></td>
                                <td><?= date('M d, Y H:i', strtotime($book['deleted_at'])) ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="action-btn restore-btn" title="Restore" 
                                                onclick="confirmRestore(<?= $book['id'] ?>, '<?= htmlspecialchars($book['title']) ?>')">
                                            <i class="fas fa-undo"></i> RESTORE
                                        </button>
                                        <button class="action-btn permanent-delete-btn" title="Permanently Delete" 
                                                onclick="confirmPermanentDelete(<?= $book['id'] ?>, '<?= htmlspecialchars($book['title']) ?>')">
                                            <i class="fas fa-trash-alt"></i> PERMANENT
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Permanent Delete Modal -->
<div id="permanentDeleteModal" class="modal" style="display: none;">
    <div class="modal-content danger-modal">
        <span class="close" onclick="closePermanentDeleteModal()">&times;</span>
        <h2><i class="fas fa-exclamation-triangle"></i> Permanent Deletion Warning</h2>
        <p class="warning-text">This action cannot be undone! The book and all its history will be permanently removed from the database.</p>
        <p><strong>Book to delete: <span id="bookToDelete"></span></strong></p>
        <form id="permanentDeleteForm" method="POST" action="<?= BASE_PATH ?>/admin/permanently-delete-book">
            <input type="hidden" id="permanentDeleteBookId" name="id">
            <div class="form-group">
                <label for="confirmText">Type <strong>PERMANENTLY DELETE</strong> to confirm:</label>
                <input type="text" id="confirmText" name="confirm" class="form-control" required 
                       placeholder="PERMANENTLY DELETE">
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-secondary" onclick="closePermanentDeleteModal()">Cancel</button>
                <button type="submit" class="btn-danger">Permanently Delete</button>
            </div>
        </form>
    </div>
</div>

<style>
.main-content {
    padding: 2rem;
    max-width: 1400px;
    margin: 0 auto;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.header-content h1 {
    margin: 0;
    color: #663399;
    font-size: 2rem;
}

.header-content p {
    margin: 0.5rem 0 0 0;
    color: #666;
}

.btn {
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s;
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #5a6268;
    transform: translateY(-2px);
}

.card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    overflow: hidden;
}

.table-responsive {
    overflow-x: auto;
}

.modern-table {
    width: 100%;
    border-collapse: collapse;
}

.table-header {
    background: linear-gradient(135deg, #663399 0%, #8a4baf 100%);
    color: white;
}

.table-header th {
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    white-space: nowrap;
}

.table-row {
    border-bottom: 1px solid #e9ecef;
    transition: all 0.2s;
}

.table-row:hover {
    background: #f8f9fa;
}

.table-row td {
    padding: 1rem;
    vertical-align: middle;
}

.book-title {
    font-weight: 600;
    color: #333;
}

.isbn-code {
    font-family: 'Courier New', monospace;
    background: #f8f9fa;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.875rem;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.action-btn {
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
    font-size: 0.75rem;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    transition: all 0.3s;
}

.restore-btn {
    background: #28a745;
    color: white;
}

.restore-btn:hover {
    background: #218838;
    transform: translateY(-2px);
}

.permanent-delete-btn {
    background: #dc3545;
    color: white;
}

.permanent-delete-btn:hover {
    background: #c82333;
    transform: translateY(-2px);
}

.empty-state {
    padding: 3rem;
    text-align: center;
    color: #999;
}

.empty-state i {
    margin-bottom: 1rem;
    color: #ddd;
}

.alert {
    padding: 1rem 1.5rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

/* Modal Styles */
.modal {
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.4);
}

.modal-content {
    background-color: #fefefe;
    margin: 10% auto;
    padding: 30px;
    border-radius: 12px;
    width: 500px;
    max-width: 90%;
    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
}

.danger-modal {
    border: 3px solid #dc3545;
}

.modal-content h2 {
    margin-top: 0;
    color: #663399;
}

.danger-modal h2 {
    color: #dc3545;
}

.warning-text {
    color: #dc3545;
    font-weight: 600;
    background: #f8d7da;
    padding: 1rem;
    border-radius: 6px;
    border-left: 4px solid #dc3545;
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

.close:hover {
    color: #000;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #333;
}

.form-control {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
}

.modal-actions {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
    margin-top: 20px;
}

.btn-danger {
    padding: 10px 20px;
    background: #dc3545;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
}

.btn-danger:hover {
    background: #c82333;
}

.text-center {
    text-align: center;
}
</style>

<script>
function confirmRestore(bookId, bookTitle) {
    if (confirm(`Are you sure you want to restore "${bookTitle}"?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= BASE_PATH ?>/admin/restore-book';
        
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'id';
        input.value = bookId;
        
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }
}

function confirmPermanentDelete(bookId, bookTitle) {
    document.getElementById('permanentDeleteBookId').value = bookId;
    document.getElementById('bookToDelete').textContent = bookTitle;
    document.getElementById('confirmText').value = '';
    document.getElementById('permanentDeleteModal').style.display = 'block';
}

function closePermanentDeleteModal() {
    document.getElementById('permanentDeleteModal').style.display = 'none';
    document.getElementById('confirmText').value = '';
}

// Form validation
document.getElementById('permanentDeleteForm').addEventListener('submit', function(e) {
    const confirmText = document.getElementById('confirmText').value;
    if (confirmText !== 'PERMANENTLY DELETE') {
        e.preventDefault();
        alert('You must type "PERMANENTLY DELETE" exactly to confirm.');
        return false;
    }
});

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('permanentDeleteModal');
    if (event.target == modal) {
        closePermanentDeleteModal();
    }
}
</script>

<?php include '../app/views/shared/layout-footer.php'; ?>
