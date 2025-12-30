<?php
session_start();
include('../includes/dbconnection.php');

if (empty($_SESSION['admin_id'])) {
    header('location:index.php');
    exit();
}
$q = mysqli_query($con,"
SELECT u.username, p.province_name, d.district_name, c.city_name
FROM users u
LEFT JOIN province p ON u.province_id=p.province_id
LEFT JOIN district d ON u.district_id=d.district_id
LEFT JOIN city c ON u.city_id=c.city_id
WHERE u.role='user'
");
?>

<h2>User Locations</h2>
<table border="1" width="100%">
<tr>
<th>User</th><th>Province</th><th>District</th><th>City</th>
</tr>
<?php while($r=mysqli_fetch_assoc($q)){ ?>
<tr>
<td><?= $r['username'] ?></td>
<td><?= $r['province_name'] ?></td>
<td><?= $r['district_name'] ?></td>
<td><?= $r['city_name'] ?></td>
</tr>
<?php } ?>
</table>
?>
