<?php
session_start();
include('../includes/dbconnection.php');

if (empty($_SESSION['agent_id'])) {
    header('location:../login.php');
    exit();
}

$agent_id = $_SESSION['agent_id'];
$msg = "";

// UPDATE PROFILE
if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $email    = mysqli_real_escape_string($con, $_POST['email']);

    $update = mysqli_query($con, "
        UPDATE users u 
        JOIN agent a ON u.user_id = a.user_id
        SET u.username='$username', u.email='$email'
        WHERE a.agent_id='$agent_id'
    ");

    if ($update) {
        $msg = "Your profile has been updated.";
    } else {
        $msg = "Something went wrong. Please try again.";
    }
}

// FETCH AGENT DATA
$ret = mysqli_query($con, "
    SELECT u.username, u.email
    FROM users u
    JOIN agent a ON u.user_id = a.user_id
    WHERE a.agent_id='$agent_id'
");
$agent = mysqli_fetch_assoc($ret);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Agent Profile</title>
<link rel="icon" type="image/x-icon" href="../../favicon.ico">
<style>
/* ===== MAIN CONTENT ===== */
.main-content {
    margin-left: 260px; /* sidebar width */
    margin-top: 60px;   /* header height */
    padding: 25px;
    background: #f4f6f9;
    min-height: 100vh;
}

/* ===== PROFILE CARD ===== */
.profile-card {
    max-width: 520px;
    background: #ffffff;
    padding: 30px;
    border-radius: 14px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}

/* TITLE */
.profile-card h3 {
    font-size: 20px;
    margin-bottom: 20px;
    color: #0f172a;
}

/* MESSAGE */
.msg {
    margin-bottom: 15px;
    color: #16a34a;
    font-size: 14px;
}

/* FORM */
.form-group {
    margin-bottom: 18px;
}

.form-group label {
    display: block;
    font-size: 14px;
    font-weight: 500;
    margin-bottom: 6px;
    color: #334155;
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
    border-color: #2563eb;
}

/* BUTTON */
.btn-update {
    padding: 10px 22px;
    background: linear-gradient(135deg, #2563eb, #38bdf8);
    border: none;
    border-radius: 10px;
    color: #fff;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: 0.3s;
}

.btn-update:hover {
    background: linear-gradient(135deg, #1e40af, #0284c7);
}

/* RESPONSIVE */
@media (max-width: 991px) {
    .main-content {
        margin-left: 0;
    }
}
</style>
</head>

<body>

<?php include('includes/sidebar.php'); ?>
<?php include('includes/header.php'); ?>

<div class="main-content">
    <div class="profile-card">
        <h3>My Profile</h3>

        <?php if ($msg) { echo "<p class='msg'>$msg</p>"; } ?>

        <form method="post">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="username" class="form-control"
                       value="<?php echo htmlspecialchars($agent['username']); ?>" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control"
                       value="<?php echo htmlspecialchars($agent['email']); ?>" required>
            </div>

            <button type="submit" name="submit" class="btn-update">
                Update Profile
            </button>
        </form>
    </div>
</div>

</body>
</html>
