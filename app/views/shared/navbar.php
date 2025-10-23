<style>
:root {
    --jacaranda-primary: #6366f1;
    --jacaranda-secondary: #4f46e5;
    --jacaranda-light: #818cf8;
    --jacaranda-dark: #3730a3;
    --navbar-height: 70px;
}

.modern-navbar {
    background: linear-gradient(135deg, var(--jacaranda-primary) 0%, var(--jacaranda-secondary) 100%);
    backdrop-filter: blur(20px);
    box-shadow: 0 4px 30px rgba(99, 102, 241, 0.3);
    border: none;
    padding: 0.75rem 1.5rem;
    height: var(--navbar-height);
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1030;
}

.modern-navbar .navbar-brand {
    font-weight: 700;
    font-size: 1.25rem;
    color: white !important;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    transition: all 0.3s ease;
    letter-spacing: 0.5px;
}

.modern-navbar .navbar-brand:hover {
    transform: translateY(-2px);
}

.modern-navbar .navbar-brand i {
    font-size: 1.5rem;
    background: rgba(255,255,255,0.25);
    padding: 0.5rem;
    border-radius: 12px;
    transition: all 0.3s ease;
}

.modern-navbar .navbar-brand:hover i {
    background: rgba(255,255,255,0.35);
    transform: rotate(5deg);
}

.modern-navbar .nav-link {
    color: rgba(255,255,255,0.9) !important;
    font-weight: 600;
    padding: 0.5rem 1rem !important;
    border-radius: 12px;
    transition: all 0.3s ease;
    margin: 0 0.25rem;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.modern-navbar .nav-link i {
    font-size: 1rem;
    transition: all 0.3s ease;
}

.modern-navbar .nav-link:hover {
    background: rgba(255,255,255,0.2);
    color: white !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.modern-navbar .nav-link:hover i {
    transform: scale(1.1);
}

.modern-navbar .nav-link.active {
    background: rgba(255,255,255,0.3);
    color: white !important;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.modern-navbar .dropdown-toggle::after {
    margin-left: 0.5rem;
    vertical-align: 0.15em;
}

.modern-navbar .dropdown-menu {
    border: none;
    border-radius: 16px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    backdrop-filter: blur(10px);
    background: rgba(255,255,255,0.98);
    margin-top: 0.75rem;
    padding: 0.75rem;
    min-width: 240px;
}

.modern-navbar .dropdown-item-text {
    padding: 0.75rem 1rem;
    background: linear-gradient(135deg, rgba(99, 102, 241, 0.1) 0%, rgba(79, 70, 229, 0.1) 100%);
    border-radius: 10px;
    margin-bottom: 0.5rem;
}

.modern-navbar .dropdown-item-text small {
    display: block;
    font-size: 0.85rem;
    line-height: 1.4;
}

.modern-navbar .dropdown-item {
    padding: 0.65rem 1rem;
    border-radius: 10px;
    margin: 0.25rem 0;
    transition: all 0.3s ease;
    font-size: 0.9rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.modern-navbar .dropdown-item i {
    font-size: 1rem;
    width: 20px;
    text-align: center;
}

.modern-navbar .dropdown-item:hover {
    background: linear-gradient(135deg, var(--jacaranda-primary) 0%, var(--jacaranda-secondary) 100%);
    color: white;
    transform: translateX(5px);
}

.modern-navbar .dropdown-item.text-danger {
    color: #dc3545 !important;
}

.modern-navbar .dropdown-item.text-danger:hover {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: white !important;
}

.modern-navbar .dropdown-divider {
    border-top: 2px solid rgba(0,0,0,0.08);
    margin: 0.75rem 0;
}

.navbar-toggler {
    border: none;
    background: rgba(255,255,255,0.25);
    border-radius: 12px;
    padding: 0.6rem 0.75rem;
    transition: all 0.3s ease;
}

.navbar-toggler:hover {
    background: rgba(255,255,255,0.35);
    transform: translateY(-2px);
}

.navbar-toggler:focus {
    box-shadow: 0 0 0 0.2rem rgba(255,255,255,0.3);
}

.navbar-toggler-icon {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 1%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2.5' d='m4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
    width: 1.5rem;
    height: 1.5rem;
}

body {
    padding-top: var(--navbar-height);
}

/* Mobile Responsive */
@media (max-width: 991.98px) {
    .modern-navbar {
        padding: 0.5rem 1rem;
    }
    
    .modern-navbar .navbar-collapse {
        background: rgba(255,255,255,0.98);
        border-radius: 16px;
        padding: 1rem;
        margin-top: 1rem;
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
    }
    
    .modern-navbar .nav-link {
        color: var(--jacaranda-primary) !important;
        margin: 0.25rem 0;
    }
    
    .modern-navbar .nav-link:hover {
        background: rgba(99, 102, 241, 0.1);
        color: var(--jacaranda-secondary) !important;
    }
    
    .modern-navbar .nav-link.active {
        background: rgba(99, 102, 241, 0.15);
        color: var(--jacaranda-secondary) !important;
    }
    
    .modern-navbar .dropdown-menu {
        background: rgba(249, 250, 251, 0.98);
        margin-left: 1rem;
    }
}

/* User Badge */
.user-badge {
    background: rgba(255,255,255,0.2);
    padding: 0.4rem 1rem;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
}

.user-badge:hover {
    background: rgba(255,255,255,0.3);
}

.user-badge i {
    font-size: 1.25rem;
}

/* Notification Badge */
.notification-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #ef4444;
    color: white;
    border-radius: 50%;
    width: 18px;
    height: 18px;
    font-size: 0.7rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
}
</style>

<nav class="navbar navbar-expand-lg modern-navbar">
    <div class="container-fluid">
        <?php 
        // Determine dashboard link based on user role
        $dashboardLink = BASE_PATH . '/';
        if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
            if ($_SESSION['role'] === 'super_admin') {
                $dashboardLink = BASE_PATH . '/admin/dashboard';
            } elseif ($_SESSION['role'] === 'librarian') {
                $dashboardLink = BASE_PATH . '/librarian/dashboard';
            }
        }
        ?>
        <a class="navbar-brand" href="<?= $dashboardLink ?>">
            <i class="fas fa-book"></i> 
            <?= htmlspecialchars($system_name ?? 'Multi-Library System') ?>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- Authenticated User Menu -->
                    <?php if ($_SESSION['role'] === 'super_admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], '/admin/dashboard') ? 'active' : '' ?>" href="<?= BASE_PATH ?>/admin/dashboard">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], '/admin/users') ? 'active' : '' ?>" href="<?= BASE_PATH ?>/admin/users">
                                <i class="fas fa-users"></i> Users
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], '/admin/libraries') ? 'active' : '' ?>" href="<?= BASE_PATH ?>/admin/libraries">
                                <i class="fas fa-building"></i> Libraries
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], '/admin/books') ? 'active' : '' ?>" href="<?= BASE_PATH ?>/admin/books">
                                <i class="fas fa-book"></i> Books
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], '/admin/reports') ? 'active' : '' ?>" href="<?= BASE_PATH ?>/admin/reports">
                                <i class="fas fa-chart-line"></i> Reports
                            </a>
                        </li>
                    <?php elseif ($_SESSION['role'] === 'librarian'): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], '/librarian/dashboard') ? 'active' : '' ?>" href="<?= BASE_PATH ?>/librarian/dashboard">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], '/librarian/books') ? 'active' : '' ?>" href="<?= BASE_PATH ?>/librarian/books">
                                <i class="fas fa-book"></i> Books
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], '/librarian/students') ? 'active' : '' ?>" href="<?= BASE_PATH ?>/librarian/students">
                                <i class="fas fa-user-graduate"></i> Students
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], '/librarian/borrows') ? 'active' : '' ?>" href="<?= BASE_PATH ?>/librarian/borrows">
                                <i class="fas fa-exchange-alt"></i> Borrowing
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], '/librarian/reports') ? 'active' : '' ?>" href="<?= BASE_PATH ?>/librarian/reports">
                                <i class="fas fa-chart-bar"></i> Reports
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], '/librarian/quick-borrow') ? 'active' : '' ?>" href="<?= BASE_PATH ?>/librarian/quick-borrow">
                                <i class="fas fa-bolt"></i> Quick Borrow
                            </a>
                        </li>
                    <?php endif; ?>
                <?php else: ?>
                    <!-- Public Menu -->
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_PATH ?>/login">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_PATH ?>/register">
                            <i class="fas fa-user-plus"></i> Register
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
            
            <?php if (isset($_SESSION['user_id'])): ?>
                <!-- User Dropdown -->
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle user-badge" href="#" id="userDropdown" role="button" 
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle"></i> 
                            <span class="d-none d-lg-inline"><?= htmlspecialchars($_SESSION['username']) ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li>
                                <span class="dropdown-item-text">
                                    <strong style="color: var(--jacaranda-primary);"><?= htmlspecialchars($_SESSION['username']) ?></strong>
                                    <small class="text-muted">
                                        <i class="fas fa-shield-alt"></i> <?= ucfirst(str_replace('_', ' ', $_SESSION['role'])) ?>
                                        <?php if ($_SESSION['role'] === 'librarian' && isset($_SESSION['library_id'])): ?>
                                            <br><i class="fas fa-building"></i> <?= htmlspecialchars($_SESSION['library_name'] ?? 'Library') ?>
                                        <?php endif; ?>
                                    </small>
                                </span>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <?php if ($_SESSION['role'] === 'super_admin'): ?>
                                <li>
                                    <a class="dropdown-item" href="<?= BASE_PATH ?>/admin/settings">
                                        <i class="fas fa-cog"></i> System Settings
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?= BASE_PATH ?>/system/preferences">
                                        <i class="fas fa-user-cog"></i> Preferences
                                    </a>
                                </li>
                            <?php endif; ?>
                            <li>
                                <a class="dropdown-item" href="<?= BASE_PATH ?>/profile">
                                    <i class="fas fa-user-circle"></i> My Profile
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="<?= BASE_PATH ?>/logout">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</nav>



<!-- Flash Messages Display -->
<div class="container-fluid" style="margin-top: 1.5rem;">
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-dismissible fade show auto-dismiss-alert" role="alert" 
             style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(5, 150, 105, 0.1) 100%);
                    border-left: 4px solid #10b981;
                    border-radius: 16px; 
                    border: none; 
                    box-shadow: 0 4px 20px rgba(16, 185, 129, 0.2);
                    padding: 1.25rem 1.5rem;
                    font-weight: 500;">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-3" style="font-size: 1.5rem; color: #10b981;"></i>
                <span style="color: #047857;"><?= $_SESSION['success']; unset($_SESSION['success']); ?></span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-dismissible fade show auto-dismiss-alert" role="alert" 
             style="background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(220, 38, 38, 0.1) 100%);
                    border-left: 4px solid #ef4444;
                    border-radius: 16px; 
                    border: none; 
                    box-shadow: 0 4px 20px rgba(239, 68, 68, 0.2);
                    padding: 1.25rem 1.5rem;
                    font-weight: 500;">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-circle me-3" style="font-size: 1.5rem; color: #ef4444;"></i>
                <span style="color: #b91c1c;"><?= $_SESSION['error']; unset($_SESSION['error']); ?></span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
</div>

<script>
// Auto-dismiss flash messages after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.auto-dismiss-alert');
    
    alerts.forEach(function(alert) {
        // Add fade-out animation after 5 seconds
        setTimeout(function() {
            // Add smooth fade-out transition
            alert.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-20px)';
            
            // Remove element from DOM after animation completes
            setTimeout(function() {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 500);
        }, 5000); // 5 seconds delay
    });
});
</script>