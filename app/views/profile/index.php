<?php 
$title = "My Profile - Multi-Library System";
include '../app/views/shared/header.php'; 
include '../app/views/shared/layout-header.php'; 
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">
            <i class="fas fa-user-circle text-primary"></i> My Profile
        </h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                <i class="fas fa-edit"></i> Edit Profile
            </button>
        </div>
    </div>

    <div class="row">
        <!-- Profile Summary Card -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="position-relative d-inline-block mb-3">
                        <?php if (!empty($user['profile_photo'])): ?>
                            <img src="<?= BASE_PATH . htmlspecialchars($user['profile_photo']) ?>" 
                                 alt="Profile Photo" class="rounded-circle" width="120" height="120" style="object-fit: cover;">
                        <?php else: ?>
                            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center" 
                                 style="width: 120px; height: 120px;">
                                <i class="fas fa-user fa-3x text-white"></i>
                            </div>
                        <?php endif; ?>
                        <button type="button" class="btn btn-sm btn-outline-primary rounded-circle position-absolute" 
                                style="bottom: 0; right: 0;" data-bs-toggle="modal" data-bs-target="#photoModal">
                            <i class="fas fa-camera"></i>
                        </button>
                    </div>
                    <h5 class="card-title"><?= htmlspecialchars($user['full_name'] ?? $user['username']) ?></h5>
                    <p class="text-muted"><?= ucfirst(str_replace('_', ' ', $user['role'] ?? 'user')) ?></p>
                    <?php if ($library): ?>
                        <p class="text-primary">
                            <i class="fas fa-building"></i> <?= htmlspecialchars($library['name']) ?>
                        </p>
                    <?php endif; ?>
                    <div class="row mt-3">
                        <div class="col">
                            <small class="text-muted">Member Since</small>
                            <br><strong><?= date('M Y', strtotime($user['created_at'] ?? 'now')) ?></strong>
                        </div>
                        <div class="col">
                            <small class="text-muted">Status</small>
                            <br><span class="badge bg-<?= ($user['status'] ?? 'inactive') === 'active' ? 'success' : 'secondary' ?>">
                                <?= ucfirst($user['status'] ?? 'inactive') ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Details -->
        <div class="col-md-8">
            <!-- Personal Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-user text-primary"></i> Personal Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <th width="40%">Full Name:</th>
                                    <td><?= htmlspecialchars($user['full_name'] ?? 'Not provided') ?></td>
                                </tr>
                                <tr>
                                    <th>Employee ID:</th>
                                    <td><?= htmlspecialchars($user['employee_id'] ?? 'Not assigned') ?></td>
                                </tr>
                                <tr>
                                    <th>Date of Birth:</th>
                                    <td>
                                        <?php if (isset($user['date_of_birth']) && $user['date_of_birth']): ?>
                                            <?= date('F j, Y', strtotime($user['date_of_birth'])) ?>
                                            <small class="text-muted">(<?= date_diff(date_create($user['date_of_birth']), date_create('today'))->y ?> years old)</small>
                                        <?php else: ?>
                                            Not provided
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Gender:</th>
                                    <td><?= htmlspecialchars(ucfirst($user['gender'] ?? 'Not specified')) ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <th width="40%">Contact Number:</th>
                                    <td><?= htmlspecialchars($user['phone'] ?? 'Not provided') ?></td>
                                </tr>
                                <tr>
                                    <th>Email Address:</th>
                                    <td><?= htmlspecialchars($user['email'] ?? 'Not provided') ?></td>
                                </tr>
                                <tr>
                                    <th>Address:</th>
                                    <td><?= htmlspecialchars($user['address'] ?? 'Not provided') ?></td>
                                </tr>
                                <tr>
                                    <th>Username:</th>
                                    <td><?= htmlspecialchars($user['username'] ?? 'N/A') ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Employee Details -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-briefcase text-success"></i> Employee Details
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <th width="40%">Role:</th>
                                    <td>
                                        <span class="badge bg-primary">
                                            <?= ucfirst(str_replace('_', ' ', $user['role'] ?? 'user')) ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Library:</th>
                                    <td><?= $library ? htmlspecialchars($library['name']) : 'Not assigned' ?></td>
                                </tr>
                                <tr>
                                    <th>Date of Joining:</th>
                                    <td><?= date('F j, Y', strtotime($user['created_at'] ?? 'now')) ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <th width="40%">Employee Status:</th>
                                    <td>
                                        <span class="badge bg-<?= ($user['status'] ?? 'inactive') === 'active' ? 'success' : 'secondary' ?>">
                                            <?= ucfirst($user['status'] ?? 'inactive') ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Supervisor:</th>
                                    <td><?= htmlspecialchars($user['supervisor'] ?? 'System Administrator') ?></td>
                                </tr>
                                <tr>
                                    <th>Department:</th>
                                    <td><?= $library ? htmlspecialchars($library['type'] . ' Library') : 'Library Services' ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Performance & Activity -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-line text-warning"></i> Performance & Activity Logs
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Performance Stats -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="fas fa-book-open fa-2x text-primary mb-2"></i>
                                <h4><?= number_format($performance['books_issued'] ?? 0) ?></h4>
                                <small class="text-muted">Books Issued</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="fas fa-undo fa-2x text-success mb-2"></i>
                                <h4><?= number_format($performance['books_returned'] ?? 0) ?></h4>
                                <small class="text-muted">Books Returned</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="fas fa-exclamation-triangle fa-2x text-warning mb-2"></i>
                                <h4><?= number_format($performance['books_lost'] ?? 0) ?></h4>
                                <small class="text-muted">Books Lost</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="fas fa-money-bill fa-2x text-info mb-2"></i>
                                <h4>MK<?= number_format($performance['fines_collected'] ?? 0) ?></h4>
                                <small class="text-muted">Fines Collected</small>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Stats -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="fas fa-chart-bar fa-2x text-secondary mb-2"></i>
                                <h4><?= number_format($performance['reports_generated'] ?? 0) ?></h4>
                                <small class="text-muted">Reports Generated</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="fas fa-user-graduate fa-2x text-primary mb-2"></i>
                                <h4><?= number_format($performance['students_registered'] ?? 0) ?></h4>
                                <small class="text-muted">Students Registered</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="fas fa-exchange-alt fa-2x text-success mb-2"></i>
                                <h4><?= number_format($performance['total_transactions'] ?? 0) ?></h4>
                                <small class="text-muted">Total Transactions</small>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <h6>Recent Activity</h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Activity</th>
                                    <th>Details</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($activity_logs)): ?>
                                    <?php foreach (array_slice($activity_logs, 0, 10) as $log): ?>
                                        <tr>
                                            <td><?= date('M j, Y', strtotime($log['created_at'] ?? $log['date'])) ?></td>
                                            <td><?= htmlspecialchars($log['activity'] ?? $log['action']) ?></td>
                                            <td><?= htmlspecialchars($log['details'] ?? $log['description']) ?></td>
                                            <td>
                                                <span class="badge bg-<?= ($log['status'] ?? 'success') === 'success' ? 'success' : 'warning' ?>">
                                                    <?= ucfirst($log['status'] ?? 'Completed') ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">No recent activity found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?= BASE_PATH ?>/profile/update">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="full_name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" 
                                       value="<?= htmlspecialchars($user['full_name'] ?? '') ?>">
                            </div>
                            <div class="mb-3">
                                <label for="employee_id" class="form-label">Employee ID</label>
                                <input type="text" class="form-control" id="employee_id" name="employee_id" 
                                       value="<?= htmlspecialchars($user['employee_id'] ?? '') ?>">
                            </div>
                            <div class="mb-3">
                                <label for="date_of_birth" class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" 
                                       value="<?= $user['date_of_birth'] ?? '' ?>">
                            </div>
                            <div class="mb-3">
                                <label for="gender" class="form-label">Gender</label>
                                <select class="form-select" id="gender" name="gender">
                                    <option value="">Select Gender</option>
                                    <option value="male" <?= ($user['gender'] ?? '') === 'male' ? 'selected' : '' ?>>Male</option>
                                    <option value="female" <?= ($user['gender'] ?? '') === 'female' ? 'selected' : '' ?>>Female</option>
                                    <option value="other" <?= ($user['gender'] ?? '') === 'other' ? 'selected' : '' ?>>Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Contact Number</label>
                                <input type="tel" class="form-control" id="phone" name="phone" 
                                       value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?= htmlspecialchars($user['email'] ?? '') ?>">
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="3"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Photo Upload Modal -->
<div class="modal fade" id="photoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Profile Photo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?= BASE_PATH ?>/profile/upload-photo" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="profile_photo" class="form-label">Choose Photo</label>
                        <input type="file" class="form-control" id="profile_photo" name="profile_photo" 
                               accept="image/*" required>
                        <div class="form-text">Maximum file size: 5MB. Supported formats: JPEG, PNG, GIF</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Upload Photo</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Debug form submissions
document.addEventListener('DOMContentLoaded', function() {
    // Debug profile update form
    const profileForm = document.querySelector('form[action*="/profile/update"]');
    if (profileForm) {
        console.log('Profile update form found:', profileForm);
        profileForm.addEventListener('submit', function(e) {
            console.log('Profile update form submitted');
            console.log('Form action:', this.action);
            console.log('Form method:', this.method);
            console.log('Form data:', new FormData(this));
            
            // Log to our debug system
            fetch('/jacarandalibraryms/public/form-debug.php', {
                method: 'POST',
                body: new FormData(this)
            }).then(response => {
                console.log('Debug request sent');
            }).catch(error => {
                console.log('Debug request failed:', error);
            });
            
            // Don't prevent default - let the form submit normally
        });
    }
    
    // Debug photo upload form  
    const photoForm = document.querySelector('form[action*="/profile/upload-photo"]');
    if (photoForm) {
        console.log('Photo upload form found:', photoForm);
        photoForm.addEventListener('submit', function(e) {
            console.log('Photo upload form submitted');
            console.log('Form action:', this.action);
            console.log('Form method:', this.method);
            console.log('Form files:', this.querySelector('input[type="file"]').files);
            
            // Don't prevent default - let the form submit normally
        });
    }
});
</script>

<?php include '../app/views/shared/footer.php'; ?>