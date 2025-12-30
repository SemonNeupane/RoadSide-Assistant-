<?php
// Start session only if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('../includes/dbconnection.php');

// Read admin id safely (NO redirect here)
$admin_id = $_SESSION['admin_id'] ?? null;

// Fetch admin name
if ($admin_id) {
    $ret = mysqli_query(
        $con,
        "SELECT username FROM users WHERE user_id='$admin_id' AND role='admin'"
    );
    $row = mysqli_fetch_assoc($ret);
    $name = $row['username'] ?? 'Admin';
} else {
    $name = 'Admin';
}
?>

<!-- Admin Header -->
<div class="admin-header">
    <div class="admin-header-left">
        <h3>Welcome Back, <?php echo htmlspecialchars($name); ?> 👋</h3>
    </div>

    <div class="admin-header-right">
        <div class="admin-user-dropdown" id="adminDropdown">
            <img src="../assets/images/user.png" alt="admin" class="admin-user-img">
            <span><?php echo htmlspecialchars($name); ?> ▼</span>

            <div class="admin-dropdown-menu" id="adminDropdownMenu">
                <a href="profile.php">My Profile</a>
                <a href="changepassword.php">Change Password</a>
                <a href="../logout.php">Logout</a>
            </div>
        </div>
    </div>
</div>
<style>
.admin-header {
    position: fixed;
    top: 0;
    left: 260px; /* sidebar width */
    right: 0;
    height: 60px;
    background: #0f172a;
    color: #fff;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 25px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.3);
    z-index: 1000;
}

.admin-header-left h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
}

.admin-header-right {
    position: relative;
}

.admin-user-dropdown {
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 10px;
}

.admin-user-img {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: 2px solid #38bdf8;
    object-fit: cover;
}

.admin-dropdown-menu {
    display: none;
    position: absolute;
    right: 0;
    top: 52px;
    background: #fff;
    color: #1f2937;
    border-radius: 8px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    min-width: 170px;
    overflow: hidden;
    z-index: 1001;
}

.admin-dropdown-menu a {
    display: block;
    padding: 11px 15px;
    text-decoration: none;
    color: #1f2937;
    font-size: 14px;
}

.admin-dropdown-menu a:hover {
    background: #e5e7eb;
}

/* Toggle */
.admin-dropdown-menu.show {
    display: block;
}

/* Content spacing */
.main-content {
    margin-left: 260px;
    margin-top: 60px;
    padding: 20px;
}

/* Mobile */
@media (max-width: 768px) {
    .admin-header {
        left: 0;
    }
    .main-content {
        margin-left: 0;
    }
}
</style>
