<?php 
$title = "Edit Student - Multi-Library System";
include '../app/views/shared/header.php'; 
include '../app/views/shared/layout-header.php'; 
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Edit Student</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="/jacarandalibraryms/librarian/view-student?id=<?= $student['id'] ?>" class="btn btn-outline-primary me-2">View Details</a>
            <a href="/jacarandalibraryms/librarian/students" class="btn btn-secondary">Back to Students</a>
        </div>
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
            <form method="POST" action="/jacarandalibraryms/librarian/edit-student">
                <input type="hidden" name="id" value="<?= $student['id'] ?>">
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="full_name" name="full_name" 
                                   value="<?= htmlspecialchars($student['full_name']) ?>" required>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="student_id" class="form-label">Student ID <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="student_id" name="student_id" 
                                   value="<?= htmlspecialchars($student['student_id']) ?>" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="class" class="form-label">Class <span class="text-danger">*</span></label>
                            <select class="form-select" id="class" name="class" required>
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
                        <div class="mb-3">
                            <label for="section" class="form-label">Section</label>
                            <input type="text" class="form-control" id="section" name="section" 
                                   value="<?= htmlspecialchars($student['section'] ?? '') ?>"
                                   placeholder="e.g., A, B, Red, Blue">
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="active" <?= $student['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                                <option value="inactive" <?= $student['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?= htmlspecialchars($student['email'] ?? '') ?>">
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" name="phone" 
                                   value="<?= htmlspecialchars($student['phone'] ?? '') ?>">
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="/jacarandalibraryms/librarian/students" class="btn btn-secondary me-md-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Student</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Student Info Card -->
    <div class="card mt-4">
        <div class="card-header">
            <h6 class="mb-0">Current Library: <?= htmlspecialchars($library['name']) ?></h6>
        </div>
        <div class="card-body">
            <p class="mb-1">
                <strong>Library Type:</strong> <?= ucfirst($library['type']) ?> School
            </p>
            <p class="mb-1">
                <strong>Valid Classes:</strong> <?= $library['type'] === 'primary' ? '1-8' : '1-4' ?>
            </p>
            <p class="mb-0">
                <strong>Created:</strong> <?= date('M d, Y', strtotime($student['created_at'])) ?>
            </p>
        </div>
    </div>
</div>

<?php include '../app/views/shared/layout-footer.php'; ?>
<?php include '../app/views/shared/footer.php'; ?>
