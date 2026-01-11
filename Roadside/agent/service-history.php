<?php
session_start();
include('../includes/dbconnection.php');

if(empty($_SESSION['agent_id'])) {
    header('location:../login.php'); 
    exit();
}
$agent_id = $_SESSION['agent_id'];

// Fetch completed bookings
$completed_services = mysqli_query($con, "
    SELECT b.booking_id, u.username AS user_name, v.model AS vehicle_model, s.service_name, c.city_name, b.completed_at, b.landmark
    FROM booking b
    JOIN users u ON b.user_id = u.user_id
    JOIN vehicle v ON b.vehicle_id = v.vehicle_id
    JOIN services s ON b.service_id = s.service_id
    JOIN city c ON b.city_id = c.city_id
    WHERE b.agent_id='$agent_id' AND b.completed_at IS NOT NULL
    ORDER BY b.completed_at DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Service History</title>
<style>
body{font-family:Arial,sans-serif;background:#f3f4f6;margin:0;padding:0;}
.main-content{margin-left:260px;margin-top:60px;padding:20px;min-height:100vh;}
h3{font-size:20px;color:#0A924E;margin-bottom:15px;}
.table-wrapper{overflow-x:auto;background:#fff;padding:20px;border-radius:12px;box-shadow:0 4px 12px rgba(0,0,0,0.08);}
table{width:100%;border-collapse:collapse;font-size:14px;}
table th, table td{padding:12px;text-align:left;border-bottom:1px solid #e5e7eb;}
table th{background:#0A924E;color:#fff;}
span.status-completed{background:#6b7280;color:#fff;padding:4px 10px;border-radius:12px;font-size:12px;font-weight:500;}
@media(max-width:768px){table,thead,tbody,tr,td,th{display:block;}thead tr{display:none;}td{position:relative;padding-left:50%;margin-bottom:15px;}td::before{position:absolute;left:15px;top:12px;font-weight:600;color:#2563eb;content:attr(data-label);}}
</style>
</head>
<body>

<?php include('includes/sidebar.php'); ?>
<?php include('includes/header.php'); ?>

<div class="main-content">

<h3>Service History</h3>
<div class="table-wrapper">
<table>
<thead>
<tr>
<th>ID</th>
<th>User</th>
<th>Vehicle</th>
<th>Service</th>
<th>City</th>
<th>Landmark</th>
<th>Completed At</th>
<th>Status</th>
</tr>
</thead>
<tbody>
<?php if(mysqli_num_rows($completed_services) > 0){
    while($c=mysqli_fetch_assoc($completed_services)){ ?>
        <tr>
            <td data-label="ID"><?php echo $c['booking_id'];?></td>
            <td data-label="User"><?php echo htmlspecialchars($c['user_name']);?></td>
            <td data-label="Vehicle"><?php echo htmlspecialchars($c['vehicle_model']);?></td>
            <td data-label="Service"><?php echo htmlspecialchars($c['service_name']);?></td>
            <td data-label="City"><?php echo htmlspecialchars($c['city_name']);?></td>
            <td data-label="Landmark"><?php echo htmlspecialchars($c['landmark']);?></td>
            <td data-label="Completed At"><?php echo $c['completed_at'];?></td>
            <td data-label="Status"><span class="status-completed">Completed</span></td>
        </tr>
<?php } } else { ?>
<tr><td colspan="8" style="text-align:center;color:#6b7280;">No completed services found</td></tr>
<?php } ?>
</tbody>
</table>
</div>

</div>

</body>
</html>
