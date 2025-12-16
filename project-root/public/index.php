<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Freelance.pro | The Future of Work</title>
    <!-- Link to the separate CSS file -->
    <link rel="stylesheet" href="css/landing.css">
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <!-- Header / Navigation -->
    <header>
        <nav class="navbar">
            <a href="index.php" class="logo">Freelance<span style="color:var(--primary-neon)">.pro</span></a>
            <div class="nav-links">
                <a href="#services">Explore</a>
                <a href="#how-it-works">How It Works</a>
                <a href="#testimonials">Stories</a>
                <a href="login.php" class="btn-login">Log In</a>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="hero-bg-image">
            <img src="https://images.unsplash.com/photo-1519608487953-e999c86e7455?q=80&w=2070&auto=format&fit=crop" alt="Dark Tech Background">
            <div class="overlay"></div>
        </div>

        <div class="hero-content">
            <h1>The Future of <br> <span class="neon-text">Digital Work</span></h1>
            <p>A decentralized marketplace connecting elite talent with visionary companies. Secure, fast, and built for the modern era.</p>
            <div class="cta-group">
                <a href="#join" class="cta-button primary">Start Now</a>
                <a href="#services" class="cta-button secondary">Browse Talent</a>
            </div>
        </div>
    </section>

    <!-- Brand Strip -->
    <div class="brand-strip">
        <p>TRUSTED BY INNOVATORS AT</p>
        <div class="brand-logos">
            <span>NEXUS</span>
            <span>CYBERDYNE</span>
            <span>OMNICORP</span>
            <span>MASSIVE</span>
            <span>GLOBAL</span>
        </div>
    </div>

    <!-- How It Works Section -->
    <section id="how-it-works" class="info-section">
        <div class="section-header">
            <h2>How It <span style="color:var(--primary-neon)">Works</span></h2>
            <p>Three simple steps to launch your next big project.</p>
        </div>
        
        <div class="steps-grid">
            <div class="step-card">
                <div class="step-icon"><i class="fas fa-search"></i></div>
                <h3>1. Search</h3>
                <p>Browse through thousands of high-rated expert portfolios and gigs to find your perfect match.</p>
            </div>
            <div class="step-card">
                <div class="step-icon"><i class="fas fa-handshake"></i></div>
                <h3>2. Hire</h3>
                <p>Connect directly, discuss your vision, and hire the talent that aligns with your goals.</p>
            </div>
            <div class="step-card">
                <div class="step-icon"><i class="fas fa-rocket"></i></div>
                <h3>3. Launch</h3>
                <p>Collaborate in real-time and get your project delivered with our secure payment protection.</p>
            </div>
        </div>
    </section>

    <!-- Popular Services (Visual Grid) -->
    <section id="services" class="visual-section">
        <div class="section-header">
            <h2>Trending <span style="color:var(--primary-neon)">Services</span></h2>
            <p>Discover the skills driving tomorrow's economy.</p>
        </div>
        
        <div class="gallery-grid">
            <div class="gallery-item">
                <img src="https://images.unsplash.com/photo-1555066931-4365d14bab8c?q=80&w=1000&auto=format&fit=crop" alt="Coding">
                <div class="gallery-overlay">
                    <h3>Web Development</h3>
                    <p>React, PHP, Node.js</p>
                </div>
            </div>
            <div class="gallery-item">
                <img src="https://images.unsplash.com/photo-1626785774573-4b799312c95d?q=80&w=1000&auto=format&fit=crop" alt="Design">
                <div class="gallery-overlay">
                    <h3>Brand Identity</h3>
                    <p>Logos, UI/UX, Art</p>
                </div>
            </div>
            <div class="gallery-item">
                <img src="https://images.unsplash.com/photo-1550751827-4bd374c3f58b?q=80&w=1000&auto=format&fit=crop" alt="Security">
                <div class="gallery-overlay">
                    <h3>Cyber Security</h3>
                    <p>Audits & Protection</p>
                </div>
            </div>
            <div class="gallery-item">
                <img src="https://images.unsplash.com/photo-1620712943543-bcc4688e7485?q=80&w=1000&auto=format&fit=crop" alt="AI">
                <div class="gallery-overlay">
                    <h3>AI Engineering</h3>
                    <p>Models & Automation</p>
                </div>
            </div>
            <div class="gallery-item">
                <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?q=80&w=1000&auto=format&fit=crop" alt="Marketing">
                <div class="gallery-overlay">
                    <h3>Digital Growth</h3>
                    <p>SEO & Campaigns</p>
                </div>
            </div>
             <div class="gallery-item">
                <img src="https://images.unsplash.com/photo-1558655146-d09347e92766?q=80&w=1000&auto=format&fit=crop" alt="Data">
                <div class="gallery-overlay">
                    <h3>Data Science</h3>
                    <p>Analytics & Big Data</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="testimonials">
        <div class="section-header">
            <h2>Success <span style="color:var(--secondary-neon)">Stories</span></h2>
            <p>Hear from the community building the future.</p>
        </div>
        
        <div class="testimonial-grid">
            <div class="test-card">
                <p class="test-text">"Freelance.pro isn't just a platform; it's an ecosystem. I found a developer here in 2 hours who completely rebuilt our backend infrastructure."</p>
                <div class="test-author">// SARAH CONNOR, CTO @ SKYNET</div>
            </div>
            <div class="test-card">
                <p class="test-text">"The payment security is unmatched. As a seller, knowing my funds are locked in escrow before I start working gives me total peace of mind."</p>
                <div class="test-author">// DAVID LIGHTMAN, FREELANCE HACKER</div>
            </div>
        </div>
    </section>

    <!-- Role Selection / Join Section -->
    <section id="join" class="role-section">
        <h2>Join The <span style="color:var(--primary-neon)">Revolution</span></h2>
        <p>Choose your path and start your journey today</p>
        
        <div class="role-container">
            <!-- Customer Card -->
            <div class="role-card">
                <h3>Hire Talent</h3>
                <p>Post jobs, review proposals, and hire top-tier experts for your next big project.</p>
                <a href="client/client_Reg.php" class="role-btn primary">Register as Client</a>
            </div>

            <!-- Seller Card -->
            <div class="role-card">
                <h3>Find Work</h3>
                <p>Create your profile, showcase your portfolio, and monetize your unique skills globally.</p>
                <a href="seller/seller_reg.php" class="role-btn primary">Register as Seller</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-column">
                <h4>Freelance.pro</h4>
                <a href="#">About Us</a>
                <a href="#">Careers</a>
                <a href="#">Press</a>
            </div>
            <div class="footer-column">
                <h4>Talent</h4>
                <a href="#">Browse Gigs</a>
                <a href="#">Top Rated</a>
                <a href="#">Success Stories</a>
            </div>
            <div class="footer-column">
                <h4>Support</h4>
                <a href="#">Help Center</a>
                <a href="#">Safety</a>
                <a href="#">Terms of Service</a>
            </div>
            <div class="footer-column">
                <h4>Connect</h4>
                <a href="#">Twitter</a>
                <a href="#">LinkedIn</a>
                <a href="#">Instagram</a>
            </div>
        </div>
        <div style="text-align: center; margin-top: 5rem; opacity: 0.5;">
            &copy; 2024 Freelance.pro. All rights reserved.
        </div>
    </footer>

</body>
</html>