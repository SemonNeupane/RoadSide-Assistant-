<?php
session_start();
include('../includes/dbconnection.php');

if(empty($_SESSION['agent_id'])) {
    header('location:../login.php');
    exit();
}
$agent_id = $_SESSION['agent_id'];

// Fetch feedback submitted by users for this agent
$stmt = $con->prepare("
    SELECT f.feedback_id, f.rating, f.comments, f.created_at,
           b.booking_id, v.model AS vehicle_model, s.service_name,
           u.username AS user_name
    FROM feedback f
    JOIN booking b ON f.booking_id = b.booking_id
    JOIN users u ON b.user_id = u.user_id
    JOIN vehicle v ON b.vehicle_id = v.vehicle_id
    JOIN services s ON b.service_id = s.service_id
    WHERE f.agent_id = ?
    ORDER BY f.created_at DESC
");
$stmt->bind_param("i", $agent_id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Agent Feedback</title>
<link rel="stylesheet" href="../../favicon.ico">

<style>
body { font-family: Arial, sans-serif; background:#f3f4f6; margin:0; }
.main-content { margin-left:260px; margin-top:60px; padding:25px; min-height:100vh; }
.card { background:#fff; padding:25px; border-radius:14px; box-shadow:0 8px 20px rgba(0,0,0,0.08); }
.card h3 { margin-bottom:20px; color:#111827; }
.feedback-box { border:1px solid #e5e7eb; border-radius:10px; padding:15px; margin-bottom:15px; }
.feedback-header { display:flex; justify-content:space-between; align-items:center; }
.rating { color:#0A924E; font-weight:bold; }
.date { font-size:12px; color:#6b7280; margin:6px 0; }
.comment { font-size:14px; color:#374151; }
.booking-info { font-size:13px; color:#334155; margin-bottom:5px; }
.no-data { text-align:center; color:#6b7280; padding:25px; }
</style>
</head>
<body>

<?php include('includes/sidebar.php'); ?>
<?php include('includes/header.php'); ?>

<div class="main-content">
<div class="card">
<h3>User Feedback</h3>

<?php if($result->num_rows > 0): ?>
<?php while($fb = $result->fetch_assoc()): ?>
<div class="feedback-box">
    <div class="booking-info">
        <strong>Booking ID:</strong> <?= $fb['booking_id']; ?> |
        <strong>User:</strong> <?= htmlentities($fb['user_name']); ?> |
        <strong>Vehicle:</strong> <?= htmlentities($fb['vehicle_model']); ?> |
        <strong>Service:</strong> <?= htmlentities($fb['service_name']); ?>
    </div>
    <div class="feedback-header">
        <span class="rating">⭐ <?= (int)$fb['rating']; ?>/5</span>
        <span class="date"><?= date('d M Y', strtotime($fb['created_at'])); ?></span>
    </div>
    <div class="comment"><?= nl2br(htmlentities($fb['comments'])); ?></div>
</div>
<?php endwhile; ?>
<?php else: ?>
<div class="no-data">No feedback received yet.</div>
<?php endif; ?>

</div>
</div>

</body>
</html>
