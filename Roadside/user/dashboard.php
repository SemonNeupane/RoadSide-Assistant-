<?php
session_start();

// Include DB
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
<link rel="stylesheet" href="../assets/css/style.css"> <!-- centralized CSS -->
<style>
/* Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: "Segoe UI", Tahoma, sans-serif;
}

/* Wrapper */
#wrapper {
    display: flex;
}

/* Main Content Area */
.content {
    margin-left: 240px; /* SAME as sidebar width */
    width: calc(100% - 240px);
    min-height: 100vh;
    background: #f3f4f6;
    padding: 20px;
}

/* Header Area */
.header {
    background: #ffffff;
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
}

/* Welcome Card */
.card {
    background: #ffffff;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 25px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.08);
}

.card h3 {
    font-size: 20px;
    color: #203A4A;
}

/* Dashboard Grid */
.dashboard-row {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
}

.dashboard-col {
    flex: 1 1 220px;
}

/* Widget Cards */
.widget {
    background: #ffffff;
    border-radius: 12px;
    padding: 25px;
    text-align: center;
    box-shadow: 0 6px 15px rgba(0,0,0,0.08);
    transition: transform 0.3s ease;
}

.widget:hover {
    transform: translateY(-6px);
}

.widget p {
    margin-top: 15px;
    font-size: 15px;
    font-weight: 500;
    color: #374151;
}

/* Circle Stats */
.circle {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    line-height: 80px;
    margin: 0 auto;
    font-size: 22px;
    font-weight: bold;
    color: #ffffff;
}

/* Color Variants */
.circle.blue {
    background: #38bdf8;
}

.circle.orange {
    background: #f59e0b;
}

.circle.green {
    background: #22c55e;
}

.circle.red {
    background: #ef4444;
}

/* Footer */
.footer {
    margin-top: 40px;
    padding: 15px;
    text-align: center;
    color: #6b7280;
    font-size: 14px;
}

/* Responsive */
@media (max-width: 768px) {
    .content {
        margin-left: 200px;
        width: calc(100% - 200px);
    }
}

@media (max-width: 576px) {
    .content {
        margin-left: 0;
        width: 100%;
    }
}

</style>
</head>
<body>

<div id="wrapper">

    <!-- Sidebar -->
    <?php include(__DIR__ . '/includes/sidebar.php'); ?>

    <!-- Main Content -->
    <div class="content">

        <!-- Header -->
        <?php include(__DIR__ . '/includes/header.php'); ?>
        

       

        <div class="dashboard-row">
            <?php
            // Stats cards
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

        <!-- Footer -->
        <?php include(__DIR__ . '/includes/footer.php'); ?>

    </div> <!-- content -->

</div> <!-- wrapper -->

</body>
</html>
