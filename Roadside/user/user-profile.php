<?php
session_start();
include('../admin/includes/dbconnection.php');

// Check if user is logged in
if(empty($_SESSION['sid'])){
    header('location:logout.php');
    exit();
}
$uid = $_SESSION['sid'];
$msg = "";

// Update profile
if(isset($_POST['submit'])){
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);

    $update = mysqli_query($con, "UPDATE users SET username='$username', phone='$phone' WHERE user_id='$uid'");
    if($update){
        $msg = "Profile updated successfully.";
    } else {
        $msg = "Something went wrong. Try again.";
    }
}

// Fetch user data
$userQuery = mysqli_query($con, "SELECT * FROM users WHERE user_id='$uid'");
$user = mysqli_fetch_assoc($userQuery);
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>RSA Nepal | User Profile</title>
<style>
/* ====== General ====== */
body{margin:0;font-family:'Segoe UI',sans-serif;background:#f8fafc;color:#334155;}
#wrapper{display:flex;}
.content-page{flex:1;padding:25px;margin-left:260px;}
.card-box{background:#fff;border-radius:15px;padding:25px;margin-bottom:20px;box-shadow:0 2px 10px rgba(0,0,0,0.05);}
.header-title{font-size:22px;font-weight:600;margin-bottom:20px;}
.form-group{margin-bottom:15px;}
.form-group label{display:block;font-weight:500;margin-bottom:5px;}
.form-control{width:100%;padding:10px 12px;border:1px solid #ced4da;border-radius:5px;font-size:1rem;}
.form-control:focus{border-color:#007bff;outline:none;box-shadow:0 0 5px rgba(0,123,255,0.3);}
.btn-primary{background:#007bff;color:#fff;border:none;padding:10px 25px;border-radius:5px;cursor:pointer;}
.btn-primary:hover{background:#0056b3;}
.alert{padding:10px 15px;border-radius:5px;margin-bottom:20px;font-weight:500;text-align:center;}
.alert-info{background:#d1ecf1;color:#0c5460;border:1px solid #bee5eb;}
@media(max-width:768px){.content-page{margin-left:0;}}
</style>
</head>
<body>

<div id="wrapper">
<?php include('includes/sidebar.php'); ?>
<div class="content-page">
<?php include('includes/header.php'); ?>

<div class="card-box">
<h4 class="header-title">Update Profile</h4>

<?php if($msg){ ?>
    <div class="alert alert-info"><?php echo $msg; ?></div>
<?php } ?>

<form method="post">

<!-- Username -->
<div class="form-group">
    <label>Username</label>
    <input type="text" name="username" class="form-control" required value="<?php echo htmlspecialchars($user['username']); ?>">
</div>

<!-- Phone -->
<div class="form-group">
    <label>Phone Number</label>
    <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($user['phone']); ?>">
</div>

<!-- Email -->
<div class="form-group">
    <label>Email (Readonly)</label>
    <input type="email" class="form-control" readonly value="<?php echo htmlspecialchars($user['email']); ?>">
</div>

<!-- Registration Date -->
<div class="form-group">
    <label>Registered On</label>
    <input type="text" class="form-control" readonly value="<?php echo $user['registration_date']; ?>">
</div>

<!-- Submit -->
<div class="form-group text-center">
    <button type="submit" name="submit" class="btn-primary">Update Profile</button>
</div>

</form>
</div>

</div>
</div>

</body>
</html>
