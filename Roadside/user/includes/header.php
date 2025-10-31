<?php
session_start();
error_reporting(0);
include('../admin/includes/dbconnection.php');

// If session not set, redirect
if (empty($_SESSION['sid'])) {
    header('location:logout.php');
    exit();
}

$uid = $_SESSION['sid'];

// Fetch logged-in user details
$query = mysqli_query($con, "SELECT username FROM users WHERE user_id='$uid'");
$user = mysqli_fetch_assoc($query);
$name = isset($user['username']) ? htmlspecialchars($user['username']) : 'User';
?>

<!-- Top Bar Start -->
<div class="topbar">
    <nav class="navbar-custom">
        <ul class="list-unstyled topbar-right-menu float-right mb-0">

            <!-- User Dropdown -->
            <li class="dropdown notification-list">
                <a class="nav-link dropdown-toggle nav-user" data-toggle="dropdown" href="#" role="button"
                   aria-haspopup="false" aria-expanded="false">
                    <img src="../assets/images/user.png" alt="user" class="rounded-circle">
                    <span class="ml-1" style="color:#fff">
                        <?php echo $name; ?> <i class="mdi mdi-chevron-down"></i>
                    </span>
                </a>

                <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated profile-dropdown">
                    <div class="dropdown-item noti-title">
                        <h6 class="text-overflow m-0">Welcome, <?php echo $name; ?>!</h6>
                    </div>

                    <!-- Profile -->
                    <a href="user-profile.php" class="dropdown-item notify-item">
                        <i class="fi-head"></i> <span>My Profile</span>
                    </a>

                    <!-- Change Password -->
                    <a href="changepassword.php" class="dropdown-item notify-item">
                        <i class="fi-cog"></i> <span>Change Password</span>
                    </a>

                    <!-- Logout -->
                    <a href="logout.php" class="dropdown-item notify-item">
                        <i class="fi-power"></i> <span>Logout</span>
                    </a>
                </div>
            </li>
        </ul>

        <!-- Sidebar Menu Button -->
        <ul class="list-inline menu-left mb-0">
            <li class="float-left">
                <button class="button-menu-mobile open-left disable-btn">
                    <i class="dripicons-menu"></i>
                </button>
            </li>
        </ul>
    </nav>
</div>
<!-- Top Bar End -->
<hr color="#fff" />
