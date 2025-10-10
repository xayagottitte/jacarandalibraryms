<?php 
$title = "Student Management - Multi-Library System";
include '../app/views/shared/header.php'; 
include '../app/views/shared/librarian-sidebar.php'; 
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Student Management</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="/librarian/create-student" class="btn btn-primary">
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
            <form method="POST" action="/librarian/students">
                <div class="row">
                    <div class="col-md-4">
                        <label for="search" class="form-label">Search Students</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="<?= htmlspecialchars($filters['search'] ?? '') ?>" 
                               placeholder="Name, Student ID, or Email">
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
                                        <a href="/librarian/view-student/<?= $student['id'] ?>" class="btn btn-outline-primary" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="/librarian/edit-student/<?= $student['id'] ?>" class="btn btn-outline-secondary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="/librarian/borrow-book?student_id=<?= $student['id'] ?>" class="btn btn-outline-success" title="Borrow Book">
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
                        <p class="text-muted">No students found. <a href="/librarian/create-student">Add your first student</a></p>
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
</main>

<?php include '../app/views/shared/footer.php'; ?>