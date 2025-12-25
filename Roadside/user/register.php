<?php
session_start();
include(__DIR__ . '/../includes/dbconnection.php');
 // Path to your centralized DB connection

$msg = '';

if(isset($_POST['register'])){
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $password = md5($_POST['password']); // Using md5 to match login system
    $role = 'user'; // This is fixed for user registration
    $status = 'active'; // Users are active immediately

    // Check if email or phone already exists
    $checkQuery = mysqli_query($con, "SELECT * FROM users WHERE email='$email' OR phone='$phone'");
    if(mysqli_num_rows($checkQuery) > 0){
        $msg = "Email or phone already registered.";
    } else {
        // Insert user
        $insertQuery = mysqli_query($con, "INSERT INTO users (username,email,phone,password,role,status) VALUES ('$username','$email','$phone','$password','$role','$status')");
        if($insertQuery){
            $msg = "Registration successful! <a href='../login.php'>Login here</a>.";
        } else {
            $msg = "Error: ".mysqli_error($con);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>RSA Nepal | User Signup</title>
<link href="../assets/css/style.css" rel="stylesheet">
<style>
body{font-family:Arial,sans-serif;background:#f2f4f7;}
.wrapper-page{max-width:400px;margin:50px auto;padding:20px;background:#fff;border-radius:12px;box-shadow:0 8px 20px rgba(0,0,0,0.1);}
h3{text-align:center;margin-bottom:20px;}
.form-control{width:100%;padding:10px;margin-bottom:15px;border-radius:8px;border:1px solid #ccc;}
.btn-custom{width:100%;padding:12px;background:#007bff;color:#fff;border:none;border-radius:8px;cursor:pointer;}
.btn-custom:hover{background:#0056b3;}
p.msg{color:red;text-align:center;font-weight:bold;}
</style>
</head>
<body>
<div class="wrapper-page">
    <h3>User Registration</h3>
    <p class="msg"><?php echo $msg; ?></p>
    <form method="post" action="">
        <input type="text" name="username" class="form-control" required placeholder="Full Name">
        <input type="email" name="email" class="form-control" required placeholder="Email">
        <input type="tel" name="phone" class="form-control" required placeholder="Phone Number">
        <input type="password" name="password" class="form-control" required placeholder="Password">
        <button type="submit" name="register" class="btn-custom">Register</button>
    </form>
    <p style="text-align:center;margin-top:15px;">Already have an account? <a href="../login.php">Login</a></p>
</div>
</body>
</html>
