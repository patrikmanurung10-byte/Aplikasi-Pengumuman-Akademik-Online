<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' . $app_name : $app_name; ?></title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="APAO Polibatam - Aplikasi Pengumuman Akademik Online Politeknik Negeri Batam. Akses informasi akademik dengan mudah dan cepat.">
    <meta name="keywords" content="APAO, Polibatam, Politeknik Negeri Batam, Pengumuman Akademik, Sistem Informasi">
    <meta name="author" content="Politeknik Negeri Batam">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?php echo isset($page_title) ? $page_title . ' - ' . $app_name : $app_name; ?>">
    <meta property="og:description" content="Aplikasi Pengumuman Akademik Online Politeknik Negeri Batam">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo \App\Core\View::url(); ?>">
    <meta property="og:image" content="<?php echo \App\Core\View::asset('images/logo_poltek.png'); ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo \App\Core\View::asset('images/logo_poltek.png'); ?>">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo \App\Core\View::asset('css/styles.css'); ?>">
    <link rel="stylesheet" href="<?php echo \App\Core\View::asset('css/landing.css'); ?>">
    
    <?php if (isset($additional_css)): ?>
        <?php foreach ($additional_css as $css): ?>
            <link rel="stylesheet" href="<?php echo $css; ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
        }
        
        <?php if (isset($inline_css)): ?>
            <?php echo $inline_css; ?>
        <?php endif; ?>
    </style>
</head>
<body<?php echo isset($body_class) ? ' class="' . $body_class . '"' : ''; ?>>

<!-- Navigation Bar (Optional) -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background: rgba(0,0,0,0.1); backdrop-filter: blur(10px); transition: all 0.3s ease;">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="<?php echo \App\Core\View::url('/'); ?>">
            <img src="<?php echo \App\Core\View::asset('images/logo_poltek.png'); ?>" alt="Logo" width="30" height="30" class="me-2">
            <span class="fw-bold">APAO Polibatam</span>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#features">Fitur</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#about">Tentang</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-outline-light btn-sm ms-2 px-3" href="<?php echo \App\Core\View::url('login'); ?>">
                        <i class="bi bi-box-arrow-in-right me-1"></i>
                        Login
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<?php 
// Show flash messages
$success = \App\Core\Session::getSuccess();
$error = \App\Core\Session::getError();
$warning = \App\Core\Session::getWarning();
$info = \App\Core\Session::getInfo();

if ($success): 
?>
<div class="alert alert-success alert-dismissible fade show position-fixed" style="top: 80px; right: 20px; z-index: 9999; max-width: 400px;">
    <?php echo htmlspecialchars($success); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if ($error): ?>
<div class="alert alert-danger alert-dismissible fade show position-fixed" style="top: 80px; right: 20px; z-index: 9999; max-width: 400px;">
    <?php echo htmlspecialchars($error); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if ($warning): ?>
<div class="alert alert-warning alert-dismissible fade show position-fixed" style="top: 80px; right: 20px; z-index: 9999; max-width: 400px;">
    <?php echo htmlspecialchars($warning); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if ($info): ?>
<div class="alert alert-info alert-dismissible fade show position-fixed" style="top: 80px; right: 20px; z-index: 9999; max-width: 400px;">
    <?php echo htmlspecialchars($info); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<!-- Main Content -->
<?php echo $content; ?>

<!-- Back to Top Button -->
<button class="btn btn-primary position-fixed" id="backToTop" style="bottom: 30px; right: 30px; z-index: 1000; border-radius: 50%; width: 50px; height: 50px; display: none;">
    <i class="bi bi-arrow-up"></i>
</button>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom JS -->
<script src="<?php echo \App\Core\View::asset('js/main.js'); ?>"></script>
<script src="<?php echo \App\Core\View::asset('js/gacor-effects.js'); ?>"></script>

<?php if (isset($additional_js)): ?>
    <?php foreach ($additional_js as $js): ?>
        <script src="<?php echo $js; ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>

<!-- AOS Animation Library -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

<!-- Particles.js -->
<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>

<!-- Landing Page JavaScript - GACOR VERSION -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS
    AOS.init({
        duration: 1000,
        once: true,
        offset: 100
    });
    
    // Initialize Particles
    particlesJS('particles-js', {
        particles: {
            number: { value: 80, density: { enable: true, value_area: 800 } },
            color: { value: "#ffffff" },
            shape: { type: "circle" },
            opacity: { value: 0.5, random: false },
            size: { value: 3, random: true },
            line_linked: {
                enable: true,
                distance: 150,
                color: "#ffffff",
                opacity: 0.4,
                width: 1
            },
            move: {
                enable: true,
                speed: 6,
                direction: "none",
                random: false,
                straight: false,
                out_mode: "out",
                bounce: false
            }
        },
        interactivity: {
            detect_on: "canvas",
            events: {
                onhover: { enable: true, mode: "repulse" },
                onclick: { enable: true, mode: "push" },
                resize: true
            },
            modes: {
                grab: { distance: 400, line_linked: { opacity: 1 } },
                bubble: { distance: 400, size: 40, duration: 2, opacity: 8, speed: 3 },
                repulse: { distance: 200, duration: 0.4 },
                push: { particles_nb: 4 },
                remove: { particles_nb: 2 }
            }
        },
        retina_detect: true
    });
    
    // Enhanced Counter Animation
    function animateCounter(element) {
        const target = parseInt(element.getAttribute('data-target'));
        const duration = 2000; // 2 seconds
        const increment = target / (duration / 16); // 60fps
        let current = 0;
        
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            
            // Add number formatting for better display
            const displayValue = Math.floor(current);
            if (displayValue >= 1000) {
                element.textContent = (displayValue / 1000).toFixed(1) + 'K';
            } else {
                element.textContent = displayValue;
            }
        }, 16);
    }
    
    // Trigger counter animation when in view
    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounter(entry.target);
                counterObserver.unobserve(entry.target);
            }
        });
    });
    
    document.querySelectorAll('.counter').forEach(counter => {
        counterObserver.observe(counter);
    });
    
    // Navbar scroll effect with enhanced styling
    const navbar = document.querySelector('.navbar');
    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            navbar.style.background = 'rgba(44, 62, 80, 0.95)';
            navbar.style.backdropFilter = 'blur(20px)';
            navbar.style.boxShadow = '0 4px 20px rgba(0,0,0,0.1)';
        } else {
            navbar.style.background = 'rgba(0,0,0,0.1)';
            navbar.style.backdropFilter = 'blur(10px)';
            navbar.style.boxShadow = 'none';
        }
    });
    
    // Enhanced back to top button
    const backToTop = document.getElementById('backToTop');
    window.addEventListener('scroll', function() {
        if (window.scrollY > 300) {
            backToTop.style.display = 'block';
            backToTop.style.animation = 'fadeInUp 0.5s ease';
        } else {
            backToTop.style.display = 'none';
        }
    });
    
    backToTop.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
    
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Enhanced alert system
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        // Add slide-in animation
        alert.style.animation = 'slideInRight 0.5s ease';
        
        // Auto-hide with fade out
        setTimeout(function() {
            if (alert.parentNode) {
                alert.style.animation = 'slideOutRight 0.5s ease';
                setTimeout(() => alert.remove(), 500);
            }
        }, 5000);
    });
    
    // Enhanced parallax effect for floating cards
    let ticking = false;
    
    function updateParallax() {
        const scrolled = window.pageYOffset;
        const parallaxElements = document.querySelectorAll('.floating-card');
        
        parallaxElements.forEach((element, index) => {
            const speed = 0.2 + (index * 0.05);
            const yPos = -(scrolled * speed);
            element.style.transform = `translateY(${yPos}px)`;
        });
        
        ticking = false;
    }
    
    window.addEventListener('scroll', function() {
        if (!ticking) {
            requestAnimationFrame(updateParallax);
            ticking = true;
        }
    });
    
    // Add hover effects to buttons
    document.querySelectorAll('.glow-button').forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.animation = 'buttonGlow 0.5s ease';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.animation = 'none';
        });
    });
    
    // Matrix rain effect for holographic display
    function createMatrixRain() {
        const hologram = document.querySelector('.holographic-display');
        if (!hologram) return;
        
        setInterval(() => {
            const rain = document.createElement('div');
            rain.textContent = Math.random() > 0.5 ? '1' : '0';
            rain.style.position = 'absolute';
            rain.style.color = '#00ff00';
            rain.style.fontSize = '10px';
            rain.style.left = Math.random() * 100 + '%';
            rain.style.top = '0';
            rain.style.animation = 'matrixFall 2s linear';
            rain.style.opacity = '0.7';
            
            hologram.appendChild(rain);
            
            setTimeout(() => {
                if (rain.parentNode) {
                    rain.remove();
                }
            }, 2000);
        }, 200);
    }
    
    createMatrixRain();
    
    // Add CSS animations dynamically
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes slideOutRight {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
        
        @keyframes fadeInUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        @keyframes buttonGlow {
            0% { box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4); }
            50% { box-shadow: 0 15px 35px rgba(102, 126, 234, 0.8); }
            100% { box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4); }
        }
        
        @keyframes matrixFall {
            from { transform: translateY(-20px); opacity: 1; }
            to { transform: translateY(150px); opacity: 0; }
        }
    `;
    document.head.appendChild(style);
});

// CSRF Token for AJAX requests
window.csrfToken = '<?php echo \App\Core\Session::getCsrfToken(); ?>';
window.baseUrl = '<?php echo \App\Core\View::url(); ?>';

// Enhanced interactive effects
window.addEventListener('load', function() {
    // Intersection Observer for animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
            }
        });
    }, observerOptions);
    
    // Observe elements for animation
    document.querySelectorAll('.feature-card, .stat-item, .about-feature-item').forEach(el => {
        observer.observe(el);
    });
    
    // Add subtle mouse tracking effect for cards
    document.addEventListener('mousemove', function(e) {
        const cards = document.querySelectorAll('.modern-card, .feature-card');
        cards.forEach(card => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            if (x >= 0 && x <= rect.width && y >= 0 && y <= rect.height) {
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;
                const rotateX = (y - centerY) / 20;
                const rotateY = (centerX - x) / 20;
                
                card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateZ(5px)`;
            } else {
                card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) translateZ(0)';
            }
        });
    });
    
    // Add loading animation completion
    document.body.classList.add('loaded');
    
    // Enhanced button interactions
    document.querySelectorAll('.btn').forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px) scale(1.02)';
        });
        
        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
    
    // Add dynamic styles
    const dynamicStyle = document.createElement('style');
    dynamicStyle.textContent = `
        .animate-in {
            animation: slideInUp 0.6s ease-out forwards;
        }
        
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .loaded .hero-content {
            animation: heroFadeIn 1.2s ease-out;
        }
        
        @keyframes heroFadeIn {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .btn {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .modern-card, .feature-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
    `;
    document.head.appendChild(dynamicStyle);
});
</script>

<?php if (isset($inline_js)): ?>
    <script>
        <?php echo $inline_js; ?>
    </script>
<?php endif; ?>

</body>
</html>