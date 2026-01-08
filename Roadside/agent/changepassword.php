<?php
session_start();
include('../includes/dbconnection.php');

if (empty($_SESSION['agent_id'])) {
    header('location:../login.php');
    exit();
}

$agent_id = $_SESSION['agent_id'];
$msg = '';

if (isset($_POST['submit'])) {
    $current_password = md5($_POST['currentpassword']);
    $new_password = md5($_POST['newpassword']);

    // Check current password
    $check = mysqli_query($con, "SELECT u.user_id 
                                 FROM users u
                                 JOIN agent a ON u.user_id = a.user_id
                                 WHERE a.agent_id='$agent_id' AND u.password='$current_password'");
    if (mysqli_num_rows($check) > 0) {
        // Update password
        mysqli_query($con, "UPDATE users u 
                            JOIN agent a ON u.user_id = a.user_id
                            SET u.password='$new_password' 
                            WHERE a.agent_id='$agent_id'");
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
<style>
/* Main content spacing to match sidebar and header */
.main-content {
    margin-left: 260px; /* sidebar width */
    margin-top: 60px;   /* header height */
    padding: 30px;
    min-height: 100vh;
    background: #f3f4f6;
    font-family: Arial, Helvetica, sans-serif;
}

/* Page Title */
.main-content h3 {
    font-size: 22px;
    font-weight: 600;
    color: #203a4a;
    margin-bottom: 20px;
}

/* Form Card */
.main-content form {
    background: #fff;
    padding: 25px 30px;
    border-radius: 12px;
    box-shadow: 0 6px 15px rgba(0,0,0,0.08);
    max-width: 500px;
}

/* Form Labels */
.main-content label {
    font-weight: 500;
    color: #374151;
}

/* Form Inputs */
.main-content input.form-control {
    border-radius: 8px;
    border: 1px solid #d1d5db;
    padding: 10px 12px;
    font-size: 14px;
    margin-bottom: 15px;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.main-content input.form-control:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 2px rgb(32,58,274);
    outline: none;
}

/* Submit Button */
.main-content button.btn-primary {
    background: #2563eb;
    border: none;
    border-radius: 8px;
    padding: 10px 20px;
    font-size: 14px;
    font-weight: 500;
    transition: background 0.2s, transform 0.2s;
}

.main-content button.btn-primary:hover {
    background: #1e40af;
    transform: translateY(-2px);
}

/* Message */
.main-content p {
    font-size: 14px;
    margin-bottom: 15px;
}

/* Responsive */
@media (max-width: 768px) {
    .main-content {
        margin-left: 0;
        padding: 20px;
    }

    .main-content form {
        width: 100%;
        padding: 20px;
    }
}
</style>

    </style>
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
</head>
<body>

<?php include('includes/sidebar.php'); ?>
<?php include('includes/header.php'); ?>




<div class="main-content" style="margin-left:260px; padding:20px; margin-top:60px;">
    <h3>Change Password</h3>
    <?php if ($msg) { echo '<p style="color:red;">'.$msg.'</p>'; } ?>

    <form method="post" name="changepassword" onsubmit="return checkpass();">
        <div class="form-group">
            <label for="currentpassword">Current Password</label>
            <input type="password" class="form-control" name="currentpassword" id="currentpassword" required>
        </div>

        <div class="form-group">
            <label for="newpassword">New Password</label>
            <input type="password" class="form-control" name="newpassword" id="newpassword" required>
        </div>

        <div class="form-group">
            <label for="confirmpassword">Confirm New Password</label>
            <input type="password" class="form-control" name="confirmpassword" id="confirmpassword" required>
        </div>

        <button type="submit" name="submit" class="btn btn-primary mt-2">Change Password</button>
    </form>
</div>

</body>
</html>
