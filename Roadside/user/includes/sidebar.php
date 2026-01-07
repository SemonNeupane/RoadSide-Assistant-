<?php
// Start session safely
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('../includes/dbconnection.php');

// Check if USER is logged in
if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header('location:../login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user info
$ret = mysqli_query($con, "
    SELECT username 
    FROM users 
    WHERE user_id='$user_id' AND role='user'
");
$row = mysqli_fetch_assoc($ret);
$name = isset($row['username']) ? htmlspecialchars($row['username']) : 'User';
?>

<!-- USER SIDEBAR -->
<div class="left side-menu">
    <div class="slimscroll-menu">

        <!-- LOGO -->
        <div class="topbar-left">
            <h3>RSA | USER</h3>
            <hr />
        </div>

        <!-- USER INFO -->
        <div class="user-box">
            <div class="user-img">
                <img src="../assets/images/user.png" alt="user-img">
            </div>
            <h5><?php echo $name; ?></h5>
            <p class="text-muted">RSA User</p>
        </div>

        <!-- MENU -->
        <div id="sidebar-menu">
            <ul id="side-menu">

                <li>
                    <a href="dashboard.php">
                        <span>Dashboard</span>
                    </a>
                </li>

                <!-- BOOKINGS -->
                <li>
                    <a href="javascript:void(0);" class="has-submenu">
                        <span>My Bookings</span>
                        <span class="menu-arrow">&#9654;</span>
                    </a>
                    <ul class="nav-second-level">
                        
                        <li><a href="service-request.php">Request Service</a></li>
                        <li><a href="service-history.php">Service History</a></li>
                    </ul>
                </li>

                

                <!-- FEEDBACK -->
                <li>
                    <a href="feedback.php">
                        <span>Feedback</span>
                    </a>
                </li>
                <li>
    <a href="/RoadSide-Assistant-/Roadside/register.php">
        <span>Be an Agent</span>
    </a>
</li>



               

            </ul>
        </div>

    </div>
</div>
<style>



.left.side-menu {
    width: 260px;
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    background: linear-gradient(180deg, #020617, #0f172a);
    box-shadow: 8px 0 30px rgba(0,0,0,0.45);
    z-index: 1000;
    overflow-y: auto;
}

/* ===== LOGO / TITLE ===== */
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

/* ===== AGENT INFO CARD ===== */
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
    background: linear-gradient(135deg, #38bdf8, #2563eb);
}
.user-img img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: #fff;
}

.user-box h5 {
    color: #ffffff;
    font-weight: 600;
    margin-bottom: 2px;
}
.user-box p {
    font-size: 13px;
    color: #94a3b8;
}

/* ===== SIDEBAR MENU ===== */
#side-menu {
    list-style: none;
    padding: 0 8px;
    margin: 0;
}

#side-menu > li {
    margin-bottom: 6px;
}

#side-menu > li > a {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 18px;
    border-radius: 16px;
    color: #cbd5e1;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.35s ease;
}

/* Hover */
#side-menu > li > a:hover {
    background: rgba(56,189,248,0.18);
    color: #ffffff;
    transform: translateX(6px);
}

/* Active Item */
#side-menu > li.active > a {
    background: linear-gradient(135deg, #38bdf8, #2563eb);
    color: #ffffff;
    box-shadow: 0 12px 28px rgba(37,99,235,0.45);
}

/* ===== SUB MENU ===== */
.nav-second-level {
    display: none;
    list-style: none;
    padding-left: 22px;
    margin-top: 6px;
}

.nav-second-level li a {
    display: block;
    padding: 10px 14px;
    font-size: 13px;
    border-radius: 12px;
    color: #94a3b8;
    text-decoration: none;
    transition: all 0.3s ease;
}

.nav-second-level li a:hover {
    background: rgba(56,189,248,0.15);
    color: #ffffff;
}

/* ===== SUBMENU OPEN ===== */
.has-submenu.open + .nav-second-level {
    display: block;
}

.has-submenu.open .menu-arrow {
    transform: rotate(90deg);
    transition: transform 0.3s ease;
}

/* ===== SCROLLBAR ===== */
.left.side-menu::-webkit-scrollbar {
    width: 6px;
}
.left.side-menu::-webkit-scrollbar-track {
    background: transparent;
}
.left.side-menu::-webkit-scrollbar-thumb {
    background: rgba(56,189,248,0.35);
    border-radius: 10px;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 991px) {
    .left.side-menu {
        left: -260px;
    }
}
</style>

<script>
document.querySelectorAll('.has-submenu').forEach(menu => {
    menu.addEventListener('click', function(e) {
        e.preventDefault();

        document.querySelectorAll('.has-submenu').forEach(item => {
            if (item !== this) item.classList.remove('open');
        });

        this.classList.toggle('open');
    });
});
</script>
