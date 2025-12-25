<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include(__DIR__ . '/includes/dbconnection.php');

$msg = '';

if (isset($_POST['login'])) {
    $emailOrPhone = mysqli_real_escape_string($con, $_POST['emailcont']);
    $password = md5($_POST['password']); // assuming passwords are stored as md5

    // Fetch user by email or phone
    $query = mysqli_query($con, "SELECT * FROM users WHERE email='$emailOrPhone' OR phone='$emailOrPhone'");

    if ($query) {
        $user = mysqli_fetch_assoc($query);

        if ($user && $user['password'] === $password) {
            if ($user['status'] != 'active') {
                $msg = "Your account is inactive. Please contact support.";
            } else {
                // Set session variables
                $_SESSION['sid'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // Role-based redirection
                switch ($user['role']) {
                    case 'user':
                        header('Location: user/dashboard.php');
                        exit();

                    case 'agent':
                        // Check if agent exists and is active
                        $agentQuery = mysqli_query($con, "SELECT * FROM agent WHERE user_id='{$user['user_id']}' AND status='active'");
                        if (mysqli_num_rows($agentQuery) > 0) {
                            $agentRow = mysqli_fetch_assoc($agentQuery);
                            $_SESSION['agent_id'] = $agentRow['agent_id']; // store agent_id in session
                            header('Location: agent/dashboard.php'); // corrected path
                            exit();
                        } else {
                            $msg = "Your agent account is not approved yet.";
                        }
                        break;

                    case 'admin':
                        header('Location: admin/dashboard.php');
                        exit();

                    default:
                        $msg = "Invalid role assigned. Contact admin.";
                }
            }
        } else {
            $msg = "Invalid email/phone or password.";
        }
    } else {
        $msg = "Database error: " . mysqli_error($con);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>RSA Nepal | Login</title>
<link href="assets/css/style.css" rel="stylesheet">
<style>
/* Simple login page styles */
body {
    font-family: Arial,sans-serif;
    background:#f2f4f7;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
    margin:0;
}
.wrapper-page {
    width:100%;
    max-width:400px;
    padding:20px;
}
.card {
    background:#fff;
    padding:30px;
    border-radius:12px;
    box-shadow:0 8px 20px rgba(0,0,0,0.1);
}
.card h3 {
    text-align:center;
    margin-bottom:20px;
    font-size:24px;
}
.form-control {
    width:100%;
    padding:10px 15px;
    margin-bottom:15px;
    border-radius:8px;
    border:1px solid #ccc;
}
.form-control:focus {
    border-color:#007bff;
    outline:none;
}
.btn-custom {
    width:100%;
    padding:12px;
    background:#007bff;
    color:#fff;
    border:none;
    border-radius:8px;
    cursor:pointer;
}
.btn-custom:hover { background:#0056b3; }
p.error {color:red;text-align:center;font-weight:bold;margin-bottom:15px;}
p.signup-links {text-align:center;margin-top:15px;}
p.signup-links a {color:#007bff;text-decoration:none;}
p.signup-links a:hover {text-decoration:underline;}
</style>
</head>
<body>
<div class="wrapper-page">
    <div class="card">
        <h3>RSA Nepal | Login</h3>
        <?php if($msg != ''): ?>
            <p class="error"><?php echo $msg; ?></p>
        <?php endif; ?>
        <form action="" method="post">
            <label>Email or Phone</label>
            <input type="text" name="emailcont" class="form-control" placeholder="Email or Phone" required>
            <label>Password</label>
            <input type="password" name="password" class="form-control" placeholder="Password" required>
            <button type="submit" name="login" class="btn-custom">Login</button>
        </form>
        <p class="signup-links">
            Don't have an account? <a href="user/user-register.php">User Signup</a> | 
            <a href="agent/agent-register.php">Agent Signup</a>
        </p>
    </div>
</div>
</body>
</html>
