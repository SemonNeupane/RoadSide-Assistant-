<?php
session_start();
include('../includes/dbconnection.php');
if (empty($_SESSION['admin_id'])) exit();

$result = mysqli_query($con, "SELECT * FROM agent WHERE status='pending'");
?>

<h2>Pending Agents</h2>
<table border="1" width="100%">
<tr>
    <th>Name</th><th>Phone</th><th>Action</th>
</tr>
<?php while($row = mysqli_fetch_assoc($result)) { ?>
<tr>
    <td><?= $row['name']; ?></td>
    <td><?= $row['phone']; ?></td>
    <td>
        <a href="approve-agent.php?id=<?= $row['agent_id']; ?>">Approve</a> |
        <a href="reject-agent.php?id=<?= $row['agent_id']; ?>">Reject</a>
    </td>
</tr>
<?php } ?>
</table>
