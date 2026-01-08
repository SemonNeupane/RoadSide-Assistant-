<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Road-Side Assistant</title>

    <!-- FAVICON -->
    <link rel="icon" type="image/x-icon" href="../favicon.ico">

    <link href="css/style.css" rel="stylesheet" />
</head>
<body>
    
<header class="header">
    <nav class="nav">
        <div class="logo" style="color: rgb(10,146,78);">RSA Nepal</div>
        <div class="nav-links">
            <a href="#" class="nav-link" onclick="showHome()">Home</a>
            <a href="register.php" class="nav-link" onclick="showSignup()"> Signup</a>
            <a href="login.php" class="nav-link">Signin</a>
        </div>
    </nav>
</header>

<div class="img1">
    <img class="slider" src="assets/images/image.png" alt="Roadside Assistance">

    <div class="hero-content">
        <h1>Roadside Assistance<br>Management System</h1>
        <p>24/7 Emergency vehicle support across Nepal. Get help when you need it most with our verified network of professional agents.</p>

        <!-- FIXED BUTTON -->
        <button class="cta-button" onclick="location.href='register.php'">Get Help Now</button>
    </div>
</div>

<div id="home-page">

<section id="services" class="services">
    <div class="container">
        <div class="section-title">
            <h2>Our Services</h2>
            <p class="section-subtitle">Professional roadside assistance tailored for Nepal's unique conditions</p>
        </div>

        <div class="services-grid">
            <div class="service-card" onclick="requestService('towing')">
                <div class="service-icon">🚛</div>
                <h3>Towing Service</h3>
                <p>Professional vehicle towing for cars, motorcycles, buses and trucks. Available 24/7 with trained operators.</p>
            </div>

            <div class="service-card" onclick="requestService('jumpstart')">
                <div class="service-icon">🔋</div>
                <h3>Battery Jumpstart</h3>
                <p>Quick battery jumpstart service to get your vehicle running again. Includes battery health check and replacement if needed.</p>
            </div>

            <div class="service-card" onclick="requestService('puncture')">
                <div class="service-icon">🛞</div>
                <h3>Flat Tire Service</h3>
                <p>Puncture repair and tire replacement service. We carry spare tires and professional repair equipment.</p>
            </div>

            <div class="service-card" onclick="requestService('fuel')">
                <div class="service-icon">⛽</div>
                <h3>Fuel Assistance</h3>
                <p>Fuel station referral and emergency towing to nearest pump. Compliance with Nepal's fuel delivery regulations.</p>
            </div>

            <div class="service-card" onclick="requestService('ev')">
                <div class="service-icon">⚡</div>
                <h3>EV Support</h3>
                <p>Electric vehicle charging assistance and specialized EV towing. Supporting Nepal's growing EV infrastructure.</p>
            </div>

            <div class="service-card" onclick="requestService('repair')">
                <div class="service-icon">🔧</div>
                <h3>Minor Repairs</h3>
                <p>On-site minor mechanical repairs and emergency fixes to get you back on the road safely.</p>
            </div>
        </div>
    </div>
</section>

</div>

<footer class="footer">
    <div class="footer-container">

        <div class="footer-about">
            <h3>RSA Nepal</h3>
            <p>Reliable 24/7 roadside assistance across Nepal. We connect you with trusted local agents to ensure your safety and mobility anytime, anywhere.</p>
        </div>

        <div class="footer-links">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="#" onclick="showHome()">Home</a></li>
                <li><a href="#" onclick="showSignup()">Signup</a></li>
                <li><a href="#" onclick="showSignin()">Signin</a></li>
                <li><a href="#" onclick="showAdminLogin()">Admin</a></li>
            </ul>
        </div>

        <div class="footer-contact">
            <h3>Contact Us</h3>
            <p>📍 Kathmandu, Nepal</p>
            <p>📞 +977-9800000000</p>
            <p>📧 support@rsanepal.com</p>
        </div>

        <div class="footer-social">
            <h3>Follow Us</h3>
            <div class="social-icons">
                <a href="#"><img src="assets/icons/facebook.png" alt="Facebook"></a>
                <a href="#"><img src="assets/icons/insta.png" alt="Instagram"></a>
                <a href="#"><img src="assets/icons/twitter.png" alt="Twitter"></a>
            </div>
        </div>

    </div>

    <div class="footer-bottom">
        <p>&copy; 2025 RSA Nepal. All Rights Reserved.</p>
    </div>
</footer>

</body>
</html>
