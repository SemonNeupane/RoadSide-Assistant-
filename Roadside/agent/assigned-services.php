<?php
session_start();
include('../includes/dbconnection.php');

if(empty($_SESSION['agent_id'])) { 
    header('location:../login.php'); 
    exit(); 
}
$agent_id = $_SESSION['agent_id'];

// Fetch services assigned to this agent
$services = mysqli_query($con, "
    SELECT DISTINCT s.service_name
    FROM booking b
    JOIN services s ON b.service_id = s.service_id
    WHERE b.agent_id='$agent_id'
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Assigned Services | RSA Nepal</title>

<link href="../assets/css/bootstrap.min.css" rel="stylesheet">
<link href="../assets/css/style.css" rel="stylesheet">

<style>
/* Main content spacing to avoid sidebar and header overlap */
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
    color: #1f2937;
    margin-bottom: 20px;
}

/* Services List Container */
.services-list {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    padding: 20px;
    list-style: none;
    max-width: 500px;
}

/* List Items */
.services-list li {
    padding: 12px 15px;
    margin-bottom: 10px;
    font-size: 14px;
    font-weight: 500;
    color: #1f2937;
    border-radius: 10px;
    background: #e0e7ff;
    transition: all 0.2s ease;
}

.services-list li:hover {
    background: linear-gradient(135deg, #2563eb, #1e40af);
    color: #fff;
    transform: translateX(4px);
}

/* Empty state */
.services-list .empty {
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
    <h3>Assigned Services</h3>
    <ul class="services-list">
        <?php if(mysqli_num_rows($services) > 0) {
            while($srv = mysqli_fetch_assoc($services)) { ?>
                <li><?php echo htmlspecialchars($srv['service_name']); ?></li>
        <?php } } else { ?>
            <li class="empty">No services assigned yet</li>
        <?php } ?>
    </ul>
</div>

</body>
</html>
