<?php
session_start();
include('../includes/dbconnection.php');

if (empty($_SESSION['admin_id'])) {
    header('location:index.php');
    exit();
}

$result = mysqli_query($con, "
    SELECT 
        v.vehicle_id,
        u.username,
        v.vehicle_type,
        v.model,
        v.registration_no
    FROM vehicle v
    LEFT JOIN users u ON v.user_id = u.user_id
");
?>

<h2>User Vehicles</h2>

<table border="1" width="100%" cellpadding="8">
<tr>
    <th>ID</th>
    <th>User</th>
    <th>Vehicle Type</th>
    <th>Model</th>
    <th>Registration No</th>
</tr>

<?php while ($row = mysqli_fetch_assoc($result)) { ?>
<tr>
    <td><?= $row['vehicle_id']; ?></td>
    <td><?= htmlspecialchars($row['username'] ?? 'N/A'); ?></td>
    <td><?= htmlspecialchars($row['vehicle_type']); ?></td>
    <td><?= htmlspecialchars($row['model']); ?></td>
    <td><?= htmlspecialchars($row['registration_no']); ?></td>
</tr>
<?php } ?>
<?php include('includes/footer.php'); ?>
 <?php include('includes/sidebar.php'); ?>
 <?php include('includes/header.php'); ?>
</table>
