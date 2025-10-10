<?php 
$title = "Library Management - Multi-Library System";
include '../app/views/shared/header.php'; 
include '../app/views/shared/admin-sidebar.php'; 
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Library Management</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="/admin/create-library" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Library
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

    <!-- Libraries Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Address</th>
                            <th>Librarians</th>
                            <th>Books</th>
                            <th>Students</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($libraries as $library): ?>
                            <tr>
                                <td><?= htmlspecialchars($library['name']) ?></td>
                                <td>
                                    <span class="badge bg-<?= $library['type'] === 'primary' ? 'info' : 'success' ?>">
                                        <?= ucfirst($library['type']) ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($library['address']) ?></td>
                                <td>
                                    <span class="badge bg-<?= $library['total_librarians'] > 0 ? 'success' : 'warning' ?>">
                                        <?= $library['total_librarians'] ?> Librarians
                                    </span>
                                </td>
                                <td><?= $library['total_books'] ?></td>
                                <td><?= $library['total_students'] ?></td>
                                <td><?= date('M j, Y', strtotime($library['created_at'])) ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="/admin/edit-library/<?= $library['id'] ?>" class="btn btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if ($library['total_librarians'] == 0 && $library['total_books'] == 0 && $library['total_students'] == 0): ?>
                                            <form method="POST" action="/admin/delete-library" class="d-inline" onsubmit="return confirmDelete('Are you sure you want to delete this library?')">
                                                <input type="hidden" name="id" value="<?= $library['id'] ?>">
                                                <button type="submit" class="btn btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Assign Librarian Section -->
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">Assign Librarian to Library</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="/admin/assign-librarian">
                <div class="row">
                    <div class="col-md-5">
                        <label for="library_id" class="form-label">Select Library</label>
                        <select class="form-select" id="library_id" name="library_id" required>
                            <option value="">Choose a library...</option>
                            <?php foreach ($libraries as $library): ?>
                                <option value="<?= $library['id'] ?>"><?= htmlspecialchars($library['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label for="librarian_id" class="form-label">Select Librarian</label>
                        <select class="form-select" id="librarian_id" name="librarian_id" required>
                            <option value="">Choose a librarian...</option>
                            <?php foreach ($available_librarians as $librarian): ?>
                                <option value="<?= $librarian['id'] ?>">
                                    <?= htmlspecialchars($librarian['full_name'] ?? $librarian['username']) ?> 
                                    (<?= htmlspecialchars($librarian['email']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-success w-100">Assign</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include '../app/views/shared/footer.php'; ?>