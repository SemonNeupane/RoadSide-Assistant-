<?php
// // Start session
// if (session_status() == PHP_SESSION_NONE) {
//     session_start();
// }

// include('../includes/dbconnection.php');

// Check admin login
// if (empty($_SESSION['admin_id'])) {
//     header('location:/index.php');
//     exit();
// }

$admin_id = $_SESSION['admin_id'];

// Fetch admin name
$ret = mysqli_query($con, "SELECT username FROM users WHERE user_id='$admin_id' AND role='admin'");
$row = mysqli_fetch_assoc($ret);
$name = isset($row['username']) ? htmlspecialchars($row['username']) : 'Admin';
?>

<!-- Admin Sidebar -->
<div class="left side-menu">
    <div class="slimscroll-menu" id="remove-scroll">

        <!-- LOGO / TITLE -->
        <div class="topbar-left">
            <h3>RSA | ADMIN</h3>
            <hr />
        </div>

        <!-- ADMIN INFO -->
        <div class="user-box">
            <div class="user-img">
                <img src="../assets/images/user.png" alt="admin-img" class="rounded-circle img-fluid">
            </div>
            <h5><?php echo $name; ?></h5>
            <p class="text-muted">System Administrator</p>
        </div>

        <!-- SIDEBAR MENU -->
        <div id="sidebar-menu">
            <ul id="side-menu">

                <!-- Dashboard -->
                <li>
                    <a href="dashboard.php">
                        <span>Dashboard</span>
                    </a>
                </li>

                <!-- User Management -->
                <li>
                    <a href="javascript:void(0);" class="has-submenu">
                        <span>User Management</span>
                        <span class="menu-arrow">&#9654;</span>
                    </a>
                    <ul class="nav-second-level">
                        <li><a href="users.php">Users</a></li>
                        <li><a href="vehicles.php">Vehicles</a></li>
                        <li><a href="user-locations.php">User Locations</a></li>
                    </ul>
                </li>

                <!-- Agent Management -->
                <li>
                    <a href="javascript:void(0);" class="has-submenu">
                        <span>Agent Management</span>
                        <span class="menu-arrow">&#9654;</span>
                    </a>
                    <ul class="nav-second-level">
                        <li><a href="pending-agents.php">Pending Agents</a></li>
                        <li><a href="approved-agents.php">Approved Agents</a></li>
                        <li><a href="agent-services.php">Agent Services</a></li>
                        <li><a href="agent-locations.php">Agent Locations</a></li>
                    </ul>
                </li>

                <!-- Services -->
                <li>
                    <a href="services.php">
                        <span>Services</span>
                    </a>
                </li>

                <!-- Location Management -->
                <li>
                    <a href="javascript:void(0);" class="has-submenu">
                        <span>Location Management</span>
                        <span class="menu-arrow">&#9654;</span>
                    </a>
                    <ul class="nav-second-level">
                        <li><a href="province.php">Province</a></li>
                        <li><a href="district.php">District</a></li>
                        <li><a href="city.php">City</a></li>
                    </ul>
                </li>

                <!-- Bookings -->
                 <li>
                    <a href="javascript:void(0);" class="has-submenu">
                        <span>Booking</span>
                        <span class="menu-arrow">&#9654;</span>
                    </a>
                    <ul class="nav-second-level">
                        <li><a href="all-bookings.php">All Booking</a></li>
                        <li><a href="active-bookings.php">Active Booking</a></li>
                        <li><a href="completed-bookings.php">Completed Booking</a></li>
                    </ul>
                </li>

                <!-- Feedback -->
                <li>
                    <a href="feedback.php">
                        <span>Feedback</span>
                    </a>
                </li>

                <!-- Logout -->
                <li>
                    <a href="../logout.php">
                        <span>Logout</span>
                    </a>
                </li>

            </ul>
        </div>

    </div>
</div>
<script>
document.querySelectorAll('.has-submenu').forEach(function(menu) {
    menu.addEventListener('click', function(e) {
        e.preventDefault();

        document.querySelectorAll('.has-submenu').forEach(item => {
            if (item !== this) item.classList.remove('open');
        });

        this.classList.toggle('open');
    });
});
</script>
<style>
    /* Hide submenu by default */
.nav-second-level {
    display: none;
}

/* Show submenu when parent link is open */
.has-submenu.open + .nav-second-level {
    display: block;
}
/* Sidebar */
.left.side-menu { width:260px; position:fixed; top:0; left:0; height:100vh; background: linear-gradient(180deg,#0f172a,#020617); box-shadow:6px 0 30px rgba(0,0,0,0.35); overflow-y:auto; z-index:1000;}
.topbar-left{text-align:center;padding:22px 15px 10px;}
.topbar-left h3{color:#38bdf8;font-weight:700;font-size:22px;margin-bottom:10px;}
.topbar-left hr{border-color: rgba(255,255,255,0.15);}
.user-box{text-align:center;padding:20px 15px;margin:10px 15px 20px;background:rgba(255,255,255,0.06);border-radius:18px;box-shadow:inset 0 0 0 1px rgba(255,255,255,0.08);}
.user-img{width:90px;height:90px;margin:0 auto 12px;border-radius:50%;padding:4px;background:linear-gradient(135deg,#2563eb,#1e40af);}
.user-img img{width:100%;height:100%;border-radius:50%;}
.user-box h5{color:#fff;margin-bottom:3px;font-weight:600;}
.user-box p{color:#94a3b8;font-size:13px;}
#side-menu{list-style:none;padding:0;margin:0;}
#side-menu > li{margin-bottom:6px;}
#side-menu > li > a{display:flex;justify-content:space-between;padding:13px 16px;border-radius:14px;color:#cbd5e1;font-size:14px;font-weight:500;text-decoration:none;transition:all 0.35s ease;}
#side-menu > li > a:hover{background: rgba(255,255,255,0.12); color:#fff; transform:translateX(6px);}
.nav-second-level{display:none; list-style:none;padding-left:20px;margin-top:6px;}
.nav-second-level li a{display:block;padding:10px 14px;font-size:13px;border-radius:10px;color:#94a3b8;text-decoration:none;transition:all 0.3s ease;}
.nav-second-level li a:hover{color:#fff;background: rgba(255,255,255,0.1);}
.has-submenu.open .nav-second-level{display:block;}
    </style>