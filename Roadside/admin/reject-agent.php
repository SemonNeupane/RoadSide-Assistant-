<?php
session_start();
include('../includes/dbconnection.php');

if (empty($_SESSION['admin_id']) || !isset($_GET['id'])) {
    header('location:pending-agents.php');
    exit();
}

$admin_id = $_SESSION['admin_id'];
$user_id = (int)$_GET['id'];

// Delete from users table or set status inactive
mysqli_query($con, "UPDATE agent SET status='inactive', disabled_by_admin='$admin_id', disabled_remarks='Rejected by admin' WHERE user_id='$user_id'");
mysqli_query($con, "UPDATE users SET status='inactive' WHERE user_id='$user_id'");

header('location:pending-agents.php');
exit();
