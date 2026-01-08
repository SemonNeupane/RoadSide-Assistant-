<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include DB connection
include('../includes/dbconnection.php');

// Check if agent is logged in
if (empty($_SESSION['agent_id'])) {
    header('location:../login.php');
    exit();
}

$agent_id = $_SESSION['agent_id'];

// Get agent name
$ret = mysqli_query($con, "SELECT u.username 
                           FROM users u
                           JOIN agent a ON u.user_id = a.user_id
                           WHERE a.agent_id='$agent_id'");
$row = mysqli_fetch_assoc($ret);
$agent_name = isset($row['username']) ? htmlspecialchars($row['username']) : 'Agent';

// Fetch dashboard stats
$total_bookings = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS total FROM booking WHERE agent_id='$agent_id'"))['total'];
$pending_bookings = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS pending FROM booking WHERE agent_id='$agent_id' AND status='active'"))['pending'];
$completed_services = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS completed FROM booking WHERE agent_id='$agent_id' AND completed_at IS NOT NULL"))['completed'];
$avg_feedback_row = mysqli_fetch_assoc(mysqli_query($con, "SELECT AVG(f.rating) AS avg_rating 
                                                           FROM feedback f
                                                           JOIN booking b ON f.booking_id=b.booking_id
                                                           WHERE b.agent_id='$agent_id'"));
$avg_feedback = round($avg_feedback_row['avg_rating'], 1);

// Fetch recent bookings (latest 10)
$recent_bookings = mysqli_query($con, "SELECT b.booking_id, u.username AS user_name, v.model AS vehicle_model, s.service_name, c.city_name, b.status, b.created_at, b.completed_at
                                      FROM booking b
                                      JOIN users u ON b.user_id = u.user_id
                                      JOIN vehicle v ON b.vehicle_id = v.vehicle_id
                                      JOIN services s ON b.service_id = s.service_id
                                      JOIN city c ON b.city_id = c.city_id
                                      WHERE b.agent_id = '$agent_id'
                                      ORDER BY b.created_at DESC
                                      LIMIT 10");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Agent Dashboard | RSA Nepal</title>
<link rel="stylesheet" href="../assets/css/fontawesome.css"> <!-- optional icons -->
<style>
/* ----- Sidebar ----- */
.left.side-menu {
    width: 260px;
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    background: linear-gradient(180deg, #0f172a, #020617);
    box-shadow: 6px 0 30px rgba(0,0,0,0.35);
    overflow-y: auto;
    z-index: 1000;
}
.topbar-left { text-align:center; padding:22px 15px 10px; }
.topbar-left h3 { color:#38bdf8; font-weight:700; font-size:22px; margin-bottom:10px; }
.topbar-left hr { border-color: rgba(255,255,255,0.15); }
.user-box { text-align:center; padding:20px 15px; margin:10px 15px 20px; background:rgba(255,255,255,0.06); border-radius:18px; box-shadow: inset 0 0 0 1px rgba(255,255,255,0.08);}
.user-img { width:90px; height:90px; margin:0 auto 12px; border-radius:50%; padding:4px; background:linear-gradient(135deg, #2563eb, #1e40af);}
.user-img img { width:100%; height:100%; border-radius:50%; }
.user-box h5 { color:#fff; margin-bottom:3px; font-weight:600;}
.user-box p { color:#94a3b8; font-size:13px;}
#side-menu { list-style:none; padding:0; margin:0; }
#side-menu > li { margin-bottom:6px; }
#side-menu > li > a { display:flex; justify-content:space-between; padding:13px 16px; border-radius:14px; color:#cbd5e1; font-size:14px; font-weight:500; text-decoration:none; transition:all 0.35s ease; }
#side-menu > li > a:hover { background: rgba(255,255,255,0.12); color:#fff; transform:translateX(6px);}
.nav-second-level { display:none; list-style:none; padding-left:20px; margin-top:6px;}
.nav-second-level li a { display:block; padding:10px 14px; font-size:13px; border-radius:10px; color:#94a3b8; text-decoration:none; transition:all 0.3s ease;}
.nav-second-level li a:hover { color:#fff; background: rgba(255,255,255,0.1);}
.has-submenu.open .nav-second-level { display:block; }

/* ----- Header ----- */
.agent-topbar {
    position: fixed;
    top: 0;
    left: 260px;
    right: 0;
    height: 60px;
    background:#1f2937;
    color:#fff;
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:0 25px;
    z-index: 999;
    box-shadow: 0 2px 6px rgba(0,0,0,0.2);
}
.agent-topbar-left h3 { margin:0; font-size:18px; font-weight:600; }
.agent-topbar-right { position: relative; }
.agent-user-dropdown { cursor:pointer; display:flex; align-items:center; gap:10px; position:relative; }
.agent-user-img { width:35px; height:35px; border-radius:50%; border:2px solid #fff; object-fit:cover; }
.agent-dropdown-menu { display:none; position:absolute; right:0; top:50px; background:#fff; color:#1f2937; border-radius:8px; box-shadow:0 5px 15px rgba(0,0,0,0.2); min-width:160px; overflow:hidden; }
.agent-dropdown-menu a { display:block; padding:10px 15px; text-decoration:none; color:#1f2937; font-size:14px; }
.agent-dropdown-menu a:hover { background:#e5e7eb; }
/* show dropdown on click */
.agent-user-dropdown.active .agent-dropdown-menu { display:block; }

/* ----- Main content ----- */
.main-content { margin-left:260px; margin-top:60px; padding:20px; }
.dashboard-cards { display:flex; gap:20px; flex-wrap:wrap; margin-bottom:30px; }
.dashboard-cards .card { flex:1; min-width:200px; background:#fff; padding:20px; border-radius:10px; box-shadow:0 4px 10px rgba(0,0,0,0.08); text-align:center; }
.dashboard-cards .card h3 { font-size:18px; margin-bottom:10px; color:#1f2937; }

/* ----- Table ----- */
table { width:100%; border-collapse:collapse; background:#fff; border-radius:10px; overflow:hidden; }
table th, table td { padding:10px; text-align:left; border-bottom:1px solid #e5e7eb; }
table th { background:#f1f5f9; color:#1f2937; }
</style>
</head>
<body>

<!-- Sidebar -->
<?php include('includes/sidebar.php'); ?>

<!-- Header -->
<div class="agent-topbar">
    <div class="agent-topbar-left">
        <h3>Welcome Back, <?php echo $agent_name; ?> 👋</h3>
    </div>
    <div class="agent-topbar-right">
        <div class="agent-user-dropdown" id="agentDropdown">
            <img src="../assets/images/user.png" alt="agent" class="agent-user-img">
            <span><?php echo $agent_name; ?> ▼</span>
            <div class="agent-dropdown-menu">
                <a href="profile.php">My Profile</a>
                <a href="changepassword.php">Change Password</a>
                <a href="/RoadSide-Assistant-/Roadside/agent/logout.php">Logout</a>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="main-content">

    <!-- Stats Cards -->
    <!-- Stats Cards -->
<div class="dashboard-cards">
    <div class="card">
        <h3>Total Bookings</h3>
        <div class="circle blue"><?php echo $total_bookings; ?></div>
    </div>
    <div class="card">
        <h3>Pending Bookings</h3>
        <div class="circle orange"><?php echo $pending_bookings; ?></div>
    </div>
    <div class="card">
        <h3>Completed Services</h3>
        <div class="circle green"><?php echo $completed_services; ?></div>
    </div>
    <div class="card">
        <h3>Average Feedback</h3>
        <div class="circle red"><?php echo $avg_feedback ?: 0; ?></div>
    </div>
</div>

<style>
.dashboard-cards {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    margin-bottom: 30px;
}

.dashboard-cards .card {
    flex: 1;
    min-width: 200px;
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    text-align: center;
}

.dashboard-cards .card h3 {
    font-size: 18px;
    margin-bottom: 15px;
    color: #203A4A;
}

/* Circle for numbers */
.circle {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    line-height: 70px;
    margin: 0 auto;
    font-size: 22px;
    font-weight: bold;
    color: #fff;
}

/* Circle colors */
.circle.blue { background: #38bdf8; }
.circle.orange { background: #f59e0b; }
.circle.green { background: #22c55e; }
.circle.red { background: #ef4444; }
</style>


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
document.getElementById('agentDropdown').addEventListener('click', function() {
    this.classList.toggle('active');
});
</script>
 <?php include(__DIR__ . '/includes/footer.php'); ?>
 

</body>
</html>

