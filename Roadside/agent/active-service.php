<?php
session_start();
include('../includes/dbconnection.php');

if(empty($_SESSION['agent_id'])) { header('location:../login.php'); exit(); }
$agent_id = $_SESSION['agent_id'];

// Active services are those assigned and not completed
$active_services = mysqli_query($con, "
    SELECT b.booking_id, u.username AS user_name, v.model AS vehicle_model, s.service_name, c.city_name, b.status
    FROM booking b
    JOIN users u ON b.user_id = u.user_id
    JOIN vehicle v ON b.vehicle_id = v.vehicle_id
    JOIN services s ON b.service_id = s.service_id
    JOIN city c ON b.city_id = c.city_id
    WHERE b.agent_id='$agent_id' AND b.status='active'
");
?>

<h3>Active Services</h3>
<table>
<thead>
<tr>
<th>ID</th><th>User</th><th>Vehicle</th><th>Service</th><th>City</th><th>Status</th>
</tr>
</thead>
<tbody>
<?php while($s=mysqli_fetch_assoc($active_services)){ ?>
<tr>
<td><?php echo $s['booking_id']; ?></td>
<td><?php echo htmlspecialchars($s['user_name']); ?></td>
<td><?php echo htmlspecialchars($s['vehicle_model']); ?></td>
<td><?php echo htmlspecialchars($s['service_name']); ?></td>
<td><?php echo htmlspecialchars($s['city_name']); ?></td>
<td><?php echo ucfirst($s['status']); ?></td>
</tr>
<?php } ?>
<?php include('includes/sidebar.php'); ?>

<?php include('includes/header.php'); ?>

</tbody>
</table>
