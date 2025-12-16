<?php
session_start();

// 1. Retrieve Flash Data (Errors & Old Inputs)
$errors = $_SESSION['errors'] ?? [];
$old    = $_SESSION['old_inputs'] ?? [];
$is_success = $_SESSION['reg_success'] ?? false; // <--- ADD THIS LINE

// 2. Clear them immediately so they don't persist on page refresh
unset($_SESSION['errors']);
unset($_SESSION['old_inputs']);
unset($_SESSION['reg_success']); // <--- ADD THIS LINE
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join the Elite | Client Registration</title>
    
    
    <link rel="stylesheet" href="../css/auth.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* Add a specific style for PHP-generated errors */
        .php-error {
            color: #ff4444;
            font-size: 0.85rem;
            margin-top: 5px;
            display: block;
        }
    </style>
</head>
<body class="auth-body split-layout-body">

    <header class="auth-nav">
        <nav class="navbar">
            <a href="../index.php" class="logo">Freelance<span style="color:var(--primary-neon)">.pro</span></a>
            <div class="nav-links">
                <a href="../login.php" class="btn-login">Log In</a>
            </div>
        </nav>
    </header>

    <div class="split-container">
        <div class="split-visual">
            <div class="visual-content">
                <h1>Find the Top 1% <br> <span class="highlight">Freelance Talent.</span></h1>
                <p class="visual-desc">Join thousands of businesses building the future.</p>
            </div>
            <div class="visual-overlay"></div>
            <img src="https://images.unsplash.com/photo-1600880292203-757bb62b4baf?q=80&w=1000&auto=format&fit=crop" class="bg-img" alt="Workspace">
        </div>

        <div class="split-form">
            <div class="form-wrapper">
                <div class="form-header">
                    <h2>Create Client Account</h2>
                    <p>Enter your details below to get started.</p>
                </div>

                <form action="../../control/client/c_action.php" method="post" enctype="multipart/form-data" class="modern-form" id="regForm">
                    
                    <input type="hidden" name="register" value="true">

                    <h3 class="form-section-title">Personal Details</h3>
                    <div class="input-grid">
                        
                        <div class="form-group">
                            <label>Full Name</label>
                            <div class="input-wrapper large">
                                <i class="fas fa-user input-icon"></i>
                                <input type="text" id="full_name" name="full_name" 
                                       placeholder="John Doe" 
                                       value="<?= htmlspecialchars($old['full_name'] ?? '') ?>">
                            </div>
                            <?php if (isset($errors['full_name'])): ?>
                                <span class="php-error"><?= $errors['full_name'] ?></span>
                            <?php endif; ?>
                            <span id="full_name_err" class="error-msg"></span>
                        </div>

                        <div class="form-group">
                            <label>Username</label>
                            <div class="input-wrapper large">
                                <i class="fas fa-at input-icon"></i>
                                <input type="text" id="username" name="username" 
                                       placeholder="johndoe" 
                                       value="<?= htmlspecialchars($old['username'] ?? '') ?>">
                            </div>
                            <?php if (isset($errors['username'])): ?>
                                <span class="php-error"><?= $errors['username'] ?></span>
                            <?php endif; ?>
                            <span id="username_err" class="error-msg"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Email Address</label>
                        <div class="input-wrapper large">
                            <i class="fas fa-envelope input-icon"></i>
                            <input type="text" id="email" name="email" 
                                   placeholder="name@company.com" 
                                   value="<?= htmlspecialchars($old['email'] ?? '') ?>">
                        </div>
                        <?php if (isset($errors['email'])): ?>
                            <span class="php-error"><?= $errors['email'] ?></span>
                        <?php endif; ?>
                        <span id="email_err" class="error-msg"></span>
                    </div>

                    <div class="form-group">
                        <label>Phone Number</label>
                        <div class="input-wrapper large">
                            <i class="fas fa-phone input-icon"></i>
                            <input type="text" id="phone" name="phone" 
                                   placeholder="+1 (555) 000-0000" 
                                   value="<?= htmlspecialchars($old['phone'] ?? '') ?>">
                        </div>
                        <?php if (isset($errors['phone'])): ?>
                            <span class="php-error"><?= $errors['phone'] ?></span>
                        <?php endif; ?>
                        <span id="phone_err" class="error-msg"></span>
                    </div>

                    <h3 class="form-section-title">Security</h3>
                    <div class="input-grid">
                        <div class="form-group">
                            <label>Password</label>
                            <div class="input-wrapper large">
                                <i class="fas fa-lock input-icon"></i>
                                <input type="password" id="password" name="password" placeholder="••••••••">
                            </div>
                            <?php if (isset($errors['password'])): ?>
                                <span class="php-error"><?= $errors['password'] ?></span>
                            <?php endif; ?>
                            <span id="password_err" class="error-msg"></span>
                        </div>

                        <div class="form-group">
                            <label>Confirm Password</label>
                            <div class="input-wrapper large">
                                <i class="fas fa-check-circle input-icon"></i>
                                <input type="password" id="confirm_password" name="confirm_password" placeholder="••••••••">
                            </div>
                            <?php if (isset($errors['confirm_password'])): ?>
                                <span class="php-error"><?= $errors['confirm_password'] ?></span>
                            <?php endif; ?>
                            <span id="confirm_password_err" class="error-msg"></span>
                        </div>
                    </div>

                    <h3 class="form-section-title">Profile Picture</h3>
                    <div class="form-group">
                        <div class="input-wrapper large">
                            <i class="fas fa-camera input-icon"></i>
                            <input type="file" name="profile_picture" accept="image/*" style="padding: 12px 12px 12px 45px;">
                        </div>
                        <?php if (isset($errors['profile_picture'])): ?>
                            <span class="php-error"><?= $errors['profile_picture'] ?></span>
                        <?php endif; ?>
                    </div>

                    <h3 class="form-section-title">Payment</h3>
                    <div class="form-group">
                        <label>Preferred Payment Method</label>
                        <div class="payment-cards-large">
                            <?php $pay = $old['payment'] ?? ''; ?>
                            
                            <label class="pay-card">
                                <input type="radio" name="payment" value="paypal" <?= $pay == 'paypal' ? 'checked' : '' ?>>
                                <div class="card-content"><i class="fab fa-paypal"></i><span>PayPal</span></div>
                            </label>
                            <label class="pay-card">
                                <input type="radio" name="payment" value="bank_transfer" <?= $pay == 'bank_transfer' ? 'checked' : '' ?>>
                                <div class="card-content"><i class="fas fa-university"></i><span>Bank</span></div>
                            </label>
                            <label class="pay-card">
                                <input type="radio" name="payment" value="crypto" <?= $pay == 'crypto' ? 'checked' : '' ?>>
                                <div class="card-content"><i class="fab fa-bitcoin"></i><span>Crypto</span></div>
                            </label>
                        </div>
                    </div>
                    
                    <div class="form-group terms-box">
                        <label class="custom-checkbox">
                            <input type="checkbox" name="terms" id="terms" <?= isset($old['terms']) ? 'checked' : '' ?>>
                            <span class="checkmark"></span>
                            <span class="terms-text">I agree to the <a href="../../terms.php">Terms of Service</a> & <a href="#">Privacy Policy</a></span>
                        </label>
                        <?php if (isset($errors['terms'])): ?>
                            <span class="php-error" style="display:block; margin-top:5px;"><?= $errors['terms'] ?></span>
                        <?php endif; ?>
                        <span id="terms_err" class="error-msg"></span>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-register-large">Create Account</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/validation.js"></script> 

    <?php if ($is_success): ?>
    <script>
        Swal.fire({
            title: "Registration Successful!",
            text: "Welcome aboard! Redirecting to login...",
            icon: "success",
            timer: 3000,
            showConfirmButton: false
        }).then(() => {
            // Redirect user to login page after success
            window.location.href = "../login.php";
        });
    </script>
    <?php endif; ?>

    <?php if (isset($errors['general'])): ?>
    <script>
        Swal.fire({
            title: "Registration Failed", 
            text: "<?= htmlspecialchars($errors['general']) ?>",
            icon: "error",
            confirmButtonColor: "#d33",
            confirmButtonText: "Close"
        });
    </script>
    <?php endif; ?>

</body>
</html>