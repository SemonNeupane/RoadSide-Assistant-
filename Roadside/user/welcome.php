<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// connect to db
include('../admin/includes/dbconnection.php');

// Check if user is logged in
if (empty($_SESSION['sid'])) {
    header('location:logout.php');
    exit();
} else {
    $uid = $_SESSION['sid'];
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>RSA Nepal | User Dashboard</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/css/style.css" rel="stylesheet" type="text/css" />
    <link href="../assets/css/icons.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="wrapper">

    <?php include_once('includes/sidebar.php'); ?>
    <div class="content-page">
        <?php include_once('includes/header.php'); ?>

        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card-box">
                            <h4 class="header-title mb-4">Welcome Back, 
                                <?php
                                $userQuery = mysqli_query($con, "SELECT username FROM users WHERE user_id='$uid'");
                                $user = mysqli_fetch_assoc($userQuery);
                                echo htmlspecialchars($user['username']);
                                ?> 👋
                            </h4>

                            <div class="row">
                                <!-- Total Bookings -->
                                <div class="col-sm-6 col-lg-3">
                                    <div class="card-box widget-chart-two">
                                        <?php
                                        $result1 = mysqli_query($con, "SELECT booking_id FROM booking WHERE user_id='$uid'");
                                        $totalBookings = mysqli_num_rows($result1);
                                        ?>
                                        <input data-plugin="knob" data-width="80" data-height="80" data-fgColor="#2d7bf4" 
                                            value="<?php echo $totalBookings; ?>" data-readOnly="true"/>
                                        <p class="text-muted mt-2">Total Bookings</p>
                                    </div>
                                </div>

                                <!-- Active Bookings -->
                                <div class="col-sm-6 col-lg-3">
                                    <div class="card-box widget-chart-two">
                                        <?php
                                        $result2 = mysqli_query($con, "SELECT booking_id FROM booking WHERE user_id='$uid' AND status='active'");
                                        $activeBookings = mysqli_num_rows($result2);
                                        ?>
                                        <input data-plugin="knob" data-width="80" data-height="80" data-fgColor="#f5a623" 
                                            value="<?php echo $activeBookings; ?>" data-readOnly="true"/>
                                        <p class="text-muted mt-2">Active Bookings</p>
                                    </div>
                                </div>

                                <!-- Completed -->
                                <div class="col-sm-6 col-lg-3">
                                    <div class="card-box widget-chart-two">
                                        <?php
                                        $result3 = mysqli_query($con, "SELECT booking_id FROM booking WHERE user_id='$uid' AND status='inactive'");
                                        $completedBookings = mysqli_num_rows($result3);
                                        ?>
                                        <input data-plugin="knob" data-width="80" data-height="80" data-fgColor="#0acf97" 
                                            value="<?php echo $completedBookings; ?>" data-readOnly="true"/>
                                        <p class="text-muted mt-2">Completed Bookings</p>
                                    </div>
                                </div>

                                <!-- Feedbacks -->
                                <div class="col-sm-6 col-lg-3">
                                    <div class="card-box widget-chart-two">
                                        <?php
                                        $result4 = mysqli_query($con, "SELECT feedback_id FROM feedback WHERE user_id='$uid'");
                                        $feedbacks = mysqli_num_rows($result4);
                                        ?>
                                        <input data-plugin="knob" data-width="80" data-height="80" data-fgColor="#ff5b5b" 
                                            value="<?php echo $feedbacks; ?>" data-readOnly="true"/>
                                        <p class="text-muted mt-2">Feedback Given</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div> <!-- end row -->
            </div> <!-- container -->
        </div> <!-- content -->

        <?php include_once('includes/footer.php'); ?>
    </div>
</div>

<!-- JS Files -->
<script src="../assets/js/jquery.min.js"></script>
<script src="../assets/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/jquery.knob.js"></script>
<script>
$(function() {
    $('[data-plugin="knob"]').knob();
});
</script>
</body>
</html>
