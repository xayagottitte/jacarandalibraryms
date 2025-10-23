<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Multi-Library System</title>
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
            max-width: 400px;
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
        
        .student-illustration {
            width: 120px;
            height: 120px;
            margin: 0 auto 1rem;
            position: relative;
        }
        
        .student-figure {
            width: 60px;
            height: 80px;
            background: #FFE4B5;
            border-radius: 20px 20px 5px 5px;
            position: relative;
            margin: 0 auto;
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
            background: #f8f9fa;
        }
        
        .btn-success {
            background: linear-gradient(135deg, #198754 0%, #20c997 100%);
            border: none;
            border-radius: 12px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(25, 135, 84, 0.3);
        }
        
        .auth-link {
            color: var(--jacaranda-primary);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .auth-link:hover {
            color: var(--jacaranda-secondary);
            text-decoration: none;
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
        
        .alert-info {
            background: rgba(13, 202, 240, 0.1);
            color: #0dcaf0;
            border-left: 4px solid #0dcaf0;
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
                <i class="fas fa-user-plus"></i>
            </div>
            
            <div class="welcome-text">
                <h2>Join Our Team!</h2>
                <p>Create your librarian account to get started.</p>
            </div>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger mb-3">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?= htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8'); unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="register">
                <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                
                <div class="form-group">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" 
                           placeholder="Choose username" required>
                </div>
                
                <div class="form-group">
                    <label for="full_name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="full_name" name="full_name" 
                           placeholder="Your full name" required>
                </div>
                
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" 
                           placeholder="your@email.com" required>
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" 
                           placeholder="Create password" required minlength="8">
                    <div style="font-size: 0.75rem; color: #666; margin-top: 0.25rem;">
                        <strong>Password requirements:</strong>
                        <ul style="margin: 0.25rem 0 0 1.25rem; padding: 0;">
                            <li>At least 8 characters long</li>
                            <li>At least one uppercase letter (A-Z)</li>
                            <li>At least one lowercase letter (a-z)</li>
                            <li>At least one number (0-9)</li>
                        </ul>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                           placeholder="Confirm password" required minlength="8">
                </div>
                
                <button type="submit" class="btn-register">
                    Register as Librarian
                </button>
            </form>
            
            <div style="padding: 0.75rem; background: rgba(13, 202, 240, 0.1); border-radius: 6px; margin-bottom: 1rem;">
                <small style="color: #0dcaf0; font-size: 0.8rem;">
                    <i class="fas fa-info-circle me-1"></i>
                    <strong>Note:</strong> Librarian accounts require Super Admin approval before access is granted.
                </small>
            </div>
            
            <div style="text-align: center; color: #666; font-size: 0.85rem;">
                Already have an account? <a href="login" class="signup-link">Login here</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert:not(.alert-info)');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
</body>
</html>