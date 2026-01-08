<?php
session_start();
include(__DIR__ . '/../includes/dbconnection.php');

if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header('location:../login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$msg = '';

if (isset($_POST['submit'])) {
    $current_password = md5($_POST['currentpassword']); // current password in DB is MD5
    $new_password     = md5($_POST['newpassword']);

    // Check current password
    $check = mysqli_query($con, "
        SELECT user_id 
        FROM users 
        WHERE user_id='$user_id' 
        AND password='$current_password'
        AND role='user'
        LIMIT 1
    ");

    if (mysqli_num_rows($check) > 0) {
        // Update password
        mysqli_query($con, "
            UPDATE users 
            SET password='$new_password' 
            WHERE user_id='$user_id'
        ");
        $msg = "Your password has been successfully changed.";
    } else {
        $msg = "Your current password is incorrect.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Change Password | RSA Nepal</title>
<link href="../assets/css/bootstrap.min.css" rel="stylesheet">
<link href="../assets/css/style.css" rel="stylesheet">

<script type="text/javascript">
function checkpass() {
    if (document.changepassword.newpassword.value != document.changepassword.confirmpassword.value) {
        alert('New Password and Confirm Password do not match');
        document.changepassword.confirmpassword.focus();
        return false;
    }
    return true;
}
</script>
<style>
    :root {
    --sidebar-width: 260px; /* match your sidebar */
    --header-height: 60px;  /* match your header */
}

/* ===== MAIN CONTENT ===== */
.content {
    margin-left: var(--sidebar-width);
    margin-top: var(--header-height);
    padding: 25px;
    min-height: 100vh;
    background: #f3f4f6; /* light background consistent with dashboard */
}

/* ===== CARD BOX ===== */
.card {
    background: #ffffff;
    padding: 25px 30px;
    border-radius: 14px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}

/* CARD TITLE */
.card h3 {
    font-size: 20px;
    font-weight: 600;
    color: #0f172a;
    margin-bottom: 20px;
}

/* ===== FORM ELEMENTS ===== */
.form-group {
    margin-bottom: 18px;
}

.form-group label {
    font-weight: 500;
    color: #334155;
    margin-bottom: 6px;
    display: block;
}

.form-control {
    width: 100%;
    padding: 10px 12px;
    font-size: 14px;
    border-radius: 10px;
    border: 1px solid #d1d5db;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #38bdf8;
    box-shadow: 0 0 5px rgba(56, 189, 248, 0.4);
    outline: none;
}

/* ===== BUTTON ===== */
.btn-primary {
    background: #38bdf8;
    color: #ffffff;
    padding: 10px 20px;
    font-size: 14px;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: #0ea5e9;
}

/* ===== MESSAGES ===== */
p[style*="color:red"] {
    font-weight: 500;
    color: #dc2626 !important; /* red alert color */
    margin-bottom: 15px;
}
footer {
    width: calc(100% - 260px);
    margin-left: 260px; /* align with sidebar */
    background: #1e293b;
    color: #fff;
    padding: 15px 20px;
    text-align: center;
    font-size: 13px;
    position: relative;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 991px) {
    .content {
        margin-left: 0;
        padding: 15px;
    }

    .card {
        padding: 20px;
    }
}

    </style>
</head>
<body>

<?php include('includes/sidebar.php'); ?>
<?php include('includes/header.php'); ?>

<div class="content">
    <div class="card">
        <div class="card-body">
            <h3 class="mb-3">Change Password</h3>
            <?php if ($msg) { echo '<p style="color:red;">'.$msg.'</p>'; } ?>

            <form method="post" name="changepassword" onsubmit="return checkpass();">
                <div class="form-group mb-2">
                    <label for="currentpassword">Current Password</label>
                    <input type="password" class="form-control" name="currentpassword" id="currentpassword" required>
                </div>

                <div class="form-group mb-2">
                    <label for="newpassword">New Password</label>
                    <input type="password" class="form-control" name="newpassword" id="newpassword" required>
                </div>

                <div class="form-group mb-2">
                    <label for="confirmpassword">Confirm New Password</label>
                    <input type="password" class="form-control" name="confirmpassword" id="confirmpassword" required>
                </div>

                <button type="submit" name="submit" class="btn btn-primary mt-2">Change Password</button>
            </form>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
</body>
</html>
