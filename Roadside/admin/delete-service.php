<?php
session_start();
include('../includes/dbconnection.php');
 if (empty($_SESSION['admin_id'])) {
    header('location:index.php');
    exit();
}
$id = intval($_GET['id']);

// Delete service
mysqli_query($con, "DELETE FROM services WHERE service_id='$id'");

header("Location: services.php");
exit();
