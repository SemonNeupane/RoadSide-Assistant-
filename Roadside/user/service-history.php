<?php  
session_start();
error_reporting(E_ALL);
include(__DIR__ . '/../includes/dbconnection.php');


if(strlen($_SESSION['sid'])==0){
    header('location:logout.php');
    exit();
}

$uid = $_SESSION['sid']; // Logged-in user ID
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>Vehicle Service Management System | Service History</title>

<!-- Bootstrap -->
<link href="../assets/css/bootstrap.min.css" rel="stylesheet">
<link href="../assets/css/style.css" rel="stylesheet">

<style>
/* Your existing CSS here (same as before) */
</style>
</head>

<body>

<div id="wrapper">

<?php include_once('includes/sidebar.php');?>

<div class="content-page">

<?php include_once('includes/header.php');?>

<div class="content">
<div class="container-fluid">

<div class="row">
<div class="col-12">
<div class="card-box">

<h4 class="mb-3">Service History</h4>

<div class="table-responsive">
<table class="table table-bordered mb-0">
<thead>
<tr>
<th>#</th>
<th>Service</th>
<th>Vehicle Model</th>
<th>Registration</th>
<th>Request Date</th>
<th>Status</th>
<th>Action</th>
</tr>
</thead>
<tbody>

<?php
$rno = mt_rand(1000,9999);
$cnt = 1;

// Use LEFT JOIN to avoid missing rows
$query = "
SELECT 
    b.booking_id,
    b.created_at,
    b.status,
    v.model,
    v.registration_no,
    s.service_name
FROM booking b
LEFT JOIN vehicle v ON b.vehicle_id = v.vehicle_id
LEFT JOIN services s ON b.service_id = s.service_id
WHERE b.user_id = '$uid'
ORDER BY b.booking_id DESC
";

$q = mysqli_query($con, $query);

// Debug info (optional, remove in production)
if(!$q){
    echo "<tr><td colspan='7' class='text-danger'>Query Error: ".mysqli_error($con)."</td></tr>";
} elseif(mysqli_num_rows($q) > 0){
    while($row = mysqli_fetch_array($q)){
        ?>
        <tr>
        <td><?php echo $cnt;?></td>
        <td><?php echo htmlentities($row['service_name'] ?? 'N/A');?></td>
        <td><?php echo htmlentities($row['model'] ?? 'N/A');?></td>
        <td><?php echo htmlentities($row['registration_no'] ?? 'N/A');?></td>
        <td><?php echo htmlentities($row['created_at']);?></td>
        <td>
        <span class="badge badge-<?php echo ($row['status']=='active')?'success':'secondary';?>">
        <?php echo ucfirst($row['status']);?>
        </span>
        </td>
        <td>
        <a href="service-view.php?bid=<?php echo base64_encode($row['booking_id'].$rno);?>">
        View Detail
        </a>
        </td>
        </tr>
        <?php
        $cnt++;
    }
} else{
    echo "<tr><td colspan='7' class='text-center text-muted'>No Service History Found</td></tr>";
}
?>

</tbody>
</table>
</div>

</div>
</div>
</div>

</div>
</div>

<?php include_once('includes/footer.php');?>

</div>
</div>

<!-- JS -->
<script src="../assets/js/jquery.min.js"></script>
<script src="../assets/js/bootstrap.bundle.min.js"></script>

</body>
</html>
