<?php 
$title = "Borrow Book - Multi-Library System";
include '../app/views/shared/header.php'; 
include '../app/views/shared/layout-header.php'; 
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Borrow Book</h1>
        <a href="/jacarandalibraryms/librarian/borrows" class="btn btn-secondary">Back to Borrow Management</a>
    </div>

    <!-- Flash Messages -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="/jacarandalibraryms/librarian/borrow-book">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="student_search" class="form-label">Select Student <span class="text-danger">*</span></label>
                            <div class="position-relative">
                                <input type="text" class="form-control" id="student_search" placeholder="Search students by name, ID, or class..." autocomplete="off">
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
                            <label for="book_search" class="form-label">Select Book <span class="text-danger">*</span></label>
                            <div class="position-relative">
                                <input type="text" class="form-control" id="book_search" placeholder="Search books by title, author, ISBN, or class level..." autocomplete="off">
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

                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>Information:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Loan period: <?= $loan_period ?? 5 ?> days</li>
                        <li>Maximum books per student: 3</li>
                        <li>Fine for overdue books: MK100 per day</li>
                        <li>Books are due on <?= date('M j, Y', strtotime('+' . ($loan_period ?? 5) . ' days')) ?></li>
                    </ul>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="/jacarandalibraryms/librarian/borrows" class="btn btn-secondary me-md-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">Borrow Book</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-bolt fa-2x text-success mb-3"></i>
                    <h5>Quick Borrow</h5>
                    <p class="text-muted">Use student ID and ISBN for faster borrowing</p>
                    <a href="/jacarandalibraryms/librarian/quick-borrow" class="btn btn-success">Go to Quick Borrow</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-plus fa-2x text-primary mb-3"></i>
                    <h5>Add New Student</h5>
                    <p class="text-muted">Can't find a student? Add them to the system</p>
                    <a href="/jacarandalibraryms/librarian/create-student" class="btn btn-primary">Add Student</a>
                </div>
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

    // Add CSS for active state
    const style = document.createElement('style');
    style.textContent = `
        .dropdown-item.active {
            background-color: #0d6efd !important;
            color: white !important;
        }
        .dropdown-item.disabled {
            opacity: 0.5;
            cursor: not-allowed !important;
        }
        .dropdown-item.disabled:hover {
            background-color: transparent !important;
        }
        mark {
            background-color: yellow;
            padding: 0;
        }
    `;
    document.head.appendChild(style);
});
</script>

<?php include '../app/views/shared/footer.php'; ?>