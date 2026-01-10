<?php
session_start();
include('../includes/dbconnection.php');

if (empty($_SESSION['admin_id'])) {
    header('location:/index.php');
    exit();
}
$result = mysqli_query($con, "
    SELECT 
        a.agent_id,
        u.username,
        u.phone,
        p.province_name,
        d.district_name,
        c.city_name
    FROM agent_location al
    JOIN agent a ON al.agent_id = a.agent_id
    JOIN users u ON a.user_id = u.user_id
    JOIN city c ON al.city_id = c.city_id
    JOIN district d ON c.district_id = d.district_id
    JOIN province p ON d.province_id = p.province_id
");

?>

<h2>Agent Locations</h2>

<table border="1" cellpadding="8">
<tr>
    <th>Agent</th>
    <th>Phone</th>
    <th>Province</th>
    <th>District</th>
    <th>City</th>
    <th>Actions</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)): ?>
<tr>
    <td><?= htmlspecialchars($row['username']) ?></td>
    <td><?= htmlspecialchars($row['phone']) ?></td>
    <td><?= $row['province_name'] ?></td>
    <td><?= $row['district_name'] ?></td>
    <td><?= $row['city_name'] ?></td>
   <td>
    <a href="edit-agent-location.php?agent_id=<?= $row['agent_id'] ?>" 
       class="btn btn-sm btn-primary">Edit</a>

    <a href="delete-agent-location.php?agent_id=<?= $row['agent_id'] ?>" 
       onclick="return confirm('Remove this agent location?')"
       class="btn btn-sm btn-danger">Delete</a>
</td>

</tr>
<?php endwhile; ?>
</table>
<?php include('includes/sidebar.php'); ?>
<?php include('includes/header.php'); ?>