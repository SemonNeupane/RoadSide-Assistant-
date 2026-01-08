<?php
session_start();
include('../includes/dbconnection.php');

if (empty($_SESSION['agent_id'])) {
    header('location:../login.php');
    exit();
}

$agent_id = $_SESSION['agent_id'];

// Fetch feedback for this agent via booking
$query = mysqli_query($con, "
    SELECT 
        f.feedback_id,
        f.rating,
        f.comments,
        f.created_at,
        u.username AS user_name
    FROM feedback f
    JOIN booking b ON f.booking_id = b.booking_id
    JOIN users u ON f.user_id = u.user_id
    WHERE b.agent_id = '$agent_id'
    ORDER BY f.created_at DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>User Feedback | Agent</title>

<style>
body {
    margin: 0;
    background: #f3f4f6;
    font-family: Arial, Helvetica, sans-serif;
}

.main-content {
    margin-left: 260px;
    margin-top: 60px;
    padding: 30px;
    min-height: 100vh;
}

.card {
    background: #fff;
    padding: 25px;
    border-radius: 14px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
}

.card h3 {
    margin-bottom: 20px;
    color: #111827;
}

/* Feedback Card */
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

.no-data {
    text-align: center;
    color: #6b7280;
    padding: 25px;
}

@media (max-width: 768px) {
    .main-content {
        margin-left: 0;
        padding: 20px;
    }
}
</style>
</head>

<body>

<?php include('includes/sidebar.php'); ?>
<?php include('includes/header.php'); ?>

<div class="main-content">
    <div class="card">
        <h3>User Feedback</h3>

        <?php if (mysqli_num_rows($query) > 0) { ?>
            <?php while ($row = mysqli_fetch_assoc($query)) { ?>
                <div class="feedback-box">
                    <div class="feedback-header">
                        <strong><?php echo htmlentities($row['user_name']); ?></strong>
                        <span class="rating">⭐ <?php echo (int)$row['rating']; ?>/5</span>
                    </div>

                    <div class="date">
                        <?php echo date('d M Y', strtotime($row['created_at'])); ?>
                    </div>

                    <div class="comment">
                        <?php echo nl2br(htmlentities($row['comments'])); ?>
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
