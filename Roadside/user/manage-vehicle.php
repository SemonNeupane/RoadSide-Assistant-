<?php
session_start(); include('includes/dbconnection.php');
if(!isset($_SESSION['sid'])) header('location:../logout.php');
$res=mysqli_query($con,"SELECT * FROM vehicle WHERE user_id='{$_SESSION['sid']}'");
?>
<html><body><h3>My Vehicles</h3>
<a href="add-vehicle.php">+ Add Vehicle</a>
<table border="1"><tr><th>ID</th><th>Type</th><th>Model</th><th>Reg No</th><th>Action</th></tr>
<?php while($v=mysqli_fetch_assoc($res)){
echo "<tr><td>{$v['vehicle_id']}</td><td>{$v['vehicle_type']}</td><td>{$v['model']}</td><td>{$v['registration_no']}</td>
<td><a href='edit-vehicle.php?id={$v['vehicle_id']}'>Edit</a> |
<a href='delete-vehicle.php?id={$v['vehicle_id']}' onclick='return confirm(\"Delete?\")'>Delete</a></td></tr>";
}?></table></body></html>
