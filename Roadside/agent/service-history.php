<?php
session_start();
include('../includes/dbconnection.php');
if(empty($_SESSION['agent_id'])) { header('location:../login.php'); exit(); }
$agent_id = $_SESSION['agent_id'];

$completed_services = mysqli_query($con, "
    SELECT b.booking_id, u.username AS user_name, v.model AS vehicle_model, s.service_name, c.city_name, b.completed_at
    FROM booking b
    JOIN users u ON b.user_id = u.user_id
    JOIN vehicle v ON b.vehicle_id = v.vehicle_id
    JOIN services s ON b.service_id = s.service_id
    JOIN city c ON b.city_id = c.city_id
    WHERE b.agent_id='$agent_id' AND b.completed_at IS NOT NULL
    ORDER BY b.completed_at DESC
");
?>

<h3>Service History</h3>
<table>
<thead>
<tr>
<th>ID</th><th>User</th><th>Vehicle</th><th>Service</th><th>City</th><th>Completed At</th>
</tr>
</thead>
<tbody>
<?php while($s=mysqli_fetch_assoc($completed_services)){ ?>
<tr>
<td><?php echo $s['booking_id']; ?></td>
<td><?php echo htmlspecialchars($s['user_name']); ?></td>
<td><?php echo htmlspecialchars($s['vehicle_model']); ?></td>
<td><?php echo htmlspecialchars($s['service_name']); ?></td>
<td><?php echo htmlspecialchars($s['city_name']); ?></td>
<td><?php echo $s['completed_at']; ?></td>
</tr>
<?php } ?>
<?php include('includes/sidebar.php'); ?>

<?php include('includes/header.php'); ?>

</tbody>
</table>
