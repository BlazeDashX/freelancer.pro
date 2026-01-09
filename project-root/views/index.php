<?php
session_start();
// Check if user is logged in to change Nav buttons
$isLoggedIn = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Freelance.Pro | Hire the Top 1% Talent</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="../assets/css/landing.css">
</head>
<body>

    <nav class="navbar">
        <div class="container nav-container">
            <a href="index.php" class="logo">FREELANCE<span class="dot">.PRO</span></a>
            
            <div class="nav-links">
                <a href="#">Find Talent</a>
                <a href="#">Find Work</a>
                <a href="#">Why Us</a>
                <a href="#">Enterprise</a>
            </div>

            <div class="auth-buttons">
                <?php if ($isLoggedIn): ?>
                    <a href="dashboard.php" class="btn btn-primary">Dashboard</a>
                <?php else: ?>
                    <a href="views/login.php" class="btn btn-ghost">Log In</a>
                    <a href="views/client/client_Reg.php" class="btn btn-primary">Sign Up</a>
                <?php endif; ?>
            </div>
            
            <div class="menu-toggle">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </nav>

    <header class="hero">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>

        <div class="container hero-content">
            <div class="hero-text fade-in-up">
                <div class="badge">
                    <i class="fas fa-star"></i> Vetted Talent Only
                </div>
                <h1>Build Your <br><span class="gradient-text">Dream Team</span> Today.</h1>
                <p>Access the top 1% of developers, designers, and creative experts. Secure payments, zero risk, and seamless collaboration.</p>
                
                <div class="cta-group">
                    <a href="#" class="btn btn-lg btn-primary"><i class="fas fa-search"></i> Hire Talent</a>
                    <a href="#" class="btn btn-lg btn-outline">Apply as Freelancer</a>
                </div>

                <div class="stats-row">
                    <div class="stat">
                        <strong>12k+</strong>
                        <span>Expert Freelancers</span>
                    </div>
                    <div class="separator"></div>
                    <div class="stat">
                        <strong>$50M+</strong>
                        <span>Paid to Talent</span>
                    </div>
                    <div class="separator"></div>
                    <div class="stat">
                        <strong>4.9/5</strong>
                        <span>Client Rating</span>
                    </div>
                </div>
            </div>
            
            <div class="hero-visual fade-in-delayed">
                <div class="glass-card">
                    <div class="card-header">
                        <div class="circle"></div>
                        <div class="lines">
                            <div class="line lg"></div>
                            <div class="line sm"></div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="skill-tag">PHP Expert</div>
                        <div class="skill-tag">React JS</div>
                        <div class="skill-tag">UI/UX</div>
                    </div>
                    <div class="card-floating-badge">
                        <i class="fas fa-check-circle"></i> Verified
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section class="trust-banner">
        <div class="container">
            <p>Trusted by industry leaders</p>
            <div class="logos">
                <i class="fab fa-google"></i>
                <i class="fab fa-microsoft"></i>
                <i class="fab fa-amazon"></i>
                <i class="fab fa-spotify"></i>
                <i class="fab fa-slack"></i>
            </div>
        </div>
    </section>

    <section class="features">
        <div class="container">
            <div class="section-header">
                <h2>Why <span class="highlight">Freelance.Pro</span>?</h2>
                <p>We handle the messy parts so you can focus on building.</p>
            </div>

            <div class="feature-grid">
                <div class="feature-card">
                    <div class="icon-box"><i class="fas fa-shield-alt"></i></div>
                    <h3>Secure Escrow</h3>
                    <p>Funds are held safely until you approve the work. No scams, no risk.</p>
                </div>
                <div class="feature-card">
                    <div class="icon-box"><i class="fas fa-bolt"></i></div>
                    <h3>Fast Hiring</h3>
                    <p>Hire in under 48 hours. Our pre-vetted talent is ready to start immediately.</p>
                </div>
                <div class="feature-card">
                    <div class="icon-box"><i class="fas fa-headset"></i></div>
                    <h3>24/7 Support</h3>
                    <p>Our dedicated support team is here to resolve disputes and help you succeed.</p>
                </div>
            </div>
        </div>
    </section>

    <script src="assets/js/landing.js"></script>
</body>
</html>