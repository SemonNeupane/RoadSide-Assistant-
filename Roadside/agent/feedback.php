<?php
session_start();
include('../includes/dbconnection.php');

// ✅ Only logged-in agents
if (empty($_SESSION['agent_id'])) {
    header('location:../login.php');
    exit();
}
$agent_id = $_SESSION['agent_id'];

// Fetch feedback submitted by users for this agent
$feedback_query = mysqli_query($con, "
    SELECT 
        f.feedback_id,
        f.rating,
        f.comments,
        f.created_at,
        b.booking_id,
        v.model AS vehicle_model,
        s.service_name,
        u.username AS user_name
    FROM feedback f
    JOIN booking b ON f.booking_id = b.booking_id
    JOIN users u ON b.user_id = u.user_id
    JOIN vehicle v ON b.vehicle_id = v.vehicle_id
    JOIN services s ON b.service_id = s.service_id
    WHERE b.agent_id = '$agent_id'
    ORDER BY f.created_at DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Agent Feedback</title>
<link rel="icon" type="image/x-icon" href="../../favicon.ico">

<style>
body {
    margin: 0;
    font-family: Arial, Helvetica, sans-serif;
    background: #f3f4f6;
}

/* Main content */
.main-content {
    margin-left: 260px;
    margin-top: 60px;
    padding: 25px;
    min-height: 100vh;
}

/* Card container */
.card {
    background: #fff;
    padding: 25px;
    border-radius: 14px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
}

/* Page title */
.card h3 {
    margin-bottom: 20px;
    color: #111827;
}

/* Feedback box */
.feedback-box {
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 15px;
}

.feedback-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.feedback-header strong {
    color: #111827;
    font-size: 15px;
}

.rating {
    color: #0A924E;
    font-weight: bold;
}

.date {
    font-size: 12px;
    color: #6b7280;
    margin: 6px 0;
}

.comment {
    font-size: 14px;
    color: #374151;
}

.booking-info {
    font-size: 13px;
    color: #334155;
    margin-bottom: 5px;
}

.no-data {
    text-align: center;
    color: #6b7280;
    padding: 25px;
}

@media (max-width: 768px) {
    .main-content { margin-left: 0; padding: 20px; }
}
</style>
</head>
<body>

<?php include('includes/sidebar.php'); ?>
<?php include('includes/header.php'); ?>

<div class="main-content">
    <div class="card">
        <h3>User Feedback</h3>

        <?php if(mysqli_num_rows($feedback_query) > 0) { ?>
            <?php while($fb = mysqli_fetch_assoc($feedback_query)) { ?>
                <div class="feedback-box">
                    <div class="booking-info">
                        <strong>Booking ID:</strong> <?php echo $fb['booking_id']; ?> |
                        <strong>User:</strong> <?php echo htmlentities($fb['user_name']); ?> |
                        <strong>Vehicle:</strong> <?php echo htmlentities($fb['vehicle_model']); ?> |
                        <strong>Service:</strong> <?php echo htmlentities($fb['service_name']); ?>
                    </div>

                    <div class="feedback-header">
                        <span class="rating">⭐ <?php echo (int)$fb['rating']; ?>/5</span>
                        <span class="date"><?php echo date('d M Y', strtotime($fb['created_at'])); ?></span>
                    </div>

                    <div class="comment">
                        <?php echo nl2br(htmlentities($fb['comments'])); ?>
                    </div>
                </div>
            <?php } ?>
        <?php } else { ?>
            <div class="no-data">No feedback received yet.</div>
        <?php } ?>

    </div>
</div>

</body>
</html>
