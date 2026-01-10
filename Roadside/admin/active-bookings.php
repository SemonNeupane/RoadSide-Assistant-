<?php
include('../includes/dbconnection.php');
$msg = '';

// Fetch active bookings only
$bookings = mysqli_query($con, "
    SELECT b.*, u.username AS user_name, a.agent_id AS agent_id, v.registration_no AS vehicle_no, 
           s.service_name, c.city_name, ul.landmark
    FROM booking b
    JOIN users u ON b.user_id=u.user_id
    JOIN agent a ON b.agent_id=a.agent_id
    JOIN vehicle v ON b.vehicle_id=v.vehicle_id
    JOIN services s ON b.service_id=s.service_id
    JOIN city c ON b.city_id=c.city_id
    LEFT JOIN user_location ul ON b.user_location_id=ul.user_location_id
    WHERE b.status='active'
    ORDER BY b.created_at DESC
");
?>

<h2>Active Bookings</h2>

<table border="1" cellpadding="5" cellspacing="0">
<tr>
    <th>ID</th>
    <th>User</th>
    <th>Agent ID</th>
    <th>Vehicle</th>
    <th>Service</th>
    <th>City</th>
    <th>Landmark</th>
    <th>Created At</th>
    <th>Status</th>
    <th>Completed At</th>
    <th>Report</th>
</tr>

<?php while($b=mysqli_fetch_assoc($bookings)): ?>
<tr>
    <td><?php echo $b['booking_id']; ?></td>
    <td><?php echo $b['user_name']; ?></td>
    <td><?php echo $b['agent_id']; ?></td>
    <td><?php echo $b['vehicle_no']; ?></td>
    <td><?php echo $b['service_name']; ?></td>
    <td><?php echo $b['city_name']; ?></td>
    <td><?php echo $b['landmark']; ?></td>
    <td><?php echo $b['created_at']; ?></td>
    <td><?php echo $b['status']; ?></td>
    <td><?php echo $b['completed_at']; ?></td>
    <td><?php echo $b['report_details']; ?></td>
</tr>
<?php endwhile; ?>

</table>
