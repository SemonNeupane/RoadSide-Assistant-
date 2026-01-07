<?php
// Start session safely
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include(__DIR__ . '/../includes/dbconnection.php');

// USER AUTH CHECK
if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header('location:../login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch feedback data
$query = "
    SELECT 
        b.booking_id,
        s.service_name,
        b.completed_at,
        f.rating,
        f.comments
    FROM booking b
    LEFT JOIN feedback f ON b.booking_id = f.booking_id
    LEFT JOIN services s ON b.service_id = s.service_id
    WHERE b.user_id = '$user_id'
    ORDER BY b.booking_id DESC
";
$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>User Feedback | RSA Nepal</title>

<style>
/* ===== CONTENT AREA (IMPORTANT) ===== */
.content {
    margin-left: 260px;
    margin-top: 60px;
    padding: 25px;
    background: #f3f4f6;
    min-height: 100vh;
}

/* ===== FEEDBACK CARD ===== */
.feedback-card {
    background: #ffffff;
    border-radius: 14px;
    padding: 25px 30px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.06);
}

/* TITLE */
.feedback-title {
    font-size: 20px;
    font-weight: 600;
    color: #0f172a;
    margin-bottom: 20px;
}

/* ===== TABLE ===== */
.feedback-table {
    width: 100%;
    border-collapse: collapse;
}

.feedback-table thead {
    background: #f1f5f9;
}

.feedback-table th {
    padding: 14px 12px;
    font-size: 14px;
    font-weight: 600;
    color: #334155;
    border-bottom: 1px solid #e5e7eb;
    text-align: left;
}

.feedback-table td {
    padding: 14px 12px;
    font-size: 14px;
    color: #475569;
    border-bottom: 1px solid #e5e7eb;
}

/* Hover */
.feedback-table tbody tr:hover {
    background: #f8fafc;
}

/* ===== BUTTON ===== */
.btn-feedback {
    display: inline-block;
    padding: 7px 16px;
    background: linear-gradient(135deg, #2563eb, #38bdf8);
    color: #ffffff;
    font-size: 13px;
    font-weight: 500;
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-feedback:hover {
    background: linear-gradient(135deg, #1e40af, #0284c7);
    transform: translateY(-1px);
}

/* ===== EMPTY ===== */
.feedback-empty {
    font-size: 14px;
    color: #6b7280;
    padding: 20px 0;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 991px) {
    .content {
        margin-left: 0;
        margin-top: 60px;
    }
}
</style>

</head>
<body>

<!-- SIDEBAR -->
<?php include('includes/sidebar.php'); ?>

<!-- HEADER -->
<?php include('includes/header.php'); ?>

<!-- CONTENT -->
<div class="content">
    <div class="feedback-card">
        <h4 class="feedback-title">Your Feedback</h4>

        <?php if(mysqli_num_rows($result) > 0) { ?>
        <table class="feedback-table">
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Service</th>
                    <th>Completed At</th>
                    <th>Rating</th>
                    <th>Comments</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

            <?php while($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['booking_id']; ?></td>
                    <td><?php echo htmlspecialchars($row['service_name']); ?></td>
                    <td>
                        <?php echo !empty($row['completed_at']) ? $row['completed_at'] : '-'; ?>
                    </td>
                    <td>
                        <?php echo !empty($row['rating']) ? $row['rating'] . '/5' : '-'; ?>
                    </td>
                    <td>
                        <?php echo !empty($row['comments']) ? htmlspecialchars($row['comments']) : '-'; ?>
                    </td>
                    <td>
                        <?php if(empty($row['rating'])) { ?>
                            <a href="add-feedback.php?booking_id=<?php echo $row['booking_id']; ?>" class="btn-feedback">
                                Give Feedback
                            </a>
                        <?php } else { echo '-'; } ?>
                    </td>
                </tr>
            <?php } ?>

            </tbody>
        </table>
        <?php } else { ?>
            <p class="feedback-empty">No completed bookings available for feedback.</p>
        <?php } ?>
    </div>
</div>

</body>
</html>
