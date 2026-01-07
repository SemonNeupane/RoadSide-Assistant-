<?php
session_start();
include(__DIR__ . '/includes/dbconnection.php');

$msg = '';


if(isset($_POST['register'])){

    $username = mysqli_real_escape_string($con, $_POST['username']);
    $email    = mysqli_real_escape_string($con, $_POST['email']);
    $phone    = mysqli_real_escape_string($con, $_POST['phone']);
    $password = md5($_POST['password']); // keep md5 as per your login system
    $role     = $_POST['role']; // user OR agent
    $status   = 'active';
    $reg_date = date('Y-m-d');

    // Check duplicate email or phone
    $check = mysqli_query($con, "SELECT user_id FROM users WHERE email='$email' OR phone='$phone'");
    if(mysqli_num_rows($check) > 0){
        $msg = "Email or phone already registered!";
    } else {

        // Insert into users table
        $insertUser = mysqli_query($con, "
            INSERT INTO users (username,email,password,phone,role,registration_date,status)
            VALUES ('$username','$email','$password','$phone','$role','$reg_date','$status')
        ");

        if($insertUser){

            $user_id = mysqli_insert_id($con);

            // If role is AGENT → insert into agent table
            if($role === 'agent'){
                mysqli_query($con, "
                    INSERT INTO agent (user_id,status,approved_date,disabled_remarks)
                    VALUES ('$user_id','active',NOW(),'')
                ");
            }

            $msg = "Registration successful! <a href='login.php'>Login here</a>";
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
<title>RSA Registration</title>

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
input[type="tel"],
select {
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
    <h2>Register</h2>

    <?php 
    if($msg != ''){ 
        $class = (strpos($msg, 'successful') !== false) ? 'success' : '';
        echo "<p class='msg $class'>$msg</p>";
    } 
    ?>

    <form method="post">

        <label>Username</label>
        <input type="text" name="username" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Phone</label>
        <input type="tel" name="phone" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <!-- ROLE SELECTION -->
        <label>Register As</label>
        <select name="role" required>
            <option value="">Select Role</option>
            <option value="user">User</option>
            <option value="agent">Agent</option>
        </select>

        <input type="submit" name="register" value="Register">

    </form>
</div>

</body>
</html>
