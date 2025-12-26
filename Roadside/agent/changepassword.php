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

<script src="../assets/js/jquery.min.js"></script>
<script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
