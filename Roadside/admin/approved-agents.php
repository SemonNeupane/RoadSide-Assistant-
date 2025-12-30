<?php
session_start();
include('../includes/dbconnection.php');
if (empty($_SESSION['admin_id'])) exit();

$result = mysqli_query($con, "SELECT * FROM agent WHERE status='approved'");
?>

<h2>Approved Agents</h2>
<table border="1" width="100%">
<tr>
    <th>Name</th><th>Phone</th><th>City</th>
</tr>
<?php while($row = mysqli_fetch_assoc($result)) { ?>
<tr>
    <td><?= $row['name']; ?></td>
    <td><?= $row['phone']; ?></td>
    <td><?= $row['city']; ?></td>
</tr>
<?php } ?>
</table>
