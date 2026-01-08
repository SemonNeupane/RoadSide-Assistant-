<?php
session_start(); include('includes/dbconnection.php');
if (!isset($_SESSION['sid'])) header('location:../logout.php');
$res=mysqli_query($con,"SELECT b.*,s.service_name,v.model FROM booking b
JOIN services s ON b.service_id=s.service_id
JOIN vehicle v ON b.vehicle_id=v.vehicle_id
WHERE b.user_id='{$_SESSION['sid']}'");
?>
<html>
    
<body><h3>My Bookings</h3>
<table border="1"><tr><th>ID</th><th>Service</th><th>Vehicle</th><th>Status</th><th>Landmark</th><th>Action</th></tr>
<?php while($r=mysqli_fetch_assoc($res)){
echo "<tr><td>{$r['booking_id']}</td><td>{$r['service_name']}</td><td>{$r['model']}</td><td>{$r['status']}</td>
<td>{$r['landmark']}</td>
<td><a href='update-booking.php?id={$r['booking_id']}'>Edit</a> |
<a href='delete-booking.php?id={$r['booking_id']}' onclick='return confirm(\"Delete?\")'>Delete</a></td></tr>";
}?></table></body></html>
