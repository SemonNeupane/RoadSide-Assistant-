<?php
session_start();
include('../includes/dbconnection.php');

if(empty($_SESSION['user_id'])){
    header('location:../login.php');
    exit();
}
$user_id = $_SESSION['user_id'];

/* ===== SUBMIT FEEDBACK ===== */
if(isset($_POST['submit_feedback'], $_POST['booking_id'], $_POST['rating'])){
    $booking_id = intval($_POST['booking_id']);
    $rating = intval($_POST['rating']);
    $comments = $_POST['comments'] ?? '';

    // Get agent_id for this booking
    $stmt_agent = $con->prepare("SELECT agent_id FROM booking WHERE booking_id=? AND user_id=? AND status='completed'");
    $stmt_agent->bind_param("ii", $booking_id, $user_id);
    $stmt_agent->execute();
    $res_agent = $stmt_agent->get_result()->fetch_assoc();
    $agent_id = $res_agent['agent_id'] ?? 0;
    $stmt_agent->close();

    if($agent_id){
        // Insert feedback
        $stmt = $con->prepare("
            INSERT INTO feedback (booking_id, user_id, agent_id, rating, comments, created_at)
            VALUES (?, ?, ?, ?, ?, CURDATE())
        ");
        $stmt->bind_param("iiiis", $booking_id, $user_id, $agent_id, $rating, $comments);
        $stmt->execute();
        $stmt->close();
    }
}

/* ===== FETCH BOOKINGS READY FOR FEEDBACK ===== */
$stmt = $con->prepare("
    SELECT b.booking_id, a.agent_id, u.username AS agent_name, s.service_name, v.model AS vehicle_model, b.completed_at
    FROM booking b
    JOIN agent a ON a.agent_id = b.agent_id
    JOIN users u ON u.user_id = a.user_id
    JOIN services s ON s.service_id = b.service_id
    JOIN vehicle v ON v.vehicle_id = b.vehicle_id
    LEFT JOIN feedback f ON f.booking_id = b.booking_id
    WHERE b.user_id = ?
      AND b.status='completed'
      AND f.feedback_id IS NULL
    ORDER BY b.completed_at DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
<title>Give Feedback</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="main-content">
<h3>Give Feedback</h3>

<?php if($result->num_rows > 0): ?>
<?php while($b = $result->fetch_assoc()): ?>
<div style="border:1px solid #ccc;padding:10px;margin-bottom:10px;">
<p><strong>Service:</strong> <?= htmlspecialchars($b['service_name']); ?> | <strong>Agent:</strong> <?= htmlspecialchars($b['agent_name']); ?></p>
<form method="post">
<input type="hidden" name="booking_id" value="<?= $b['booking_id']; ?>">
<label>Rating (1-5)</label>
<select name="rating" required>
<option value="">Select</option>
<?php for($i=1;$i<=5;$i++): ?>
<option value="<?= $i; ?>"><?= $i; ?></option>
<?php endfor; ?>
</select>
<br>
<label>Comments</label><br>
<textarea name="comments" rows="2" cols="50"></textarea>
<br>
<button type="submit" name="submit_feedback">Submit Feedback</button>
</form>
</div>
<?php endwhile; ?>
<?php else: ?>
<p>No completed services to give feedback for.</p>
<?php endif; ?>

</div>
</body>
</html>
