<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
include('../admin/includes/dbconnection.php');

// Initialize message variable
$msg = "";

if(isset($_POST['submit'])) {
    // Get form values
    $username = mysqli_real_escape_string($con, $_POST['fullname']);
    $phone    = mysqli_real_escape_string($con, $_POST['mobilenumber']);
    $email    = mysqli_real_escape_string($con, $_POST['email']);
    $password = md5($_POST['password']); // md5 for now
    $reg_date = date('Y-m-d');

    // Check if email or phone already exists
    $check = mysqli_query($con, "SELECT user_id FROM users WHERE email='$email' OR phone='$phone'");
    if(!$check){
        die("Query failed: " . mysqli_error($con));
    }

    if(mysqli_num_rows($check) > 0){
        $msg = "This email or contact number is already associated with another account";
    } else {
        // Insert into users table
        $insert = mysqli_query($con, "INSERT INTO users (username, email, password, phone, registration_date) VALUES ('$username', '$email', '$password', '$phone', '$reg_date')");
        if(!$insert){
            die("Insert failed: " . mysqli_error($con));
        } else {
            $msg = "You have successfully registered. <a href='index.php'>Login Here</a>";
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Vehicle Service Management System | Sign Up</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="../assets/css/style.css" rel="stylesheet" />
    <script>
        function checkpass() {
            if(document.signup.password.value != document.signup.repeatpassword.value){
                alert('Password and Repeat Password do not match');
                document.signup.repeatpassword.focus();
                return false;
            }
            return true;
        }
    </script>
</head>
<body class="account-pages">

<div class="wrapper-page account-page-full">
    <div class="card">
        <div class="card-block">
            <div class="account-box">
                <div class="card-box p-5">
                    <h3 class="text-center pb-4">
                        <a href="../index.php"><span>RSAM | Sign Up</span></a>
                    </h3>
                    <p style="color:red; text-align:center;"><?php echo $msg; ?></p>

                    <form name="signup" method="post" onsubmit="return checkpass();">
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" name="fullname" class="form-control" required placeholder="Enter Your Full Name">
                        </div>

                        <div class="form-group">
                            <label>Mobile Number</label>
                            <input type="text" name="mobilenumber" class="form-control" required maxlength="10" pattern="[0-9]+" placeholder="Enter Your Mobile Number">
                        </div>

                        <div class="form-group">
                            <label>Email address</label>
                            <input type="email" name="email" class="form-control" required placeholder="abc@gmail.com">
                        </div>

                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required placeholder="Enter your password">
                        </div>

                        <div class="form-group">
                            <label>Repeat Password</label>
                            <input type="password" name="repeatpassword" class="form-control" required placeholder="Repeat your password">
                        </div>

                        <div class="form-group">
                            <div class="checkbox">
                                <input id="terms" type="checkbox" required>
                                <label for="terms">I accept <a href="terms-conditions.php">Terms and Conditions</a></label>
                            </div>
                        </div>

                        <div class="form-group text-center">
                            <button type="submit" name="submit" class="btn btn-custom btn-block">Sign Up Free</button>
                        </div>
                    </form>

                    <p class="text-center">Already have an account? <a href="index.php"><b>Sign In</b></a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../assets/js/jquery.min.js"></script>
<script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
