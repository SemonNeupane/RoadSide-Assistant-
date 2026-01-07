<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Road-Side Assistant</title>
    <link href="css/style.css" rel="stylesheet" />
</head>
<body>
    
    <header class="header">
        <nav class="nav">
            <div class="logo">RSA Nepal</div>
            <div class="nav-links">
                <a href="#" class="nav-link" onclick="showHome()">Home</a>
                <a href="register.php" class="nav-link" onclick="showSignup()"> Signup</a>
                

                <a href="login.php" class="nav-link" >Signin</a>
                <!-- <a href="#" class="nav-link" onclick="showAdminLogin()">Admin</a> -->
            </div>
        </nav>
    </header>
    <div class="img1">
    <img class="slider" src="assets/images/image.png" alt="Roadside Assistance">

    <div class="hero-content">
        <h1>Roadside Assistance<br>Management System</h1>
        <p>24/7 Emergency vehicle support across Nepal. Get help when you need it most with our verified network of professional agents.</p>
        <button class="cta-button" onclick="location.href='#quick-request'">Get Help Now</button>

        

    </div>
</div>

            <!-- </div>
</div> -->

    <!-- Home Page -->
    <div id="home-page">
        <!-- Hero Section -->
        <!-- <section class="hero"> -->
            <!-- <div class="vehicle-icons">
                <div class="vehicle-icon">🚛</div>
                <div class="vehicle-icon">🏍️</div>
                <div class="vehicle-icon">🔋</div>
                <div class="vehicle-icon">⛽</div>
                <div class="vehicle-icon">🔧</div>
                <div class="vehicle-icon">🛞</div>
            </div> -->
            <!-- <div class="hero-content">
                <h1>Roadside Assistance<br>Management System</h1>
                <p>24/7 Emergency vehicle support across Nepal. Get help when you need it most with our verified network of professional agents.</p>
                <button class="cta-button" onclick="scrollToServices()">Get Help Now</button>
            </div> -->
        <!-- </section> -->

        <!-- Services Section -->
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
         <!-- Quick Request Section -->
        <section id="quick-request" class="quick-request">
            <div class="container">
                <div class="section-title">
                    <h2 style="color: white;">Quick Service Request</h2>
                    <p class="section-subtitle" style="color: #ffc107;">Get help in just a few clicks</p>
                </div>
                
                <form class="request-form" id="quick-request-form">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Service Type</label>
                            <select id="service-type" class="form-control" required>
                                <option value="">Select Service</option>
                                <option value="towing">🚛 Towing Service</option>
                                <option value="jumpstart">🔋 Battery Jumpstart</option>
                                <option value="puncture">🛞 Flat Tire Repair</option>
                                <option value="fuel">⛽ Fuel Assistance</option>
                                <option value="ev">⚡ EV Support</option>
                                <option value="repair">🔧 Minor Repair</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Vehicle Type</label>
                            <select id="vehicle-type" class="form-control" required>
                                <option value="">Select Vehicle</option>
                                <option value="motorcycle">🏍️ Motorcycle</option>
                                <option value="car">🚗 Car</option>
                                <option value="bus">🚌 Bus</option>
                                <option value="truck">🚚 Truck</option>
                                <option value="ev">🔌 Electric Vehicle</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Your Location</label>
                            <input type="text" id="location" class="form-control" placeholder="Enter your current location" required>
                            <!-- <button type="button" class="location-btn" onclick="getCurrentLocation()">📍 Use GPS Location</button> -->
                        </div>
                        
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="tel" id="phone" class="form-control" placeholder="+977-98xxxxxxxx" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Additional Details</label>
                        <textarea id="details" class="form-control" rows="3" placeholder="Describe your problem in detail..."></textarea>
                    </div>
                    
                    <div class="text-center">
                        <button type="submit" class="cta-button">Request Service Now</button>
                    </div>
                </form>
            </div>
        </section>
    </div>
    <!-- Footer Section -->
<footer class="footer">
    <div class="footer-container">
        <!-- About Section -->
        <div class="footer-about">
            <h3>RSA Nepal</h3>
            <p>Reliable 24/7 roadside assistance across Nepal. We connect you with trusted local agents to ensure your safety and mobility anytime, anywhere.</p>
        </div>

        <!-- Quick Links -->
        <div class="footer-links">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="#" onclick="showHome()">Home</a></li>
                <li><a href="#" onclick="showSignup()">Signup</a></li>
                <li><a href="#" onclick="showSignin()">Signin</a></li>
                <li><a href="#" onclick="showAdminLogin()">Admin</a></li>
            </ul>
        </div>

        <!-- Contact Info -->
        <div class="footer-contact">
            <h3>Contact Us</h3>
            <p>📍 Kathmandu, Nepal</p>
            <p>📞 +977-9800000000</p>
            <p>📧 support@rsanepal.com</p>
        </div>

        <!-- Social Links -->
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