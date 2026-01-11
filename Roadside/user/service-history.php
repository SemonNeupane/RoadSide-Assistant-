<?php
session_start();
include(__DIR__ . '/../includes/dbconnection.php');

if(empty($_SESSION['user_id']) || $_SESSION['role']!=='user'){
    header('location:../login.php');
    exit();
}
$uid = $_SESSION['user_id'];

$bookings = mysqli_query($con, "
SELECT b.booking_id, b.created_at, b.status AS booking_status, b.landmark,
       v.model AS vehicle_model, s.service_name, a.username AS agent_name
FROM booking b
LEFT JOIN vehicle v ON b.vehicle_id = v.vehicle_id
LEFT JOIN services s ON b.service_id = s.service_id
LEFT JOIN agent a ON b.agent_id = a.agent_id
WHERE b.user_id='$uid'
ORDER BY b.created_at DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Service History</title>
<style>
body{font-family:Arial,sans-serif;background:#f4f7fb;margin:0;padding:0;}
.container{width:95%;max-width:900px;margin:50px auto;}
table{width:100%;border-collapse:collapse;background:#fff;box-shadow:0 4px 15px rgba(0,0,0,0.1);border-radius:10px;overflow:hidden;}
th, td{padding:12px;text-align:left;border-bottom:1px solid #e5e7eb;}
th{background:#0b2e59;color:#fff;}
.badge{padding:4px 10px;border-radius:8px;color:#fff;font-weight:500;font-size:13px;}
.pending{background:#facc15;}
.active{background:#0A924E;}
.completed{background:#2563eb;}
.cancelled{background:#dc2626;}
</style>
</head>
<body>
<div class="container">
<h2>My Service History</h2>
<table>
<thead>
<tr>
<th>ID</th><th>Service</th><th>Vehicle</th><th>Agent</th><th>Landmark</th><th>Date</th><th>Status</th>
</tr>
</thead>
<tbody>
<?php if(mysqli_num_rows($bookings)>0){
    while($b=mysqli_fetch_assoc($bookings)){ ?>
<tr>
<td><?= $b['booking_id'] ?></td>
<td><?= htmlspecialchars($b['service_name']) ?></td>
<td><?= htmlspecialchars($b['vehicle_model']) ?></td>
<td><?= htmlspecialchars($b['agent_name'] ?? 'Not Assigned') ?></td>
<td><?= htmlspecialchars($b['landmark']) ?></td>
<td><?= $b['created_at'] ?></td>
<td><span class="badge <?= $b['booking_status'] ?>"><?= ucfirst($b['booking_status']) ?></span></td>
</tr>
<?php } } else { ?>
<tr><td colspan="7" style="text-align:center;">No bookings found</td></tr>
<?php } ?>
</tbody>
</table>
</div>
</body>
</html>
