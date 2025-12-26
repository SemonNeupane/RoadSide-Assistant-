<?php
session_start();
include('../includes/dbconnection.php');
if(empty($_SESSION['agent_id'])) { header('location:../login.php'); exit(); }
$agent_id = $_SESSION['agent_id'];

// Fetch services that have been assigned to this agent through bookings
$services = mysqli_query($con, "
    SELECT DISTINCT s.service_name
    FROM booking b
    JOIN services s ON b.service_id = s.service_id
    WHERE b.agent_id='$agent_id'
");
?>

<h3>Assigned Services</h3>
<ul>
<?php while($srv = mysqli_fetch_assoc($services)) { ?>
    <li><?php echo htmlspecialchars($srv['service_name']); ?></li>
<?php } ?>
<?php include('includes/sidebar.php'); ?>

<?php include('includes/header.php'); ?>

</ul>
