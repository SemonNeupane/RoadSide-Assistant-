<?php
// Start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include DB connection
include('../includes/dbconnection.php');

// Check if admin is logged in
if (empty($_SESSION['admin_id'])) {
    header('location:index.php');
    exit();
}

$admin_id = $_SESSION['admin_id'];

// Get admin name
$ret = mysqli_query($con, "SELECT username FROM users WHERE user_id='$admin_id' AND role='admin'");
$row = mysqli_fetch_assoc($ret);
$admin_name = isset($row['username']) ? htmlspecialchars($row['username']) : 'Admin';

// Fetch stats
$total_users = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS total FROM users WHERE role='user'"))['total'];
$total_agents = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS total FROM agent"))['total'];
$total_services = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS total FROM services"))['total'];
$total_bookings = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS total FROM booking"))['total'];
$total_feedback = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS total FROM feedback"))['total'];

// Fetch recent bookings (latest 10)
$recent_bookings = mysqli_query($con, "SELECT b.booking_id, u.username AS user_name, v.model AS vehicle_model, s.service_name, c.city_name, b.status, b.created_at, b.completed_at
                                      FROM booking b
                                      JOIN users u ON b.user_id = u.user_id
                                      JOIN vehicle v ON b.vehicle_id = v.vehicle_id
                                      JOIN services s ON b.service_id = s.service_id
                                      JOIN city c ON b.city_id = c.city_id
                                      ORDER BY b.created_at DESC
                                      LIMIT 10");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard | RSA Nepal</title>
<link rel="stylesheet" href="../assets/css/fontawesome.css"> <!-- optional icons -->

<style>
/* ----- Reuse same CSS as agent dashboard ----- */
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

/* Header */
.admin-topbar{position:fixed;top:0;left:260px;right:0;height:60px;background:#1f2937;color:#fff;display:flex;justify-content:space-between;align-items:center;padding:0 25px;z-index:999;box-shadow:0 2px 6px rgba(0,0,0,0.2);}
.admin-topbar-left h3{margin:0;font-size:18px;font-weight:600;}
.admin-topbar-right{position:relative;}
.admin-user-dropdown{cursor:pointer;display:flex;align-items:center;gap:10px;position:relative;}
.admin-user-img{width:35px;height:35px;border-radius:50%;border:2px solid #fff;object-fit:cover;}
.admin-dropdown-menu{display:none;position:absolute;right:0;top:50px;background:#fff;color:#1f2937;border-radius:8px;box-shadow:0 5px 15px rgba(0,0,0,0.2);min-width:160px;overflow:hidden;}
.admin-dropdown-menu a{display:block;padding:10px 15px;text-decoration:none;color:#1f2937;font-size:14px;}
.admin-dropdown-menu a:hover{background:#e5e7eb;}
.admin-user-dropdown.active .admin-dropdown-menu{display:block;}

/* Main content */
.main-content{margin-left:260px;margin-top:60px;padding:20px;}
.dashboard-cards{display:flex;gap:20px;flex-wrap:wrap;margin-bottom:30px;}
.dashboard-cards .card{flex:1;min-width:200px;background:#fff;padding:20px;border-radius:10px;box-shadow:0 4px 10px rgba(0,0,0,0.08);text-align:center;}
.dashboard-cards .card h3{font-size:18px;margin-bottom:10px;color:#1f2937;}
.circle{width:70px;height:70px;border-radius:50%;line-height:70px;margin:0 auto;font-size:22px;font-weight:bold;color:#fff;}
.circle.blue{background:#38bdf8;}
.circle.orange{background:#f59e0b;}
.circle.green{background:#22c55e;}
.circle.red{background:#ef4444;}

/* Table */
table{width:100%;border-collapse:collapse;background:#fff;border-radius:10px;overflow:hidden;}
table th, table td{padding:10px;text-align:left;border-bottom:1px solid #e5e7eb;}
table th{background:#f1f5f9;color:#1f2937;}
</style>
</head>
<body>

<!-- Sidebar -->
<?php include('includes/sidebar.php'); ?>




<!-- Header -->
<div class="admin-topbar">
    <div class="admin-topbar-left">
        <h3>Welcome Back, <?php echo $admin_name; ?> 👋</h3>
    </div>
    <div class="admin-topbar-right">
        <div class="admin-user-dropdown" id="adminDropdown">
            <img src="../assets/images/user.png" alt="admin" class="admin-user-img">
            <span></?php echo $admin_name; ?> ▼</span>
            <div class="admin-dropdown-menu">
                <a href="profile.php">My Profile</a>
                <a href="changepassword.php">Change Password</a>
                <a href="../logout.php">Logout</a>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="main-content">

    <!-- Stats Cards -->
    <div class="dashboard-cards">
        <div class="card">
            <h3>Total Users</h3>
            <div class="circle blue"><?php echo $total_users; ?></div>
        </div>
        <div class="card">
            <h3>Total Agents</h3>
            <div class="circle orange"><?php echo $total_agents; ?></div>
        </div>
        <div class="card">
            <h3>Total Services</h3>
            <div class="circle green"><?php echo $total_services; ?></div>
        </div>
        <div class="card">
            <h3>Total Bookings</h3>
            <div class="circle red"><?php echo $total_bookings; ?></div>
        </div>
        <div class="card">
            <h3>Total Feedback</h3>
            <div class="circle blue"><?php echo $total_feedback; ?></div>
        </div>
    </div>

    <!-- Recent Bookings Table -->
    <h3>Recent Bookings</h3>
    <table>
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>User</th>
                <th>Vehicle</th>
                <th>Service</th>
                <th>City</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Completed At</th>
            </tr>
        </thead>
        <tbody>
            <?php while($booking = mysqli_fetch_assoc($recent_bookings)) { ?>
                <tr>
                    <td><?php echo $booking['booking_id']; ?></td>
                    <td><?php echo htmlspecialchars($booking['user_name']); ?></td>
                    <td><?php echo htmlspecialchars($booking['vehicle_model']); ?></td>
                    <td><?php echo htmlspecialchars($booking['service_name']); ?></td>
                    <td><?php echo htmlspecialchars($booking['city_name']); ?></td>
                    <td><?php echo ucfirst($booking['status']); ?></td>
                    <td><?php echo $booking['created_at']; ?></td>
                    <td><?php echo $booking['completed_at']; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

</div>

<!-- JS for dropdown -->
<script>
document.getElementById('adminDropdown').addEventListener('click', function() {
    this.classList.toggle('active');
});
</script>

</body>
</html>
