<?php
session_start();
include('../includes/dbconnection.php');

if (empty($_SESSION['admin_id'])) {
    header('location:/index.php');
    exit();
}

$result = mysqli_query($con, "
    SELECT u.user_id, u.username, u.email, u.phone, a.status, a.approved_date
    FROM users u
    JOIN agent a ON u.user_id = a.user_id
    WHERE u.role = 'agent'
    AND u.status = 'active'
    AND a.status = 'active'
");
?>

<h2>Approved Agents</h2>

<table border="1" cellpadding="8">
<tr>
    <th>Username</th>
    <th>Email</th>
    <th>Phone</th>
    <th>Status</th>
    <th>Approved Date</th>
    <th>Actions</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)): ?>
<tr>
    <td><?= htmlspecialchars($row['username']) ?></td>
    <td><?= htmlspecialchars($row['email']) ?></td>
    <td><?= htmlspecialchars($row['phone']) ?></td>
    <td><?= $row['status'] ?></td>
    <td><?= $row['approved_date'] ?></td>
    <td>
        <a href="disable-agent.php?id=<?= $row['user_id'] ?>">Disable</a>
    </td>
</tr>
<?php endwhile; ?>
</table>
<?php include('includes/sidebar.php'); ?>
<?php include('includes/header.php'); ?>