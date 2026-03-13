<?php
require_once __DIR__ . '/admin_auth.php';

$error = '';
$remember_checked = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember_me']);
    
    if (!$email || !$password) {
        $error = 'Please enter email and password';
    } else if (admin_login($email, $password)) {
        // Handle remember me functionality
        if ($remember) {
            setcookie('admin_remember', base64_encode($email), time() + (30 * 24 * 60 * 60), '/'); // 30 days
        }
        header('Location: admin-dashboard.php');
        exit;
    } else {
        $error = 'Invalid credentials';
    }
}

// Check if remember me cookie exists
$remembered_email = '';
if (isset($_COOKIE['admin_remember'])) {
    $remembered_email = base64_decode($_COOKIE['admin_remember']);
    $remember_checked = 'checked';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | St. Anthony's Church Green Park</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="threads-fix.css">
    <link rel="stylesheet" href="responsive.css">
    <link rel="stylesheet" href="gallery.css">
    <link rel="stylesheet" href="mobile-enhancements.css">
    <style>
        .login-container { 
            max-width: 450px; 
            margin: 3rem auto; 
            padding: 2.5rem; 
            background-color: var(--card-bg); 
            border-radius: 12px; 
            box-shadow: 0 8px 25px rgba(0,0,0,0.15); 
            border: 1px solid var(--border-color);
            animation: fadeIn 0.5s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .login-header { 
            text-align: center; 
            margin-bottom: 2rem; 
        }
        
        .login-header i { 
            font-size: 3.5rem; 
            color: var(--primary-color); 
            margin-bottom: 1rem; 
            display: block;
        }
        
        .login-header h2 {
            color: var(--primary-color);
            margin: 0.5rem 0;
            font-size: 1.8rem;
        }
        
        .login-header p {
            color: var(--text-secondary, #666);
            margin: 0;
        }
        
        .form-group { 
            margin-bottom: 1.5rem; 
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--text-color);
        }
        
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: var(--bg-color);
            color: var(--text-color);
        }
        
        .form-group input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(139, 69, 19, 0.1);
        }
        
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 1.5rem 0;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .remember-me input[type="checkbox"] {
            width: auto;
            margin: 0;
        }
        
        .forgot-password a {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }
        
        .forgot-password a:hover {
            color: var(--secondary-color);
            text-decoration: underline;
        }
        
        .form-actions { 
            margin-top: 2rem; 
            display: flex; 
            flex-direction: column; 
            gap: 1rem; 
        }
        
        .btn-primary {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            color: #fff;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(139, 69, 19, 0.3);
        }
        
        .back-link {
            color: var(--primary-color);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .back-link:hover {
            color: var(--secondary-color);
        }
        
        .error { 
            background: #ffebee; 
            color: #c62828; 
            padding: .75rem 1rem; 
            border-radius: 8px; 
            margin-bottom: 1rem; 
            border-left: 4px solid #c62828;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .back-nav { 
            margin: 1rem; 
        }
        
        /* Dark mode adjustments */
        .dark-mode .form-group input {
            background-color: var(--card-bg);
            border-color: rgba(255, 255, 255, 0.1);
        }
        
        .dark-mode .error {
            background: rgba(255, 82, 82, 0.1);
            color: #ff5252;
            border-left-color: #ff5252;
        }
    </style>
    <script>
        // Remove any previous client-side flag to avoid confusion
        try { localStorage.removeItem('adminLoggedIn'); } catch (e) {}
    </script>
    <script defer src="script.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('serverAdminLoginForm');
            if (form) {
                form.addEventListener('submit', function() {
                    // Let PHP handle the submission
                });
            }
        });
    </script>
    <style>
        /* Dark mode button positioning for this page */
        .dark-mode-toggle{position:fixed;right:16px;bottom:16px;z-index:1000}
    </style>
    </head>
<body>
    <div class="stars" id="stars"></div>
    <button class="dark-mode-toggle" id="darkModeToggle" title="Toggle Dark Mode"><i class="fas fa-moon"></i></button>
    <div class="back-nav">
        <a href="index.html" class="back-link"><i class="fas fa-arrow-left"></i> Back to Home</a>
    </div>

    <div class="section">
        <div class="login-container">
            <div class="login-header">
                <i class="fas fa-user-shield"></i>
                <h2>Admin Login</h2>
                <p>Please enter your credentials to access the admin area</p>
            </div>
            <?php if ($error): ?>
                <div class="error"><i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form id="serverAdminLoginForm" method="POST" action="">
                <div class="form-group">
                    <label for="adminEmail"><i class="fas fa-envelope"></i> Admin Email</label>
                    <input type="email" id="adminEmail" name="email" placeholder="Enter your admin email" value="<?php echo htmlspecialchars($remembered_email); ?>" required>
                </div>
                <div class="form-group">
                    <label for="adminPassword"><i class="fas fa-lock"></i> Password</label>
                    <input type="password" id="adminPassword" name="password" placeholder="Enter your password" required>
                </div>
                
                <div class="form-options">
                    <div class="remember-me">
                        <input type="checkbox" id="rememberMe" name="remember_me" <?php echo $remember_checked; ?>>
                        <label for="rememberMe">Remember Me</label>
                    </div>
                    <div class="forgot-password">
                        <a href="forgot-password.html" id="forgotPasswordLink">
                            <i class="fas fa-key"></i> Forgot Password?
                        </a>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-sign-in-alt"></i> Login to Admin Panel
                    </button>
                </div>
            </form>
        </div>
        <a href="index.html" class="home-link"><i class="fas fa-home"></i> Return to Homepage</a>
    </div>
</body>
</html>
