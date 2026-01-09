<?php
// Start session if needed
session_start();

// Include database connection
include('../includes/dbconnection.php'); // <-- make sure path is correct

// Optional: check if admin is logged in
if (empty($_SESSION['admin_id'])) {
    header('location:index.php');
    exit();
}

// Now $con is available
$result = mysqli_query($con, "
    SELECT b.booking_id, u.username, s.service_name, b.status, b.created_at
    FROM booking b
    JOIN users u ON b.user_id = u.user_id
    JOIN services s ON b.service_id = s.service_id
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Bookings | Admin</title>
    <link rel="icon" type="image/x-icon" href="../../favicon.ico">
</head>
<body>
    <h2>All Bookings</h2>
<table border="1" width="100%">
<tr>
    <th>ID</th>
    <th>User</th>
    <th>Service</th>
    <th>Status</th>
    <th>Date</th>
</tr>
<?php while($row = mysqli_fetch_assoc($result)) { ?>
<tr>
    <td><?= $row['booking_id'] ?></td>
    <td><?= htmlspecialchars($row['username']) ?></td>
    <td><?= htmlspecialchars($row['service_name']) ?></td>
    <td><?= $row['status'] ?></td>
    <td><?= $row['created_at'] ?></td>
</tr>
<?php } ?>
<?php include('includes/footer.php'); ?>
 <?php include('includes/sidebar.php'); ?>
 <?php include('includes/header.php'); ?> 


</table>

</body>
</html>