<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RSA Nepal</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/x-icon" href="../favicon.ico">
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
            <a href="#services" class="nav-link">Our Services </a>
            <a href="register.php" class="nav-link">Signup</a>
            <a href="login.php" class="nav-link">Signin</a>
        </div>
    </nav>
</header>

<main>
    <section class="hero-section">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1>Roadside Assistant<br><span>Vehicle Service System</span></h1>
            <p>24/7 Emergency vehicle support across Nepal. Get help when you need it most with our verified network of professional agents.</p>
            <div class="hero-btns">
                <button class="btn-primary" onclick="location.href='register.php'">Book Assistance </button>
                <button class="btn-secondary" onclick="location.href='#services'">Explore Services</button>
            </div>
        </div>
    </section>

    <section class="services" id="services">
        <div class="container">
            <div class="section-title">
                <h2>Our Services</h2>
                <p>Professional roadside assistance tailored for Nepal's unique conditions</p>
            </div>

            <div class="services-grid">
                <div class="service-card">
                    <div class="service-icon">🚛</div>
                    <h3>Towing Service</h3>
                    <p>Professional vehicle towing for cars, motorcycles, and trucks available 24/7.</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">🔋</div>
                    <h3>Battery Jumpstart</h3>
                    <p>Quick battery jumpstart service to get your vehicle running again instantly.</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">🛞</div>
                    <h3>Flat Tire Service</h3>
                    <p>Puncture repair and tire replacement service at your location with expert tools.</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">⛽</div>
                    <h3>Fuel Assistance</h3>
                    <p>Emergency fuel delivery or towing to the nearest pump across all Nepal regions.</p>
                </div>
    
            </div>
        </div>
    </section>
</main>

<footer class="footer">
    <div class="footer-container">
        <div class="footer-column footer-about">
            <h3>RSA Nepal</h3>
            <p>Reliable 24/7 roadside assistance across Nepal. We connect you with trusted local agents.</p>
        </div>

        <div class="footer-column">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="register.php">Signup</a></li>
                <li><a href="login.php">Signin</a></li>
            </ul>
        </div>

        <div class="footer-column">
            <h3>Contact Us</h3>
            <p>📍 Kathmandu, Nepal</p>
            <p>📞 +977-9800000000</p>
        </div>

        <div class="footer-column">
            <h3>Follow Us</h3>
            <div class="social-icons">
                <a href="#"><img src="assets/icons/facebook.png" alt="Facebook"></a>
                <a href="#"><img src="assets/icons/insta.png" alt="Instagram"></a>
                <a href="#"><img src="assets/icons/twitter.png" alt="Twitter"></a>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2026 RSA Nepal. All Rights Reserved.</p>
    </div>
</footer>

</body>
</html>