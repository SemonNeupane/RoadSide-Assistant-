<?php
session_start();
include('../includes/dbconnection.php');

if (empty($_SESSION['admin_id'])) {
    header('location:/index.php');
    exit();
}

$result = mysqli_query($con, "
    SELECT u.user_id, u.username, u.email, u.phone, a.status
    FROM users u
    JOIN agent a ON u.user_id = a.user_id
    WHERE u.role = 'agent'
    AND a.status = 'inactive'
");
?>

<h2>Pending Agents</h2>

<table border="1" cellpadding="8">
<tr>
    <th>Username</th>
    <th>Email</th>
    <th>Phone</th>
    <th>Status</th>
    <th>Actions</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)): ?>
<tr>
    <td><?= htmlspecialchars($row['username']) ?></td>
    <td><?= htmlspecialchars($row['email']) ?></td>
    <td><?= htmlspecialchars($row['phone']) ?></td>
    <td><?= $row['status'] ?></td>
    <td>
        <a href="approve-agent.php?id=<?= $row['user_id'] ?>">Approve</a> |
        <a href="reject-agent.php?id=<?= $row['user_id'] ?>">Reject</a>
    </td>
</tr>
<?php endwhile; ?>
</table>
<?php include('includes/sidebar.php'); ?>
<?php include('includes/header.php'); ?>
