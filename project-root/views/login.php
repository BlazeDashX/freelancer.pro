<?php
session_start();
require_once '../model/clientRegistrationDB.php';

// --- FIX 1: INSTANTIATE DATABASE CONNECTION ---
$db = new mydb();
$conn = $db->createConObject();
// ----------------------------------------------

$error = null;
$loginSuccess = false;
$redirectUrl = '';

// Handle Logout
if (isset($_GET['logout'])) {
    session_destroy();
    // --- FIX 2: CORRECT REDIRECT PATH (Relative path) ---
    header("Location: login.php");
    exit;
}

// Check if already logged in (Direct redirect if session exists)
if (isset($_SESSION['user_role'])) {
    $role = $_SESSION['user_role'];
    $redirectUrl = match ($role) {
        'client' => '../client/client_dashboard.php', // Check these paths relative to views/
        'seller' => '../freelancer/freelancer_dashboard.php',
        'admin'  => '../admin_dashboard.php',
        default  => '../default_dashboard.php',
    };
    header("Location: " . $redirectUrl);
    exit;
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // --- FIX 3: SELECT FROM 'clients' TABLE (Not 'users') ---
    // Note: We removed 'role' from SELECT because your registration didn't insert it.
    $stmt = $conn->prepare("SELECT id, username, password FROM clients WHERE email = ?");

    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // 2. Verify Hashed Password
            if (password_verify($password, $user['password'])) {

                // Set Session Variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $email;

                // --- FIX 4: MANUALLY ASSIGN ROLE ---
                // Since this login is specifically reading from the 'clients' table
                $_SESSION['user_role'] = 'client';

                // Determine Redirect Path
                $redirectUrl = match ($_SESSION['user_role']) {
                    'client' => '../views/client/client_dashboard.php', // Adjusted path to go up one level
                    'seller' => '../freelancer/freelancer_dashboard.php',
                    'admin'  => '../admin_dashboard.php',
                    default  => '../default_dashboard.php',
                };

                // 3. Trigger JS Success Flag
                $loginSuccess = true;
            } else {
                $error = "Invalid email or password.";
            }
        } else {
            $error = "Invalid email or password.";
        }
        $stmt->close();
    } else {
        $error = "Database error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Freelance.Pro</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600&family=Space+Grotesk:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="../assets/css/auth.css">
    <style>
        /* Embedding critical CSS if the file link is broken */
        :root {
            --neon-primary: #3b82f6;
        }

        /* (Your existing CSS logic here via link) */
    </style>
</head>

<body>

    <div class="brand-section">
        <div class="logo-area">
            <div class="logo-text"><a href="index.php">FREELANCE<span class="logo-dot">.PRO</span></a></div>
        </div>

        <div class="hero-text">
            <h1>Find the <br><span class="highlight">Top 1% Talent</span></h1>
            <p>Join the decentralized marketplace trusted by the world's leading tech startups.</p>

            <div class="proof-chips">
                <div class="chip"><i class="fas fa-check-circle"></i> Verified Pros</div>
                <div class="chip"><i class="fas fa-shield-alt"></i> Secure Escrow</div>
            </div>
        </div>

        <div style="font-size: 0.8rem; color: #444; margin-top: 2rem;">© 2025 Freelance.Pro Inc.</div>
    </div>

    <div class="login-section">

        <div class="top-nav">
            <span>New here?</span>
            <a href="client/client_Reg.php">Create Account</a>
        </div>

        <div class="auth-card">
            <div class="auth-header">
                <h2>Welcome back</h2>
                <p>Enter your credentials to access your dashboard.</p>
            </div>

            <?php if ($error): ?>
                <div class="error-banner" style="background: #fee2e2; color: #991b1b; padding: 10px; border-radius: 6px; margin-bottom: 15px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span><?= htmlspecialchars($error) ?></span>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="input-group">
                    <label class="input-label">Email Address</label>
                    <div class="field-wrap">
                        <input type="email" name="email" placeholder="name@company.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                        <i class="fas fa-envelope icon"></i>
                    </div>
                </div>

                <div class="input-group">
                    <label class="input-label">Password</label>
                    <div class="field-wrap">
                        <input type="password" name="password" id="password" placeholder="••••••••" required>
                        <i class="fas fa-lock icon"></i>
                        <i class="fas fa-eye toggle-pass" onclick="togglePassword()"></i>
                    </div>
                </div>

                <div class="utility-links">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="checkbox" name="remember" id="remember" style="accent-color: var(--neon-primary);">
                        <span style="font-size: 0.85rem;">Remember me</span>
                    </label>
                    <a href="#" style="font-size: 0.85rem;">Forgot Password?</a>
                </div>

                <button type="submit" class="btn-submit">LOG IN</button>
            </form>

            <div class="divider">
                <span>Or continue with</span>
            </div>

            <div class="social-grid">
                <button class="social-btn">
                    <i class="fab fa-google"></i> Google
                </button>
                <button class="social-btn">
                    <i class="fab fa-github"></i> GitHub
                </button>
            </div>
        </div>
    </div>
</body>

</html>