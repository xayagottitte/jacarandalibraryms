<?php 
$title = "Student Management - Multi-Library System";
include '../app/views/shared/header.php'; 
include '../app/views/shared/layout-header.php'; 
?>

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Student Management</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="/jacarandalibraryms/librarian/create-student" class="btn btn-primary">
                <i class="fas fa-user-plus"></i> Add Student
            </a>
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

    <!-- Search and Filter Form -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="POST" action="/jacarandalibraryms/librarian/students">
                <div class="row">
                    <div class="col-md-4">
                        <label for="search" class="form-label">Live Search Students</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="studentLiveSearch" 
                                   placeholder="Search by name, student ID, email, or class" 
                                   autocomplete="off">
                            <button class="btn btn-outline-secondary" type="button" id="clearStudentSearch">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <input type="hidden" class="form-control" id="search" name="search" 
                               value="<?= htmlspecialchars($filters['search'] ?? '') ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="class" class="form-label">Class</label>
                        <select class="form-select" id="class" name="class">
                            <option value="">All Classes</option>
                            <?php foreach ($classes as $class): ?>
                                <option value="<?= htmlspecialchars($class['class']) ?>" 
                                    <?= ($filters['class'] ?? '') === $class['class'] ? 'selected' : '' ?>>
                                    Class <?= htmlspecialchars($class['class']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">All Status</option>
                            <option value="active" <?= ($filters['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                            <option value="inactive" <?= ($filters['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Students Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Full Name</th>
                            <th>Class & Section</th>
                            <th>Contact</th>
                            <th>Active Borrows</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($student['student_id']) ?></strong>
                                </td>
                                <td><?= htmlspecialchars($student['full_name']) ?></td>
                                <td>
                                    Class <?= htmlspecialchars($student['class']) ?>
                                    <?php if ($student['section']): ?>
                                        - Section <?= htmlspecialchars($student['section']) ?>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($student['email']): ?>
                                        <div><i class="fas fa-envelope"></i> <?= htmlspecialchars($student['email']) ?></div>
                                    <?php endif; ?>
                                    <?php if ($student['phone']): ?>
                                        <div><i class="fas fa-phone"></i> <?= htmlspecialchars($student['phone']) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($student['active_borrows'] > 0): ?>
                                        <span class="badge bg-warning"><?= $student['active_borrows'] ?> books</span>
                                    <?php else: ?>
                                        <span class="badge bg-success">No active borrows</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $student['status'] === 'active' ? 'success' : 'secondary' ?>">
                                        <?= ucfirst($student['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="/jacarandalibraryms/librarian/view-student?id=<?= $student['id'] ?>" class="btn btn-outline-primary" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="/jacarandalibraryms/librarian/edit-student?id=<?= $student['id'] ?>" class="btn btn-outline-secondary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="/jacarandalibraryms/librarian/borrow-book?student_id=<?= $student['id'] ?>" class="btn btn-outline-success" title="Borrow Book">
                                            <i class="fas fa-book"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <?php if (empty($students)): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-user-graduate fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No students found. <a href="/jacarandalibraryms/librarian/create-student">Add your first student</a></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Library Info -->
    <div class="card mt-4">
        <div class="card-body">
            <h6>Library Information</h6>
            <p class="mb-0">
                <strong><?= htmlspecialchars($library['name']) ?></strong> 
                (<?= ucfirst($library['type']) ?> School) - 
                Valid classes: <?= $library['type'] === 'primary' ? '1-8' : '1-4' ?>
            </p>
        </div>
    </div>
</div>

<style>
/* Live Search Styling */
#studentLiveSearch {
    transition: all 0.3s ease;
    border: 2px solid #e9ecef;
}

#studentLiveSearch:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

#clearStudentSearch {
    transition: all 0.2s ease;
    display: none;
}

#clearStudentSearch:hover {
    background-color: #dc3545;
    border-color: #dc3545;
    color: white;
}

.student-row {
    transition: opacity 0.3s ease;
}

.search-highlight {
    background-color: #fff3cd;
    padding: 2px 4px;
    border-radius: 3px;
    font-weight: 600;
}

#searchStatus {
    font-size: 0.875rem;
    font-weight: 500;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const liveSearchInput = document.getElementById('studentLiveSearch');
    const clearButton = document.getElementById('clearStudentSearch');
    const tableRows = document.querySelectorAll('tbody tr');
    const totalStudents = tableRows.length;
    
    // Add search status display
    const searchContainer = liveSearchInput.closest('.col-md-4');
    const statusElement = document.createElement('div');
    statusElement.id = 'searchStatus';
    statusElement.className = 'text-muted mt-1';
    statusElement.style.display = 'none';
    searchContainer.appendChild(statusElement);
    
    // Helper functions
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
    
    // Live search functionality
    liveSearchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        let visibleRows = 0;
        
        tableRows.forEach(row => {
            if (row.cells.length === 0) return; // Skip empty rows
            
            // Get cell content
            const studentId = row.cells[0].textContent.toLowerCase();
            const fullName = row.cells[1].textContent.toLowerCase();
            const classSection = row.cells[2].textContent.toLowerCase();
            const contact = row.cells[3].textContent.toLowerCase();
            
            // Check if search term matches any field
            const isMatch = studentId.includes(searchTerm) ||
                           fullName.includes(searchTerm) ||
                           classSection.includes(searchTerm) ||
                           contact.includes(searchTerm);
            
            if (isMatch || searchTerm === '') {
                row.style.display = '';
                visibleRows++;
                
                // Add highlighting if there's a search term
                if (searchTerm !== '') {
                    highlightText(row.cells[0], searchTerm);
                    highlightText(row.cells[1], searchTerm);
                    highlightText(row.cells[2], searchTerm);
                    highlightText(row.cells[3], searchTerm);
                } else {
                    // Remove highlighting when search is cleared
                    removeHighlight(row.cells[0]);
                    removeHighlight(row.cells[1]);
                    removeHighlight(row.cells[2]);
                    removeHighlight(row.cells[3]);
                }
            } else {
                row.style.display = 'none';
            }
        });
        
        // Update search status
        if (searchTerm !== '') {
            if (visibleRows === 0) {
                statusElement.textContent = 'No students found';
                statusElement.style.color = '#dc3545';
            } else {
                statusElement.textContent = `${visibleRows} of ${totalStudents} student${visibleRows !== 1 ? 's' : ''} shown`;
                statusElement.style.color = '#28a745';
            }
            statusElement.style.display = 'block';
            clearButton.style.display = 'block';
        } else {
            statusElement.style.display = 'none';
            clearButton.style.display = 'none';
        }
    });
    
    // Clear search functionality
    clearButton.addEventListener('click', function() {
        liveSearchInput.value = '';
        liveSearchInput.dispatchEvent(new Event('input'));
        liveSearchInput.focus();
    });
    
    // Keyboard shortcuts
    liveSearchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            this.value = '';
            this.dispatchEvent(new Event('input'));
            this.blur();
        }
    });
    
    // Focus search input when user starts typing (anywhere on page)
    document.addEventListener('keydown', function(e) {
        if (!['INPUT', 'TEXTAREA', 'SELECT'].includes(document.activeElement.tagName) && 
            e.key.match(/[a-zA-Z0-9]/)) {
            liveSearchInput.focus();
        }
    });
});
</script>

<?php include '../app/views/shared/footer.php'; ?>