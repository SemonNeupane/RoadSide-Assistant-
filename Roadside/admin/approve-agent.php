<?php
session_start();
include('../includes/dbconnection.php');

if (empty($_SESSION['admin_id']) || !isset($_GET['id'])) {
    header('location:pending-agents.php');
    exit();
}

$admin_id = (int) $_SESSION['admin_id'];
$user_id  = (int) $_GET['id'];
$approved_date = date('Y-m-d H:i:s');

// 1️⃣ Activate user
mysqli_query($con, "
    UPDATE users 
    SET status = 'active'
    WHERE user_id = '$user_id'
");

// 2️⃣ Activate agent
mysqli_query($con, "
    UPDATE agent SET
        status = 'active',
        approved_by_admin = '$admin_id',
        approved_date = '$approved_date'
    WHERE user_id = '$user_id'
");

header('location:pending-agents.php');
exit();
