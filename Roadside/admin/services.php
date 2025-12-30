<?php
// Start session if needed
session_start();

// Include database connection
include('../includes/dbconnection.php'); // <-- make sure path is correct

// Optional: check if admin is logged in
if (empty($_SESSION['admin_id'])) {
    header('location:index.php');
    exit();
}


$q = mysqli_query($con,"SELECT service_id, service_name, service_price FROM service");
?>
<h2>Services</h2>
<table border="1" width="100%">
<tr><th>ID</th><th>Name</th><th>Price</th></tr>
<?php while($r=mysqli_fetch_assoc($q)){ ?>
<tr>
<td><?= $r['service_id'] ?></td>
<td><?= $r['service_name'] ?></td>
<td><?= $r['service_price'] ?></td>
</tr>
<?php } ?>
</table>
