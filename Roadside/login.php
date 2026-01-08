<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include(__DIR__ . '/includes/dbconnection.php');

$msg = '';

if (isset($_POST['login'])) {

    $emailOrPhone = mysqli_real_escape_string($con, $_POST['emailcont']);
    $password = md5($_POST['password']); // MD5 as per your system

    $query = mysqli_query($con, "
        SELECT * FROM users 
        WHERE (email='$emailOrPhone' OR phone='$emailOrPhone') 
        LIMIT 1
    ");

    if ($query && mysqli_num_rows($query) === 1) {

        $user = mysqli_fetch_assoc($query);

        if ($user['password'] === $password) {

            if ($user['status'] !== 'active') {
                $msg = "Your account is inactive. Please contact support.";
            } else {

                // Common sessions
                $_SESSION['user_id']  = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role']     = $user['role'];

                // USER
                if ($user['role'] === 'user') {
                    header("Location: user/dashboard.php");
                    exit();
                }

                // AGENT
                if ($user['role'] === 'agent') {

                    $agentQuery = mysqli_query($con, "
                        SELECT agent_id 
                        FROM agent 
                        WHERE user_id='{$user['user_id']}' 
                        AND status='active'
                        LIMIT 1
                    ");

                    if (mysqli_num_rows($agentQuery) === 1) {
                        $agent = mysqli_fetch_assoc($agentQuery);
                        $_SESSION['agent_id'] = $agent['agent_id']; // 🔥 REQUIRED
                        header("Location: agent/dashboard.php");
                        exit();
                    } else {
                        $msg = "Your agent account is not approved yet.";
                    }
                }

                // ADMIN
                if ($user['role'] === 'admin') {
                    header("Location: admin/dashboard.php");
                    exit();
                }
            }

        } else {
            $msg = "Invalid email/phone or password.";
        }

    } else {
        $msg = "Invalid email/phone or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>RSA Nepal | Login</title>
 <link rel="icon" type="image/x-icon" href="../favicon.ico">

    <link href="css/style.css" rel="stylesheet" />
<style>
body{
    margin:0;
    font-family: Arial, sans-serif;
    background:linear-gradient(135deg, #0f172a, #020617);;
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}

.login-box{
    width: 500px;
    background:#fff;
    padding:30px;
    border-radius:12px;
    box-shadow:0 10px 25px rgba(0,0,0,0.15);
}

.login-box h2{
    text-align:center;
    margin-bottom:20px;
    color:#1f2937;
}

.input-group{
    margin-bottom:15px;
}

.input-group label{
    font-size:14px;
    color:#374151;
}

.input-group input{
    width:100%;
    padding:10px;
    margin-top:5px;
    border-radius:8px;
    border:1px solid #d1d5db;
    outline:none;
}

.input-group input:focus{
    border-color:#2563eb;
}

.btn{
    width:100%;
    padding:12px;
    background:#2563eb;
    color:#fff;
    border:none;
    border-radius:8px;
    font-size:16px;
    cursor:pointer;
}

.btn:hover{
    background:#1d4ed8;
}

.error{
    background:#fee2e2;
    color:#991b1b;
    padding:10px;
    margin-bottom:15px;
    border-radius:6px;
    text-align:center;
    font-size:14px;
}
</style>
</head>

<body>

<div class="login-box">
    <h2>Login</h2>

    <?php if ($msg != '') { ?>
        <div class="error"><?php echo $msg; ?></div>
    <?php } ?>

    <form method="POST">
        <div class="input-group">
            <label>Email or Phone</label>
            <input type="text" name="emailcont" required>
        </div>

        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>

        <button type="submit" name="login" class="btn">Login</button>
    </form>
</div>

</body>
</html>