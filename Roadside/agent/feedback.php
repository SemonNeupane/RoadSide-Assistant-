<?php
session_start();
include('../includes/dbconnection.php');
if(empty($_SESSION['agent_id'])) { header('location:../login.php'); exit(); }

$agent_id = $_SESSION['agent_id'];

// Fetch feedback related to this agent
$feedbacks = mysqli_query($con, "
    SELECT f.feedback_id, f.rating, f.created_at, u.username AS user_name, b.booking_id
    FROM feedback f
    JOIN booking b ON f.booking_id = b.booking_id
    JOIN users u ON b.user_id = u.user_id
    WHERE b.agent_id = '$agent_id'
    ORDER BY f.created_at DESC
");
?><?php include('includes/sidebar.php'); ?>
<?php include('includes/header.php'); ?>



<h3>Agent Feedback</h3>
<table border="1" cellpadding="10">
    <thead>
        <tr>
            <th>Feedback ID</th>
            <th>User</th>
            <th>Booking ID</th>
            <th>Rating</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        <?php while($f = mysqli_fetch_assoc($feedbacks)) { ?>
        <tr>
            <td><?php echo $f['feedback_id']; ?></td>
            <td><?php echo htmlspecialchars($f['user_name']); ?></td>
            <td><?php echo $f['booking_id']; ?></td>
            <td><?php echo $f['rating']; ?></td>
            <td><?php echo $f['created_at']; ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>
 <?php include(__DIR__ . '/includes/footer.php'); ?>
