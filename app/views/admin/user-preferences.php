<?php 
$title = "User Preferences - Jacaranda Libraries";
include '../app/views/shared/header.php'; 
include '../app/views/shared/layout-header.php'; 
?>

<style>
    :root {
        --jacaranda-primary: #663399;
        --jacaranda-secondary: #8a4baf;
    }
    .page-header-modern {
        background: white;
        border-radius: 15px;
        padding: 1.75rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        border-left: 5px solid var(--jacaranda-primary);
    }
    .page-header-modern h3 {
        background: linear-gradient(135deg, var(--jacaranda-primary) 0%, var(--jacaranda-secondary) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-weight: 600;
        margin: 0;
        font-size: 1.75rem;
    }
    .modern-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }
    .modern-card:hover {
        box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    }
    .form-label-modern {
        font-weight: 600;
        color: #4a5568;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    .form-control-modern, .form-select-modern {
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.65rem 1rem;
        transition: all 0.3s ease;
    }
    .form-control-modern:focus, .form-select-modern:focus {
        border-color: var(--jacaranda-primary);
        box-shadow: 0 0 0 3px rgba(102, 51, 153, 0.1);
    }
    .btn-gradient-primary {
        background: linear-gradient(135deg, var(--jacaranda-primary) 0%, var(--jacaranda-secondary) 100%);
        border: none;
        color: white;
        padding: 0.65rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(102, 51, 153, 0.3);
    }
    .btn-gradient-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 51, 153, 0.4);
        color: white;
    }
    .btn-outline-modern {
        border: 2px solid #e2e8f0;
        color: #4a5568;
        padding: 0.65rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .btn-outline-modern:hover {
        border-color: var(--jacaranda-primary);
        color: var(--jacaranda-primary);
    }
    .alert-modern {
        border: none;
        border-radius: 12px;
        padding: 1rem 1.25rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    .form-section-title {
        color: var(--jacaranda-primary);
        font-weight: 600;
        font-size: 1.1rem;
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
</style>

<div class="page-header-modern">
    <div class="d-flex justify-content-between align-items-center">
        <h3><i class="fas fa-cog me-2"></i>User Preferences</h3>
        <a href="<?= BASE_PATH ?>/admin/dashboard" class="btn btn-outline-modern">
            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
        </a>
    </div>
</div>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-modern alert-dismissible fade show">
        <i class="fas fa-exclamation-circle me-2"></i>
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-modern alert-dismissible fade show">
        <i class="fas fa-check-circle me-2"></i>
        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="modern-card">
    <div class="card-body p-4">
        <form method="POST" action="<?= BASE_PATH ?>/system/preferences">
            <div class="form-section-title">
                <i class="fas fa-palette"></i> General Preferences
            </div>
            
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label for="theme" class="form-label-modern">
                        <i class="fas fa-moon"></i> Theme
                    </label>
                    <select class="form-select form-select-modern" id="theme" name="theme">
                        <option value="light" <?= ($data['preferences']['theme'] ?? 'light') === 'light' ? 'selected' : '' ?>>Light</option>
                        <option value="dark" <?= ($data['preferences']['theme'] ?? 'light') === 'dark' ? 'selected' : '' ?>>Dark</option>
                        <option value="auto" <?= ($data['preferences']['theme'] ?? 'light') === 'auto' ? 'selected' : '' ?>>Auto</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="language" class="form-label-modern">
                        <i class="fas fa-language"></i> Language
                    </label>
                    <select class="form-select form-select-modern" id="language" name="language">
                        <option value="en" <?= ($data['preferences']['language'] ?? 'en') === 'en' ? 'selected' : '' ?>>English</option>
                        <option value="es" <?= ($data['preferences']['language'] ?? 'en') === 'es' ? 'selected' : '' ?>>Spanish</option>
                        <option value="fr" <?= ($data['preferences']['language'] ?? 'en') === 'fr' ? 'selected' : '' ?>>French</option>
                    </select>
                </div>
            </div>

            <div class="form-section-title">
                <i class="fas fa-clock"></i> Date & Time Settings
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label for="timezone" class="form-label-modern">
                        <i class="fas fa-globe"></i> Timezone
                    </label>
                    <select class="form-select form-select-modern" id="timezone" name="timezone">
                        <option value="America/New_York" <?= ($data['preferences']['timezone'] ?? 'America/New_York') === 'America/New_York' ? 'selected' : '' ?>>Eastern Time (EDT/EST)</option>
                        <option value="America/Chicago" <?= ($data['preferences']['timezone'] ?? 'America/New_York') === 'America/Chicago' ? 'selected' : '' ?>>Central Time (CDT/CST)</option>
                        <option value="America/Denver" <?= ($data['preferences']['timezone'] ?? 'America/New_York') === 'America/Denver' ? 'selected' : '' ?>>Mountain Time (MDT/MST)</option>
                        <option value="America/Los_Angeles" <?= ($data['preferences']['timezone'] ?? 'America/New_York') === 'America/Los_Angeles' ? 'selected' : '' ?>>Pacific Time (PDT/PST)</option>
                        <option value="Europe/London" <?= ($data['preferences']['timezone'] ?? 'America/New_York') === 'Europe/London' ? 'selected' : '' ?>>GMT (London)</option>
                        <option value="UTC" <?= ($data['preferences']['timezone'] ?? 'America/New_York') === 'UTC' ? 'selected' : '' ?>>UTC</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="date_format" class="form-label-modern">
                        <i class="fas fa-calendar-alt"></i> Date Format
                    </label>
                    <select class="form-select form-select-modern" id="date_format" name="date_format">
                        <option value="Y-m-d" <?= ($data['preferences']['date_format'] ?? 'Y-m-d') === 'Y-m-d' ? 'selected' : '' ?>>YYYY-MM-DD</option>
                        <option value="m/d/Y" <?= ($data['preferences']['date_format'] ?? 'Y-m-d') === 'm/d/Y' ? 'selected' : '' ?>>MM/DD/YYYY</option>
                        <option value="d/m/Y" <?= ($data['preferences']['date_format'] ?? 'Y-m-d') === 'd/m/Y' ? 'selected' : '' ?>>DD/MM/YYYY</option>
                        <option value="M j, Y" <?= ($data['preferences']['date_format'] ?? 'Y-m-d') === 'M j, Y' ? 'selected' : '' ?>>Month DD, YYYY</option>
                    </select>
                </div>
            </div>

            <div class="form-section-title">
                <i class="fas fa-bell"></i> Notifications
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="email_notifications" name="email_notifications" 
                               <?= ($data['preferences']['email_notifications'] ?? true) ? 'checked' : '' ?>>
                        <label class="form-check-label form-label-modern" for="email_notifications">
                            <i class="fas fa-envelope"></i> Email Notifications
                        </label>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="desktop_notifications" name="desktop_notifications" 
                               <?= ($data['preferences']['desktop_notifications'] ?? false) ? 'checked' : '' ?>>
                        <label class="form-check-label form-label-modern" for="desktop_notifications">
                            <i class="fas fa-desktop"></i> Desktop Notifications
                        </label>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-3 justify-content-end mt-4 pt-3 border-top">
                <a href="<?= BASE_PATH ?>/admin/dashboard" class="btn btn-outline-modern">
                    <i class="fas fa-times me-2"></i>Cancel
                </a>
                <button type="submit" class="btn btn-gradient-primary">
                    <i class="fas fa-save me-2"></i>Save Preferences
                </button>
            </div>
        </form>
    </div>
</div>

<?php include '../app/views/shared/layout-footer.php'; ?>
<?php include '../app/views/shared/footer.php'; ?>
