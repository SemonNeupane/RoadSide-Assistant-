<?php
session_start();
include('../includes/dbconnection.php');

if (empty($_SESSION['admin_id']) || !isset($_GET['id'])) {
    header('location:approved-agents.php');
    exit();
}

$admin_id = $_SESSION['admin_id'];
$user_id = (int)$_GET['id'];

mysqli_query($con, "
    UPDATE agent SET 
    status='inactive',
    disabled_by_admin='$admin_id',
    disabled_remarks='Disabled by admin'
    WHERE user_id='$user_id'
");

mysqli_query($con, "UPDATE users SET status='inactive' WHERE user_id='$user_id'");

header('location:approved-agents.php');
exit();
