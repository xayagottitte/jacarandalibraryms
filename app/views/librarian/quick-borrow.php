<?php 
$title = "Quick Borrow - Jacaranda Libraries";
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
    --warning-gradient-start: #f59e0b;
    --warning-gradient-end: #d97706;
}

.quick-borrow-container {
    padding: 2rem;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    min-height: 100vh;
}

/* Page Header */
.page-header {
    background: linear-gradient(135deg, var(--success-gradient-start) 0%, var(--success-gradient-end) 100%);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 10px 30px rgba(16, 185, 129, 0.25);
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

.btn-standard {
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

.btn-standard:hover {
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
}

/* Form Card */
.form-card {
    background: white;
    border-radius: 20px;
    padding: 2.5rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    margin-bottom: 2rem;
}

.form-card label {
    font-weight: 700;
    color: var(--grey-dark);
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.75rem;
}

.form-card .form-control {
    border: 2px solid var(--grey-light);
    border-radius: 12px;
    padding: 0.875rem 1.25rem;
    font-size: 1rem;
    transition: all 0.3s;
    font-weight: 500;
}

.form-card .form-control:focus {
    border-color: var(--success-gradient-start);
    box-shadow: 0 0 0 0.2rem rgba(16, 185, 129, 0.25);
    outline: none;
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

/* Warning Alert */
.alert-warning-modern {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.15) 0%, rgba(217, 119, 6, 0.15) 100%);
    border-left: 4px solid var(--warning-gradient-start);
    border-radius: 12px;
    padding: 1.25rem 1.5rem;
    margin: 1.5rem 0;
    display: flex;
    align-items: start;
    gap: 1rem;
}

.alert-warning-modern i {
    color: var(--warning-gradient-start);
    font-size: 1.5rem;
    margin-top: 0.125rem;
}

.alert-warning-modern strong {
    color: var(--grey-dark);
    font-weight: 700;
}

.alert-warning-modern p {
    margin: 0;
    color: var(--grey-dark);
    font-weight: 500;
    line-height: 1.6;
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
}

.btn-cancel:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(107, 114, 128, 0.3);
    color: white;
}

.btn-submit {
    background: linear-gradient(135deg, var(--success-gradient-start) 0%, var(--success-gradient-end) 100%);
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
    box-shadow: 0 8px 20px rgba(16, 185, 129, 0.35);
    color: white;
}

.btn-submit i {
    font-size: 1rem;
}

/* Reference Card */
.reference-card {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.reference-header {
    background: linear-gradient(135deg, var(--grey-lighter) 0%, white 100%);
    border-radius: 12px;
    padding: 1rem 1.5rem;
    margin-bottom: 1.5rem;
    border-left: 4px solid var(--primary-purple);
}

.reference-header h5 {
    margin: 0;
    font-weight: 700;
    font-size: 1rem;
    color: var(--grey-dark);
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

/* Modern Table */
.modern-table {
    width: 100%;
    margin-bottom: 0;
    border-collapse: collapse;
    font-size: 0.9rem;
}

.modern-table thead th {
    background: linear-gradient(135deg, var(--grey-lighter) 0%, white 100%);
    border-bottom: 2px solid var(--grey-light);
    padding: 1rem;
    font-weight: 700;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
    text-align: left;
    color: var(--grey-dark);
    text-transform: uppercase;
}

.modern-table tbody tr {
    transition: all 0.2s ease;
    border-bottom: 1px solid var(--grey-light);
}

.modern-table tbody tr:hover {
    background: var(--grey-lighter);
    transform: scale(1.005);
}

.modern-table tbody td {
    padding: 1rem;
    vertical-align: middle;
    color: var(--grey-medium);
    font-weight: 500;
}

.modern-table tbody td code {
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--dark-purple) 100%);
    color: white;
    padding: 0.35rem 0.75rem;
    border-radius: 6px;
    font-family: 'Courier New', monospace;
    font-weight: 700;
    font-size: 0.8rem;
}

.empty-state {
    text-align: center;
    padding: 3rem 1rem;
}

.empty-state i {
    color: var(--grey-medium);
    font-size: 3rem;
    margin-bottom: 1rem;
}

.empty-state p {
    color: var(--grey-medium);
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
    font-size: 1.125rem;
}

.input-with-icon .form-control {
    padding-left: 3rem;
}
</style>

<div class="container-fluid quick-borrow-container">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <h1><i class="fas fa-bolt"></i>Quick Borrow</h1>
            <a href="<?= BASE_PATH ?>/librarian/borrow-book" class="btn-standard">
                <i class="fas fa-list"></i> Standard Borrow
            </a>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert-danger-modern alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><?= $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="form-card">
        <form method="POST" action="<?= BASE_PATH ?>/librarian/quick-borrow">
            <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="student_id" class="form-label">
                            <i class="fas fa-user-graduate me-2"></i>Student ID <span class="text-danger">*</span>
                        </label>
                        <div class="input-with-icon">
                            <i class="fas fa-id-card"></i>
                            <input type="text" class="form-control" id="student_id" name="student_id" 
                                   placeholder="Type Student ID or Name" autocomplete="off" required>
                            <div id="student_suggestions" class="list-group" style="position:absolute; z-index: 1000; width:100%; top: 110%; display:none;"></div>
                        </div>
                        <div class="form-text">Enter the student's unique ID</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="isbn" class="form-label">
                            <i class="fas fa-barcode me-2"></i>Book ISBN <span class="text-danger">*</span>
                        </label>
                        <div class="input-with-icon">
                            <i class="fas fa-book"></i>
                            <input type="text" class="form-control" id="isbn" name="isbn" 
                                   placeholder="Type ISBN or Title" autocomplete="off" required>
                            <div id="book_suggestions" class="list-group" style="position:absolute; z-index: 1000; width:100%; top: 110%; display:none;"></div>
                        </div>
                        <div class="form-text">Enter the book's ISBN number</div>
                    </div>
                </div>
            </div>

            <div class="alert-warning-modern">
                <i class="fas fa-exclamation-triangle"></i>
                <div>
                    <p><strong>Note:</strong> This method uses Student ID and ISBN for faster processing. 
                    Make sure you have the correct information.</p>
                </div>
            </div>

            <div class="d-flex gap-3 justify-content-end mt-4">
                <a href="<?= BASE_PATH ?>/librarian/borrows" class="btn-cancel">
                    <i class="fas fa-times me-2"></i> Cancel
                </a>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-bolt"></i> Quick Borrow
                </button>
            </div>
        </form>
    </div>

    <!-- Recent Students (for reference) -->
    <div class="reference-card">
        <div class="reference-header">
            <h5><i class="fas fa-users"></i>Recent Students (for reference)</h5>
        </div>
        <div>
            <?php
            $studentModel = new Student();
            $recentStudents = $studentModel->getStudentsByLibrary($_SESSION['library_id'], []);
            $recentStudents = array_slice($recentStudents, 0, 5);
            ?>
            
            <?php if (!empty($recentStudents)): ?>
                <div class="table-responsive">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>Student ID</th>
                                <th>Name</th>
                                <th>Class</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentStudents as $student): ?>
                                <tr>
                                    <td><code><?= htmlspecialchars($student['student_id']) ?></code></td>
                                    <td><?= htmlspecialchars($student['full_name']) ?></td>
                                    <td>Class <?= htmlspecialchars($student['class']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-user-slash"></i>
                    <p>No students found.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const studentIdInput = document.getElementById('student_id');
    const isbnInput = document.getElementById('isbn');
    const studentSug = document.getElementById('student_suggestions');
    const bookSug = document.getElementById('book_suggestions');

    // Focus on first input
    studentIdInput.focus();

    // Auto-format ISBN (remove dashes and spaces)
    isbnInput.addEventListener('blur', function() {
        this.value = this.value.replace(/[-\s]/g, '');
    });

    let studentTimer, bookTimer;
    studentIdInput.addEventListener('input', function() {
        clearTimeout(studentTimer);
        const q = this.value.trim();
        if (!q) { studentSug.style.display='none'; studentSug.innerHTML=''; return; }
        studentTimer = setTimeout(async () => {
            const res = await fetch('<?= BASE_PATH ?>/librarian/search-students?q=' + encodeURIComponent(q));
            const data = await res.json();
            studentSug.innerHTML = data.map(s => `
                <a href="#" class="list-group-item list-group-item-action" data-id="${s.student_id}">
                    <div class="d-flex justify-content-between"><strong>${s.student_id}</strong><small>Class ${s.class}</small></div>
                    <div>${s.full_name}</div>
                </a>`).join('');
            studentSug.style.display = data.length ? 'block' : 'none';
            studentSug.querySelectorAll('a').forEach(a => a.addEventListener('click', (e) => {
                e.preventDefault();
                studentIdInput.value = a.getAttribute('data-id');
                studentSug.style.display='none';
            }));
        }, 200);
    });

    isbnInput.addEventListener('input', function() {
        clearTimeout(bookTimer);
        const q = this.value.trim();
        if (!q) { bookSug.style.display='none'; bookSug.innerHTML=''; return; }
        bookTimer = setTimeout(async () => {
            const res = await fetch('<?= BASE_PATH ?>/librarian/search-books?q=' + encodeURIComponent(q));
            const data = await res.json();
            bookSug.innerHTML = data.map(b => `
                <a href="#" class="list-group-item list-group-item-action" data-isbn="${b.isbn || ''}">
                    <div class="d-flex justify-content-between"><strong>${b.title}</strong><small>${b.available_copies} avail</small></div>
                    <div>${b.author || ''} ${b.isbn ? ' • ISBN ' + b.isbn : ''}</div>
                </a>`).join('');
            bookSug.style.display = data.length ? 'block' : 'none';
            bookSug.querySelectorAll('a').forEach(a => a.addEventListener('click', (e) => {
                e.preventDefault();
                const isbn = a.getAttribute('data-isbn');
                if (isbn) {
                    isbnInput.value = isbn;
                } else {
                    // fallback: keep title typed; borrow flow uses ISBN primarily
                }
                bookSug.style.display='none';
            }));
        }, 200);
    });
});
</script>

<?php include '../app/views/shared/footer.php'; ?>