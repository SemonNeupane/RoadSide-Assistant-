<?php
session_start();
include('../includes/dbconnection.php');

// Check admin login
if (empty($_SESSION['admin_id'])) {
    header('location:index.php');
    exit();
}

// Correct query according to database
$result = mysqli_query($con, "
    SELECT user_id, username, email, phone, registration_date
    FROM users
    WHERE role = 'user'
");
?>

<h2>Registered Users</h2>

<table border="1" width="100%" cellpadding="10" cellspacing="0">
    <tr style="background:#f1f5f9;">
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Joined On</th>
    </tr>

    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?= $row['user_id']; ?></td>
            <td><?= htmlspecialchars($row['username']); ?></td>
            <td><?= htmlspecialchars($row['email']); ?></td>
            <td><?= htmlspecialchars($row['phone']); ?></td>
            <td><?= $row['registration_date']; ?></td>
        </tr>
    <?php } ?>
 
<?php include('includes/footer.php'); ?>
 <?php include('includes/sidebar.php'); ?>
 <?php include('includes/header.php'); ?> 


</table>
