<?php
session_start();
include('../includes/dbconnection.php');

if (empty($_SESSION['admin_id'])) {
    header('location:index.php');
    exit();
}


// Fetch all services
$result = mysqli_query($con, "SELECT * FROM services ORDER BY service_name ASC");
?>

<h2>Services</h2>
<a href="add-service.php" class="btn btn-success">Add New Service</a>
<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Service Name</th>
        <th>Description</th>
        <th>Action</th>
    </tr>
    <?php while($row = mysqli_fetch_assoc($result)): ?>
    <tr>
        <td><?= $row['service_id'] ?></td>
        <td><?= htmlspecialchars($row['service_name']) ?></td>
        <td><?= htmlspecialchars($row['description']) ?></td>
        <td>
            <a href="edit-service.php?id=<?= $row['service_id'] ?>">Edit</a> | 
            <a href="delete-service.php?id=<?= $row['service_id'] ?>" 
               onclick="return confirm('Are you sure?')">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
