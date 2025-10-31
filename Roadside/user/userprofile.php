<?php
session_start();
include('../admin/includes/dbconnection.php');

// Redirect if not logged in
if (!isset($_SESSION['sid']) || strlen($_SESSION['sid']) == 0) {
    header('location:logout.php');
    exit();
}

$msg = "";

if (isset($_POST['submit'])) {
    $uid = $_SESSION['sid'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Update query for 'users' table
    $query = mysqli_query($con, "UPDATE users SET username='$username', email='$email', phone='$phone' WHERE user_id='$uid'");
    if ($query) {
        $msg = "✅ Your profile has been updated successfully.";
    } else {
        $msg = "❌ Something went wrong. Please try again.";
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>User Profile | Roadside Assistance</title>

    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="../assets/css/style.css" rel="stylesheet" />
</head>

<body>
<div id="wrapper">
    <?php include_once('includes/sidebar.php'); ?>
    <div class="content-page">
        <?php include_once('includes/header.php'); ?>

        <div class="content">
            <div class="container-fluid">
                <div class="card-box">
                    <h4 class="header-title">Update Profile</h4>
                    <p style="font-size:16px; color:red; text-align:center;">
                        <?php if ($msg) echo $msg; ?>
                    </p>

                    <?php
                    $uid = $_SESSION['sid'];
                    $ret = mysqli_query($con, "SELECT * FROM users WHERE user_id='$uid'");
                    $cnt = 1;
                    while ($row = mysqli_fetch_array($ret)) {
                    ?>

                    <form method="post" class="form-horizontal">
                        <div class="form-group row">
                            <label class="col-2 col-form-label">Username</label>
                            <div class="col-10">
                                <input type="text" name="username" class="form-control" required value="<?php echo $row['username']; ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-2 col-form-label">Email</label>
                            <div class="col-10">
                                <input type="email" name="email" class="form-control" required value="<?php echo $row['email']; ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-2 col-form-label">Phone</label>
                            <div class="col-10">
                                <input type="text" name="phone" class="form-control" value="<?php echo $row['phone']; ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-2 col-form-label">Registration Date</label>
                            <div class="col-10">
                                <input type="text" class="form-control" readonly value="<?php echo $row['registration_date']; ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-12 text-center">
                                <button type="submit" name="submit" class="btn btn-info">Update</button>
                            </div>
                        </div>
                    </form>
                    <?php } ?>
                </div>
            </div>
        </div>

        <?php include_once('includes/footer.php'); ?>
    </div>
</div>

<script src="../assets/js/jquery.min.js"></script>
<script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
