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

    <div class="settings-header">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="settings-title mb-1">System Settings</h2>
                <p class="settings-subtitle">Configure and manage your library system preferences</p>
            </div>
            <div class="header-actions">
                <button class="btn btn-outline-secondary btn-sm" onclick="resetToDefaults()">
                    <i class="fas fa-undo me-1"></i> Reset to Defaults
                </button>
                <button class="btn btn-primary btn-sm" onclick="exportSettings()">
                    <i class="fas fa-download me-1"></i> Export Settings
                </button>
                <button class="btn btn-warning btn-sm" onclick="debugTabs()" style="margin-left: 10px;">
                    <i class="fas fa-bug me-1"></i> Debug Tabs
                </button>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show modern-alert" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show modern-alert" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Settings Tabs -->
    <div class="settings-tabs">
        <ul class="nav nav-tabs modern-tabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#general-settings" type="button" role="tab" aria-controls="general-settings" aria-selected="true">
                    <i class="fas fa-cog me-2"></i>General Settings
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#library-settings" type="button" role="tab" aria-controls="library-settings" aria-selected="false">
                    <i class="fas fa-book me-2"></i>Library Settings
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#notification-settings" type="button" role="tab" aria-controls="notification-settings" aria-selected="false">
                    <i class="fas fa-bell me-2"></i>Notifications
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#security-settings" type="button" role="tab" aria-controls="security-settings" aria-selected="false">
                    <i class="fas fa-shield-alt me-2"></i>Security
                </button>
            </li>
        </ul>

        <div class="tab-content modern-tab-content">
            <form method="POST" action="<?= BASE_PATH ?>/admin/settings" class="settings-form">
                <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                
                <!-- General Settings Tab -->
                <div class="tab-pane fade show active" id="general-settings" role="tabpanel" aria-labelledby="general-settings-tab">
                    <div class="settings-section" style="border: 1px solid #e9ecef; border-radius: 10px; padding: 20px; background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);">
                        <div class="section-header mb-3">
                            <h5 style="color: #663399;">System Configuration</h5>
                            <p class="text-muted mb-0">Basic system-wide settings and configurations</p>
                        </div>
                        <div class="row g-3">
                            <div class="col-lg-6">
                                <div class="form-group-modern">
                                    <label class="form-label-modern">System Name</label>
                                    <input type="text" class="form-control-modern" name="settings[system_name]" 
                                           value="<?= getSettingValue($settings, 'system_name', 'Multi-Library System') ?>" placeholder="Enter system name">
                                    <div class="form-help">The name displayed across the system</div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group-modern">
                                    <label class="form-label-modern">Default Language</label>
                                    <select class="form-control-modern" name="settings[default_language]">
                                        <option value="en">English</option>
                                        <option value="es">Spanish</option>
                                        <option value="fr">French</option>
                                    </select>
                                    <div class="form-help">Default system language</div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group-modern">
                                    <label class="form-label-modern">Timezone</label>
                                    <select class="form-control-modern" name="settings[timezone]">
                                        <option value="America/New_York">Eastern Time</option>
                                        <option value="America/Chicago">Central Time</option>
                                        <option value="America/Denver">Mountain Time</option>
                                        <option value="America/Los_Angeles">Pacific Time</option>
                                        <option value="UTC">UTC</option>
                                    </select>
                                    <div class="form-help">System timezone for date/time display</div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group-modern">
                                    <label class="form-label-modern">Date Format</label>
                                    <select class="form-control-modern" name="settings[date_format]">
                                        <option value="Y-m-d">YYYY-MM-DD</option>
                                        <option value="m/d/Y">MM/DD/YYYY</option>
                                        <option value="d/m/Y">DD/MM/YYYY</option>
                                        <option value="M j, Y">Month DD, YYYY</option>
                                    </select>
                                    <div class="form-help">How dates are displayed throughout the system</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Library Settings Tab -->
                <div class="tab-pane fade" id="library-settings" role="tabpanel" aria-labelledby="library-settings-tab">
                    <div class="settings-section" style="border: 1px solid #e9ecef; border-radius: 10px; padding: 20px; background: linear-gradient(135deg, #f5f8ff 0%, #ffffff 100%);">
                        <div class="section-header mb-3">
                            <h5 style="color: #663399;">Library Operations</h5>
                            <p class="text-muted mb-0">Configure borrowing rules, fines, and library-specific settings</p>
                        </div>

                        <!-- Loan Period Section -->
                        <div class="alert alert-info mb-4">
                            <i class="fas fa-info-circle"></i>
                            <strong>Note:</strong> The settings below are system-wide defaults. Individual libraries can override these in the 
                            <a href="<?= BASE_PATH ?>/admin/libraries" class="alert-link">Libraries</a> section.
                        </div>

                        <div class="row g-3">
                            <!-- Loan Period Days -->
                            <div class="col-lg-6">
                                <div class="form-group-modern">
                                    <label class="form-label-modern">
                                        <i class="fas fa-calendar-alt me-2"></i>Default Loan Period (Days)
                                    </label>
                                    <input type="number" class="form-control-modern" 
                                           name="settings[loan_period_days]" 
                                           value="<?= getSettingValue($settings, 'loan_period_days', '5') ?>" 
                                           min="1" max="90" required>
                                    <div class="form-help">
                                        Number of days students can borrow books by default. Each library can override this.
                                    </div>
                                </div>
                            </div>

                            <!-- Max Books Per Student -->
                            <div class="col-lg-6">
                                <div class="form-group-modern">
                                    <label class="form-label-modern">
                                        <i class="fas fa-book me-2"></i>Maximum Books Per Student
                                    </label>
                                    <input type="number" class="form-control-modern" 
                                           name="settings[max_books_per_student]" 
                                           value="<?= getSettingValue($settings, 'max_books_per_student', '5') ?>" 
                                           min="1" max="20" required>
                                    <div class="form-help">
                                        Maximum number of books a student can borrow at once
                                    </div>
                                </div>
                            </div>

                            <!-- Fine Per Day -->
                            <div class="col-lg-6">
                                <div class="form-group-modern">
                                    <label class="form-label-modern">
                                        <i class="fas fa-dollar-sign me-2"></i>Fine Per Day (Overdue)
                                    </label>
                                    <input type="number" class="form-control-modern" 
                                           name="settings[fine_per_day]" 
                                           value="<?= getSettingValue($settings, 'fine_per_day', '5') ?>" 
                                           min="0" step="0.50" required>
                                    <div class="form-help">
                                        Fine amount charged per day for overdue books (in dollars)
                                    </div>
                                </div>
                            </div>

                            <!-- Reservation Period -->
                            <div class="col-lg-6">
                                <div class="form-group-modern">
                                    <label class="form-label-modern">
                                        <i class="fas fa-clock me-2"></i>Reservation Period (Days)
                                    </label>
                                    <input type="number" class="form-control-modern" 
                                           name="settings[reservation_period]" 
                                           value="<?= getSettingValue($settings, 'reservation_period', '7') ?>" 
                                           min="1" max="30" required>
                                    <div class="form-help">
                                        Number of days a book reservation remains valid
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Stats Card -->
                        <div class="card mt-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                            <div class="card-body">
                                <h6 class="mb-3"><i class="fas fa-chart-line me-2"></i>Current Configuration Summary</h6>
                                <div class="row text-center">
                                    <div class="col-md-3">
                                        <h3 class="mb-0"><?= getSettingValue($settings, 'loan_period_days', '5') ?></h3>
                                        <small>Days to Borrow</small>
                                    </div>
                                    <div class="col-md-3">
                                        <h3 class="mb-0"><?= getSettingValue($settings, 'max_books_per_student', '5') ?></h3>
                                        <small>Max Books/Student</small>
                                    </div>
                                    <div class="col-md-3">
                                        <h3 class="mb-0">$<?= getSettingValue($settings, 'fine_per_day', '5') ?></h3>
                                        <small>Fine Per Day</small>
                                    </div>
                                    <div class="col-md-3">
                                        <h3 class="mb-0"><?= getSettingValue($settings, 'reservation_period', '7') ?></h3>
                                        <small>Reservation Days</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Per-Library Configuration Info -->
                        <div class="card mt-4 border-primary">
                            <div class="card-header bg-primary text-white">
                                <i class="fas fa-building me-2"></i>Per-Library Configuration
                            </div>
                            <div class="card-body">
                                <p class="mb-2">
                                    <strong>Individual Library Settings:</strong> Each library can have its own loan period that overrides the system default.
                                </p>
                                <p class="mb-0">
                                    To configure per-library loan periods, go to 
                                    <a href="<?= BASE_PATH ?>/admin/libraries" class="btn btn-sm btn-primary">
                                        <i class="fas fa-building me-1"></i>Manage Libraries
                                    </a>
                                    and click "Edit" on any library.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notification Settings Tab -->
                <div class="tab-pane fade" id="notification-settings" role="tabpanel" aria-labelledby="notification-settings-tab">
                    <div class="settings-section" style="border: 1px solid #e9ecef; border-radius: 10px; padding: 20px; background: linear-gradient(135deg, #fff8f0 0%, #ffffff 100%);">
                        <div class="section-header mb-3">
                            <h5 style="color: #663399;">Notification Preferences</h5>
                            <p class="text-muted mb-0">Configure email notifications and system alerts</p>
                        </div>
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="notification-group">
                                    <h5>Email Notifications</h5>
                                    <div class="row g-3">
                                        <div class="col-lg-6">
                                            <div class="form-check-modern">
                                                <input class="form-check-input" type="checkbox" id="emailOverdue" checked>
                                                <label class="form-check-label" for="emailOverdue">
                                                    <strong>Overdue Book Notifications</strong>
                                                    <span>Send emails for overdue books</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-check-modern">
                                                <input class="form-check-input" type="checkbox" id="emailReturns">
                                                <label class="form-check-label" for="emailReturns">
                                                    <strong>Return Reminders</strong>
                                                    <span>Send return reminder emails</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-check-modern">
                                                <input class="form-check-input" type="checkbox" id="emailReservations">
                                                <label class="form-check-label" for="emailReservations">
                                                    <strong>Reservation Notifications</strong>
                                                    <span>Notify when reserved books are available</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-check-modern">
                                                <input class="form-check-input" type="checkbox" id="emailSystem" checked>
                                                <label class="form-check-label" for="emailSystem">
                                                    <strong>System Notifications</strong>
                                                    <span>Important system updates and alerts</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security Settings Tab -->
                <div class="tab-pane fade" id="security-settings" role="tabpanel" aria-labelledby="security-settings-tab">
                    <div class="settings-section" style="border: 1px solid #e9ecef; border-radius: 10px; padding: 20px; background: linear-gradient(135deg, #fff0f0 0%, #ffffff 100%);">
                        <div class="section-header mb-3">
                            <h5 style="color: #663399;">Security Configuration</h5>
                            <p class="text-muted mb-0">Manage authentication, session, and security settings</p>
                        </div>
                        <div class="row g-3">
                            <div class="col-lg-6">
                                <div class="form-group-modern">
                                    <label class="form-label-modern">Session Timeout</label>
                                    <select class="form-control-modern" name="settings[session_timeout]">
                                        <option value="1800">30 minutes</option>
                                        <option value="3600" selected>1 hour</option>
                                        <option value="7200">2 hours</option>
                                        <option value="14400">4 hours</option>
                                    </select>
                                    <div class="form-help">Automatic logout after inactivity</div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group-modern">
                                    <label class="form-label-modern">Password Minimum Length</label>
                                    <input type="number" class="form-control-modern" name="settings[min_password_length]" 
                                           value="6" min="4" max="20">
                                    <div class="form-help">Minimum required password length</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="security-options">
                                    <h5>Security Features</h5>
                                    <div class="row g-3">
                                        <div class="col-lg-6">
                                            <div class="form-check-modern">
                                                <input class="form-check-input" type="checkbox" id="twoFactor">
                                                <label class="form-check-label" for="twoFactor">
                                                    <strong>Two-Factor Authentication</strong>
                                                    <span>Require 2FA for admin accounts</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-check-modern">
                                                <input class="form-check-input" type="checkbox" id="loginLogging" checked>
                                                <label class="form-check-label" for="loginLogging">
                                                    <strong>Login Activity Logging</strong>
                                                    <span>Track all login attempts</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="settings-actions">
                    <div class="d-flex justify-content-end gap-3">
                        <button type="button" class="btn btn-outline-secondary btn-sm">Cancel Changes</button>
                        <button type="submit" class="btn btn-primary btn-sm px-3">
                            <i class="fas fa-save me-2"></i>Save All Settings
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- System Information -->
    <div class="system-info-section mt-3">
        <div class="section-header mb-3">
            <h4>System Information</h4>
            <p>Current system status and technical information</p>
        </div>
        
        <div class="row g-3">
            <!-- System Stats Cards -->
            <div class="col-xl-3 col-lg-6">
                <div class="info-card" style="border: 1px solid #663399; background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);">
                    <div class="info-card-header">
                        <i class="fab fa-php" style="color: #663399;"></i>
                        <h6>PHP Version</h6>
                    </div>
                    <div class="info-card-value" style="font-size: 1rem;"><?= phpversion() ?></div>
                    <div class="info-card-status success">Running</div>
                </div>
            </div>
            
            <div class="col-xl-3 col-lg-6">
                <div class="info-card" style="border: 1px solid #28a745; background: linear-gradient(135deg, #f8fff8 0%, #ffffff 100%);">
                    <div class="info-card-header">
                        <i class="fas fa-database" style="color: #28a745;"></i>
                        <h6>Database</h6>
                    </div>
                    <div class="info-card-value" style="font-size: 1rem;">MySQL</div>
                    <div class="info-card-status success">Connected</div>
                </div>
            </div>
            
            <div class="col-xl-3 col-lg-6">
                <div class="info-card" style="border: 1px solid #ffc107; background: linear-gradient(135deg, #fffdf5 0%, #ffffff 100%);">
                    <div class="info-card-header">
                        <i class="fas fa-memory" style="color: #ffc107;"></i>
                        <h6>Memory Usage</h6>
                    </div>
                    <div class="info-card-value" style="font-size: 1rem;"><?= round(memory_get_usage(true) / 1024 / 1024, 2) ?> MB</div>
                    <div class="info-card-status warning">Monitoring</div>
                </div>
            </div>
            
            <div class="col-xl-3 col-lg-6">
                <div class="info-card" style="border: 1px solid #17a2b8; background: linear-gradient(135deg, #f5feff 0%, #ffffff 100%);">
                    <div class="info-card-header">
                        <i class="fas fa-clock" style="color: #17a2b8;"></i>
                        <h6>System Time</h6>
                    </div>
                    <div class="info-card-value" style="font-size: 1rem;"><?= date('H:i:s') ?></div>
                    <div class="info-card-status info"><?= date_default_timezone_get() ?></div>
                </div>
            </div>
        </div>
        
        <!-- Detailed System Info -->
        <div class="detailed-info mt-3">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="info-group" style="border: 1px solid #e9ecef; border-radius: 8px; padding: 15px; background: #f8f9fa;">
                        <h6 class="mb-3"><i class="fas fa-server me-2" style="color: #663399;"></i>Server Information</h6>
                        <div class="info-list">
                            <div class="info-item">
                                <span class="label">Server Software</span>
                                <span class="value text-muted"><?= $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown' ?></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Document Root</span>
                                <span class="value text-muted"><?= basename($_SERVER['DOCUMENT_ROOT'] ?? 'N/A') ?></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Server Admin</span>
                                <span class="value text-muted"><?= $_SERVER['SERVER_ADMIN'] ?? 'Not Set' ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="info-group" style="border: 1px solid #e9ecef; border-radius: 8px; padding: 15px; background: #f8f9fa;">
                        <h6 class="mb-3"><i class="fas fa-cogs me-2" style="color: #663399;"></i>PHP Configuration</h6>
                        <div class="info-list">
                            <div class="info-item">
                                <span class="label">Max Upload Size</span>
                                <span class="value text-muted"><?= ini_get('upload_max_filesize') ?></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Memory Limit</span>
                                <span class="value text-muted"><?= ini_get('memory_limit') ?></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Max Execution Time</span>
                                <span class="value text-muted"><?= ini_get('max_execution_time') ?>s</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<style>
/* Modern Settings Page Styling */
.settings-header {
    margin-bottom: 2rem;
}

.settings-title {
    color: #1a202c;
    font-weight: 700;
    font-size: 2rem;
}

.settings-subtitle {
    color: #6b7280;
    font-size: 1.1rem;
    margin: 0;
}

.header-actions {
    display: flex;
    gap: 0.75rem;
}

.modern-alert {
    border: none;
    border-radius: 12px;
    padding: 1rem 1.25rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

/* Modern Tabs */
.modern-tabs {
    border: none;
    background: white;
    border-radius: 12px 12px 0 0;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    padding: 0.5rem 0.5rem 0;
}

.modern-tabs .nav-link {
    border: none;
    background: none;
    color: #6b7280;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.2s ease;
    margin-right: 0.5rem;
}

.modern-tabs .nav-link:hover {
    background: #f3f4f6;
    color: #374151;
}

.modern-tabs .nav-link.active {
    background: #663399;
    color: white;
}

.modern-tab-content {
    background: white;
    border-radius: 0 0 12px 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    padding: 2rem;
    min-height: 500px;
}

/* Ensure tab panes are visible when active */
.tab-pane {
    display: none;
}

.tab-pane.active {
    display: block !important;
}

.tab-pane.show.active {
    display: block !important;
}

/* Temporary: Show all tabs for debugging */
.debug-show-all .tab-pane {
    display: block !important;
    border: 2px solid red;
    margin-bottom: 20px;
}

/* Settings Sections */
.settings-section {
    margin-bottom: 2rem;
}

.section-header {
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #f3f4f6;
}

.section-header h4 {
    color: #1a202c;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.section-header p {
    color: #6b7280;
    margin: 0;
    font-size: 0.95rem;
}

/* Modern Form Elements */
.form-group-modern {
    margin-bottom: 1rem;
}

.form-label-modern {
    display: block;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.form-control-modern {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 0.875rem;
    transition: all 0.2s ease;
    background: #fff;
}

.form-control-modern:focus {
    outline: none;
    border-color: #663399;
    box-shadow: 0 0 0 3px rgba(102, 51, 153, 0.1);
}

.form-help {
    font-size: 0.8rem;
    color: #6b7280;
    margin-top: 0.25rem;
}

/* Modern Checkboxes */
.form-check-modern {
    padding: 1rem;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    transition: all 0.2s ease;
    margin-bottom: 1rem;
}

.form-check-modern:hover {
    border-color: #663399;
    background: #fafbfc;
}

.form-check-modern .form-check-input {
    margin-top: 0;
    margin-right: 0.75rem;
    width: 1.2rem;
    height: 1.2rem;
}

.form-check-modern .form-check-input:checked {
    background-color: #663399;
    border-color: #663399;
}

.form-check-modern .form-check-label strong {
    color: #1a202c;
    font-weight: 600;
    display: block;
}

.form-check-modern .form-check-label span {
    color: #6b7280;
    font-size: 0.875rem;
}

/* Settings Actions */
.settings-actions {
    margin-top: 1.5rem;
    padding-top: 1rem;
    border-top: 2px solid #663399;
    background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
    border-radius: 8px;
    padding: 15px;
}

/* System Information Cards */
.system-info-section {
    margin-top: 1.5rem;
}

.info-card {
    background: white;
    border-radius: 8px;
    padding: 1rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    border: 1px solid #e5e7eb;
    transition: transform 0.2s ease;
}

.info-card:hover {
    transform: translateY(-2px);
}

.info-card-header {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.75rem;
}

.info-card-header i {
    font-size: 1.5rem;
    color: #663399;
}

.info-card-header h5 {
    margin: 0;
    font-weight: 600;
    color: #374151;
}

.info-card-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1a202c;
    margin-bottom: 0.5rem;
}

.info-card-status {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    display: inline-block;
}

.info-card-status.success {
    background: #10b981;
    color: white;
}

.info-card-status.warning {
    background: #f59e0b;
    color: white;
}

.info-card-status.info {
    background: #3b82f6;
    color: white;
}

/* Detailed Info */
.detailed-info {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.info-group h5 {
    color: #1a202c;
    font-weight: 600;
    margin-bottom: 1rem;
}

.info-list {
    space-y: 0.75rem;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f3f4f6;
}

.info-item:last-child {
    border-bottom: none;
}

.info-item .label {
    font-weight: 500;
    color: #6b7280;
}

.info-item .value {
    color: #1a202c;
    font-weight: 500;
    font-family: 'Courier New', monospace;
    font-size: 0.875rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .settings-header .d-flex {
        flex-direction: column;
        gap: 1rem;
    }
    
    .header-actions {
        justify-content: stretch;
    }
    
    .modern-tabs .nav-link {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
    }
    
    .modern-tab-content {
        padding: 1.5rem;
    }
}
</style>

<script>
// Settings Page JavaScript
function resetToDefaults() {
    if (confirm('Are you sure you want to reset all settings to their default values? This action cannot be undone.')) {
        // Add reset functionality
        alert('Reset functionality to be implemented');
    }
}

function exportSettings() {
    // Add export functionality
    alert('Export functionality to be implemented');
}

function debugTabs() {
    const tabContent = document.querySelector('.modern-tab-content');
    if (tabContent.classList.contains('debug-show-all')) {
        tabContent.classList.remove('debug-show-all');
        alert('Debug mode OFF - Tabs working normally');
    } else {
        tabContent.classList.add('debug-show-all');
        alert('Debug mode ON - All tabs visible. Check if content appears.');
    }
}

// Auto-save functionality
let autoSaveTimeout;
document.querySelectorAll('.form-control-modern, .form-check-input').forEach(input => {
    input.addEventListener('change', function() {
        clearTimeout(autoSaveTimeout);
        autoSaveTimeout = setTimeout(() => {
            // Show auto-save indicator
            const indicator = document.createElement('span');
            indicator.className = 'auto-save-indicator';
            indicator.textContent = 'Auto-saved';
            indicator.style.cssText = 'position: fixed; top: 20px; right: 20px; background: #10b981; color: white; padding: 0.5rem 1rem; border-radius: 6px; font-size: 0.875rem; z-index: 1000;';
            document.body.appendChild(indicator);
            setTimeout(() => indicator.remove(), 2000);
        }, 1000);
    });
});

// Tab persistence and debugging
document.addEventListener('DOMContentLoaded', function() {
    console.log('Settings page loaded');
    
    // Debug: Check if tab elements exist
    const tabButtons = document.querySelectorAll('[data-bs-toggle="tab"]');
    const tabPanes = document.querySelectorAll('.tab-pane');
    console.log('Found tab buttons:', tabButtons.length);
    console.log('Found tab panes:', tabPanes.length);
    
    // Debug: Check tab pane content
    tabPanes.forEach((pane, index) => {
        console.log(`Tab pane ${index} (${pane.id}):`, pane.innerHTML.length > 0 ? 'Has content' : 'Empty');
    });
    
    // Make sure Bootstrap tabs are initialized
    tabButtons.forEach((button, index) => {
        console.log(`Tab ${index}:`, button.getAttribute('data-bs-target'));
        
        button.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Tab clicked:', this.getAttribute('data-bs-target'));
            
            // Manual tab switching if Bootstrap fails
            const targetId = this.getAttribute('data-bs-target');
            const targetPane = document.querySelector(targetId);
            
            // Hide all panes
            tabPanes.forEach(pane => {
                pane.classList.remove('show', 'active');
            });
            
            // Remove active from all buttons
            tabButtons.forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Show target pane
            if (targetPane) {
                targetPane.classList.add('show', 'active');
                this.classList.add('active');
                console.log('Manually activated tab:', targetId);
            }
            
            // Store active tab
            localStorage.setItem('activeSettingsTab', targetId);
        });
    });
    
    // Restore active tab
    const activeTab = localStorage.getItem('activeSettingsTab');
    if (activeTab && activeTab !== '#general-settings') {
        const tabButton = document.querySelector(`[data-bs-target="${activeTab}"]`);
        if (tabButton) {
            setTimeout(() => tabButton.click(), 100);
        }
    }
});
</script>

<?php include '../app/views/shared/layout-footer.php'; ?>