<?php
session_start();
include(__DIR__ . '/../includes/dbconnection.php');

if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header('location:../login.php');
    exit();
}

$uid = $_SESSION['user_id'];

/* ===== AJAX CANCEL REQUEST ===== */
if (isset($_POST['ajax_action']) && $_POST['ajax_action'] === 'cancel') {
    $booking_id = intval($_POST['booking_id']);

    // allow cancel only if pending
    $check = mysqli_query($con, "
        SELECT booking_id FROM booking
        WHERE booking_id='$booking_id'
          AND user_id='$uid'
          AND status='pending'
        LIMIT 1
    ");

    if (mysqli_num_rows($check) > 0) {
        mysqli_query($con, "UPDATE booking SET status='cancelled' WHERE booking_id='$booking_id'");
        mysqli_query($con, "UPDATE booking_requests SET status='disabled' WHERE booking_id='$booking_id'");
        echo json_encode(['status'=>1,'msg'=>'Booking cancelled successfully']);
    } else {
        echo json_encode(['status'=>0,'msg'=>'Unable to cancel this booking']);
    }
    exit();
}

/* ===== FETCH SERVICE HISTORY ===== */
$bookings = mysqli_query($con, "
SELECT 
    b.booking_id,
    b.created_at,
    b.completed_at,
    b.status AS booking_status,
    b.landmark,
    v.model AS vehicle_model,
    s.service_name,
    u.username AS agent_name
FROM booking b
LEFT JOIN vehicle v ON b.vehicle_id = v.vehicle_id
LEFT JOIN services s ON b.service_id = s.service_id
LEFT JOIN agent a ON b.agent_id = a.agent_id
LEFT JOIN users u ON a.user_id = u.user_id
WHERE b.user_id = '$uid'
ORDER BY b.created_at DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Service History</title>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<style>
body{font-family:Arial,sans-serif;background:#f4f7fb;margin:0;}
.container{width:95%;max-width:1000px;margin:40px auto;}
h2{text-align:center;margin-bottom:20px;}
table{width:100%;border-collapse:collapse;background:#fff;border-radius:10px;overflow:hidden;box-shadow:0 4px 15px rgba(0,0,0,.1);}
th,td{padding:12px;border-bottom:1px solid #e5e7eb;}
th{background:#0b2e59;color:#fff;}
.badge{padding:4px 10px;border-radius:8px;color:#fff;font-size:13px;}
.pending{background:#facc15;}
.active{background:#0A924E;}
.completed{background:#2563eb;}
.cancelled{background:#dc2626;}
button{padding:6px 12px;border:none;border-radius:6px;cursor:pointer;}
.cancel{background:#dc2626;color:#fff;}
.msg{text-align:center;font-weight:bold;margin-bottom:10px;color:green;}
</style>
</head>
<body>

<div class="container">
<h2>My Service History</h2>
<div id="msg" class="msg"></div>

<table>
<thead>
<tr>
<th>ID</th>
<th>Service</th>
<th>Vehicle</th>
<th>Agent</th>
<th>Landmark</th>
<th>Date</th>
<th>Status</th>
<th>Action</th>
</tr>
</thead>
<tbody>

<?php if(mysqli_num_rows($bookings)>0): ?>
<?php while($b=mysqli_fetch_assoc($bookings)): 
    $date = ($b['booking_status']==='completed' && $b['completed_at'])
            ? $b['completed_at']
            : $b['created_at'];
?>
<tr id="row-<?= $b['booking_id'] ?>">
<td><?= $b['booking_id'] ?></td>
<td><?= htmlspecialchars($b['service_name']) ?></td>
<td><?= htmlspecialchars($b['vehicle_model']) ?></td>
<td><?= htmlspecialchars($b['agent_name'] ?? 'Not Assigned') ?></td>
<td><?= htmlspecialchars($b['landmark']) ?></td>
<td><?= $date ?></td>
<td>
    <span class="badge <?= $b['booking_status'] ?>">
        <?= ucfirst($b['booking_status']) ?>
    </span>
</td>
<td>
<?php if($b['booking_status']==='pending'): ?>
<button class="cancel" data-id="<?= $b['booking_id'] ?>">Cancel</button>
<?php else: ?>
—
<?php endif; ?>
</td>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr><td colspan="8" style="text-align:center;">No bookings found</td></tr>
<?php endif; ?>

</tbody>
</table>
</div>

<script>
$(document).on('click','.cancel',function(){
    if(!confirm('Are you sure you want to cancel this booking?')) return;

    let booking_id = $(this).data('id');

    $.post('', {ajax_action:'cancel', booking_id:booking_id}, function(res){
        let data = JSON.parse(res);
        $('#msg').text(data.msg).fadeIn().delay(2000).fadeOut();

        if(data.status==1){
            $('#row-'+booking_id+' .badge')
                .removeClass('pending')
                .addClass('cancelled')
                .text('Cancelled');
            $('#row-'+booking_id+' td:last').html('—');
        }
    });
});
</script>

</body>
</html>
