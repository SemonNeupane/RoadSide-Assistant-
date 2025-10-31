<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include('includes/dbconnection.php');

$msg = '';

if (isset($_POST['login'])) {
    // Get input and escape to prevent SQL injection
    $emailOrPhone = mysqli_real_escape_string($con, $_POST['emailcont']);
    $password = md5($_POST['password']); // Assuming passwords are stored as md5

    // Query the database
    $query = mysqli_query($con, "SELECT user_id, username FROM users WHERE (email='$emailOrPhone' OR phone='$emailOrPhone') AND password='$password' AND status='active'");

    if ($query) {
        $user = mysqli_fetch_assoc($query);
        if ($user) {
            // Login success
            $_SESSION['sid'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            header('Location: welcome.php'); // Redirect to dashboard or welcome page
            exit();
        } else {
            $msg = "Invalid email/phone or password.";
        }
    } else {
        $msg = "Error: " . mysqli_error($con);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Roadside Assistant | Login</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body class="account-pages">

<div class="accountbg" style="background: url('../assets/images/bg-2.jpg'); background-size: cover; background-position: center;"></div>

<div class="wrapper-page account-page-full">
    <div class="card">
        <div class="card-block">
            <div class="account-box">
                <div class="card-box p-5">
                    <h3 class="text-center pb-4">
                        <a href="../index.php"><span>RSAM | User Login</span></a>
                    </h3>
                    <hr>
                    <p style="color:red; text-align:center;"><?php echo $msg; ?></p>

                    <form action="" method="post">
                        <div class="form-group">
                            <label>Email or Phone</label>
                            <input type="text" name="emailcont" class="form-control" required placeholder="Registered Email or Contact Number">
                        </div>

                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required placeholder="Enter your password">
                            <a href="forget-password.php" class="float-right"><small>Forgot your password?</small></a>
                        </div>

                        <button type="submit" name="login" class="btn btn-custom btn-block">Sign In</button>
                    </form>

                    <p class="text-center mt-3">
                        Don't have an account? <a href="register.php"><b>Sign Up</b></a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mt-4">
        <p><?php echo date('Y'); ?> © 2025 RSA Nepal. All Rights Reserved.</p>
    </div>
</div>

<script src="../assets/js/jquery.min.js"></script>
<script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
