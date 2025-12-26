<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('../includes/dbconnection.php');

// Check if agent is logged in
if (empty($_SESSION['agent_id'])) {
    header('location:../login.php');
    exit();
}

$agent_id = $_SESSION['agent_id'];

// Fetch agent name from users table
$ret = mysqli_query($con, "SELECT username FROM users WHERE user_id='$agent_id' AND role='agent'");
$row = mysqli_fetch_assoc($ret);
$name = isset($row['username']) ? htmlspecialchars($row['username']) : 'Agent';
?>
<!-- Agent Header -->
<div class="agent-header">
    <div class="agent-header-left">
        <h3>Welcome Back, <?php echo $name; ?> 👋</h3>
    </div>
    <div class="agent-header-right">
        <div class="agent-user-dropdown" id="agentDropdown">
            <img src="../assets/images/user.png" alt="agent" class="agent-user-img">
            <span><?php echo $name; ?> ▼</span>
            <div class="agent-dropdown-menu" id="agentDropdownMenu">
                <a href="profile.php">My Profile</a>
                <a href="changepassword.php">Change Password</a>
               <a href="../logout.php">Logout</a>

            </div>
        </div>
    </div>
</div>

<style>
/* Agent Header */
.agent-header {
    position: fixed;
    top: 0;
    left: 260px; /* matches sidebar width */
    right: 0;
    height: 60px;
    background: #1f2937;
    color: #fff;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 25px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    z-index: 1000;
}

.agent-header-left h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
}

.agent-header-right {
    position: relative;
}

.agent-user-dropdown {
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 10px;
}

.agent-user-img {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    border: 2px solid #fff;
    object-fit: cover;
}

.agent-dropdown-menu {
    display: none;
    position: absolute;
    right: 0;
    top: 50px;
    background: #fff;
    color: #1f2937;
    border-radius: 8px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    min-width: 160px;
    overflow: hidden;
    z-index: 1001;
}

.agent-dropdown-menu a {
    display: block;
    padding: 10px 15px;
    text-decoration: none;
    color: #1f2937;
    font-size: 14px;
    transition: background 0.2s;
}

.agent-dropdown-menu a:hover {
    background: #e5e7eb;
}

/* Show dropdown via JS toggle */
.agent-dropdown-menu.show {
    display: block;
}

/* Main content spacing to avoid header overlap */
.main-content {
    margin-left: 260px; /* sidebar width */
    margin-top: 60px;   /* header height */
    padding: 20px;
    width: calc(100% - 260px);
}

/* Responsive */
@media (max-width: 768px) {
    .agent-header {
        left: 0;
        padding: 0 10px;
    }
    .main-content {
        margin-left: 0;
        width: 100%;
    }
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const dropdown = document.getElementById("agentDropdown");
    const menu = document.getElementById("agentDropdownMenu");

    // Toggle dropdown on click
    dropdown.addEventListener("click", function(e) {
        e.stopPropagation(); // prevent immediate close
        menu.classList.toggle("show");
    });

    // Close dropdown if clicked outside
    document.addEventListener("click", function() {
        menu.classList.remove("show");
    });
});
</script>
