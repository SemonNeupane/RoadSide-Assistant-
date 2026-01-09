<?php
// Start session safely
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('../includes/dbconnection.php');

// USER AUTH CHECK
if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header('location:../login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$msg = "";

// HANDLE UPDATE
if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $email    = mysqli_real_escape_string($con, $_POST['email']);
    $phone    = mysqli_real_escape_string($con, $_POST['phone']);

    $update = mysqli_query($con, "
        UPDATE users 
        SET username='$username', email='$email', phone='$phone'
        WHERE user_id='$user_id'
    ");

    if ($update) {
        $msg = "Profile updated successfully.";
    } else {
        $msg = "Something went wrong. Please try again.";
    }
}

// FETCH USER DATA
$ret = mysqli_query($con, "
    SELECT username, email, phone 
    FROM users 
    WHERE user_id='$user_id' AND role='user'
");
$user = mysqli_fetch_assoc($ret);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>User Profile</title>
<link rel="icon" type="image/x-icon" href="../../favicon.ico">
<style>
/* ===== CONTENT ===== */
.content {
    margin-left: 260px;
    margin-top: 60px;
    padding: 25px;
    background: #f3f4f6;
    min-height: 100vh;
}

/* ===== CARD ===== */
.profile-card {
    background: #ffffff;
    padding: 30px;
    border-radius: 14px;
    max-width: 600px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
}

/* TITLE */
.profile-card h3 {
    margin-bottom: 20px;
    font-size: 20px;
    font-weight: 600;
    color: #0f172a;
}

/* MESSAGE */
.msg {
    margin-bottom: 15px;
    font-size: 14px;
    color: green;
}

/* FORM */
.form-group {
    margin-bottom: 18px;
}

.form-group label {
    display: block;
    font-size: 14px;
    font-weight: 500;
    color: #334155;
    margin-bottom: 6px;
}

.form-control {
    width: 100%;
    padding: 11px 12px;
    border-radius: 8px;
    border: 1px solid #cbd5e1;
    font-size: 14px;
}

.form-control:focus {
    outline: none;
    border-color: #38bdf8;
}

/* BUTTON */
.btn-update {
    padding: 10px 22px;
    background: linear-gradient(135deg, #2563eb, #38bdf8);
    color: #fff;
    font-size: 14px;
    font-weight: 500;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    transition: 0.3s;
}

.btn-update:hover {
    background: linear-gradient(135deg, #1e40af, #0284c7);
}
footer {
    margin-left: 260px; /* align with sidebar */
    width: calc(100% - 260px);
    background: #1e293b;
    color: #fff;
    padding: 15px 20px;
    text-align: center;
    font-size: 13px;
    position: relative;
}
/* RESPONSIVE */
@media (max-width: 991px) {
    .content {
        margin-left: 0;
    }
}
</style>

</head>
<body>

<!-- SIDEBAR -->
<?php include('includes/sidebar.php'); ?>

<!-- HEADER -->
<?php include('includes/header.php'); ?>

<!-- CONTENT -->
<div class="content">
    <div class="profile-card">
        <h3>My Profile</h3>

        <?php if($msg){ echo "<p class='msg'>$msg</p>"; } ?>

        <form method="post">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="username" class="form-control"
                       value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control"
                       value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>

            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" class="form-control"
                       value="<?php echo htmlspecialchars($user['phone']); ?>" required>
            </div>

            <button type="submit" name="submit" class="btn-update">
                Update Profile
            </button>
        </form>
    </div>
</div>
<?php include('includes/footer.php'); ?>

</body>
</html>
