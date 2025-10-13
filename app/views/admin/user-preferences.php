<?php 
$title = "User Preferences - Multi-Library System";
include '../app/views/shared/header.php'; 
include '../app/views/shared/layout-header.php'; 
?>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="mb-0">User Preferences</h3>
                <a href="<?= BASE_PATH ?>/admin/dashboard" class="btn btn-secondary btn-sm">Back to Dashboard</a>
            </div>

            <!-- Flash Messages -->
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm">
                <div class="card-body p-3">
                    <form method="POST" action="<?= BASE_PATH ?>/system/preferences">
                        <h5 class="card-title mb-3">General Preferences</h5>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="theme" class="form-label form-label-sm">Theme</label>
                                    <select class="form-select form-select-sm" id="theme" name="theme">
                                        <option value="light" <?= ($data['preferences']['theme'] ?? 'light') === 'light' ? 'selected' : '' ?>>Light</option>
                                        <option value="dark" <?= ($data['preferences']['theme'] ?? 'light') === 'dark' ? 'selected' : '' ?>>Dark</option>
                                        <option value="auto" <?= ($data['preferences']['theme'] ?? 'light') === 'auto' ? 'selected' : '' ?>>Auto</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="language" class="form-label form-label-sm">Language</label>
                                    <select class="form-select form-select-sm" id="language" name="language">
                                        <option value="en" <?= ($data['preferences']['language'] ?? 'en') === 'en' ? 'selected' : '' ?>>English</option>
                                        <option value="es" <?= ($data['preferences']['language'] ?? 'en') === 'es' ? 'selected' : '' ?>>Spanish</option>
                                        <option value="fr" <?= ($data['preferences']['language'] ?? 'en') === 'fr' ? 'selected' : '' ?>>French</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="timezone" class="form-label form-label-sm">Timezone</label>
                                    <select class="form-select form-select-sm" id="timezone" name="timezone">
                                        <option value="America/New_York" <?= ($data['preferences']['timezone'] ?? 'America/New_York') === 'America/New_York' ? 'selected' : '' ?>>Eastern Time (EDT/EST)</option>
                                        <option value="America/Chicago" <?= ($data['preferences']['timezone'] ?? 'America/New_York') === 'America/Chicago' ? 'selected' : '' ?>>Central Time (CDT/CST)</option>
                                        <option value="America/Denver" <?= ($data['preferences']['timezone'] ?? 'America/New_York') === 'America/Denver' ? 'selected' : '' ?>>Mountain Time (MDT/MST)</option>
                                        <option value="America/Los_Angeles" <?= ($data['preferences']['timezone'] ?? 'America/New_York') === 'America/Los_Angeles' ? 'selected' : '' ?>>Pacific Time (PDT/PST)</option>
                                        <option value="Europe/London" <?= ($data['preferences']['timezone'] ?? 'America/New_York') === 'Europe/London' ? 'selected' : '' ?>>GMT (London)</option>
                                        <option value="UTC" <?= ($data['preferences']['timezone'] ?? 'America/New_York') === 'UTC' ? 'selected' : '' ?>>UTC</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="date_format" class="form-label form-label-sm">Date Format</label>
                                    <select class="form-select form-select-sm" id="date_format" name="date_format">
                                        <option value="Y-m-d" <?= ($data['preferences']['date_format'] ?? 'Y-m-d') === 'Y-m-d' ? 'selected' : '' ?>>YYYY-MM-DD</option>
                                        <option value="m/d/Y" <?= ($data['preferences']['date_format'] ?? 'Y-m-d') === 'm/d/Y' ? 'selected' : '' ?>>MM/DD/YYYY</option>
                                        <option value="d/m/Y" <?= ($data['preferences']['date_format'] ?? 'Y-m-d') === 'd/m/Y' ? 'selected' : '' ?>>DD/MM/YYYY</option>
                                        <option value="M j, Y" <?= ($data['preferences']['date_format'] ?? 'Y-m-d') === 'M j, Y' ? 'selected' : '' ?>>Month DD, YYYY</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <h5 class="card-title mb-3 mt-4">Dashboard Preferences</h5>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="items_per_page" class="form-label form-label-sm">Items Per Page</label>
                                    <select class="form-select form-select-sm" id="items_per_page" name="items_per_page">
                                        <option value="10" <?= ($data['preferences']['items_per_page'] ?? '20') === '10' ? 'selected' : '' ?>>10</option>
                                        <option value="20" <?= ($data['preferences']['items_per_page'] ?? '20') === '20' ? 'selected' : '' ?>>20</option>
                                        <option value="50" <?= ($data['preferences']['items_per_page'] ?? '20') === '50' ? 'selected' : '' ?>>50</option>
                                        <option value="100" <?= ($data['preferences']['items_per_page'] ?? '20') === '100' ? 'selected' : '' ?>>100</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="default_view" class="form-label form-label-sm">Default Dashboard View</label>
                                    <select class="form-select form-select-sm" id="default_view" name="default_view">
                                        <option value="overview" <?= ($data['preferences']['default_view'] ?? 'overview') === 'overview' ? 'selected' : '' ?>>Overview</option>
                                        <option value="users" <?= ($data['preferences']['default_view'] ?? 'overview') === 'users' ? 'selected' : '' ?>>Users</option>
                                        <option value="libraries" <?= ($data['preferences']['default_view'] ?? 'overview') === 'libraries' ? 'selected' : '' ?>>Libraries</option>
                                        <option value="reports" <?= ($data['preferences']['default_view'] ?? 'overview') === 'reports' ? 'selected' : '' ?>>Reports</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <h5 class="card-title mb-3 mt-4">Notification Preferences</h5>
                        
                        <div class="row g-3">
                            <div class="col-md-12">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="email_notifications" name="email_notifications" value="1" 
                                           <?= ($data['preferences']['email_notifications'] ?? '1') === '1' ? 'checked' : '' ?>>
                                    <label class="form-check-label form-label-sm" for="email_notifications">
                                        Email Notifications
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="browser_notifications" name="browser_notifications" value="1"
                                           <?= ($data['preferences']['browser_notifications'] ?? '1') === '1' ? 'checked' : '' ?>>
                                    <label class="form-check-label form-label-sm" for="browser_notifications">
                                        Browser Notifications
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="daily_summary" name="daily_summary" value="1"
                                           <?= ($data['preferences']['daily_summary'] ?? '0') === '1' ? 'checked' : '' ?>>
                                    <label class="form-check-label form-label-sm" for="daily_summary">
                                        Daily Summary Email
                                    </label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="system_alerts" name="system_alerts" value="1"
                                           <?= ($data['preferences']['system_alerts'] ?? '1') === '1' ? 'checked' : '' ?>>
                                    <label class="form-check-label form-label-sm" for="system_alerts">
                                        System Alerts
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 justify-content-end mt-4">
                            <a href="<?= BASE_PATH ?>/admin/dashboard" class="btn btn-secondary btn-sm">Cancel</a>
                            <button type="reset" class="btn btn-outline-secondary btn-sm">Reset</button>
                            <button type="submit" class="btn btn-primary btn-sm">Save Preferences</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>

<style>
.form-label-sm {
    font-size: 0.875rem;
    font-weight: 500;
    margin-bottom: 0.25rem;
}

.form-control-sm, .form-select-sm {
    font-size: 0.875rem;
    padding: 0.375rem 0.75rem;
}

.form-check-label {
    font-size: 0.875rem;
}

.card {
    border: 1px solid #e3e6f0;
    border-radius: 0.35rem;
}

.modern-dashboard {
    background: #f8f9fc;
    min-height: calc(100vh - 60px);
}

.card-title {
    color: #663399;
    font-size: 1.1rem;
    font-weight: 600;
    border-bottom: 2px solid #663399;
    padding-bottom: 0.5rem;
    margin-bottom: 1rem;
}
</style>

<?php include '../app/views/shared/layout-footer.php'; ?>
<?php include '../app/views/shared/footer.php'; ?>