<?php
session_start();
include('../includes/dbconnection.php');

// ✅ Only logged-in agents
if(empty($_SESSION['agent_id'])) { 
    header('location:../login.php'); 
    exit(); 
}
$agent_id = $_SESSION['agent_id'];

// Handle Approve / Reject actions
if(isset($_POST['action']) && isset($_POST['request_id'])){
    $request_id = intval($_POST['request_id']);
    $action = $_POST['action'];

    if($action === 'accept'){
        // Set this request as accepted
        mysqli_query($con, "
            UPDATE booking_requests 
            SET status='accepted', responded_at=NOW() 
            WHERE request_id='$request_id'
        ");
        // Disable all other requests for the same booking
        $booking_id = mysqli_fetch_assoc(mysqli_query($con, "SELECT booking_id FROM booking_requests WHERE request_id='$request_id'"))['booking_id'];
        mysqli_query($con, "
            UPDATE booking_requests 
            SET status='disabled' 
            WHERE booking_id='$booking_id' AND request_id!='$request_id'
        ");
    }
    elseif($action === 'reject'){
        // Reject this request
        mysqli_query($con, "
            UPDATE booking_requests 
            SET status='rejected', responded_at=NOW() 
            WHERE request_id='$request_id'
        ");
    }
}

// Fetch all booking requests assigned to this agent
$requests = mysqli_query($con, "
    SELECT br.request_id, br.status, br.responded_at,
           b.booking_id, b.landmark, b.created_at AS booking_created,
           v.model AS vehicle_model,
           s.service_name,
           u.username AS user_name,
           c.city_name
    FROM booking_requests br
    JOIN booking b ON br.booking_id = b.booking_id
    JOIN users u ON b.user_id = u.user_id
    JOIN vehicle v ON b.vehicle_id = v.vehicle_id
    JOIN services s ON b.service_id = s.service_id
    JOIN city c ON b.city_id = c.city_id
    WHERE br.agent_id='$agent_id'
    ORDER BY br.request_id DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Agent Booking Requests</title>
<link rel="icon" type="image/x-icon" href="../../favicon.ico">

<style>
body{font-family:Arial,sans-serif;background:#f3f4f6;margin:0;padding:0;}
.main-content{margin-left:260px;margin-top:60px;padding:20px;min-height:100vh;}
h3{font-size:20px;color:#0A924E;margin-bottom:20px;}
.table-wrapper{overflow-x:auto;background:#fff;padding:20px;border-radius:12px;box-shadow:0 4px 12px rgba(0,0,0,0.08);}
table{width:100%;border-collapse:collapse;}
table th, table td{padding:12px;text-align:left;border-bottom:1px solid #e5e7eb;}
table th{background:#0A924E;color:#fff;}
.badge{padding:4px 10px;border-radius:12px;color:#fff;font-size:12px;font-weight:500;}
.badge.pending{background:#2563eb;}
.badge.accepted{background:#16a34a;}
.badge.rejected{background:#dc2626;}
.badge.disabled{background:#6b7280;}
button{padding:6px 12px;border:none;border-radius:8px;cursor:pointer;margin-right:5px;font-weight:500;}
button.accept{background:#16a34a;color:#fff;}
button.reject{background:#dc2626;color:#fff;}
button:disabled{opacity:0.6;cursor:not-allowed;}
@media(max-width:768px){table,thead,tbody,tr,td,th{display:block;}thead tr{display:none;}td{position:relative;padding-left:50%;margin-bottom:15px;}td::before{position:absolute;left:15px;top:12px;font-weight:600;color:#2563eb;content:attr(data-label);}}
</style>
</head>
<body>

<?php include('includes/sidebar.php'); ?>
<?php include('includes/header.php'); ?>

<div class="main-content">
<h3>Booking Requests Assigned</h3>
<div class="table-wrapper">
<table>
<thead>
<tr>
<th>#</th>
<th>User</th>
<th>Vehicle</th>
<th>Service</th>
<th>City</th>
<th>Landmark</th>
<th>Booking Date</th>
<th>Status</th>
<th>Action</th>
</tr>
</thead>
<tbody>
<?php
if(mysqli_num_rows($requests) > 0){
    $cnt = 1;
    while($r = mysqli_fetch_assoc($requests)){
        ?>
        <tr>
            <td data-label="#"><?php echo $cnt;?></td>
            <td data-label="User"><?php echo htmlspecialchars($r['user_name']);?></td>
            <td data-label="Vehicle"><?php echo htmlspecialchars($r['vehicle_model']);?></td>
            <td data-label="Service"><?php echo htmlspecialchars($r['service_name']);?></td>
            <td data-label="City"><?php echo htmlspecialchars($r['city_name']);?></td>
            <td data-label="Landmark"><?php echo htmlspecialchars($r['landmark']);?></td>
            <td data-label="Booking Date"><?php echo date('d M Y', strtotime($r['booking_created']));?></td>
            <td data-label="Status">
                <span class="badge <?php echo $r['status'];?>"><?php echo ucfirst($r['status']);?></span>
            </td>
            <td data-label="Action">
                <?php if($r['status']=='pending'){ ?>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="request_id" value="<?php echo $r['request_id'];?>">
                    <button type="submit" name="action" value="accept" class="accept">Approve</button>
                    <button type="submit" name="action" value="reject" class="reject">Reject</button>
                </form>
                <?php } else { ?>
                    <button disabled><?php echo ucfirst($r['status']);?></button>
                <?php } ?>
            </td>
        </tr>
        <?php
        $cnt++;
    }
} else {
    echo '<tr><td colspan="9" style="text-align:center;color:#6b7280;">No booking requests assigned</td></tr>';
}
?>
</tbody>
</table>
</div>
</div>

</body>
</html>
