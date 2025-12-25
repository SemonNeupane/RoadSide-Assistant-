<?php
session_start(); include('../includes/dbconnection.php');
if(!isset($_SESSION['sid'])) header('location:../logout.php');
if(isset($_POST['submit'])){
$stmt=$con->prepare("INSERT INTO feedback(booking_id,user_id,rating,comments,created_at)
VALUES(?,?,?,?,CURDATE())");
$stmt->bind_param("iiis",$_POST['booking_id'],$_SESSION['sid'],$_POST['rating'],$_POST['comments']);
$stmt->execute(); echo "<script>alert('Feedback submitted');window.location='dashboard.php';</script>";}
?>
<html><body><h3>Give Feedback</h3>
<form method="post">
Booking ID:<input name="booking_id"><br>
Rating (1-5):<input type="number" name="rating" min="1" max="5"><br>
Comments:<textarea name="comments"></textarea><br>
<button name="submit">Send</button>
</form></body></html>
