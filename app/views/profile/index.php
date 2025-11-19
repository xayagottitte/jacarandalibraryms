<?php 
$title = "My Profile - Jacaranda Libraries";
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
    --info-gradient-start: #3b82f6;
    --info-gradient-end: #2563eb;
}

.profile-container {
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

.btn-edit-profile {
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

.btn-edit-profile:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-2px);
    color: white;
    box-shadow: 0 6px 20px rgba(255, 255, 255, 0.2);
}

/* Profile Summary Card */
.profile-card {
    background: white;
    border-radius: 20px;
    padding: 2.5rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    text-align: center;
    height: 100%;
    position: relative;
    overflow: hidden;
}

.profile-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 120px;
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--dark-purple) 100%);
    border-radius: 20px 20px 0 0;
}

.profile-avatar-container {
    position: relative;
    z-index: 1;
    margin-top: 1rem;
    margin-bottom: 1.5rem;
}

.profile-avatar {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 5px solid white;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}

.profile-avatar-placeholder {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--light-purple) 0%, var(--accent-purple) 100%);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 5px solid white;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}

.btn-camera {
    position: absolute;
    bottom: 5px;
    right: 5px;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: white;
    border: 2px solid var(--primary-purple);
    color: var(--primary-purple);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s;
    z-index: 2;
}

.btn-camera:hover {
    background: var(--primary-purple);
    color: white;
    transform: scale(1.1);
}

.profile-name {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--grey-dark);
    margin-bottom: 0.5rem;
}

.profile-role {
    color: var(--grey-medium);
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.875rem;
    letter-spacing: 0.5px;
}

.profile-library {
    color: var(--primary-purple);
    font-weight: 600;
    margin-top: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.profile-stats {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 2px solid var(--grey-light);
}

.profile-stat {
    text-align: center;
}

.profile-stat-label {
    font-size: 0.75rem;
    color: var(--grey-medium);
    text-transform: uppercase;
    font-weight: 600;
    letter-spacing: 0.5px;
    margin-bottom: 0.5rem;
}

.profile-stat-value {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--grey-dark);
}

.badge-modern {
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge-success {
    background: linear-gradient(135deg, var(--success-gradient-start) 0%, var(--success-gradient-end) 100%);
    color: white;
}

.badge-secondary {
    background: linear-gradient(135deg, var(--grey-medium) 0%, var(--grey-dark) 100%);
    color: white;
}

/* Info Cards */
.info-card {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    margin-bottom: 2rem;
}

.info-card-header {
    background: linear-gradient(135deg, var(--grey-lighter) 0%, white 100%);
    border-radius: 12px;
    padding: 1rem 1.5rem;
    margin-bottom: 1.5rem;
    border-left: 4px solid var(--primary-purple);
}

.info-card-header.success {
    border-left-color: var(--success-gradient-start);
}

.info-card-header.warning {
    border-left-color: var(--warning-gradient-start);
}

.info-card-header h5 {
    margin: 0;
    font-weight: 700;
    font-size: 1rem;
    color: var(--grey-dark);
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.info-table {
    width: 100%;
}

.info-table tr {
    border-bottom: 1px solid var(--grey-light);
}

.info-table tr:last-child {
    border-bottom: none;
}

.info-table th {
    padding: 0.875rem 0;
    font-weight: 700;
    color: var(--grey-dark);
    font-size: 0.875rem;
    text-align: left;
    width: 40%;
}

.info-table td {
    padding: 0.875rem 0;
    color: var(--grey-medium);
    font-weight: 500;
}

/* Performance Stats */
.perf-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.25rem;
    margin-bottom: 2rem;
}

.perf-stat-card {
    background: linear-gradient(135deg, var(--grey-lighter) 0%, white 100%);
    border-radius: 16px;
    padding: 1.75rem;
    text-align: center;
    transition: all 0.3s;
    border: 2px solid transparent;
}

.perf-stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    border-color: var(--primary-purple);
}

.perf-stat-icon {
    font-size: 2.5rem;
    margin-bottom: 1rem;
}

.perf-stat-value {
    font-size: 2rem;
    font-weight: 800;
    color: var(--grey-dark);
    margin-bottom: 0.5rem;
}

.perf-stat-label {
    font-size: 0.8rem;
    color: var(--grey-medium);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Activity Table */
.activity-table {
    width: 100%;
    margin-bottom: 0;
    border-collapse: collapse;
    font-size: 0.9rem;
}

.activity-table thead th {
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

.activity-table tbody tr {
    transition: all 0.2s ease;
    border-bottom: 1px solid var(--grey-light);
}

.activity-table tbody tr:hover {
    background: var(--grey-lighter);
    transform: scale(1.005);
}

.activity-table tbody td {
    padding: 1rem;
    vertical-align: middle;
    color: var(--grey-medium);
    font-weight: 500;
}

.empty-state {
    text-align: center;
    padding: 3rem 1rem;
    color: var(--grey-medium);
}

/* Modal Styles */
.modal-content {
    border-radius: 20px;
    border: none;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
}

.modal-header {
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--dark-purple) 100%);
    color: white;
    border-radius: 20px 20px 0 0;
    padding: 1.5rem 2rem;
    border-bottom: none;
}

.modal-header .modal-title {
    font-weight: 700;
    font-size: 1.25rem;
}

.modal-header .btn-close {
    filter: brightness(0) invert(1);
}

.modal-body {
    padding: 2rem;
}

.modal-body label {
    font-weight: 700;
    color: var(--grey-dark);
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
}

.modal-body .form-control,
.modal-body .form-select,
.modal-body textarea {
    border: 2px solid var(--grey-light);
    border-radius: 12px;
    padding: 0.75rem 1rem;
    font-size: 0.95rem;
    transition: all 0.3s;
}

.modal-body .form-control:focus,
.modal-body .form-select:focus,
.modal-body textarea:focus {
    border-color: var(--primary-purple);
    box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.25);
    outline: none;
}

.modal-footer {
    padding: 1.5rem 2rem;
    border-top: 2px solid var(--grey-light);
}

.btn-modal-cancel {
    background: linear-gradient(135deg, var(--grey-medium) 0%, var(--grey-dark) 100%);
    border: none;
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-modal-cancel:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(107, 114, 128, 0.3);
    color: white;
}

.btn-modal-submit {
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--dark-purple) 100%);
    border: none;
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-modal-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(99, 102, 241, 0.3);
    color: white;
}
</style>

<div class="container-fluid profile-container">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <h1><i class="fas fa-user-circle"></i>My Profile</h1>
            <button type="button" class="btn-edit-profile" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                <i class="fas fa-edit"></i> Edit Profile
            </button>
        </div>
    </div>

    <div class="row">
        <!-- Profile Summary Card -->
        <div class="col-md-4 mb-4">
            <div class="profile-card">
                <div class="profile-avatar-container">
                    <?php if (!empty($user['profile_photo'])): ?>
                        <img src="<?= BASE_PATH . htmlspecialchars($user['profile_photo']) ?>" 
                             alt="Profile Photo" class="profile-avatar">
                    <?php else: ?>
                        <div class="profile-avatar-placeholder">
                            <i class="fas fa-user fa-3x text-white"></i>
                        </div>
                    <?php endif; ?>
                    <button type="button" class="btn-camera" data-bs-toggle="modal" data-bs-target="#photoModal">
                        <i class="fas fa-camera"></i>
                    </button>
                </div>
                <h5 class="profile-name"><?= htmlspecialchars($user['full_name'] ?? $user['username']) ?></h5>
                <p class="profile-role"><?= ucfirst(str_replace('_', ' ', $user['role'] ?? 'user')) ?></p>
                <?php if ($library): ?>
                    <p class="profile-library">
                        <i class="fas fa-building"></i> <?= htmlspecialchars($library['name']) ?>
                    </p>
                <?php endif; ?>
                <div class="profile-stats">
                    <div class="profile-stat">
                        <div class="profile-stat-label">Member Since</div>
                        <div class="profile-stat-value"><?= date('M Y', strtotime($user['created_at'] ?? 'now')) ?></div>
                    </div>
                    <div class="profile-stat">
                        <div class="profile-stat-label">Status</div>
                        <div class="profile-stat-value">
                            <span class="badge-modern badge-<?= ($user['status'] ?? 'inactive') === 'active' ? 'success' : 'secondary' ?>">
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
            <div class="info-card">
                <div class="info-card-header">
                    <h5><i class="fas fa-user"></i>Personal Information</h5>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <table class="info-table">
                            <tr>
                                <th>Full Name:</th>
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
                        <table class="info-table">
                            <tr>
                                <th>Contact Number:</th>
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

            <!-- Employee Details -->
            <div class="info-card">
                <div class="info-card-header success">
                    <h5><i class="fas fa-briefcase"></i>Employee Details</h5>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <table class="info-table">
                            <tr>
                                <th>Role:</th>
                                <td>
                                    <span class="badge-modern badge-success">
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
                        <table class="info-table">
                            <tr>
                                <th>Employee Status:</th>
                                <td>
                                    <span class="badge-modern badge-<?= ($user['status'] ?? 'inactive') === 'active' ? 'success' : 'secondary' ?>">
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

            <!-- Performance & Activity -->
            <div class="info-card">
                <div class="info-card-header warning">
                    <h5><i class="fas fa-chart-line"></i>Performance & Activity Logs</h5>
                </div>
                
                <!-- Performance Stats -->
                <div class="perf-stats-grid">
                    <div class="perf-stat-card">
                        <i class="fas fa-book-open perf-stat-icon" style="color: var(--primary-purple);"></i>
                        <div class="perf-stat-value"><?= number_format($performance['books_issued'] ?? 0) ?></div>
                        <div class="perf-stat-label">Books Issued</div>
                    </div>
                    <div class="perf-stat-card">
                        <i class="fas fa-undo perf-stat-icon" style="color: var(--success-gradient-start);"></i>
                        <div class="perf-stat-value"><?= number_format($performance['books_returned'] ?? 0) ?></div>
                        <div class="perf-stat-label">Books Returned</div>
                    </div>
                    <div class="perf-stat-card">
                        <i class="fas fa-exclamation-triangle perf-stat-icon" style="color: var(--warning-gradient-start);"></i>
                        <div class="perf-stat-value"><?= number_format($performance['books_lost'] ?? 0) ?></div>
                        <div class="perf-stat-label">Books Lost</div>
                    </div>
                    <div class="perf-stat-card">
                        <i class="fas fa-money-bill perf-stat-icon" style="color: var(--info-gradient-start);"></i>
                        <div class="perf-stat-value">MK<?= number_format($performance['fines_collected'] ?? 0) ?></div>
                        <div class="perf-stat-label">Fines Collected</div>
                    </div>
                    <div class="perf-stat-card">
                        <i class="fas fa-chart-bar perf-stat-icon" style="color: var(--grey-dark);"></i>
                        <div class="perf-stat-value"><?= number_format($performance['reports_generated'] ?? 0) ?></div>
                        <div class="perf-stat-label">Reports Generated</div>
                    </div>
                    <div class="perf-stat-card">
                        <i class="fas fa-user-graduate perf-stat-icon" style="color: var(--primary-purple);"></i>
                        <div class="perf-stat-value"><?= number_format($performance['students_registered'] ?? 0) ?></div>
                        <div class="perf-stat-label">Students Registered</div>
                    </div>
                    <div class="perf-stat-card">
                        <i class="fas fa-exchange-alt perf-stat-icon" style="color: var(--success-gradient-start);"></i>
                        <div class="perf-stat-value"><?= number_format($performance['total_transactions'] ?? 0) ?></div>
                        <div class="perf-stat-label">Total Transactions</div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <h6 style="font-weight: 700; color: var(--grey-dark); margin: 2rem 0 1rem 0;">Recent Activity</h6>
                <div class="table-responsive">
                    <table class="activity-table">
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
                                            <span class="badge-modern badge-<?= ($log['status'] ?? 'success') === 'success' ? 'success' : 'warning' ?>">
                                                <?= ucfirst($log['status'] ?? 'Completed') ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="empty-state">No recent activity found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div><!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?= BASE_PATH ?>/profile/update">
                <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                <div class="modal-body">
                    <div class="row g-3">
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
                    <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-modal-submit">Save Changes</button>
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
                <h5 class="modal-title"><i class="fas fa-camera me-2"></i>Upload Profile Photo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?= BASE_PATH ?>/profile/upload-photo" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="profile_photo" class="form-label">Choose Photo</label>
                        <input type="file" class="form-control" id="profile_photo" name="profile_photo" 
                               accept="image/*" required>
                        <div class="form-text" style="color: var(--grey-medium); font-size: 0.85rem; margin-top: 0.5rem;">
                            Maximum file size: 5MB. Supported formats: JPEG, PNG, GIF
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-modal-submit">Upload Photo</button>
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