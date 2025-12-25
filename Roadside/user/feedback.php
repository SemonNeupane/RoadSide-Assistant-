<?php
session_start();
error_reporting(0);
include(__DIR__ . '/../includes/dbconnection.php');


if(strlen($_SESSION['sid'])==0){
    header('location:logout.php');
    exit();
}

$uid = $_SESSION['sid'];

// Fetch feedback for all bookings of this user
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
    WHERE b.user_id = '$uid'
    ORDER BY b.completed_at DESC
";
$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>RSA Nepal - Feedback</title>
<link href="../assets/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    font-family: Arial, Helvetica, sans-serif;
    background: #f4f6f8;
    margin: 0;
    padding: 0;
}
#wrapper { display:flex; min-height:100vh; }
.content-page { flex:1; padding:30px; margin-left:260px; }
.card-box {
    background:#fff;
    border:1px solid #e5e7eb;
    border-radius:6px;
    padding:20px 25px;
    margin-bottom:20px;
}
.header-title {
    font-size:16px;
    font-weight:600;
    margin-bottom:20px;
    color:#111827;
}
.table {
    width: 100%;
    border-collapse: collapse;
}
.table th, .table td {
    padding: 12px 10px;
    border-bottom: 1px solid #e5e7eb;
    text-align: left;
    font-size:14px;
    color:#374151;
}
.table th {
    background:#f9fafb;
    font-weight:600;
}
.btn-feedback {
    background:#38bdf8;
    color:#fff;
    padding:6px 14px;
    font-size:13px;
    border-radius:4px;
    text-decoration:none;
    transition:0.3s;
}
.btn-feedback:hover { background:#0ea5e9; color:#fff; }
</style>
</head>

<body>
<div id="wrapper">

<?php include('includes/sidebar.php'); ?>

<div class="content-page">
    <div class="card-box">
        <h4 class="header-title">Your Feedback</h4>
        <?php if(mysqli_num_rows($result) > 0){ ?>
        <table class="table">
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
            <?php while($row = mysqli_fetch_assoc($result)){ ?>
                <tr>
                    <td><?php echo $row['booking_id']; ?></td>
                    <td><?php echo $row['service_name']; ?></td>
                    <td><?php echo !empty($row['completed_at']) ? $row['completed_at'] : '-'; ?></td>
                    <td><?php echo !empty($row['rating']) ? $row['rating'] : '-'; ?></td>
                    <td><?php echo !empty($row['comments']) ? $row['comments'] : '-'; ?></td>
                    <td>
                        <?php if(empty($row['rating'])){ ?>
                            <a href="add-feedback.php?booking_id=<?php echo $row['booking_id']; ?>" class="btn-feedback">Give Feedback</a>
                        <?php } else { echo '-'; } ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <?php } else { ?>
            <p>No bookings found to give feedback.</p>
        <?php } ?>
    </div>
</div>

</div> <!-- wrapper -->

</body>
</html>
