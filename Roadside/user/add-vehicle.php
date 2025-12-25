<?php
session_start(); include('includes/dbconnection.php');
if(!isset($_SESSION['sid'])) header('location:../logout.php');
if(isset($_POST['submit'])){
$stmt=$con->prepare("INSERT INTO vehicle(vehicle_type,model,registration_no,user_id)VALUES(?,?,?,?)");
$stmt->bind_param("sssi",$_POST['type'],$_POST['model'],$_POST['reg'],$_SESSION['sid']);
$stmt->execute(); echo "<script>alert('Vehicle Added');window.location='manage-vehicle.php';</script>";}
?>
<html><body>
<h3>Add Vehicle</h3>
<form method="post">
Type:<input name="type"><br>
Model:<input name="model"><br>
Reg No:<input name="reg"><br>
<button name="submit">Add</button>
</form></body></html>
