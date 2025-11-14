<?php 
$title = "System Settings - Multi-Library System";
include '../app/views/shared/header.php'; 
include '../app/views/shared/layout-header.php'; 

// Helper function to get setting values
function getSettingValue($settings, $key, $default = '') {
    foreach ($settings as $setting) {
        if ($setting['setting_key'] === $key) {
            return htmlspecialchars($setting['setting_value']);
        }
    }
    return $default;
}
?>

<style>
    :root {
        --jacaranda-primary: #663399;
        --jacaranda-secondary: #8a4baf;
    }
    
    .settings-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        color: white;
        box-shadow: 0 4px 20px rgba(102, 51, 153, 0.3);
    }
    
    .settings-header h2 {
        font-weight: 700;
        margin-bottom: 0.5rem;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
    }
    
    .settings-header p {
        opacity: 0.95;
        margin-bottom: 0;
    }
    
    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    }
    
    .card-header {
        background: linear-gradient(135deg, var(--jacaranda-primary) 0%, var(--jacaranda-secondary) 100%) !important;
        border: none;
        padding: 1.25rem 1.5rem;
    }
    
    .card-header h5 {
        font-weight: 600;
        margin: 0;
    }
    
    .card-body {
        padding: 1.5rem;
    }
    
    .form-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 0.5rem;
    }
    
    .form-control, .form-select {
        border-radius: 8px;
        border: 2px solid #e9ecef;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: var(--jacaranda-primary);
        box-shadow: 0 0 0 0.2rem rgba(102, 51, 153, 0.15);
    }
    
    .alert {
        border: none;
        border-radius: 10px;
        padding: 1rem 1.25rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .alert-info {
        background: linear-gradient(135deg, #e0f2fe 0%, #dbeafe 100%);
        color: #075985;
        border-left: 4px solid #0284c7;
    }
    
    .alert-success {
        background: linear-gradient(135deg, #dcfce7 0%, #d1fae5 100%);
        color: #065f46;
        border-left: 4px solid #10b981;
    }
    
    .alert-danger {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #991b1b;
        border-left: 4px solid #dc2626;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, var(--jacaranda-primary) 0%, var(--jacaranda-secondary) 100%);
        border: none;
        padding: 0.75rem 2rem;
        font-weight: 600;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(102, 51, 153, 0.3);
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 51, 153, 0.4);
        background: linear-gradient(135deg, var(--jacaranda-secondary) 0%, var(--jacaranda-primary) 100%);
    }
    
    .system-info-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: 2px solid #dee2e6;
        border-radius: 10px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
    }
    
    .system-info-card:hover {
        border-color: var(--jacaranda-primary);
        transform: translateY(-3px);
        box-shadow: 0 4px 15px rgba(102, 51, 153, 0.15);
    }
    
    .system-info-card i {
        font-size: 2.5rem;
        margin-bottom: 1rem;
    }
    
    .system-info-card h6 {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
    }
    
    .system-info-card p {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--jacaranda-primary);
        margin-bottom: 0.5rem;
    }
    
    .badge {
        padding: 0.4rem 0.8rem;
        font-weight: 600;
        border-radius: 6px;
    }
    
    small.text-muted {
        display: block;
        margin-top: 0.5rem;
        font-size: 0.875rem;
        color: #6c757d;
    }
    
    .summary-card {
        background: linear-gradient(135deg, var(--jacaranda-primary) 0%, var(--jacaranda-secondary) 100%);
        border: none;
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(102, 51, 153, 0.3);
    }
    
    .summary-card h3 {
        font-weight: 700;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
    }
    
    .info-table {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .info-table h6 {
        color: var(--jacaranda-primary);
        font-weight: 700;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #e9ecef;
    }
    
    .info-table table {
        margin-bottom: 0;
    }
    
    .info-table td {
        padding: 0.75rem 0.5rem;
        border-bottom: 1px solid #f1f3f5;
    }
    
    .info-table td:first-child {
        font-weight: 600;
        color: #495057;
        width: 40%;
    }
</style>

<div class="container-fluid px-4 py-4">
    <div class="settings-header">
        <h2><i class="fas fa-cog me-3"></i>System Settings</h2>
        <p>Configure and manage your multi-library system preferences</p>
    </div>

    <!-- Flash Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_PATH ?>/admin/settings">
        <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
        
        <!-- General Settings -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-cog me-2"></i>General Settings</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">System Name</label>
                        <input type="text" class="form-control" name="settings[system_name]" 
                               value="<?= getSettingValue($settings, 'system_name', 'Multi-Library System') ?>">
                        <small class="text-muted">The name displayed across the system</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Date Format</label>
                        <select class="form-select" name="settings[date_format]">
                            <option value="Y-m-d" <?= getSettingValue($settings, 'date_format') === 'Y-m-d' ? 'selected' : '' ?>>YYYY-MM-DD (2025-11-14)</option>
                            <option value="m/d/Y" <?= getSettingValue($settings, 'date_format') === 'm/d/Y' ? 'selected' : '' ?>>MM/DD/YYYY (11/14/2025)</option>
                            <option value="d/m/Y" <?= getSettingValue($settings, 'date_format') === 'd/m/Y' ? 'selected' : '' ?>>DD/MM/YYYY (14/11/2025)</option>
                            <option value="M j, Y" <?= getSettingValue($settings, 'date_format') === 'M j, Y' ? 'selected' : '' ?>>Month DD, YYYY (Nov 14, 2025)</option>
                        </select>
                        <small class="text-muted">How dates are displayed throughout the system</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Library Settings -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-book me-2"></i>Library Settings</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Note:</strong> These are system-wide defaults. Individual libraries can override these in the 
                    <a href="<?= BASE_PATH ?>/admin/libraries" class="alert-link">Libraries</a> section.
                </div>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">
                            <i class="fas fa-calendar-alt me-1"></i>Default Loan Period (Days)
                        </label>
                        <input type="number" class="form-control" 
                               name="settings[loan_period_days]" 
                               value="<?= getSettingValue($settings, 'loan_period_days', '5') ?>" 
                               min="1" max="90" required>
                        <small class="text-muted">Number of days students can borrow books</small>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">
                            <i class="fas fa-book me-1"></i>Maximum Books Per Student
                        </label>
                        <input type="number" class="form-control" 
                               name="settings[max_books_per_student]" 
                               value="<?= getSettingValue($settings, 'max_books_per_student', '5') ?>" 
                               min="1" max="20" required>
                        <small class="text-muted">Maximum books per student at once</small>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">
                            <i class="fas fa-dollar-sign me-1"></i>Fine Per Day (MK)
                        </label>
                        <input type="number" class="form-control" 
                               name="settings[fine_per_day]" 
                               value="<?= getSettingValue($settings, 'fine_per_day', '500') ?>" 
                               min="0" step="50" required>
                        <small class="text-muted">Fine amount for overdue books (Malawian Kwacha)</small>
                    </div>
                </div>

                <!-- Summary Card -->
                <div class="card mt-4 summary-card">
                    <div class="card-body text-white">
                        <h6 class="mb-3"><i class="fas fa-chart-line me-2"></i>Current Configuration Summary</h6>
                        <div class="row text-center">
                            <div class="col-md-4">
                                <h3 class="mb-0"><?= getSettingValue($settings, 'loan_period_days', '5') ?></h3>
                                <small>Days to Borrow</small>
                            </div>
                            <div class="col-md-4">
                                <h3 class="mb-0"><?= getSettingValue($settings, 'max_books_per_student', '5') ?></h3>
                                <small>Max Books/Student</small>
                            </div>
                            <div class="col-md-4">
                                <h3 class="mb-0">MK <?= getSettingValue($settings, 'fine_per_day', '500') ?></h3>
                                <small>Fine Per Day</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Security Settings -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Security Settings</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Session Timeout</label>
                        <select class="form-select" name="settings[session_timeout]">
                            <option value="1800" <?= getSettingValue($settings, 'session_timeout') === '1800' ? 'selected' : '' ?>>30 minutes</option>
                            <option value="3600" <?= getSettingValue($settings, 'session_timeout', '3600') === '3600' ? 'selected' : '' ?>>1 hour</option>
                            <option value="7200" <?= getSettingValue($settings, 'session_timeout') === '7200' ? 'selected' : '' ?>>2 hours</option>
                            <option value="14400" <?= getSettingValue($settings, 'session_timeout') === '14400' ? 'selected' : '' ?>>4 hours</option>
                        </select>
                        <small class="text-muted">Automatic logout after inactivity</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Password Minimum Length</label>
                        <input type="number" class="form-control" name="settings[min_password_length]" 
                               value="<?= getSettingValue($settings, 'min_password_length', '6') ?>" min="4" max="20">
                        <small class="text-muted">Minimum required password length</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Save Button -->
        <div class="mb-4 d-flex justify-content-end">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-save me-2"></i>Save All Settings
            </button>
        </div>
    </form>

    <!-- System Information -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 text-white"><i class="fas fa-info-circle me-2"></i>System Information</h5>
        </div>
        <div class="card-body">
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="system-info-card">
                        <i class="fab fa-php text-primary"></i>
                        <h6>PHP Version</h6>
                        <p class="mb-0"><?= phpversion() ?></p>
                        <span class="badge bg-success">Running</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="system-info-card">
                        <i class="fas fa-database text-success"></i>
                        <h6>Database</h6>
                        <p class="mb-0">MySQL</p>
                        <span class="badge bg-success">Connected</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="system-info-card">
                        <i class="fas fa-memory text-warning"></i>
                        <h6>Memory Usage</h6>
                        <p class="mb-0"><?= round(memory_get_usage(true) / 1024 / 1024, 2) ?> MB</p>
                        <span class="badge bg-info">Monitoring</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="system-info-card">
                        <i class="fas fa-clock text-info"></i>
                        <h6>System Time</h6>
                        <p class="mb-0"><?= date('H:i:s') ?></p>
                        <span class="badge bg-secondary"><?= date_default_timezone_get() ?></span>
                    </div>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="info-table">
                        <h6><i class="fas fa-server me-2"></i>Server Information</h6>
                        <table class="table table-sm">
                            <tr>
                                <td>Server Software</td>
                                <td class="text-muted"><?= $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown' ?></td>
                            </tr>
                            <tr>
                                <td>Document Root</td>
                                <td class="text-muted"><?= basename($_SERVER['DOCUMENT_ROOT'] ?? 'N/A') ?></td>
                            </tr>
                            <tr>
                                <td>Server Admin</td>
                                <td class="text-muted"><?= $_SERVER['SERVER_ADMIN'] ?? 'Not Set' ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-table">
                        <h6><i class="fas fa-cogs me-2"></i>PHP Configuration</h6>
                        <table class="table table-sm">
                            <tr>
                                <td>Max Upload Size</td>
                                <td class="text-muted"><?= ini_get('upload_max_filesize') ?></td>
                            </tr>
                            <tr>
                                <td>Memory Limit</td>
                                <td class="text-muted"><?= ini_get('memory_limit') ?></td>
                            </tr>
                            <tr>
                                <td>Max Execution Time</td>
                                <td class="text-muted"><?= ini_get('max_execution_time') ?>s</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-dismiss alerts after 5 seconds
setTimeout(function() {
    const alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach(alert => {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
    });
}, 5000);
</script>

<?php include '../app/views/shared/layout-footer.php'; ?>
