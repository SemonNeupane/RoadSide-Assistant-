<?php
session_start();
include('../includes/dbconnection.php');

if (empty($_SESSION['admin_id']) || !isset($_GET['id'])) {
    header('location:agent-locations.php');
    exit();
}

$id = (int) $_GET['id'];

mysqli_query($con, "
    DELETE FROM agent_location 
    WHERE agent_location_id='$id'
");

header('location:agent-locations.php');
exit();
