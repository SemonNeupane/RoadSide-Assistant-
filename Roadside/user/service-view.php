<?php
session_start();
include(__DIR__ . '/../includes/dbconnection.php');

// ✅ Only logged-in users
if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header('location:../login.php');
    exit();
}

$uid = $_SESSION['user_id']; // logged-in user
$rno = 1000; // Must match the random number used in service-history.php

// Check if bid parameter exists
if(!isset($_GET['bid'])){
    die("Invalid request.");
}

// Decode booking ID
$encoded_bid = $_GET['bid'];
$decoded = base64_decode($encoded_bid);
$booking_id = intval(substr($decoded, 0, -strlen($rno))); // remove $rno at the end

// Fetch booking details for this user
$query = "
SELECT 
    b.booking_id,
    b.created_at AS request_date,
    b.completed_at,
    b.status,
    b.landmark,
    v.vehicle_type,
    v.model AS vehicle_model,
    s.service_name,
    s.description AS service_desc,
    u.username AS user_name,
    c.city_name,
    p.province_name,
    d.district_name,
    a.agent_id
FROM booking b
LEFT JOIN vehicle v ON b.vehicle_id = v.vehicle_id
LEFT JOIN services s ON b.service_id = s.service_id
LEFT JOIN user_location ul ON b.user_location_id = ul.user_location_id
LEFT JOIN city c ON ul.city_id = c.city_id
LEFT JOIN province p ON ul.province_id = p.province_id
LEFT JOIN district d ON ul.district_id = d.district_id
LEFT JOIN agent a ON b.agent_id = a.agent_id
LEFT JOIN users u ON b.user_id = u.user_id
WHERE b.booking_id = '$booking_id' AND b.user_id = '$uid'
LIMIT 1
";

$result = mysqli_query($con, $query);

if(!$result || mysqli_num_rows($result) == 0){
    die("Booking not found or access denied.");
}

$booking = mysqli_fetch_assoc($result);
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title> Service Detail</title>
<link rel="icon" type="image/x-icon" href="../../favicon.ico">
<!-- Bootstrap -->
<link href="../assets/css/bootstrap.min.css" rel="stylesheet">
<link href="../assets/css/style.css" rel="stylesheet">

<style>
.content { margin-left: 260px; margin-top: 60px; padding: 25px; min-height: 100vh; background: #f3f4f6; }
.card-box { background: #fff; padding: 25px; border-radius: 14px; box-shadow: 0 10px 25px rgba(0,0,0,0.08); max-width: 800px; margin: auto; }
.card-box h4 { font-size: 20px; font-weight: 600; margin-bottom: 20px; color: #0f172a; }
.detail-row { margin-bottom: 15px; }
.detail-row label { font-weight: 600; color: #334155; display: block; margin-bottom: 4px; }
.detail-row span { display: block; font-size: 14px; color: #0f172a; }
.badge-success { background: #22c55e; color: #fff; padding: 5px 12px; border-radius: 8px; font-weight: 500; }
.badge-secondary { background: #6b7280; color: #fff; padding: 5px 12px; border-radius: 8px; font-weight: 500; }
.btn-back { background: #38bdf8; color: #fff; padding: 8px 18px; border-radius: 8px; border: none; cursor: pointer; text-decoration: none; }
.btn-back:hover { background: #0ea5e9; color: #fff; }
</style>
</head>
<body>

<div class="content">

<?php include('includes/sidebar.php'); ?>
<?php include('includes/header.php'); ?>

<div class="card-box">
    <h4>Service Request Detail</h4>

    <div class="detail-row">
        <label>Service Name:</label>
        <span><?php echo htmlentities($booking['service_name']); ?></span>
    </div>

    <div class="detail-row">
        <label>Service Description:</label>
        <span><?php echo htmlentities($booking['service_desc']); ?></span>
    </div>

    <div class="detail-row">
        <label>Vehicle Type:</label>
        <span><?php echo htmlentities($booking['vehicle_type']); ?></span>
    </div>

    <div class="detail-row">
        <label>Vehicle Model:</label>
        <span><?php echo htmlentities($booking['vehicle_model']); ?></span>
    </div>

    <div class="detail-row">
        <label>Province:</label>
        <span><?php echo htmlentities($booking['province_name']); ?></span>
    </div>

    <div class="detail-row">
        <label>District:</label>
        <span><?php echo htmlentities($booking['district_name']); ?></span>
    </div>

    <div class="detail-row">
        <label>City:</label>
        <span><?php echo htmlentities($booking['city_name']); ?></span>
    </div>

    <div class="detail-row">
        <label>Landmark / Pickup Location:</label>
        <span><?php echo htmlentities($booking['landmark']); ?></span>
    </div>

    <div class="detail-row">
        <label>Request Date:</label>
        <span><?php echo htmlentities(date('d M Y', strtotime($booking['request_date']))); ?></span>
    </div>

    <div class="detail-row">
        <label>Status:</label>
        <span class="badge <?php echo ($booking['status']=='active') ? 'badge-success' : 'badge-secondary'; ?>">
            <?php echo ucfirst($booking['status']); ?>
        </span>
    </div>

    <?php if($booking['completed_at'] && $booking['status']=='inactive'): ?>
    <div class="detail-row">
        <label>Completed At:</label>
        <span><?php echo htmlentities(date('d M Y', strtotime($booking['completed_at']))); ?></span>
    </div>
    <?php endif; ?>

    <a href="service-history.php" class="btn-back">← Back to History</a>

</div>

</div>
</body>
</html>
