<?php
session_start();

// Fixed admin credentials
$ADMIN_USERNAME = 'admin';
$ADMIN_PASSWORD = 'admin123';

$msg = '';

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($username === $ADMIN_USERNAME && $password === $ADMIN_PASSWORD) {
        $_SESSION['admin_id'] = 1; // <-- use 'admin_id' here
        header('Location: dashboard.php');
        exit();
    } else {
        $msg = "Invalid Username or Password!";
    }
}

// Already logged in → redirect to dashboard
if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit();
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>RSA | Admin Login</title>
<link rel="icon" type="image/x-icon" href="../../favicon.ico">
<style>
body {
    font-family: Arial, sans-serif;
    background: linear-gradient(135deg, #0f172a, #020617);
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}
.card {
    width: 380px;
    background: #fff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0,0,0,.3);
}
h3 {
    text-align: center;
    margin-bottom: 20px;
}
input {
    width: 100%;
    padding: 12px;
    margin-bottom: 15px;
    border-radius: 8px;
    border: 1px solid #ccc;
}
button {
    width: 100%;
    padding: 12px;
    background: #2563eb;
    color: #fff;
    border: none;
    border-radius: 8px;
    cursor: pointer;
}
button:hover { background: #1e40af; }
.error {
    color: red;
    text-align: center;
    margin-bottom: 10px;
}
</style>
</head>
<body>

<div class="card">
    <h3>RSA | Admin Login</h3>

    <?php if ($msg) { ?>
        <p class="error"><?php echo $msg; ?></p>
    <?php } ?>

    <form method="post">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
    </form>
</div>

</body>
</html>

