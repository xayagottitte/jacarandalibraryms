<style>
:root {
    --jacaranda-primary: #663399;
    --jacaranda-secondary: #8a4baf;
    --navbar-height: 60px;
}

.modern-navbar {
    background: linear-gradient(135deg, var(--jacaranda-primary) 0%, var(--jacaranda-secondary) 100%);
    backdrop-filter: blur(10px);
    box-shadow: 0 2px 15px rgba(0,0,0,0.1);
    border: none;
    padding: 0.5rem 1rem;
    height: var(--navbar-height);
}

.modern-navbar .navbar-brand {
    font-weight: 600;
    font-size: 1rem;
    color: white !important;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.4rem;
}

.modern-navbar .navbar-brand i {
    font-size: 1.1rem;
    background: rgba(255,255,255,0.2);
    padding: 0.3rem;
    border-radius: 50%;
}

.modern-navbar .nav-link {
    color: rgba(255,255,255,0.9) !important;
    font-weight: 500;
    padding: 0.3rem 0.8rem !important;
    border-radius: 15px;
    transition: all 0.3s ease;
    margin: 0 0.15rem;
    font-size: 0.9rem;
}

.modern-navbar .nav-link:hover {
    background: rgba(255,255,255,0.15);
    color: white !important;
    transform: translateY(-1px);
}

.modern-navbar .nav-link.active {
    background: rgba(255,255,255,0.2);
    color: white !important;
}

.modern-navbar .dropdown-menu {
    border: none;
    border-radius: 15px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.15);
    backdrop-filter: blur(10px);
    background: rgba(255,255,255,0.95);
    margin-top: 0.5rem;
}

.modern-navbar .dropdown-item {
    padding: 0.5rem 1rem;
    border-radius: 8px;
    margin: 0.15rem;
    transition: all 0.3s ease;
    font-size: 0.85rem;
}

.modern-navbar .dropdown-item:hover {
    background: var(--jacaranda-primary);
    color: white;
    transform: translateX(5px);
}

.modern-navbar .dropdown-item.text-danger:hover {
    background: #dc3545;
    color: white;
}

.navbar-toggler {
    border: none;
    background: rgba(255,255,255,0.2);
    border-radius: 10px;
    padding: 0.5rem;
}

.navbar-toggler:focus {
    box-shadow: none;
}

.navbar-toggler-icon {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 1%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='m4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
}

body {
    padding-top: var(--navbar-height);
}
</style>

<nav class="navbar navbar-expand-lg modern-navbar fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= BASE_PATH ?>/">
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
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" 
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle"></i> 
                            <?= htmlspecialchars($_SESSION['username']) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li>
                                <span class="dropdown-item-text">
                                    <small class="text-muted">
                                        <?= ucfirst(str_replace('_', ' ', $_SESSION['role'])) ?>
                                        <?php if ($_SESSION['role'] === 'librarian' && isset($_SESSION['library_id'])): ?>
                                            <br><?= htmlspecialchars($_SESSION['library_name'] ?? 'Library') ?>
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
                            <?php endif; ?>
                            <li>
                                <a class="dropdown-item" href="<?= BASE_PATH ?>/system/preferences">
                                    <i class="fas fa-user-cog"></i> Preferences
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
<div class="container-fluid" style="margin-top: 1rem;">
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 15px; border: none; box-shadow: 0 4px 15px rgba(40, 167, 69, 0.2);">
            <i class="fas fa-check-circle me-2"></i>
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 15px; border: none; box-shadow: 0 4px 15px rgba(220, 53, 69, 0.2);">
            <i class="fas fa-exclamation-circle me-2"></i>
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
</div>