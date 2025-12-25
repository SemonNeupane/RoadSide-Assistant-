<?php
session_start(); include('includes/dbconnection.php');
if(!isset($_SESSION['sid'])) header('location:../logout.php');
$id=$_GET['id'];
if(isset($_POST['update'])){
$stmt=$con->prepare("UPDATE vehicle SET vehicle_type=?,model=?,registration_no=? WHERE vehicle_id=? AND user_id=?");
$stmt->bind_param("sssii",$_POST['type'],$_POST['model'],$_POST['reg'],$id,$_SESSION['sid']);
$stmt->execute(); echo "<script>alert('Updated');window.location='manage-vehicle.php';</script>";}
$row=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM vehicle WHERE vehicle_id='$id' AND user_id='{$_SESSION['sid']}'"));
?>
<html><body>
<form method="post">
Type:<input name="type" value="<?php echo $row['vehicle_type'];?>"><br>
Model:<input name="model" value="<?php echo $row['model'];?>"><br>
Reg No:<input name="reg" value="<?php echo $row['registration_no'];?>"><br>
<button name="update">Update</button></form></body></html>
