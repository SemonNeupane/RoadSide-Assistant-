<?php
session_start();
include('../includes/dbconnection.php');

if (empty($_SESSION['admin_id'])) {
    header('location:/index.php');
    exit();
}

$id = (int)$_GET['id'];

mysqli_query($con, "DELETE FROM users WHERE user_id='$id'");

header('location:users.php');
exit();
