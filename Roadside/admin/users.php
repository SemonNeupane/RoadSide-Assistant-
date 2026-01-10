<?php
session_start();
include('../includes/dbconnection.php');

if (empty($_SESSION['admin_id'])) {
    header('location:/index.php');
    exit();
}

$result = mysqli_query($con, "
    SELECT user_id, username, email, phone, registration_date, status
    FROM users
    WHERE role='user'
    ORDER BY registration_date DESC
");
?>

<h2>Users</h2>

<table border="1" cellpadding="8">
<tr>
    <th>Username</th>
    <th>Email</th>
    <th>Phone</th>
    <th>Registered On</th>
    <th>Status</th>
    <th>Actions</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)): ?>
<tr>
    <td><?= htmlspecialchars($row['username']) ?></td>
    <td><?= htmlspecialchars($row['email']) ?></td>
    <td><?= htmlspecialchars($row['phone']) ?></td>
    <td><?= htmlspecialchars($row['registration_date']) ?></td>
    <td><?= $row['status'] ?></td>
    <td>
        <a href="user-status.php?id=<?= $row['user_id'] ?>&status=<?= $row['status'] ?>">
            <?= $row['status']=='active'?'Deactivate':'Activate' ?>
        </a> |
        <a href="user-delete.php?id=<?= $row['user_id'] ?>" 
           onclick="return confirm('Delete this user?')">
           Delete
        </a>
    </td>
</tr>
<?php endwhile; ?>
</table>
<?php include('includes/sidebar.php'); ?>
<?php include('includes/header.php'); ?>