<?php 
$title = "Edit Student - Jacaranda Libraries";
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
    --blue-gradient-start: #3b82f6;
    --blue-gradient-end: #2563eb;
}

.edit-student-container {
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

.page-header h2 {
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.page-header h2 i {
    font-size: 2rem;
}

.header-actions {
    display: flex;
    gap: 0.75rem;
}

.btn-header {
    background: rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    font-size: 0.95rem;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-header:hover {
    background: rgba(255, 255, 255, 0.35);
    transform: translateY(-2px);
    color: white;
    box-shadow: 0 6px 20px rgba(255, 255, 255, 0.2);
}

/* Form Card */
.form-card {
    background: white;
    border-radius: 20px;
    padding: 2.5rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    margin-bottom: 2rem;
}

.section-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--grey-dark);
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid var(--grey-light);
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.section-title i {
    font-size: 1.5rem;
    color: var(--primary-purple);
}

/* Form Groups */
.form-group-custom {
    margin-bottom: 1.75rem;
}

.form-label-custom {
    font-weight: 600;
    color: var(--grey-dark);
    margin-bottom: 0.75rem;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-label-custom i {
    color: var(--primary-purple);
    font-size: 1rem;
}

.form-label-custom .required {
    color: #ef4444;
    margin-left: 0.25rem;
}

.form-control-custom,
.form-select-custom {
    border: 2px solid var(--grey-light);
    border-radius: 12px;
    padding: 0.875rem 1.25rem;
    font-size: 1rem;
    transition: all 0.3s;
    background: white;
}

.form-control-custom:focus,
.form-select-custom:focus {
    border-color: var(--primary-purple);
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    outline: none;
}

.form-control-custom:hover,
.form-select-custom:hover {
    border-color: var(--light-purple);
}

/* Info Box */
.info-box {
    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
    border-left: 4px solid var(--primary-purple);
    border-radius: 12px;
    padding: 1.25rem;
    margin-bottom: 2rem;
    display: flex;
    align-items: start;
    gap: 1rem;
}

.info-box i {
    color: var(--primary-purple);
    font-size: 1.5rem;
    margin-top: 0.25rem;
}

.info-box-content h6 {
    color: var(--grey-dark);
    font-weight: 700;
    margin-bottom: 0.5rem;
    font-size: 1rem;
}

.info-box-content p {
    color: var(--grey-medium);
    margin: 0;
    font-size: 0.9rem;
    line-height: 1.6;
}

/* Library Info Card */
.library-card {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.library-card h6 {
    font-size: 1.125rem;
    font-weight: 700;
    color: var(--grey-dark);
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.library-card h6 i {
    color: var(--primary-purple);
    font-size: 1.25rem;
}

.library-info-item {
    padding: 0.75rem 0;
    border-bottom: 1px solid var(--grey-lighter);
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.library-info-item:last-child {
    border-bottom: none;
}

.library-info-item i {
    color: var(--primary-purple);
    font-size: 1rem;
    width: 20px;
}

.library-info-item strong {
    color: var(--grey-dark);
    font-weight: 600;
    width: 120px;
}

.library-info-item span {
    color: var(--grey-medium);
    font-weight: 500;
}

/* Action Buttons */
.btn-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 2px solid var(--grey-light);
}

.btn-primary-custom {
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--dark-purple) 100%);
    border: none;
    color: white;
    padding: 1rem 2rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    cursor: pointer;
    box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
}

.btn-primary-custom:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 25px rgba(99, 102, 241, 0.4);
}

.btn-secondary-custom {
    background: white;
    border: 2px solid var(--grey-light);
    color: var(--grey-dark);
    padding: 1rem 2rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    text-decoration: none;
}

.btn-secondary-custom:hover {
    background: var(--grey-lighter);
    border-color: var(--grey-medium);
    color: var(--grey-dark);
    transform: translateY(-2px);
}

/* Responsive */
@media (max-width: 768px) {
    .edit-student-container {
        padding: 1rem;
    }
    
    .page-header {
        padding: 1.5rem;
    }
    
    .page-header h2 {
        font-size: 1.5rem;
    }
    
    .header-actions {
        flex-direction: column;
        width: 100%;
    }
    
    .btn-header {
        width: 100%;
        justify-content: center;
    }
    
    .form-card {
        padding: 1.5rem;
    }
    
    .btn-actions {
        flex-direction: column;
    }
    
    .btn-primary-custom,
    .btn-secondary-custom {
        width: 100%;
        justify-content: center;
    }
}
</style>

<div class="container-fluid edit-student-container">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h2><i class="fas fa-user-edit"></i>Edit Student</h2>
            <div class="header-actions">
                <a href="/jacarandalibraryms/librarian/view-student?id=<?= $student['id'] ?>" class="btn-header">
                    <i class="fas fa-eye"></i>View Details
                </a>
                <a href="/jacarandalibraryms/librarian/students" class="btn-header">
                    <i class="fas fa-arrow-left"></i>Back to Students
                </a>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show auto-dismiss-alert" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><?= $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="info-box">
                <i class="fas fa-info-circle"></i>
                <div class="info-box-content">
                    <h6>Editing Student Information</h6>
                    <p>Update the student's details below. Fields marked with <span style="color: #ef4444;">*</span> are required. Changes will be saved to the library database.</p>
                </div>
            </div>

            <div class="form-card">
                <form method="POST" action="/jacarandalibraryms/librarian/edit-student">
                    <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                    <input type="hidden" name="id" value="<?= $student['id'] ?>">
                    
                    <!-- Personal Information Section -->
                    <div class="section-title">
                        <i class="fas fa-user"></i>
                        <span>Personal Information</span>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group-custom">
                                <label for="full_name" class="form-label-custom">
                                    <i class="fas fa-id-badge"></i>
                                    Full Name<span class="required">*</span>
                                </label>
                                <input type="text" class="form-control form-control-custom" id="full_name" name="full_name" 
                                       value="<?= htmlspecialchars($student['full_name']) ?>" 
                                       placeholder="Enter student's full name" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group-custom">
                                <label for="student_id" class="form-label-custom">
                                    <i class="fas fa-hashtag"></i>
                                    Student ID<span class="required">*</span>
                                </label>
                                <input type="text" class="form-control form-control-custom" id="student_id" name="student_id" 
                                       value="<?= htmlspecialchars($student['student_id']) ?>" 
                                       placeholder="Enter unique student ID" required>
                            </div>
                        </div>
                    </div>

                    <!-- Academic Information Section -->
                    <div class="section-title mt-4">
                        <i class="fas fa-graduation-cap"></i>
                        <span>Academic Information</span>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group-custom">
                                <label for="class" class="form-label-custom">
                                    <i class="fas fa-chalkboard"></i>
                                    Class<span class="required">*</span>
                                </label>
                                <select class="form-select form-select-custom" id="class" name="class" required>
                                    <option value="">Select Class</option>
                                    <?php 
                                    $maxClass = $library['type'] === 'primary' ? 8 : 4;
                                    for ($i = 1; $i <= $maxClass; $i++): 
                                    ?>
                                        <option value="<?= $i ?>" <?= $student['class'] == $i ? 'selected' : '' ?>>
                                            Class <?= $i ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group-custom">
                                <label for="section" class="form-label-custom">
                                    <i class="fas fa-layer-group"></i>
                                    Section
                                </label>
                                <input type="text" class="form-control form-control-custom" id="section" name="section" 
                                       value="<?= htmlspecialchars($student['section'] ?? '') ?>"
                                       placeholder="e.g., A, B, Red, Blue">
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group-custom">
                                <label for="status" class="form-label-custom">
                                    <i class="fas fa-toggle-on"></i>
                                    Status
                                </label>
                                <select class="form-select form-select-custom" id="status" name="status">
                                    <option value="active" <?= $student['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                                    <option value="inactive" <?= $student['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information Section -->
                    <div class="section-title mt-4">
                        <i class="fas fa-address-book"></i>
                        <span>Contact Information</span>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group-custom">
                                <label for="email" class="form-label-custom">
                                    <i class="fas fa-envelope"></i>
                                    Email Address
                                </label>
                                <input type="email" class="form-control form-control-custom" id="email" name="email" 
                                       value="<?= htmlspecialchars($student['email'] ?? '') ?>"
                                       placeholder="student@example.com">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group-custom">
                                <label for="phone" class="form-label-custom">
                                    <i class="fas fa-phone"></i>
                                    Phone Number
                                </label>
                                <input type="tel" class="form-control form-control-custom" id="phone" name="phone" 
                                       value="<?= htmlspecialchars($student['phone'] ?? '') ?>"
                                       placeholder="+265 XXX XXX XXX">
                            </div>
                        </div>
                    </div>

                    <div class="btn-actions">
                        <button type="submit" class="btn-primary-custom">
                            <i class="fas fa-save"></i>
                            Update Student
                        </button>
                        <a href="/jacarandalibraryms/librarian/students" class="btn-secondary-custom">
                            <i class="fas fa-times"></i>
                            Cancel
                        </a>
                    </div>
                </form>
            </div>

            <!-- Library Info Card -->
            <div class="library-card">
                <h6>
                    <i class="fas fa-university"></i>
                    <?= htmlspecialchars($library['name']) ?>
                </h6>
                <div class="library-info-item">
                    <i class="fas fa-building"></i>
                    <strong>Library Type:</strong>
                    <span><?= ucfirst($library['type']) ?> School</span>
                </div>
                <div class="library-info-item">
                    <i class="fas fa-list-ol"></i>
                    <strong>Valid Classes:</strong>
                    <span><?= $library['type'] === 'primary' ? '1-8' : '1-4' ?></span>
                </div>
                <div class="library-info-item">
                    <i class="fas fa-calendar-plus"></i>
                    <strong>Created:</strong>
                    <span><?= date('M d, Y', strtotime($student['created_at'])) ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../app/views/shared/layout-footer.php'; ?>
<?php include '../app/views/shared/footer.php'; ?>
