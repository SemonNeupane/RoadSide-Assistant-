<div class="sidebar">
    <h2>RSA Nepal</h2>

    <a href="dashboard.php">Dashboard</a>

    <!-- My Bookings with submenu -->
    <div class="submenu">
        <a href="#" class="submenu-toggle">
            My Bookings
            <span class="arrow">&#9654;</span>
        </a>

        <div class="submenu-links">
            <a href="service-request.php">Request Service</a>
            <a href="service-history.php">Service History</a>
        </div>
    </div>

    <a href="feedback.php">Feedback</a>
    <a href="../logout.php">Logout</a>
</div>
<style>
    /* Sidebar Base */
.sidebar {
    width: 240px;
    height: 100vh;
    background: #1f2937;
    position: fixed;
    top: 0;
    left: 0;
    padding-top: 20px;
}

/* Title */
.sidebar h2 {
    text-align: center;
    margin-bottom: 30px;
    color: #38bdf8;
}

/* Main Links */
.sidebar a {
    display: block;
    padding: 14px 20px;
    color: #e5e7eb;
    text-decoration: none;
    font-size: 15px;
    transition: all 0.3s ease;
}

.sidebar a:hover {
    background: #111827;
    color: #38bdf8;
}

/* My Bookings Dropdown */
.submenu {
    width: 100%;
}

/* Submenu Toggle Link */
.submenu-toggle {
    cursor: pointer;
}

/* Arrow Icon */
.arrow {
    float: right;
    font-size: 12px;
    transition: transform 0.3s ease;
}

/* Submenu Links */
.submenu-links {
    display: none;
    background: #111827;
}

.submenu-links a {
    padding: 12px 40px;
    font-size: 14px;
    color: #d1d5db;
}

/* Hover on Submenu */
.submenu-links a:hover {
    background: #020617;
    color: #38bdf8;
}

/* Open State */
.submenu.open .submenu-links {
    display: block;
}

.submenu.open .arrow {
    transform: rotate(90deg);
}

    </style>
    <script>
document.querySelector('.submenu-toggle').addEventListener('click', function(e){
    e.preventDefault();
    this.parentElement.classList.toggle('open');
});
</script>
