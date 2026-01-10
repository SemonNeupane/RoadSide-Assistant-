<?php
session_start();
include('../includes/dbconnection.php');
if(empty($_SESSION['admin_id'])) header('location:../index.php');

if(isset($_GET['id'])){
    $id = $_GET['id'];
    mysqli_query($con,"DELETE FROM users WHERE user_id='$id' AND role='agent'");
    header('location:approved-agents.php');
}
?>
