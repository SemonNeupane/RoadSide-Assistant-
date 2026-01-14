<?php
session_start();
include('../includes/dbconnection.php');

if(empty($_SESSION['agent_id'])) {
    header('location:../login.php');
    exit();
}
$agent_id = $_SESSION['agent_id'];

/* ===== MARK SERVICE COMPLETED ===== */
if(isset($_POST['mark_completed'], $_POST['booking_id'])){
    $booking_id = intval($_POST['booking_id']);
    $stmt = $con->prepare("
        UPDATE booking
        SET status = 'completed',
            completed_at = NOW()
        WHERE booking_id = ?
          AND agent_id = ?
          AND status = 'active'
    ");
    $stmt->bind_param("ii", $booking_id, $agent_id);
    $stmt->execute();
    $stmt->close();
}

/* ===== FETCH ACTIVE SERVICES ===== */
$stmt = $con->prepare("
    SELECT b.booking_id,
           u.username AS user_name,
           v.model AS vehicle_model,
           s.service_name,
           c.city_name,
           b.landmark,
           b.created_at
    FROM booking b
    JOIN users u ON u.user_id = b.user_id
    JOIN vehicle v ON v.vehicle_id = b.vehicle_id
    JOIN services s ON s.service_id = b.service_id
    JOIN city c ON c.city_id = b.city_id
    WHERE b.agent_id = ?
      AND b.status = 'active'
      AND b.completed_at IS NULL
    ORDER BY b.created_at DESC
");
$stmt->bind_param("i", $agent_id);
$stmt->execute();
$active_services = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Active Services</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php include('includes/sidebar.php'); ?>
<?php include('includes/header.php'); ?>

<div class="main-content">
<h3>Active Services</h3>
<table border="1" cellpadding="10" cellspacing="0">
<tr>
<th>ID</th>
<th>User</th>
<th>Vehicle</th>
<th>Service</th>
<th>City</th>
<th>Landmark</th>
<th>Started At</th>
<th>Action</th>
</tr>

<?php if($active_services->num_rows > 0): ?>
<?php while($s = $active_services->fetch_assoc()): ?>
<tr>
<td><?= $s['booking_id']; ?></td>
<td><?= htmlspecialchars($s['user_name']); ?></td>
<td><?= htmlspecialchars($s['vehicle_model']); ?></td>
<td><?= htmlspecialchars($s['service_name']); ?></td>
<td><?= htmlspecialchars($s['city_name']); ?></td>
<td><?= htmlspecialchars($s['landmark']); ?></td>
<td><?= date('d M Y H:i', strtotime($s['created_at'])); ?></td>
<td>
<form method="post" onsubmit="return confirm('Mark this service as completed?');">
<input type="hidden" name="booking_id" value="<?= $s['booking_id']; ?>">
<button type="submit" name="mark_completed">Mark Completed</button>
</form>
</td>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr><td colspan="8" align="center">No active services</td></tr>
<?php endif; ?>

</table>
</div>
</body>
</html>
