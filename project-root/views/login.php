<?php
// Include the controller
require_once '../controller/authController.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Freelance.Pro</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="../assets/css/login.css">
    
    <script src="../assets/js/login.js" defer></script>
</head>
<body>

    <div class="login-card">
        
        <div style="text-align: center;">
            <a href="../index.php" class="brand-title">FREELANCE.PRO</a>
            <h1>Welcome Back</h1>
            <p class="subtitle">Enter your credentials to access your dashboard.</p>
        </div>

        <?php if (isset($error_message) && !empty($error_message)): ?>
            <div class="error-banner">
                <strong>Error:</strong> <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" id="loginForm">
            
            <div style="margin-bottom: 1.2rem;">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" 
                       placeholder="name@company.com"
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                       required>
                <span id="emailError" style="color: var(--neon-danger); font-size: 0.8rem; display:block; margin-top:5px;"></span>
            </div>

            <div style="margin-bottom: 1.2rem;">
                <label for="password">Password</label>
                <div class="password-wrapper">
                    <input type="password" name="password" id="password" placeholder="••••••••" required>
                    <button type="button" id="togglePassword">Show</button>
                </div>
                <span id="passwordError" style="color: var(--neon-danger); font-size: 0.8rem; display:block; margin-top:5px;"></span>
            </div>

            <div class="flex-between">
                <label style="margin:0; cursor:pointer;">
                    <input type="checkbox" name="remember" id="remember" style="width:auto; margin-right:5px;"> 
                    Remember me
                </label>
                <a href="forgot_password.php">Forgot Password?</a>
            </div>

            <button type="submit" name="login_btn">LOG IN</button>

        </form>

        <div class="divider">
            <span>Or continue with</span>
        </div>

        <div class="flex-between">
            <button type="button" class="social-btn">Google</button>
            <button type="button" class="social-btn">GitHub</button>
        </div>

        <div style="text-align: center; margin-top: 2rem; color: var(--text-muted); font-size: 0.9rem;">
            New here? <a href="client/client_Reg.php" style="color: var(--neon-primary); font-weight:600;">Create Account</a>
        </div>

    </div>

</body>
</html>