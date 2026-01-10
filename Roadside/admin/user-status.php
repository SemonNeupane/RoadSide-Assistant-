<?php
session_start();
include('../includes/dbconnection.php');

if (empty($_SESSION['admin_id'])) {
    header('location:/index.php');
    exit();
}

$id = (int)$_GET['id'];
$status = $_GET['status'] === 'active' ? 'inactive' : 'active';

mysqli_query($con, "
    UPDATE users 
    SET status='$status'
    WHERE user_id='$id'
");

header('location:users.php');
exit();
