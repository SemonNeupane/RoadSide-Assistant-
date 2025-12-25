<?php
session_start(); include('includes/dbconnection.php');
if(!isset($_SESSION['sid'])) header('location:../logout.php');
$msg="";
if(isset($_POST['submit'])){
$cur=$_POST['current']; $new=$_POST['new']; $id=$_SESSION['sid'];
$q=mysqli_query($con,"SELECT password FROM users WHERE user_id='$id'");
$r=mysqli_fetch_assoc($q);
if(password_verify($cur,$r['password'])){
$hash=password_hash($new,PASSWORD_DEFAULT);
$stmt=$con->prepare("UPDATE users SET password=? WHERE user_id=?");
$stmt->bind_param("si",$hash,$id); $stmt->execute();
$msg="Password updated.";
}else $msg="Wrong current password.";
}
?>
<html><body><h3>Change Password</h3><p style="color:red"><?php echo $msg;?></p>
<form method="post">
Current:<input type="password" name="current"><br>
New:<input type="password" name="new"><br>
<button name="submit">Change</button>
</form></body></html>
