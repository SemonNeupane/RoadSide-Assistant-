<?php
session_start();
include('../includes/dbconnection.php');

if (empty($_SESSION['agent_id'])) {
    header('location:../login.php');
    exit();
}

$agent_id = $_SESSION['agent_id'];
$msg = '';

// Handle form submission
if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];

    $update = mysqli_query($con, "UPDATE users u 
                                  JOIN agent a ON u.user_id = a.user_id
                                  SET u.username='$username', u.email='$email'
                                  WHERE a.agent_id='$agent_id'");
    if ($update) {
        $msg = "Your profile has been updated.";
    } else {
        $msg = "Something went wrong. Please try again.";
    }
}

// Fetch agent details
$ret = mysqli_query($con, "SELECT u.username, u.email
                           FROM users u
                           JOIN agent a ON u.user_id = a.user_id
                           WHERE a.agent_id='$agent_id'");
$agent = mysqli_fetch_assoc($ret);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Agent Profile | RSA Nepal</title>
<link href="../assets/css/bootstrap.min.css" rel="stylesheet">
<link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>

<?php include('includes/sidebar.php'); ?>

<?php include('includes/header.php'); ?>

<div class="main-content" style="margin-left:260px; padding:20px; margin-top:60px;">
    <h3>Update Profile</h3>
    <?php if($msg){ echo '<p style="color:green;">'.$msg.'</p>'; } ?>
    <form method="post">
        <div class="form-group">
            <label for="username">Full Name</label>
            <input type="text" class="form-control" id="username" name="username" required value="<?php echo htmlspecialchars($agent['username']); ?>">
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" required value="<?php echo htmlspecialchars($agent['email']); ?>">
        </div>
        <button type="submit" name="submit" class="btn btn-primary mt-2">Update</button>
    </form>
</div>

<script src="../assets/js/jquery.min.js"></script>
<script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>

