<?php
session_start();
include('../includes/dbconnection.php');

if (empty($_SESSION['admin_id'])) {
    header('location:/index.php');
    exit();
}

$query = mysqli_query($con, "
    SELECT 
        ul.user_location_id,
        u.username,
        u.phone,
        p.province_name,
        d.district_name,
        c.city_name,
        ul.landmark,
        ul.created_at
    FROM user_location ul
    JOIN users u ON ul.user_id = u.user_id
    JOIN province p ON ul.province_id = p.province_id
    JOIN district d ON ul.district_id = d.district_id
    JOIN city c ON ul.city_id = c.city_id
    WHERE u.role = 'user'
    ORDER BY ul.created_at DESC
");

?>

<h2>User Locations</h2>

<table border="1" cellpadding="8">
<tr>
    <th>User</th>
    <th>Phone</th>
    <th>Province</th>
    <th>District</th>
    <th>City</th>
    <th>Landmark</th>
    <th>Date</th>
    <th>Action</th>
</tr>

<?php while($row = mysqli_fetch_assoc($query)): ?>
<tr>
    <td><?= htmlspecialchars($row['username']) ?></td>
    <td><?= htmlspecialchars($row['phone']) ?></td>
    <td><?= $row['province_name'] ?></td>
    <td><?= $row['district_name'] ?></td>
    <td><?= $row['city_name'] ?></td>
    <td><?= htmlspecialchars($row['landmark']) ?></td>
    <td><?= $row['created_at'] ?></td>
    <td>
        <a href="edit-user-location.php?id=<?= $row['user_location_id'] ?>">Edit</a>
    </td>
</tr>
<?php endwhile; ?>
</table>
<?php include('includes/sidebar.php'); ?>
<?php include('includes/header.php'); ?>