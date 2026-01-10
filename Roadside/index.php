<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RSA Nepal</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/x-icon" href="assets/icons/favicon.ico">
</head>
<body>

<header class="header">
    <nav class="nav-container">
        <div class="logo">
            <img src="assets/icons/logo.jpg" alt="RSA Logo" class="header-logo-img">
            <div class="logo-text">RSA<span> Nepal</span></div>
        </div>
        
        <div class="nav-links">
            <a href="#" class="nav-link">Home</a>
            <a href="#services" class="nav-link">Our Services</a>
            <a href="#why-us" class="nav-link">Why Choose Us</a>
            <a href="register.php" class="nav-link btn-signup">Signup</a>
            <a href="login.php" class="nav-link btn-signin">Signin</a>
        </div>
    </nav>
</header>

<main>
    <section class="hero-section">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1>RoadSide Assistant<br><span>Vehicle Service System</span></h1>
            <p>24/7 Emergency vehicle support across Nepal. Get help when you need it most with our verified network of professional agents.</p>
            <div class="hero-btns">
                <button class="btn-primary" onclick="location.href='register.php'">Book Assistance</button>
                <button class="btn-secondary" onclick="location.href='#services'">Explore Services</button>
            </div>
        </div>
    </section>

    <section class="services" id="services">
        <div class="container">
            <div class="section-title">
                <h2>Our Services</h2>
                <div class="underline"></div>
                <p>Tailored roadside solutions for Nepal's unique driving conditions.</p>
            </div>

            <div class="services-grid">
                <div class="service-card">
                    <div class="service-icon">🚛</div>
                    <h3>Towing Service</h3>
                    <p>Professional vehicle recovery for cars and bikes available on all major highways.</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">🔋</div>
                    <h3>Battery Jumpstart</h3>
                    <p>Instant battery boost to get you moving, no matter the weather.</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">🛞</div>
                    <h3>Flat Tire Service</h3>
                    <p>On-site puncture repair and tire replacement with expert tools.</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">⛽</div>
                    <h3>Fuel Delivery</h3>
                    <p>Emergency fuel delivery to get you safely to the nearest pump.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="why-us" id="why-us">
        <div class="container">
            <div class="why-header">
                <span class="badge">Reliability Matters</span>
                <h2>Why RSA Nepal?</h2>
                <p>We combine technology with local expertise to provide the fastest response times in the country.</p>
            </div>

            <div class="why-grid">
                <div class="why-card">
                    <div class="why-number">01</div>
                    <div class="why-icon-bg">📍</div>
                    <h3>Fastest Response</h3>
                    <p>Our intelligent location system locates the nearest agent to reach you in record time.</p>
                </div>

                <div class="why-card">
                    <div class="why-number">02</div>
                    <div class="why-icon-bg">🛡️</div>
                    <h3>Verified Agents</h3>
                    <p>Every mechanic in our network is technically certified and background-verified for your safety.</p>
                </div>

                <div class="why-card">
                    <div class="why-number">03</div>
                    <div class="why-icon-bg">🛣️</div>
                    <h3>Highway Safety</h3>
                    <p>Dedicated recovery teams stationed near high-risk zones and major highway junctions.</p>
                </div>
            </div>
        </div>
    </section>
</main>

<footer class="footer">
    <div class="footer-container">
        <div class="footer-column footer-about">
            <h3>RSA <span>Nepal</span></h3>
            <p>Reliable 24/7 roadside assistance across Nepal. Connecting you with trusted local recovery experts.</p>
        </div>

        <div class="footer-column">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="#services">Services</a></li>
                <li><a href="register.php">Sign Up</a></li>
                <li><a href="login.php">Sign In</a></li>
            </ul>
        </div>

        <div class="footer-column">
            <h3>Contact Us</h3>
            <p>📍 Kathmandu, Nepal</p>
            <p>📞 +977-9800000000</p>
            <p>📧 support@rsanepal.com</p>
        </div>

        <div class="footer-column">
            <h3>Follow Us</h3>
            <div class="social-icons">
                <a href="#"><img src="assets/icons/facebook.png" alt="FB"></a>
                <a href="#"><img src="assets/icons/insta.png" alt="IG"></a>
                <a href="#"><img src="assets/icons/twitter.png" alt="TW"></a>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2026 RSA Nepal. All Rights Reserved.</p>
    </div>
</footer>

</body>
</html>