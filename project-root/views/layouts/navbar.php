<?php
$base_url = '/freelancer.pro/project-root'; 
?>

<header>
    <nav class="navbar">
        <a href="<?php echo $base_url; ?>/public/index.php" class="logo">
            Freelance<span style="color:var(--primary-neon)">.pro</span>
        </a>

        <div class="nav-links">
            <a href="<?php echo $base_url; ?>/public/index.php#services">Explore</a>
            <a href="<?php echo $base_url; ?>/public/index.php#how-it-works">How It Works</a>
            <a href="<?php echo $base_url; ?>/public/index.php#testimonials">Stories</a>
            <a href="<?php echo $base_url; ?>/views/login.php" class="btn-login">Log In</a>
        </div>
    </nav>
</header>