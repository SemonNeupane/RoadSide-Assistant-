<?php
session_start();
include('../includes/dbconnection.php');

if(empty($_SESSION['agent_id'])) exit(json_encode(['status'=>'error','message'=>'Unauthorized']));

if(isset($_POST['booking_id'])){
    $bid = intval($_POST['booking_id']);
    $aid = $_SESSION['agent_id'];

    mysqli_query($con, "UPDATE booking SET completed_at=NOW() WHERE booking_id='$bid' AND agent_id='$aid'");
    echo json_encode(['status'=>'success','message'=>'Booking marked as completed!']);
}
?>
