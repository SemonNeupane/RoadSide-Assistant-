<?php
session_start();

// --------------------------
// FIXED ADMIN CREDENTIALS
// --------------------------
$ADMIN_USERNAME = 'admin';
$ADMIN_PASSWORD = 'admin123'; // Change this as you like

$msg = '';

// --------------------------
// LOGIN PROCESS
// --------------------------
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === $ADMIN_USERNAME && $password === $ADMIN_PASSWORD) {
        $_SESSION['adid'] = 1; // fixed admin session ID
        header('Location: dashboard.php');
        exit();
    } else {
        $msg = "Invalid Details.";
    }
}

// --------------------------
// REDIRECT IF ALREADY LOGGED IN
// --------------------------
// if (isset($_SESSION['adid'])) {
//     header('Location: index.php');
//     exit();
// }
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>VSMS | Admin Login</title>
    <style>
        /* ---------------------------- */
/* Admin Login Page CSS          */
/* ---------------------------- */

/* Background */
.accountbg {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: url('../assets/images/bg-1.jpg') no-repeat center center;
    background-size: cover;
    z-index: -1;
}

/* Wrapper */
.wrapper-page {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

/* Card */
.card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
    background: rgba(15, 23, 42, 0.9); /* dark blue */
    color: #fff;
}

/* Card box padding */
.card-box {
    padding: 40px 30px;
}

/* Title */
.account-box h3 {
    font-size: 24px;
    font-weight: 700;
    color: #38bdf8; /* sky blue */
    margin-bottom: 15px;
}

/* Input fields */
.form-control {
    border-radius: 10px;
    border: 1px solid #94a3b8;
    background: rgba(255, 255, 255, 0.05);
    color: #fff;
    padding: 10px 15px;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #2563eb; /* blue highlight */
    box-shadow: 0 0 5px rgba(37, 99, 235, 0.5);
    background: rgba(255, 255, 255, 0.1);
    color: #fff;
}

/* Labels */
label {
    color: #cbd5e1;
    font-weight: 500;
}

/* Buttons */
.btn-custom {
    background: linear-gradient(135deg, #2563eb, #1e40af);
    border: none;
    color: #fff;
    font-weight: 600;
    border-radius: 10px;
    padding: 10px 0;
    transition: all 0.3s ease;
}

.btn-custom:hover {
    background: linear-gradient(135deg, #1e40af, #2563eb);
    transform: translateY(-2px);
}

/* Links */
a {
    color: #38bdf8;
    text-decoration: none;
}

a:hover {
    color: #1e40af;
}

/* Error Message */
p[style*="color:red"] {
    font-weight: 500;
    margin-bottom: 15px;
}

/* Footer text */
.m-t-40 {
    margin-top: 40px;
    color: #94a3b8;
    font-size: 14px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .card-box {
        padding: 25px 20px;
    }
}

        </style>
</head>
<body class="account-pages">

<div class="accountbg" style="background: url('../assets/images/bg-1.jpg');background-size: cover;background-position: center;"></div>

<div class="wrapper-page account-page-full">

    <div class="card">
        <div class="card-block">
            <div class="account-box">
                <div class="card-box p-5">
                    <h3 class="text-uppercase text-center">
                        <span>VSMS | Admin Login</span>
                    </h3>
                    <hr color="#000"/>
                    <p style="font-size:16px; color:red" align="center">
                        <?php if($msg){echo $msg;} ?>
                    </p>

                    <form action="#" method="post">

                        <div class="form-group m-b-20 row">
                            <div class="col-12">
                                <label for="username">User Name</label>
                                <input class="form-control" type="text" id="username" name="username" required placeholder="User Name">
                            </div>
                        </div>

                        <div class="form-group row m-b-20">
                            <div class="col-12">
                                <label for="password">Password</label>
                                <input class="form-control" type="password" id="password" name="password" required placeholder="Enter your password">
                            </div>
                        </div>

                        <div class="form-group row text-center m-t-10">
                            <div class="col-12">
                                <button class="btn btn-block btn-custom waves-effect waves-light" type="submit" name="login">Sign In</button>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

    <div class="m-t-40 text-center">
        <p class="account-copyright"><?php echo date('Y'); ?> © Vehicle Service Management System</p>
    </div>

</div>

<script src="../assets/js/jquery.min.js"></script>
<script src="../assets/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/metisMenu.min.js"></script>
<script src="../assets/js/waves.js"></script>
<script src="../assets/js/jquery.slimscroll.js"></script>
<script src="../assets/js/jquery.core.js"></script>
<script src="../assets/js/jquery.app.js"></script>

</body>
</html>
