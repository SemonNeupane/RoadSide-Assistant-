<?php
session_start(); include('includes/dbconnection.php');
if (!isset($_SESSION['sid'])) header('location:../logout.php');
$id=$_GET['id'];
if(isset($_POST['update'])){
  $stmt=$con->prepare("UPDATE booking SET landmark=? WHERE booking_id=? AND user_id=?");
  $stmt->bind_param("sii",$_POST['landmark'],$id,$_SESSION['sid']);
  $stmt->execute();
  echo "<script>alert('Updated');window.location='view-booking.php';</script>";
}
$row=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM booking WHERE booking_id='$id' AND user_id='{$_SESSION['sid']}'"));
?>
<html><body><h3>Edit Booking</h3>
<form method="post">
Landmark:<input name="landmark" value="<?php echo $row['landmark'];?>"><br>
<button name="update">Update</button>
</form></body></html>
