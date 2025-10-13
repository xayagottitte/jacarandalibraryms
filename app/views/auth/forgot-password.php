<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Multi-Library System</title>
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
            background: linear-gradient(135deg, #DC3545 0%, #E91E63 100%);
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
            border-color: #DC3545;
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
            background: white;
        }
        
        .form-control::placeholder {
            color: #adb5bd;
        }
        
        .btn-reset {
            width: 100%;
            background: linear-gradient(135deg, #DC3545 0%, #E91E63 100%);
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
            background: linear-gradient(135deg, #c82333 0%, #d91a72 100%);
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
                <i class="fas fa-key"></i>
            </div>
            
            <div class="welcome-text">
                <h2>Forgot Password?</h2>
                <p>Enter your email address and we'll send you a link to reset your password.</p>
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

            <form method="POST" action="forgot-password/send">
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" 
                           placeholder="your@email.com" required>
                </div>

                <button type="submit" class="btn-reset">
                    Send Reset Link
                </button>
            </form>
            
            <div style="text-align: center; color: #666; font-size: 0.85rem;">
                Remember your password? <a href="login" class="signup-link">Back to Login</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
</body>
</html>