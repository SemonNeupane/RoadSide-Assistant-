<?php

session_start();
include('../includes/dbconnection.php');

if (empty($_SESSION['admin_id'])) {
    header('location:index.php');
    exit();
}
$q = mysqli_query($con,"
    SELECT b.booking_id, u.username, s.service_name, b.status, b.created_at
    FROM booking b
    JOIN users u ON b.user_id = u.user_id
    JOIN services s ON b.service_id = s.service_id
    WHERE b.status='active'
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Active-Bookings | Admin</title>
    <link rel="icon" type="image/x-icon" href="../../favicon.ico">
</head>
<body>
    <h2>Active Bookings</h2>
<table border="1" width="100%">
<tr>
    <th>ID</th>
    <th>User</th>
    <th>Service</th>
    <th>Status</th>
    <th>Date</th>
</tr>
<?php while($r = mysqli_fetch_assoc($q)) { ?>
<tr>
    <td><?= $r['booking_id'] ?></td>
    <td><?= htmlspecialchars($r['username']) ?></td>
    <td><?= htmlspecialchars($r['service_name']) ?></td>
    <td><?= $r['status'] ?></td>
    <td><?= $r['created_at'] ?></td>
</tr>
<?php } ?>
<?php include('includes/footer.php'); ?>
 <?php include('includes/sidebar.php'); ?>
 <?php include('includes/header.php'); ?> 
</table>
</body>
</html>


