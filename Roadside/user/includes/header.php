<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include(__DIR__ . '/../../includes/dbconnection.php');

// Check if USER is logged in
if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header('location:../login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch username
$ret = mysqli_query($con, "
    SELECT username 
    FROM users 
    WHERE user_id='$user_id' AND role='user' 
    LIMIT 1
");
$row = mysqli_fetch_assoc($ret);
$name = $row ? htmlspecialchars($row['username']) : 'User';
?>

<div class="user-header">
    <div class="user-header-left">
        <h3>Welcome Back, <?php echo $name; ?> 👋</h3>
    </div>

    <div class="user-header-right">
        <div class="user-dropdown" id="userDropdown">
            <img src="../assets/images/user.png" alt="user" class="user-img">
            <span><?php echo $name; ?> ▾</span>

            <div class="user-dropdown-menu" id="userDropdownMenu">
                <a href="profile.php">My Profile</a>
                <a href="changepassword.php">Change Password</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </div>
</div>
<style>
    :root {
    --sidebar-width: 260px;
    --header-height: 60px;
}

/* USER HEADER */
.user-header {
    position: fixed;
    top: 0;
    left: var(--sidebar-width);
    right: 0;
    height: var(--header-height);
    background: #1f2937;
    color: #ffffff;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 25px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.25);
    z-index: 1000;
}

/* LEFT TEXT */
.user-header-left h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
}

/* RIGHT DROPDOWN */
.user-header-right {
    position: relative;
}

.user-dropdown {
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
}

.user-img {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: 2px solid #1f2937;
    object-fit: cover;
}

/* DROPDOWN MENU */
.user-dropdown-menu {
    display: none;
    position: absolute;
    right: 0;
    top: 52px;
    background: #ffffff;
    color: #1f2937;
    border-radius: 10px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.2);
    min-width: 170px;
    overflow: hidden;
}

.user-dropdown-menu a {
    display: block;
    padding: 12px 16px;
    font-size: 14px;
    color: #1f2937;
    text-decoration: none;
    transition: background 0.25s;
}

.user-dropdown-menu a:hover {
    background: #f1f5f9;
}

/* SHOW MENU */
.user-dropdown-menu.show {
    display: block;
}
.content {
    margin-left: var(--sidebar-width);
    margin-top: var(--header-height);
    padding: 20px;
    width: calc(100% - var(--sidebar-width));
    background: #f3f4f6;
    min-height: 100vh;
}


    </style>
    <script>
document.addEventListener("DOMContentLoaded", function () {
    const dropdown = document.getElementById("userDropdown");
    const menu = document.getElementById("userDropdownMenu");

    dropdown.addEventListener("click", function (e) {
        e.stopPropagation();
        menu.classList.toggle("show");
    });

    document.addEventListener("click", function () {
        menu.classList.remove("show");
    });
});
</script>
