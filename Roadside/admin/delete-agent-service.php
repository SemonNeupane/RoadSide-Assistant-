<?php
session_start();
include('../includes/dbconnection.php');
if (empty($_SESSION['admin_id'])) {
    header('location:/index.php');
    exit();
}
$id = intval($_GET['id']);
mysqli_query($con, "DELETE FROM agent_service WHERE agent_service_id='$id'");
header("Location: agent-services.php");
exit();
