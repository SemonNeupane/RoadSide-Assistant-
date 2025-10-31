<?php
session_start();
error_reporting(0);
include('../admin/includes/dbconnection.php');

// Check if user is logged in
if (empty($_SESSION['sid'])) {
    header('location:logout.php');
    exit();
}

$uid = $_SESSION['sid'];
// Fetch user name from db_rsa.users table
$ret = mysqli_query($con, "SELECT username FROM users WHERE user_id='$uid'");
$row = mysqli_fetch_array($ret);
$name = htmlspecialchars($row['username']);
?>

<!-- ========== Left Sidebar Start ========== -->
<div class="left side-menu">
    <div class="slimscroll-menu" id="remove-scroll">

        <!-- LOGO -->
        <div class="topbar-left">
            <h3>RSA | USER</h3>
            <hr />
        </div>

        <!-- User box -->
        <div class="user-box">
            <div class="user-img">
                <img src="../assets/images/user.png" alt="user-img" class="rounded-circle img-fluid">
            </div>

            <h5><?php echo $name; ?></h5>
            <p class="text-muted">RSA User</p>
        </div>

        <!-- Sidebar Menu -->
        <div id="sidebar-menu">
            <ul class="metismenu" id="side-menu">
                <li>
                    <a href="welcome.php">
                        <i class="fi-air-play"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="javascript:void(0);">
                        <i class="fi-layers"></i><span>Service Requests</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li><a href="service-request.php">Request Service</a></li>
                        <li><a href="service-history.php">Request History</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript:void(0);">
                        <i class="fi-layers"></i><span>Enquiries</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li><a href="enquiry-form.php">Enquiry Form</a></li>
                        <li><a href="enquiry-history.php">Enquiry History</a></li>
                    </ul>
                </li>

                <li>
                    <a href="feedback.php">
                        <i class="fi-comment"></i>
                        <span>Feedback</span>
                    </a>
                </li>

                <li>
                    <a href="support.php">
                        <i class="fi-help"></i>
                        <span>Support</span>
                    </a>
                </li>

                <li>
                    <a href="logout.php">
                        <i class="fi-power"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- End Sidebar Menu -->

        <div class="clearfix"></div>
    </div>
</div>
<!-- Left Sidebar End -->
