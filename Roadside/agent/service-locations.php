<?php
session_start();
include('../includes/dbconnection.php');
if(empty($_SESSION['agent_id'])) { header('location:../login.php'); exit(); }

$agent_id = $_SESSION['agent_id'];

// Fetch cities where this agent has bookings
$cities = mysqli_query($con, "
    SELECT DISTINCT c.city_name
    FROM booking b
    JOIN city c ON b.city_id = c.city_id
    WHERE b.agent_id = '$agent_id'
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Service Locations | RSA Nepal</title>



<style>
/* Main content spacing to match sidebar and header */
.main-content {
    margin-left: 260px; /* sidebar width */
    margin-top: 60px;   /* header height */
    padding: 20px;
    min-height: 100vh;
    background: #f3f4f6;
    font-family: Arial, Helvetica, sans-serif;
}

/* Page Title */
.main-content h3 {
    font-size: 20px;
    font-weight: 600;
    color: #203A4A;
    margin-bottom: 20px;
}

/* Locations List Container */
.locations-list {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    padding: 20px;
    list-style: none;
    max-width: 500px;
}

/* List Items */
.locations-list li {
    padding: 12px 15px;
    margin-bottom: 10px;
    font-size: 14px;
    font-weight: 500;
    color: #203A4A;
    border-radius: 10px;
    background: #e0e7ff;
    transition: all 0.2s ease;
}

.locations-list li:hover {
    background: linear-gradient(135deg, #2563eb, #1e40af);
    color: #fff;
    transform: translateX(4px);
}

/* Empty state */
.locations-list .empty {
    text-align: center;
    color: #6b7280;
    font-style: italic;
    padding: 15px 0;
}
</style>
</head>
<body>

<?php include('includes/sidebar.php'); ?>
<?php include('includes/header.php'); ?>

<div class="main-content">
    <h3>Service Locations</h3>
    <ul class="locations-list">
        <?php if(mysqli_num_rows($cities) > 0) {
            while($city = mysqli_fetch_assoc($cities)) { ?>
                <li><?php echo htmlspecialchars($city['city_name']); ?></li>
        <?php } } else { ?>
            <li class="empty">No service locations assigned yet</li>
        <?php } ?>
    </ul>
</div>

</body>
</html>
