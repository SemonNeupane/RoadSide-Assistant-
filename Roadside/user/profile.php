<?php
session_start(); include('includes/dbconnection.php');
if(!isset($_SESSION['sid'])) header('location:../logout.php');
$id=$_SESSION['sid'];
if(isset($_POST['update'])){
$stmt=$con->prepare("UPDATE users SET username=?,phone=? WHERE user_id=?");
$stmt->bind_param("ssi",$_POST['username'],$_POST['phone'],$id);
$stmt->execute(); echo "<script>alert('Profile Updated');</script>";}
$r=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM users WHERE user_id='$id'"));
?>
<html><body>
<h3>My Profile</h3>
<form method="post">
Name:<input name="username" value="<?php echo $r['username'];?>"><br>
Email:<input value="<?php echo $r['email'];?>" disabled><br>
Phone:<input name="phone" value="<?php echo $r['phone'];?>"><br>
<button name="update">Update</button>
</form>
<a href="change-password.php">Change Password</a>
</body></html>
