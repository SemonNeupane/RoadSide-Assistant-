<?php
session_start(); include('includes/dbconnection.php');
if (!isset($_SESSION['sid'])) header('location:../logout.php');
$id=$_GET['id'];
$stmt=$con->prepare("DELETE FROM booking WHERE booking_id=? AND user_id=?");
$stmt->bind_param("ii",$id,$_SESSION['sid']);
$stmt->execute();
echo "<script>alert('Deleted');window.location='view-booking.php';</script>";
?>
