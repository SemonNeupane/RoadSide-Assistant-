<?php
session_start();
include(__DIR__ . '/../includes/dbconnection.php');
$msg = '';

if(isset($_POST['register'])){
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $password = md5($_POST['password']); // Use password_hash() for stronger security in real apps

    // Check if email already exists
    $checkEmail = mysqli_query($con, "SELECT * FROM users WHERE email='$email'");
    if(mysqli_num_rows($checkEmail) > 0){
        $msg = "Email already exists!";
    } else {
        // Insert into users table
        $insertUser = mysqli_query($con, "INSERT INTO users(username,email,password,phone,role,registration_date,status) 
                                         VALUES('$username','$email','$password','$phone','agent',NOW(),'active')");
        if($insertUser){
            $user_id = mysqli_insert_id($con);

            // Insert into agent table
            $insertAgent = mysqli_query($con, "INSERT INTO agent(user_id,status,approved_date) 
                                              VALUES('$user_id','active',NOW())");

            if($insertAgent){
                $msg = "Agent registered successfully!";
            } else {
                $msg = "Error inserting agent data!";
            }
        } else {
            $msg = "Error inserting user data!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Agent Registration</title>
<style>
body {
    font-family: Arial, sans-serif;
    background: #f4f6f8;
    margin: 0;
    padding: 0;
}
.container {
    max-width: 400px;
    margin: 80px auto;
    background: #fff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
h2 {
    text-align: center;
    color: #007bff;
}
form {
    display: flex;
    flex-direction: column;
}
label {
    margin-bottom: 5px;
    font-weight: bold;
}
input[type="text"],
input[type="email"],
input[type="password"],
input[type="tel"] {
    padding: 10px;
    margin-bottom: 15px;
    border:1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
}
input[type="submit"] {
    padding: 12px;
    background: #007bff;
    color: #fff;
    border:none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.3s ease;
}
input[type="submit"]:hover {
    background: #0056b3;
}
.msg {
    text-align: center;
    margin-bottom: 15px;
    font-weight: bold;
    color: red;
}
.success {
    color: green;
}
</style>
</head>
<body>

<div class="container">
    <h2>Agent Registration</h2>

    <?php if($msg != '') { 
        $color = strpos($msg, 'success') !== false ? 'success' : '';
        echo "<p class='msg $color'>$msg</p>";
    } ?>

    <form method="post" action="">
        <label>Username</label>
        <input type="text" name="username" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Phone</label>
        <input type="tel" name="phone" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <input type="submit" name="register" value="Register">
    </form>
</div>

</body>
</html>
