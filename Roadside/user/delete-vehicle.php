<?php
session_start(); include('includes/dbconnection.php');
if(!isset($_SESSION['sid'])) header('location:../logout.php');
$id=$_GET['id'];
$stmt=$con->prepare("DELETE FROM vehicle WHERE vehicle_id=? AND user_id=?");
$stmt->bind_param("ii",$id,$_SESSION['sid']); $stmt->execute();
echo "<script>alert('Deleted');window.location='manage-vehicle.php';</script>";
?>
