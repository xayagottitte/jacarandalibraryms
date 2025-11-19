<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Jacaranda Libraries</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --jacaranda-primary: #663399;
            --jacaranda-secondary: #8a4baf;
            --jacaranda-light: #b366cc;
            --jacaranda-dark: #4a2570;
            --jacaranda-bg: #f8f6fc;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--jacaranda-primary) 0%, var(--jacaranda-secondary) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
            position: relative;
        }
        
        .auth-container {
            width: 100%;
            max-width: 350px;
            position: relative;
            z-index: 2;
        }
        
        .auth-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            padding: 1.75rem;
            text-align: center;
        }
        
        .brand-logo {
            width: 45px;
            height: 45px;
            background: var(--jacaranda-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: white;
            font-size: 1.25rem;
        }
        
        .welcome-text h2 {
            color: #333;
            font-weight: 700;
            font-size: 1.3rem;
            margin-bottom: 0.25rem;
        }
        
        .welcome-text p {
            color: #666;
            font-size: 0.85rem;
            margin-bottom: 1.5rem;
        }
        
        .auth-header i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.9;
        }
        
        .auth-body {
            padding: 2rem;
        }
        
        .form-group {
            margin-bottom: 1rem;
            text-align: left;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.4rem;
            color: #333;
            font-weight: 500;
            font-size: 0.85rem;
        }
        
        .form-control {
            width: 100%;
            padding: 0.65rem 0.85rem;
            border: 1px solid #e1e5e9;
            border-radius: 6px;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--jacaranda-primary);
            box-shadow: 0 0 0 3px rgba(102, 51, 153, 0.1);
            background: white;
        }
        
        .form-control::placeholder {
            color: #adb5bd;
        }
        
        .form-check {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.25rem;
        }
        
        .form-check-input {
            margin-right: 0.4rem;
        }
        
        .form-check-label {
            font-size: 0.8rem;
            color: #666;
        }
        
        .forgot-link {
            color: var(--jacaranda-primary);
            text-decoration: none;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .forgot-link:hover {
            text-decoration: underline;
        }
        
        .btn-signin {
            width: 100%;
            background: var(--jacaranda-primary);
            color: white;
            border: none;
            padding: 0.65rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 0.85rem;
            transition: all 0.3s ease;
        }
        
        .btn-signin:hover {
            background: var(--jacaranda-dark);
            transform: translateY(-1px);
        }
        
        .signup-link {
            color: var(--jacaranda-primary);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.85rem;
        }
        
        .signup-link:hover {
            text-decoration: underline;
        }
        
        .alert {
            border-radius: 12px;
            border: none;
            font-weight: 500;
        }
        
        .alert-danger {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }
        
        .alert-success {
            background: rgba(25, 135, 84, 0.1);
            color: #198754;
        }
        
        .alert-warning {
            background: rgba(255, 193, 7, 0.1);
            color: #856404;
            border: 1px solid rgba(255, 193, 7, 0.3);
        }
        
        .alert-warning strong {
            font-weight: 600;
        }
        
        .floating-shapes {
            position: fixed;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
        }
        
        .shape {
            position: absolute;
            opacity: 0.1;
            animation: float 6s ease-in-out infinite;
        }
        
        .shape:nth-child(1) {
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .shape:nth-child(2) {
            top: 60%;
            right: 10%;
            animation-delay: 2s;
        }
        
        .shape:nth-child(3) {
            bottom: 20%;
            left: 20%;
            animation-delay: 4s;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
        
        @media (max-width: 576px) {
            .auth-container {
                padding: 15px;
            }
            
            .auth-header, .auth-body {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="brand-logo">
                <i class="fas fa-book-open"></i>
            </div>
            
            <div class="welcome-text">
                <h2>Welcome Back!</h2>
                <p>We missed you! Please enter your details.</p>
            </div>

            <?php 
            // Check for timeout or security redirect
            if (isset($_GET['timeout']) && $_GET['timeout'] == '1'): ?>
                <div class="alert alert-warning mb-3">
                    <i class="fas fa-clock me-2"></i>
                    Your session has expired due to inactivity. Please log in again.
                </div>
            <?php elseif (isset($_GET['security']) && $_GET['security'] == '1'): ?>
                <div class="alert alert-danger mb-3">
                    <i class="fas fa-shield-alt me-2"></i>
                    Session security violation detected. Please log in again.
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger mb-3">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?= htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8'); unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success mb-3">
                    <i class="fas fa-check-circle me-2"></i>
                    <?= htmlspecialchars($_SESSION['success'], ENT_QUOTES, 'UTF-8'); unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>
            
            <?php 
            // Display lockout information if available
            if (isset($_SESSION['lockout_info'])): 
                $lockoutInfo = $_SESSION['lockout_info'];
            ?>
                <?php if ($lockoutInfo['locked']): ?>
                    <div class="alert alert-warning mb-3" id="lockout-alert">
                        <i class="fas fa-lock me-2"></i>
                        <strong>Account Locked</strong><br>
                        <small>Too many failed attempts (<?= $lockoutInfo['attempts'] ?>). Please wait <span id="countdown"><?= $lockoutInfo['remaining_time'] ?></span> seconds.</small>
                    </div>
                <?php else: ?>
                    <?php if ($lockoutInfo['attempts'] > 0): ?>
                        <div class="alert alert-warning mb-3" style="font-size: 0.85rem;">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Failed attempts: <?= $lockoutInfo['attempts'] ?> of <?= MAX_LOGIN_ATTEMPTS ?>. 
                            <?= $lockoutInfo['remaining_attempts'] ?> attempt(s) remaining.
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            <?php 
                unset($_SESSION['lockout_info']); 
            endif; 
            ?>

            <form method="POST" action="login">
                <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" 
                           placeholder="Enter Your Email" required>
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" 
                           placeholder="Enter Password" required>
                </div>
                
                <div class="form-check">
                    <div>
                        <input type="checkbox" class="form-check-input" id="remember">
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>
                    <a href="forgot-password" class="forgot-link">Forgot password?</a>
                </div>
                
                <button type="submit" class="btn-signin">
                    Sign In
                </button>
            </form>
            
            <div style="text-align: center; color: #666; font-size: 0.9rem; margin-top: 1rem;">
                Don't have an account? <a href="register" class="signup-link">Sign up</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert:not(#lockout-alert)');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
        
        // Lockout countdown timer
        const countdownElement = document.getElementById('countdown');
        if (countdownElement) {
            let remainingTime = parseInt(countdownElement.textContent);
            const loginButton = document.querySelector('.btn-signin');
            
            // Disable login button during lockout
            if (loginButton) {
                loginButton.disabled = true;
                loginButton.style.opacity = '0.5';
                loginButton.style.cursor = 'not-allowed';
            }
            
            const countdownInterval = setInterval(() => {
                remainingTime--;
                
                if (remainingTime <= 0) {
                    clearInterval(countdownInterval);
                    // Re-enable login and reload page
                    if (loginButton) {
                        loginButton.disabled = false;
                        loginButton.style.opacity = '1';
                        loginButton.style.cursor = 'pointer';
                    }
                    location.reload();
                } else {
                    countdownElement.textContent = remainingTime;
                }
            }, 1000);
        }
        
        // Add smooth entrance animation
        document.addEventListener('DOMContentLoaded', function() {
            const authCard = document.querySelector('.auth-card');
            authCard.style.opacity = '0';
            authCard.style.transform = 'translateY(30px)';
            
            setTimeout(() => {
                authCard.style.transition = 'all 0.6s ease';
                authCard.style.opacity = '1';
                authCard.style.transform = 'translateY(0)';
            }, 100);
        });
    </script>
</body>
</html>