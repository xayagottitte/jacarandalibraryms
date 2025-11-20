<?php 
$title = "Student Management - Jacaranda Libraries";
include '../app/views/shared/header.php'; 
include '../app/views/shared/layout-header.php'; 
?>

<style>
/* Modern Dashboard Color Variables */
:root {
    --primary-purple: #663399;
    --dark-purple: #522d7a;
    --light-purple: #8a4baf;
    --accent-purple: #b07acc;
    --jacaranda-primary: #663399;
    --jacaranda-secondary: #8a4baf;
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

.students-container {
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

.btn-add-student {
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

.btn-add-student:hover {
    background: rgba(255, 255, 255, 0.3);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
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

.filter-card .input-group-text,
.filter-card .btn-outline-secondary {
    border: 2px solid var(--grey-light);
    border-radius: 12px;
    background: white;
    color: var(--grey-medium);
}

.filter-card .btn-outline-secondary:hover {
    background: var(--red-gradient-start);
    border-color: var(--red-gradient-start);
    color: white;
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

/* Modern Table Container */
.table-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
}

.modern-students-table {
    width: 100%;
    margin-bottom: 0;
    border-collapse: collapse;
    font-size: 0.9rem;
}

.modern-students-table thead th {
    background: linear-gradient(135deg, var(--grey-lighter) 0%, white 100%);
    border-bottom: 2px solid var(--grey-light);
    padding: 1.25rem 1rem;
    font-weight: 700;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
    text-align: left;
    color: var(--grey-dark);
    text-transform: uppercase;
}

.modern-students-table tbody tr {
    transition: all 0.2s ease;
    border-bottom: 1px solid var(--grey-light);
}

.modern-students-table tbody tr:hover {
    background: var(--grey-lighter);
    transform: scale(1.005);
}

.modern-students-table tbody td {
    padding: 1.25rem 1rem;
    vertical-align: middle;
    color: var(--grey-medium);
    font-weight: 500;
}

.student-id {
    font-weight: 700;
    color: var(--grey-dark);
    font-family: 'Courier New', monospace;
    font-size: 1rem;
}

.student-name {
    font-weight: 600;
    color: var(--grey-dark);
    font-size: 1rem;
}

.class-info {
    font-weight: 600;
    color: var(--grey-medium);
}

.contact-info {
    font-size: 0.85rem;
}

.contact-info i {
    color: var(--primary-purple);
    margin-right: 0.5rem;
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

.badge-success-modern {
    background: linear-gradient(135deg, var(--success-gradient-start) 0%, var(--success-gradient-end) 100%);
    color: white;
}

.badge-warning-modern {
    background: linear-gradient(135deg, var(--warning-gradient-start) 0%, var(--warning-gradient-end) 100%);
    color: white;
}

.badge-secondary-modern {
    background: linear-gradient(135deg, #9ca3af 0%, #6b7280 100%);
    color: white;
}

.badge-danger-modern {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
}

.inactive-student {
    background-color: #fef2f2 !important;
    opacity: 0.85;
}

.inactive-student:hover {
    background-color: #fee2e2 !important;
    opacity: 1;
}

/* Action Buttons */
.action-buttons-group {
    display: flex;
    gap: 0.5rem;
}

.action-btn-modern {
    padding: 0.5rem 1rem;
    border-radius: 8px;
    border: none;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.action-btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.btn-view {
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--dark-purple) 100%);
    color: white;
}

.btn-edit {
    background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
    color: white;
}

.btn-borrow {
    background: linear-gradient(135deg, var(--success-gradient-start) 0%, var(--success-gradient-end) 100%);
    color: white;
}

/* Info Card */
.info-card {
    background: linear-gradient(135deg, var(--grey-lighter) 0%, white 100%);
    border-radius: 20px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    padding: 1.5rem;
    margin-top: 2rem;
}

.info-card h6 {
    font-weight: 700;
    color: var(--grey-dark);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 1rem;
    font-size: 0.875rem;
}

.info-card p {
    color: var(--grey-medium);
    font-weight: 500;
    margin-bottom: 0;
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

/* Live Search Styling */
.search-highlight {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    padding: 2px 4px;
    border-radius: 4px;
    font-weight: 700;
}
</style>

<div class="container-fluid students-container">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <h1><i class="fas fa-user-graduate me-3"></i>Student Management</h1>
            <a href="/jacarandalibraryms/librarian/create-student" class="btn-add-student">
                <i class="fas fa-user-plus"></i> Add Student
            </a>
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

    <!-- Search and Filter Form -->
    <div class="filter-card">
        <form method="POST" action="/jacarandalibraryms/librarian/students" id="filterForm">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Live Search Students</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="studentLiveSearch" 
                               placeholder="Search by name, ID, email, or class" 
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
                    <select class="form-select filter-input" id="class" name="class">
                        <option value="">All Classes</option>
                        <?php 
                        $maxClass = ($library['type'] ?? 'primary') === 'primary' ? 8 : 4;
                        for ($i = 1; $i <= $maxClass; $i++): 
                        ?>
                            <option value="<?= $i ?>" <?= ($filters['class'] ?? '') == $i ? 'selected' : '' ?>>
                                Class <?= $i ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select filter-input" id="status" name="status">
                        <option value="">All Status</option>
                        <option value="active" <?= ($filters['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= ($filters['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" id="clearFilters" class="btn btn-filter w-100">
                        <i class="fas fa-times me-2"></i>Clear
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Students Table -->
    <div class="table-card">
        <div class="table-responsive">
            <table class="modern-students-table">
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
                        <tr class="student-row <?= $student['status'] === 'inactive' ? 'inactive-student' : '' ?>">
                            <td>
                                <span class="student-id"><?= htmlspecialchars($student['student_id']) ?></span>
                            </td>
                            <td>
                                <span class="student-name">
                                    <?= htmlspecialchars($student['full_name']) ?>
                                    <?php if ($student['status'] === 'inactive'): ?>
                                        <span class="badge bg-danger ms-2" style="font-size: 0.65rem;" title="Inactive Student">
                                            <i class="fas fa-ban"></i> Inactive
                                        </span>
                                    <?php endif; ?>
                                </span>
                            </td>
                            <td>
                                <span class="class-info">
                                    Class <?= htmlspecialchars($student['class']) ?>
                                    <?php if ($student['section']): ?>
                                        - Section <?= htmlspecialchars($student['section']) ?>
                                    <?php endif; ?>
                                </span>
                            </td>
                            <td>
                                <div class="contact-info">
                                    <?php if ($student['email']): ?>
                                        <div><i class="fas fa-envelope"></i> <?= htmlspecialchars($student['email']) ?></div>
                                    <?php endif; ?>
                                    <?php if ($student['phone']): ?>
                                        <div><i class="fas fa-phone"></i> <?= htmlspecialchars($student['phone']) ?></div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <?php if ($student['active_borrows'] > 0): ?>
                                    <span class="badge-modern badge-warning-modern"><?= $student['active_borrows'] ?> books</span>
                                <?php else: ?>
                                    <span class="badge-modern badge-success-modern">No borrows</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge-modern <?= $student['status'] === 'active' ? 'badge-success-modern' : 'badge-danger-modern' ?>">
                                    <i class="fas <?= $student['status'] === 'active' ? 'fa-check-circle' : 'fa-times-circle' ?> me-1"></i>
                                    <?= ucfirst($student['status']) ?>
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons-group">
                                    <a href="/jacarandalibraryms/librarian/view-student?id=<?= $student['id'] ?>" class="action-btn-modern btn-view" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="/jacarandalibraryms/librarian/edit-student?id=<?= $student['id'] ?>" class="action-btn-modern btn-edit" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php if ($student['status'] === 'active'): ?>
                                        <a href="/jacarandalibraryms/librarian/borrow-book?student_id=<?= $student['id'] ?>" class="action-btn-modern btn-borrow" title="Borrow Book">
                                            <i class="fas fa-book"></i>
                                        </a>
                                        <button type="button" class="action-btn-modern btn-deactivate" 
                                                data-student-id="<?= $student['id'] ?>" 
                                                data-student-name="<?= htmlspecialchars($student['full_name']) ?>" 
                                                title="Deactivate Student">
                                            <i class="fas fa-user-slash"></i>
                                        </button>
                                    <?php else: ?>
                                        <button type="button" class="action-btn-modern btn-activate" 
                                                data-student-id="<?= $student['id'] ?>" 
                                                data-student-name="<?= htmlspecialchars($student['full_name']) ?>" 
                                                title="Activate Student">
                                            <i class="fas fa-user-check"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?php if (empty($students)): ?>
                <div class="empty-state">
                    <i class="fas fa-user-graduate fa-3x"></i>
                    <p>No students found. <a href="/jacarandalibraryms/librarian/create-student">Add your first student</a></p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Library Info -->
    <div class="info-card">
        <h6><i class="fas fa-info-circle me-2"></i>Library Information</h6>
        <p>
            <strong><?= htmlspecialchars($library['name']) ?></strong> 
            (<?= ucfirst($library['type']) ?> School) - 
            Valid classes: <?= $library['type'] === 'primary' ? '1-8' : '1-4' ?>
        </p>
    </div>
</div>

<!-- Deactivate Student Modal -->
<div class="modal fade" id="deactivateModal" tabindex="-1" aria-labelledby="deactivateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deactivateModalLabel">
                    <i class="fas fa-user-slash me-2"></i>Deactivate Student
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="deactivateForm" method="POST" action="<?= BASE_PATH ?>/librarian/deactivate-student">
                <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                <div class="modal-body">
                    <input type="hidden" id="deactivate_student_id" name="student_id">
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Are you sure you want to deactivate <strong><span id="deactivate_student_name"></span></strong>?
                    </div>

                    <div class="mb-3">
                        <label for="deactivate_reason" class="form-label">
                            Reason for Deactivation <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control" id="deactivate_reason" name="reason" 
                                  rows="3" required placeholder="Enter reason for deactivating this student..."></textarea>
                        <small class="form-text text-muted">
                            This reason will be recorded in the activity log.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-user-slash me-2"></i>Deactivate Student
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Activate Student Modal -->
<div class="modal fade" id="activateModal" tabindex="-1" aria-labelledby="activateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="activateModalLabel">
                    <i class="fas fa-user-check me-2"></i>Activate Student
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="activateForm" method="POST" action="<?= BASE_PATH ?>/librarian/activate-student">
                <div class="modal-body">
                    <input type="hidden" id="activate_student_id" name="student_id">
                    
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>
                        Are you sure you want to activate <strong><span id="activate_student_name"></span></strong>?
                    </div>

                    <div class="mb-3">
                        <label for="activate_reason" class="form-label">
                            Reason for Activation <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control" id="activate_reason" name="reason" 
                                  rows="3" required placeholder="Enter reason for activating this student..."></textarea>
                        <small class="form-text text-muted">
                            This reason will be recorded in the activity log.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-user-check me-2"></i>Activate Student
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Live Search Styling */
.student-row {
    transition: opacity 0.3s ease;
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
    
    // AJAX Filter functionality
    const filterInputs = document.querySelectorAll('.filter-input');
    const clearFiltersBtn = document.getElementById('clearFilters');
    const studentsTableBody = document.querySelector('.modern-students-table tbody');
    
    // Apply filters on any select change
    filterInputs.forEach(input => {
        input.addEventListener('change', function() {
            applyFilters();
        });
    });
    
    // Clear filters button
    clearFiltersBtn.addEventListener('click', function() {
        filterInputs.forEach(input => {
            input.selectedIndex = 0;
        });
        liveSearchInput.value = '';
        document.getElementById('search').value = '';
        applyFilters();
    });
    
    function applyFilters() {
        // Show loading state
        if (studentsTableBody) {
            studentsTableBody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted">Loading students...</p>
                    </td>
                </tr>
            `;
        }
        
        // Build form data
        const formData = new FormData(document.getElementById('filterForm'));
        
        // Fetch filtered data
        fetch('<?= BASE_PATH ?>/librarian/students', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            // Parse the HTML response
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            
            // Update table body
            const newTableBody = doc.querySelector('.modern-students-table tbody');
            if (newTableBody && studentsTableBody) {
                studentsTableBody.innerHTML = newTableBody.innerHTML;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (studentsTableBody) {
                studentsTableBody.innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center py-5 text-danger">
                            <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                            <p>Error loading students. Please try again.</p>
                        </td>
                    </tr>
                `;
            }
        });
    }
});

// Deactivate/Activate Student Modals
let deactivateModal, activateModal;

document.addEventListener('click', function(e) {
    // Handle deactivate button
    const deactivateBtn = e.target.closest('.btn-deactivate');
    if (deactivateBtn) {
        e.preventDefault();
        if (!deactivateModal) {
            deactivateModal = new bootstrap.Modal(document.getElementById('deactivateModal'));
        }
        document.getElementById('deactivate_student_id').value = deactivateBtn.dataset.studentId;
        document.getElementById('deactivate_student_name').textContent = deactivateBtn.dataset.studentName;
        document.getElementById('deactivate_reason').value = '';
        deactivateModal.show();
    }
    
    // Handle activate button
    const activateBtn = e.target.closest('.btn-activate');
    if (activateBtn) {
        e.preventDefault();
        if (!activateModal) {
            activateModal = new bootstrap.Modal(document.getElementById('activateModal'));
        }
        document.getElementById('activate_student_id').value = activateBtn.dataset.studentId;
        document.getElementById('activate_student_name').textContent = activateBtn.dataset.studentName;
        document.getElementById('activate_reason').value = '';
        activateModal.show();
    }
});

// Handle form submissions
document.addEventListener('DOMContentLoaded', function() {
    const deactivateForm = document.getElementById('deactivateForm');
    if (deactivateForm) {
        deactivateForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(deactivateForm);
            
            fetch(deactivateForm.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(() => {
                if (deactivateModal) deactivateModal.hide();
                window.location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to deactivate student');
            });
        });
    }
    
    const activateForm = document.getElementById('activateForm');
    if (activateForm) {
        activateForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(activateForm);
            
            fetch(activateForm.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(() => {
                if (activateModal) activateModal.hide();
                window.location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to activate student');
            });
        });
    }
});
</script>

<?php include '../app/views/shared/footer.php'; ?>