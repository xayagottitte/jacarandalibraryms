<!-- Sidebar -->
<div class="container-fluid">
    <div class="row">
        <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse bg-dark">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], 'dashboard') ? 'active' : '' ?>" href="/librarian/dashboard">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], 'books') ? 'active' : '' ?>" href="/librarian/books">
                            <i class="fas fa-book"></i> Book Management
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], 'students') ? 'active' : '' ?>" href="/librarian/students">
                            <i class="fas fa-user-graduate"></i> Student Management
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], 'borrows') || str_contains($_SERVER['REQUEST_URI'], 'borrow') ? 'active' : '' ?>" href="/librarian/borrows">
                            <i class="fas fa-exchange-alt"></i> Borrow Management
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], 'reports') ? 'active' : '' ?>" href="/librarian/reports">
                            <i class="fas fa-chart-bar"></i> Reports
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], 'quick-borrow') ? 'active' : '' ?>" href="/librarian/quick-borrow">
                            <i class="fas fa-bolt"></i> Quick Borrow
                        </a>
                    </li>
                </ul>
            </div>
        </nav>