<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Multi-Library System</title>
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
            background: linear-gradient(135deg, #198754 0%, #20c997 100%);
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
            border-color: #198754;
            box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.1);
            background: white;
        }
        
        .form-control::placeholder {
            color: #adb5bd;
        }
        
        .btn-reset {
            width: 100%;
            background: linear-gradient(135deg, #198754 0%, #20c997 100%);
            color: white;
            border: none;
            padding: 0.65rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 0.85rem;
            transition: all 0.3s ease;
        }
        
        .btn-reset:hover {
            background: linear-gradient(135deg, #146c43 0%, #198754 100%);
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
            border-radius: 8px;
            border: none;
            font-size: 0.85rem;
        }
        
        .password-strength {
            height: 3px;
            margin-top: 0.5rem;
            border-radius: 2px;
            transition: all 0.3s ease;
        }
        
        @media (max-width: 576px) {
            .auth-container {
                padding: 15px;
            }
            
            .auth-card {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="brand-logo">
                <i class="fas fa-lock"></i>
            </div>
            
            <div class="welcome-text">
                <h2>Reset Password</h2>
                <p>Enter your new password below.</p>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger mb-3">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success mb-3">
                    <i class="fas fa-check-circle me-2"></i>
                    <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="reset-password/reset" id="resetForm">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token ?? '') ?>">
                
                <div class="form-group">
                    <label for="password" class="form-label">New Password</label>
                    <input type="password" class="form-control" id="password" name="password" 
                           placeholder="Enter new password" required minlength="6">
                    <div class="password-strength" id="passwordStrength"></div>
                    <div style="font-size: 0.75rem; color: #666; margin-top: 0.25rem;">
                        Password must be at least 6 characters long.
                    </div>
                </div>

                
                <div class="form-group">
                    <label for="confirm_password" class="form-label">Confirm New Password</label>
                    <input type="password" class="form-control" id="confirm_password" 
                           name="confirm_password" placeholder="Confirm new password" required>
                    <div class="invalid-feedback" id="confirmFeedback" style="font-size: 0.75rem; color: #dc3545; margin-top: 0.25rem; display: none;">
                        Passwords do not match.
                    </div>
                </div>

                <button type="submit" class="btn-reset" id="submitBtn">
                    Reset Password
                </button>
            </form>
            
            <div style="text-align: center; color: #666; font-size: 0.85rem;">
                Remember your password? <a href="login" class="signup-link">Back to Login</a>
            </div>
        </div>
    </div>    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const confirmInput = document.getElementById('confirm_password');
            const strengthBar = document.getElementById('passwordStrength');
            const confirmFeedback = document.getElementById('confirmFeedback');
            const submitBtn = document.getElementById('submitBtn');
            const toggleButtons = document.querySelectorAll('.toggle-password');
            
            // Toggle password visibility
            toggleButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const input = this.parentElement.querySelector('input');
                    const icon = this.querySelector('i');
                    
                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        input.type = 'password';
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                });
            });
            
            // Check password strength
            passwordInput.addEventListener('input', function() {
                const password = this.value;
                let strength = 0;
                
                if (password.length >= 6) strength += 25;
                if (password.match(/[a-z]/)) strength += 25;
                if (password.match(/[A-Z]/)) strength += 25;
                if (password.match(/[0-9]/)) strength += 25;
                
                // Update strength bar
                strengthBar.style.width = strength + '%';
                if (strength < 50) {
                    strengthBar.style.backgroundColor = '#dc3545';
                } else if (strength < 75) {
                    strengthBar.style.backgroundColor = '#ffc107';
                } else {
                    strengthBar.style.backgroundColor = '#198754';
                }
            });
            
            // Check password confirmation
            confirmInput.addEventListener('input', function() {
                if (this.value !== passwordInput.value) {
                    this.classList.add('is-invalid');
                    confirmFeedback.style.display = 'block';
                } else {
                    this.classList.remove('is-invalid');
                    confirmFeedback.style.display = 'none';
                }
            });
            
            // Form validation
            document.getElementById('resetForm').addEventListener('submit', function(e) {
                if (passwordInput.value !== confirmInput.value) {
                    e.preventDefault();
                    confirmInput.classList.add('is-invalid');
                    confirmFeedback.style.display = 'block';
                    confirmInput.focus();
                }
                
                if (passwordInput.value.length < 6) {
                    e.preventDefault();
                    passwordInput.focus();
                }
            });
            
            // Auto-hide alerts after 5 seconds
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>
</body>
</html>