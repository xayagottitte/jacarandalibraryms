<?php 
$title = "Borrow Book - Jacaranda Libraries";
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
    --blue-gradient-start: #3b82f6;
    --blue-gradient-end: #2563eb;
}

.borrow-container {
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
    margin-bottom: 2rem;
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

/* Search Input Enhancement */
.search-wrapper {
    position: relative;
}

.search-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--grey-medium);
    font-size: 1rem;
    z-index: 1;
    pointer-events: none;
}

.form-card .form-control {
    border: 2px solid var(--grey-light);
    border-radius: 12px;
    padding: 0.875rem 1.25rem 0.875rem 2.75rem;
    font-size: 1rem;
    transition: all 0.3s;
    font-weight: 500;
    background-color: white;
}

.form-card .form-control:focus {
    border-color: var(--primary-purple);
    box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.25);
    outline: none;
    background-color: white;
}

.form-card .form-control::placeholder {
    color: var(--grey-medium);
    opacity: 0.6;
}

/* Custom Dropdown */
.dropdown-menu {
    border: 2px solid var(--grey-light);
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
    padding: 0.5rem;
    margin-top: 0.5rem;
}

.dropdown-item {
    border-radius: 8px;
    padding: 0.75rem 1rem;
    margin-bottom: 0.25rem;
    transition: all 0.2s;
    border-left: 3px solid transparent;
}

.dropdown-item:hover {
    background-color: rgba(99, 102, 241, 0.1);
    border-left-color: var(--primary-purple);
}

.dropdown-item.active {
    background-color: var(--primary-purple) !important;
    color: white !important;
    border-left-color: var(--dark-purple);
}

.dropdown-item.disabled {
    opacity: 0.5;
    cursor: not-allowed !important;
    background-color: var(--grey-lighter) !important;
}

.dropdown-item.disabled:hover {
    background-color: var(--grey-lighter) !important;
    border-left-color: transparent;
}

.badge {
    padding: 0.35rem 0.65rem;
    border-radius: 6px;
    font-weight: 600;
    font-size: 0.75rem;
}

mark {
    background-color: #fef08a;
    padding: 0;
    border-radius: 2px;
}

/* Info Box */
.info-box {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(37, 99, 235, 0.1) 100%);
    border-left: 4px solid var(--blue-gradient-start);
    border-radius: 12px;
    padding: 1.5rem;
    margin-top: 2rem;
    margin-bottom: 2rem;
}

.info-box i {
    color: var(--blue-gradient-start);
    font-size: 1.5rem;
}

.info-box strong {
    font-weight: 700;
    color: var(--grey-dark);
}

.info-box ul {
    color: var(--grey-dark);
    font-weight: 500;
    line-height: 1.8;
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

/* Quick Link Cards */
.quick-link-card {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s;
    text-align: center;
    height: 100%;
}

.quick-link-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
}

.quick-link-icon {
    width: 80px;
    height: 80px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    font-size: 2rem;
    transition: all 0.3s;
}

.quick-link-icon.success {
    background: linear-gradient(135deg, var(--success-gradient-start) 0%, var(--success-gradient-end) 100%);
    color: white;
}

.quick-link-icon.primary {
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--dark-purple) 100%);
    color: white;
}

.quick-link-card:hover .quick-link-icon {
    transform: scale(1.1) rotate(5deg);
}

.quick-link-card h5 {
    font-weight: 700;
    color: var(--grey-dark);
    margin-bottom: 0.75rem;
}

.quick-link-card p {
    color: var(--grey-medium);
    font-size: 0.9rem;
    margin-bottom: 1.5rem;
}

.btn-quick-action {
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.875rem;
    transition: all 0.3s;
    border: none;
}

.btn-quick-action.success {
    background: linear-gradient(135deg, var(--success-gradient-start) 0%, var(--success-gradient-end) 100%);
    color: white;
}

.btn-quick-action.primary {
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--dark-purple) 100%);
    color: white;
}

.btn-quick-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
    color: white;
}

.text-danger {
    color: var(--red-gradient-start) !important;
}
</style>

<div class="container-fluid borrow-container">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <h1><i class="fas fa-hand-holding-heart"></i>Borrow Book</h1>
            <a href="/jacarandalibraryms/librarian/borrows" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back to Borrow Management
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
        <form method="POST" action="/jacarandalibraryms/librarian/borrow-book">
            <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
            <!-- Selection Section -->
            <div class="form-section">
                <h3 class="form-section-title">
                    <i class="fas fa-search"></i> Select Student & Book
                </h3>
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="student_search" class="form-label">
                                <i class="fas fa-user-graduate"></i> Select Student <span class="text-danger">*</span>
                            </label>
                            <div class="search-wrapper">
                                <i class="fas fa-search search-icon"></i>
                                <input type="text" class="form-control" id="student_search" 
                                       placeholder="Search students by name, ID, or class..." autocomplete="off">
                                <input type="hidden" id="student_id" name="student_id" required>
                                <div id="student_dropdown" class="dropdown-menu w-100" style="max-height: 300px; overflow-y: auto; display: none;">
                                    <?php foreach ($students as $student): ?>
                                        <a class="dropdown-item student-option" href="#" 
                                           data-id="<?= $student['id'] ?>"
                                           data-name="<?= htmlspecialchars($student['full_name']) ?>"
                                           data-student-id="<?= htmlspecialchars($student['student_id']) ?>"
                                           data-class="<?= htmlspecialchars($student['class']) ?>"
                                           data-search="<?= htmlspecialchars(strtolower($student['full_name'] . ' ' . $student['student_id'] . ' ' . $student['class'])) ?>">
                                            <div>
                                                <strong><?= htmlspecialchars($student['full_name']) ?></strong>
                                                <br><small class="text-muted"><?= htmlspecialchars($student['student_id']) ?> - Class <?= htmlspecialchars($student['class']) ?></small>
                                            </div>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="book_search" class="form-label">
                                <i class="fas fa-book"></i> Select Book <span class="text-danger">*</span>
                            </label>
                            <div class="search-wrapper">
                                <i class="fas fa-search search-icon"></i>
                                <input type="text" class="form-control" id="book_search" 
                                       placeholder="Search books by title, author, ISBN, or class level..." autocomplete="off">
                                <input type="hidden" id="book_id" name="book_id" required>
                                <div id="book_dropdown" class="dropdown-menu w-100" style="max-height: 300px; overflow-y: auto; display: none;">
                                    <?php foreach ($books as $book): ?>
                                        <a class="dropdown-item book-option <?= $book['available_copies'] <= 0 ? 'disabled' : '' ?>" href="#" 
                                           data-id="<?= $book['id'] ?>"
                                           data-title="<?= htmlspecialchars($book['title']) ?>"
                                           data-author="<?= htmlspecialchars($book['author']) ?>"
                                           data-isbn="<?= htmlspecialchars($book['isbn'] ?? 'N/A') ?>"
                                           data-class-level="<?= htmlspecialchars($book['class_level'] ?? 'All') ?>"
                                           data-available="<?= $book['available_copies'] ?>"
                                           data-search="<?= htmlspecialchars(strtolower($book['title'] . ' ' . $book['author'] . ' ' . ($book['isbn'] ?? '') . ' ' . ($book['class_level'] ?? 'all') . ' class ' . ($book['class_level'] ?? 'all'))) ?>">
                                            <div>
                                                <strong><?= htmlspecialchars($book['title']) ?></strong>
                                                <?php if ($book['class_level']): ?>
                                                    <span class="badge bg-info ms-2">Class <?= htmlspecialchars($book['class_level']) ?></span>
                                                <?php endif; ?>
                                                <br><small class="text-muted">by <?= htmlspecialchars($book['author']) ?> 
                                                <?php if ($book['isbn']): ?>(ISBN: <?= htmlspecialchars($book['isbn']) ?>)<?php endif; ?>
                                                - <span class="<?= $book['available_copies'] > 0 ? 'text-success' : 'text-danger' ?>">
                                                    <?= $book['available_copies'] ?> available
                                                </span></small>
                                            </div>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Box -->
            <div class="info-box">
                <div class="d-flex align-items-start">
                    <i class="fas fa-info-circle me-3"></i>
                    <div>
                        <strong>Borrowing Information:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Loan period: <?= $loan_period ?? 5 ?> days</li>
                            <li>Maximum books per student: 3</li>
                            <li>Fine for overdue books: MK100 per day</li>
                            <li>Books are due on <?= date('M j, Y', strtotime('+' . ($loan_period ?? 5) . ' days')) ?></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex gap-3 justify-content-end mt-4">
                <a href="/jacarandalibraryms/librarian/borrows" class="btn-cancel">
                    <i class="fas fa-times"></i> Cancel
                </a>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-check-circle"></i> Borrow Book
                </button>
            </div>
        </form>
    </div>

    <!-- Quick Links -->
    <div class="row g-4 mt-2">
        <div class="col-md-6">
            <div class="quick-link-card">
                <div class="quick-link-icon success">
                    <i class="fas fa-bolt"></i>
                </div>
                <h5>Quick Borrow</h5>
                <p>Use student ID and ISBN for faster borrowing</p>
                <a href="/jacarandalibraryms/librarian/quick-borrow" class="btn btn-quick-action success">
                    <i class="fas fa-arrow-right me-2"></i> Go to Quick Borrow
                </a>
            </div>
        </div>
        <div class="col-md-6">
            <div class="quick-link-card">
                <div class="quick-link-icon primary">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h5>Add New Student</h5>
                <p>Can't find a student? Add them to the system</p>
                <a href="/jacarandalibraryms/librarian/create-student" class="btn btn-quick-action primary">
                    <i class="fas fa-plus me-2"></i> Add Student
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Student search functionality
    const studentSearch = document.getElementById('student_search');
    const studentDropdown = document.getElementById('student_dropdown');
    const studentIdInput = document.getElementById('student_id');
    const studentOptions = document.querySelectorAll('.student-option');

    // Book search functionality
    const bookSearch = document.getElementById('book_search');
    const bookDropdown = document.getElementById('book_dropdown');
    const bookIdInput = document.getElementById('book_id');
    const bookOptions = document.querySelectorAll('.book-option');

    // Auto-select student if coming from student page
    const urlParams = new URLSearchParams(window.location.search);
    const preSelectedStudentId = urlParams.get('student_id');
    if (preSelectedStudentId) {
        const preSelectedStudent = document.querySelector(`[data-id="${preSelectedStudentId}"]`);
        if (preSelectedStudent) {
            studentSearch.value = preSelectedStudent.dataset.name;
            studentIdInput.value = preSelectedStudentId;
        }
    }

    // Student search functionality
    studentSearch.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        let visibleOptions = 0;

        studentOptions.forEach(option => {
            const searchData = option.dataset.search;
            if (!searchTerm || searchData.includes(searchTerm)) {
                option.style.display = 'block';
                visibleOptions++;
                
                // Highlight matching text
                const strongElement = option.querySelector('strong');
                const smallElement = option.querySelector('small');
                
                if (strongElement) {
                    strongElement.innerHTML = highlightText(option.dataset.name, searchTerm);
                }
                if (smallElement) {
                    const originalText = `${option.dataset.studentId} - Class ${option.dataset.class}`;
                    smallElement.innerHTML = highlightText(originalText, searchTerm);
                }
            } else {
                option.style.display = 'none';
            }
        });

        // Show/hide dropdown
        if (searchTerm && visibleOptions > 0) {
            studentDropdown.style.display = 'block';
        } else {
            studentDropdown.style.display = 'none';
        }

        // Clear selection if search doesn't match
        if (!searchTerm) {
            studentIdInput.value = '';
        }
    });

    // Student option selection
    studentOptions.forEach(option => {
        option.addEventListener('click', function(e) {
            e.preventDefault();
            studentSearch.value = this.dataset.name;
            studentIdInput.value = this.dataset.id;
            studentDropdown.style.display = 'none';
        });
    });

    // Book search functionality
    bookSearch.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        let visibleOptions = 0;

        bookOptions.forEach(option => {
            const searchData = option.dataset.search;
            if (!searchTerm || searchData.includes(searchTerm)) {
                option.style.display = 'block';
                visibleOptions++;
                
                // Highlight matching text
                const strongElement = option.querySelector('strong');
                const smallElement = option.querySelector('small');
                const badgeElement = option.querySelector('.badge');
                
                if (strongElement) {
                    strongElement.innerHTML = highlightText(option.dataset.title, searchTerm);
                }
                if (badgeElement && option.dataset.classLevel !== 'All') {
                    badgeElement.innerHTML = highlightText(`Class ${option.dataset.classLevel}`, searchTerm);
                }
                if (smallElement) {
                    const originalText = `by ${option.dataset.author} ${option.dataset.isbn !== 'N/A' ? '(ISBN: ' + option.dataset.isbn + ')' : ''} - ${option.dataset.available} available`;
                    smallElement.innerHTML = highlightText(originalText, searchTerm);
                }
            } else {
                option.style.display = 'none';
            }
        });

        // Show/hide dropdown
        if (searchTerm && visibleOptions > 0) {
            bookDropdown.style.display = 'block';
        } else {
            bookDropdown.style.display = 'none';
        }

        // Clear selection if search doesn't match
        if (!searchTerm) {
            bookIdInput.value = '';
        }
    });

    // Book option selection
    bookOptions.forEach(option => {
        option.addEventListener('click', function(e) {
            e.preventDefault();
            if (this.classList.contains('disabled')) return;
            
            bookSearch.value = this.dataset.title;
            bookIdInput.value = this.dataset.id;
            bookDropdown.style.display = 'none';
        });
    });

    // Hide dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!studentSearch.contains(e.target) && !studentDropdown.contains(e.target)) {
            studentDropdown.style.display = 'none';
        }
        if (!bookSearch.contains(e.target) && !bookDropdown.contains(e.target)) {
            bookDropdown.style.display = 'none';
        }
    });

    // Show dropdown on focus
    studentSearch.addEventListener('focus', function() {
        if (this.value.trim()) {
            studentDropdown.style.display = 'block';
        }
    });

    bookSearch.addEventListener('focus', function() {
        if (this.value.trim()) {
            bookDropdown.style.display = 'block';
        }
    });

    // Keyboard navigation
    [studentSearch, bookSearch].forEach(searchInput => {
        searchInput.addEventListener('keydown', function(e) {
            const dropdown = searchInput === studentSearch ? studentDropdown : bookDropdown;
            const visibleOptions = dropdown.querySelectorAll('a:not([style*="none"])');
            
            if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
                e.preventDefault();
                let currentIndex = -1;
                
                // Find currently focused option
                visibleOptions.forEach((option, index) => {
                    if (option.classList.contains('active')) {
                        currentIndex = index;
                    }
                });

                // Remove current active class
                visibleOptions.forEach(option => option.classList.remove('active'));

                // Calculate new index
                if (e.key === 'ArrowDown') {
                    currentIndex = (currentIndex + 1) % visibleOptions.length;
                } else {
                    currentIndex = currentIndex <= 0 ? visibleOptions.length - 1 : currentIndex - 1;
                }

                // Add active class to new option
                if (visibleOptions[currentIndex]) {
                    visibleOptions[currentIndex].classList.add('active');
                }
            } else if (e.key === 'Enter') {
                e.preventDefault();
                const activeOption = dropdown.querySelector('a.active');
                if (activeOption) {
                    activeOption.click();
                }
            } else if (e.key === 'Escape') {
                dropdown.style.display = 'none';
            }
        });
    });

    // Function to highlight matching text
    function highlightText(text, searchTerm) {
        if (!searchTerm) return text;
        const regex = new RegExp(`(${searchTerm})`, 'gi');
        return text.replace(regex, '<mark>$1</mark>');
    }

    // Add CSS for active state (moved inline to component styles above)
    // Styling is already handled in the style block
});
</script>

<?php include '../app/views/shared/footer.php'; ?>