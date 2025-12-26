<?php
session_start();
include('../includes/dbconnection.php');

if(empty($_SESSION['agent_id'])) {
    header('location:../login.php'); exit();
}
$agent_id = $_SESSION['agent_id'];

// Fetch assigned bookings that are not completed
$assigned_bookings = mysqli_query($con, "
    SELECT b.booking_id, u.username AS user_name, v.model AS vehicle_model, s.service_name, c.city_name, b.status, b.created_at
    FROM booking b
    JOIN users u ON b.user_id = u.user_id
    JOIN vehicle v ON b.vehicle_id = v.vehicle_id
    JOIN services s ON b.service_id = s.service_id
    JOIN city c ON b.city_id = c.city_id
    WHERE b.agent_id='$agent_id' AND b.completed_at IS NULL
    ORDER BY b.created_at DESC
");
?>

<h3>Assigned Bookings</h3>
<table>
<thead>
<tr>
<th>ID</th><th>User</th><th>Vehicle</th><th>Service</th><th>City</th><th>Status</th><th>Created At</th>
</tr>
</thead>
<tbody>
<?php while($b=mysqli_fetch_assoc($assigned_bookings)){ ?>
<tr>
<td><?php echo $b['booking_id']; ?></td>
<td><?php echo htmlspecialchars($b['user_name']); ?></td>
<td><?php echo htmlspecialchars($b['vehicle_model']); ?></td>
<td><?php echo htmlspecialchars($b['service_name']); ?></td>
<td><?php echo htmlspecialchars($b['city_name']); ?></td>
<td><?php echo ucfirst($b['status']); ?></td>
<td><?php echo $b['created_at']; ?></td>
</tr>
<?php } ?>
<?php include('includes/sidebar.php'); ?>

<?php include('includes/header.php'); ?>

</tbody>
</table>
