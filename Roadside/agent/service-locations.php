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
<?php include('includes/sidebar.php'); ?>

<?php include('includes/header.php'); ?>

<h3>Service Locations</h3>
<ul>
<?php while($city = mysqli_fetch_assoc($cities)) { ?>
    <li><?php echo htmlspecialchars($city['city_name']); ?></li>
<?php } ?>
</ul>
