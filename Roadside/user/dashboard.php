<?php
session_start();
include(__DIR__ . '/../includes/dbconnection.php');

// ✅ Correct session check
if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header('Location: ../login.php');
    exit();
}

$uid = intval($_SESSION['user_id']);

// Fetch user info
$userQuery = mysqli_query($con, "SELECT * FROM users WHERE user_id='$uid' LIMIT 1");
$user = mysqli_fetch_assoc($userQuery);
if (!$user) {
    header("location:../logout.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>RSA Nepal | User Dashboard</title>
<link rel="stylesheet" href="../assets/css/style.css">
<style>
/* Reset */
* { margin: 0; padding: 0; box-sizing: border-box; font-family: "Segoe UI", Tahoma, sans-serif; }

/* Wrapper */
#wrapper {
    display: flex;
}

/* Sidebar fixed */
#sidebar {
    width: 240px;
    position: fixed;
    top: 0;
    left: 0;
    height: 100%;
    background: #1f2937;
    overflow-y: auto;
}

/* Main content wrapper */
.content-wrapper {
    margin-left: 240px;
    width: calc(100% - 240px);
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

/* Scrollable content */
.content {
    flex: 1;
    padding: 20px;
    background: #f3f4f6;
    overflow-y: auto;
}

/* Header */
.header {
    background: #fff;
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
}

/* Dashboard widgets */
.dashboard-row {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
}
.dashboard-col {
    flex: 1 1 220px;
}
.widget {
    background: #fff;
    border-radius: 12px;
    padding: 25px;
    text-align: center;
    box-shadow: 0 6px 15px rgba(0,0,0,0.08);
    transition: transform 0.3s ease;
}
.widget:hover { transform: translateY(-6px); }
.widget p { margin-top: 15px; font-size: 15px; font-weight: 500; color: #374151; }

.circle {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    line-height: 80px;
    margin: 0 auto;
    font-size: 22px;
    font-weight: bold;
    color: #fff;
}
.circle.blue { background: #38bdf8; }
.circle.orange { background: #f59e0b; }
.circle.green { background: #22c55e; }
.circle.red { background: #ef4444; }

/* Sticky Footer */
.footer {
    background: #1f2937;
    color: #fff;
    text-align: center;
    padding: 15px;
    position: sticky;
    bottom: 0;
    width: 100%;
    z-index: 10;
}

/* Responsive */
@media (max-width: 768px) {
    #sidebar { width: 200px; }
    .content-wrapper { margin-left: 200px; width: calc(100% - 200px); }
}
@media (max-width: 576px) {
    #sidebar { position: relative; width: 100%; height: auto; }
    .content-wrapper { margin-left: 0; width: 100%; }
}
</style>
</head>
<body>

<div id="wrapper">

    <!-- Sidebar -->
    <?php include(__DIR__ . '/includes/sidebar.php'); ?>

    <!-- Main Content Wrapper -->
    <div class="content-wrapper">

        <!-- Header -->
        <?php include(__DIR__ . '/includes/header.php'); ?>

        <!-- Scrollable Content -->
        <div class="content">
            <div class="dashboard-row">
                <?php
                $stats = [
                    ['label'=>'Total Bookings','color'=>'blue','query'=>"SELECT booking_id FROM booking WHERE user_id='$uid'"],
                    ['label'=>'Active Bookings','color'=>'orange','query'=>"SELECT booking_id FROM booking WHERE user_id='$uid' AND status='active'"],
                    ['label'=>'Completed Services','color'=>'green','query'=>"SELECT booking_id FROM booking WHERE user_id='$uid' AND completed_at IS NOT NULL"],
                    ['label'=>'Feedback Given','color'=>'red','query'=>"SELECT feedback_id FROM feedback WHERE user_id='$uid'"]
                ];
                foreach($stats as $stat){
                    $result = mysqli_query($con, $stat['query']);
                    $count = mysqli_num_rows($result);
                ?>
                <div class="dashboard-col">
                    <div class="widget">
                        <div class="circle <?php echo $stat['color']; ?>"><?php echo $count; ?></div>
                        <p><?php echo $stat['label']; ?></p>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>

        <!-- Sticky Footer -->
        <div class="footer">
            © 2026 Roadside Assistance System | User Panel.
        </div>

    </div> <!-- content-wrapper -->

</div> <!-- wrapper -->

</body>
</html>
