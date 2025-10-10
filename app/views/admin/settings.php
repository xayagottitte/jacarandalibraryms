<?php 
$title = "System Settings - Multi-Library System";
include '../app/views/shared/header.php'; 
include '../app/views/shared/admin-sidebar.php'; 
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">System Settings</h1>
    </div>

    <!-- Flash Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="/admin/settings">
                <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                
                <div class="row">
                    <?php foreach ($settings as $setting): ?>
                        <div class="col-md-6 mb-3">
                            <label for="setting_<?= $setting['id'] ?>" class="form-label">
                                <?= htmlspecialchars(ucwords(str_replace('_', ' ', $setting['setting_key']))) ?>
                            </label>
                            <?php if (strpos($setting['setting_key'], 'period') !== false || strpos($setting['setting_key'], 'max') !== false): ?>
                                <input type="number" 
                                       class="form-control" 
                                       id="setting_<?= $setting['id'] ?>" 
                                       name="settings[<?= $setting['setting_key'] ?>]" 
                                       value="<?= htmlspecialchars($setting['setting_value']) ?>"
                                       min="1">
                            <?php elseif (strpos($setting['setting_key'], 'fine') !== false): ?>
                                <input type="number" 
                                       class="form-control" 
                                       id="setting_<?= $setting['id'] ?>" 
                                       name="settings[<?= $setting['setting_key'] ?>]" 
                                       value="<?= htmlspecialchars($setting['setting_value']) ?>"
                                       min="0" step="0.5">
                            <?php else: ?>
                                <input type="text" 
                                       class="form-control" 
                                       id="setting_<?= $setting['id'] ?>" 
                                       name="settings[<?= $setting['setting_key'] ?>]" 
                                       value="<?= htmlspecialchars($setting['setting_value']) ?>">
                            <?php endif; ?>
                            <?php if (!empty($setting['description'])): ?>
                                <div class="form-text"><?= htmlspecialchars($setting['description']) ?></div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                    <button type="submit" class="btn btn-primary">Save Settings</button>
                </div>
            </form>
        </div>
    </div>

    <!-- System Information -->
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">System Information</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-sm">
                        <tr>
                            <td><strong>PHP Version</strong></td>
                            <td><?= phpversion() ?></td>
                        </tr>
                        <tr>
                            <td><strong>Database</strong></td>
                            <td>MySQL</td>
                        </tr>
                        <tr>
                            <td><strong>Server Software</strong></td>
                            <td><?= $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown' ?></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-sm">
                        <tr>
                            <td><strong>System Time</strong></td>
                            <td><?= date('Y-m-d H:i:s') ?></td>
                        </tr>
                        <tr>
                            <td><strong>Timezone</strong></td>
                            <td><?= date_default_timezone_get() ?></td>
                        </tr>
                        <tr>
                            <td><strong>Memory Usage</strong></td>
                            <td><?= round(memory_get_usage(true) / 1024 / 1024, 2) ?> MB</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../app/views/shared/footer.php'; ?>