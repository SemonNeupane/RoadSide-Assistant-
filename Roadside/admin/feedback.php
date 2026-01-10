<?php
session_start();
include('../includes/dbconnection.php');

// Admin authentication
if (empty($_SESSION['admin_id'])) {
    header('location:../login.php');
    exit();
}

// OPTIONAL: Delete feedback
if (isset($_GET['delete'])) {
    $fid = intval($_GET['delete']);
    mysqli_query($con, "DELETE FROM feedback WHERE feedback_id='$fid'");
    header("location:feedback.php");
    exit();
}

// Fetch feedback given by users
$result = mysqli_query($con, "
    SELECT 
        f.feedback_id,
        f.rating,
        f.comments,
        f.created_at,
        u.username AS user_name,
        b.booking_id
    FROM feedback f
    JOIN users u ON f.user_id = u.user_id
    JOIN booking b ON f.booking_id = b.booking_id
    ORDER BY f.created_at DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Feedback</title>
    <style>
        table { width:100%; border-collapse: collapse; background:#fff; }
        th, td { padding:10px; border:1px solid #ddd; text-align:left; }
        th { background:#0f172a; color:#fff; }
        .rating { font-weight:bold; color:#2563eb; }
        .delete { color:red; }
    </style>
</head>
<body>

<?php include('includes/sidebar.php'); ?>
<?php include('includes/header.php'); ?>

<div class="main-content">
    <h2>User Feedback</h2>

    <table>
        <tr>
            <th>#</th>
            <th>User</th>
            <th>Booking ID</th>
            <th>Rating</th>
            <th>Comment</th>
            <th>Date</th>
            <th>Action</th>
        </tr>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php $i=1; while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $i++; ?></td>
                <td><?= htmlspecialchars($row['user_name']); ?></td>
                <td><?= $row['booking_id']; ?></td>
                <td class="rating"><?= $row['rating']; ?>/5</td>
                <td><?= htmlspecialchars($row['comments']); ?></td>
                <td><?= $row['created_at']; ?></td>
                <td>
                    <a class="delete"
                       href="feedback.php?delete=<?= $row['feedback_id']; ?>"
                       onclick="return confirm('Delete this feedback?')">
                       Delete
                    </a>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="7">No feedback found.</td>
            </tr>
        <?php endif; ?>
    </table>
</div>

</body>
</html>
