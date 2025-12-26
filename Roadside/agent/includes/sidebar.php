<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include('../includes/dbconnection.php');

// Check if agent is logged in
if (empty($_SESSION['agent_id'])) {
    header('location:../login.php');
    exit();
}

$agent_id = $_SESSION['agent_id'];

// Fetch agent name from users table
$ret = mysqli_query($con, "SELECT username FROM users WHERE user_id='$agent_id' AND role='agent'");
$row = mysqli_fetch_assoc($ret);
$name = isset($row['username']) ? htmlspecialchars($row['username']) : 'Agent';
?>

<!-- Agent Sidebar -->
<div class="left side-menu">
    <div class="slimscroll-menu" id="remove-scroll">

        <!-- LOGO / TITLE -->
        <div class="topbar-left">
            <h3>RSA | AGENT</h3>
            <hr />
        </div>

        <!-- Agent Info -->
        <div class="user-box">
            <div class="user-img">
                <img src="../assets/images/user.png" alt="agent-img" class="rounded-circle img-fluid">
            </div>
            <h5><?php echo $name; ?></h5>
            <p class="text-muted">RSA Agent</p>
        </div>

        <!-- Sidebar Menu -->
        <div id="sidebar-menu">
            <ul id="side-menu">
                <li>
                    <a href="dashboard.php">
                        <span>Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="javascript:void(0);" class="has-submenu">
                        <span>My Bookings</span>
                        <span class="menu-arrow">&#9654;</span>
                    </a>
                    <ul class="nav-second-level">
                        <li><a href="booking-assigned.php">Booking Assigned</a></li>

                        <li><a href="active-service.php">Active Service</a></li>

                        <li><a href="service-history.php">Service History</a></li>
                    </ul>
                </li>
<!-- My Services -->
    <li>
        <a href="javascript:void(0);" class="has-submenu">
            <span>My Services</span>
            <span class="menu-arrow">&#9654;</span>
        </a>
        <ul class="nav-second-level">
            <li><a href="assigned-services.php">Assigned Services</a></li>
            <li><a href="service-locations.php">Service Locations</a></li>
        </ul>
    </li>

                 <li>
                    <a href="feedback.php">
                        <span>feedback</span>
                    </a>
                </li> -

                <!-- <li>
                    <a href="../logout.php">
                        <span>Logout</span>
                    </a>
                </li> -->
            </ul> 
        </div>

    </div>
</div>

<!-- Sidebar CSS -->
<style>
/* Left Sidebar */
.left.side-menu {
    width: 260px;
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    background: linear-gradient(180deg, #0f172a, #020617);
    box-shadow: 6px 0 30px rgba(0,0,0,0.35);
    z-index: 1000;
    overflow-y: auto;
}

/* LOGO / TITLE */
.topbar-left {
    text-align: center;
    padding: 22px 15px 10px;
}
.topbar-left h3 {
    color: #38bdf8;
    font-weight: 700;
    font-size: 22px;
    letter-spacing: 1px;
    margin-bottom: 10px;
}
.topbar-left hr {
    border-color: rgba(255,255,255,0.15);
}

/* USER BOX */
.user-box {
    text-align: center;
    padding: 15px 10px;
    margin: 5px 10px 15px;
    background: rgba(255,255,255,0.06);
    border-radius: 18px;
    box-shadow: inset 0 0 0 1px rgba(255,255,255,0.08);
}
.user-img {
    width: 60px;
    height: 60px;
    margin: 0 auto 10px;
    border-radius: 50%;
    padding: 3px;
    background: linear-gradient(135deg, #2563eb, #1e40af);
}
.user-img img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: #fff;
}
.user-box h5 {
    color: #ffffff;
    font-weight: 500;
    margin-bottom: 2px;
}
.user-box p {
    font-size: 13px;
    color: #94a3b8;
}

/* SIDEBAR MENU */
#side-menu {
    list-style: none;
    padding: 0;
    margin: 0;
}
#side-menu > li {
    margin-bottom: 6px;
}
#side-menu > li > a {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 13px 16px;
    border-radius: 14px;
    color: #cbd5e1;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.35s ease;
}
#side-menu > li > a:hover {
    background: rgba(255,255,255,0.12);
    color: #ffffff;
    transform: translateX(6px);
}
#side-menu li.active > a {
    background: linear-gradient(135deg, #2563eb, #1e40af);
    color: #ffffff;
    box-shadow: 0 10px 25px rgba(37,99,235,0.5);
}

/* Sub Menu */
.nav-second-level {
    display: none;
    list-style: none;
    padding-left: 20px;
    margin-top: 6px;
}
.nav-second-level li a {
    display: block;
    padding: 10px 14px;
    font-size: 13px;
    border-radius: 10px;
    color: #94a3b8;
    text-decoration: none;
    transition: all 0.3s ease;
}
.nav-second-level li a:hover {
    color: #ffffff;
    background: rgba(255,255,255,0.1);
}

/* Show submenu when parent link is clicked */
.has-submenu.open + .nav-second-level {
    display: block;
}

/* Rotate arrow when open */
.has-submenu.open .menu-arrow {
    transform: rotate(90deg);
}


/* Scrollbar */
.left.side-menu::-webkit-scrollbar {
    width: 6px;
}
.left.side-menu::-webkit-scrollbar-track {
    background: transparent;
}
.left.side-menu::-webkit-scrollbar-thumb {
    background: rgba(255,255,255,0.2);
    border-radius: 10px;
}

/* Responsive */
@media (max-width: 991px) {
    .left.side-menu {
        left: -260px;
    }
}
</style>
<script>
document.querySelectorAll('.has-submenu').forEach(function(menu) {
    menu.addEventListener('click', function(e) {
        e.preventDefault();

        // close others
        document.querySelectorAll('.has-submenu').forEach(item => {
            if (item !== this) item.classList.remove('open');
        });

        // toggle this one
        this.classList.toggle('open');
    });
});
</script>
