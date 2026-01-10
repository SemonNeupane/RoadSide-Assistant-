<?php
session_start();
include('../includes/dbconnection.php');
if (empty($_SESSION['admin_id'])) {
    header('location:/index.php');
    exit();
}
$msg = '';

// Fetch all agent services
$query = mysqli_query($con, "
    SELECT asv.agent_service_id, u.username AS agent_name, s.service_name, c.city_name
    FROM agent_service as asv
    JOIN agent a ON asv.agent_id = a.agent_id
    JOIN users u ON a.user_id = u.user_id
    JOIN services s ON asv.service_id = s.service_id
    LEFT JOIN agent_location al ON asv.agent_city_id = al.agent_city_id
    LEFT JOIN city c ON al.city_id = c.city_id
    ORDER BY u.username ASC
");
?>

<h2>Agent Services</h2>
<a href="add-agent-service.php">Add New Agent Service</a>
<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>Agent Name</th>
            <th>Service</th>
            <th>City</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = mysqli_fetch_assoc($query)) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['agent_name']); ?></td>
                <td><?php echo htmlspecialchars($row['service_name']); ?></td>
                <td><?php echo htmlspecialchars($row['city_name']); ?></td>
                <td>
                    <a href="edit-agent-service.php?id=<?php echo $row['agent_service_id']; ?>">Edit</a> |
                    <a href="delete-agent-service.php?id=<?php echo $row['agent_service_id']; ?>" onclick="return confirm('Are you sure?');">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<?php include('includes/sidebar.php'); ?>
<?php include('includes/header.php'); ?>