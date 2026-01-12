<?php
session_start();
include(__DIR__ . '/../includes/dbconnection.php');

// Logged-in agent
$agent_id = $_SESSION['agent_id'] ?? 1; // replace with actual session

// Handle AJAX actions (approve/reject/delete)
if(isset($_POST['ajax_action'], $_POST['request_id'])){
    $request_id = intval($_POST['request_id']);
    $action = $_POST['ajax_action'];
    $response = ['status'=>0,'msg'=>''];

    // Fetch booking request
    $req_q = mysqli_query($con,"SELECT booking_id,status FROM booking_requests WHERE request_id='$request_id' AND agent_id='$agent_id' LIMIT 1");
    if(mysqli_num_rows($req_q)>0){
        $req = mysqli_fetch_assoc($req_q);
        $booking_id = $req['booking_id'];

        if($action==='approve'){
            mysqli_query($con,"UPDATE booking_requests SET status='accepted', responded_at=NOW() WHERE request_id='$request_id'");
            mysqli_query($con,"UPDATE booking_requests SET status='disabled', responded_at=NOW() WHERE booking_id='$booking_id' AND agent_id!='$agent_id'");
            mysqli_query($con,"UPDATE booking SET agent_id='$agent_id', status='active' WHERE booking_id='$booking_id'");
            $response=['status'=>1,'msg'=>'You have accepted the service request.'];
        } elseif($action==='reject'){
            mysqli_query($con,"UPDATE booking_requests SET status='rejected', responded_at=NOW() WHERE request_id='$request_id'");
            $response=['status'=>1,'msg'=>'You have rejected the service request.'];
        } elseif($action==='delete'){
            mysqli_query($con,"DELETE FROM booking_requests WHERE request_id='$request_id' AND agent_id='$agent_id'");
            $response=['status'=>1,'msg'=>'Request removed.'];
        }
    } else {
        $response=['status'=>0,'msg'=>'Request not found.'];
    }

    echo json_encode($response);
    exit();
}

// Fetch assigned booking requests
$booking_requests = mysqli_query($con,"
SELECT br.request_id, br.status AS request_status, br.responded_at,
       b.booking_id, b.user_id, b.vehicle_id, b.service_id, b.landmark,
       v.model AS vehicle_model, s.service_name, u.username AS user_name, c.city_name
FROM booking_requests br
JOIN booking b ON br.booking_id = b.booking_id
JOIN vehicle v ON b.vehicle_id = v.vehicle_id
JOIN services s ON b.service_id = s.service_id
JOIN users u ON b.user_id = u.user_id
JOIN city c ON b.city_id = c.city_id
WHERE br.agent_id='$agent_id'
ORDER BY br.request_id DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Assigned Booking Requests</title>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
<h2>Assigned Booking Requests</h2>
<div id="msg" class="msg"></div>

<table id="booking-table">
<thead>
<tr>
<th>#</th>
<th>User</th>
<th>Vehicle</th>
<th>Service</th>
<th>City</th>
<th>Landmark</th>
<th>Request Status</th>
<th>Responded At</th>
<th>Action</th>
</tr>
</thead>
<tbody>
<?php
if(mysqli_num_rows($booking_requests) > 0){
    $cnt=1;
    while($row=mysqli_fetch_assoc($booking_requests)){
        echo "<tr id='row-".$row['request_id']."'>";
        echo "<td>".$cnt."</td>";
        echo "<td>".htmlspecialchars($row['user_name'])."</td>";
        echo "<td>".htmlspecialchars($row['vehicle_model'])."</td>";
        echo "<td>".htmlspecialchars($row['service_name'])."</td>";
        echo "<td>".htmlspecialchars($row['city_name'])."</td>";
        echo "<td>".htmlspecialchars($row['landmark'])."</td>";
        $status=$row['request_status'];
        echo "<td><span class='badge badge-".$status."'>".ucfirst($status)."</span></td>";
        echo "<td>".($row['responded_at'] ?? 'N/A')."</td>";
        echo "<td>";
        if($status==='pending'){
            echo "<button class='approve' data-id='".$row['request_id']."'>Approve</button>";
            echo "<button class='reject' data-id='".$row['request_id']."'>Reject</button>";
            echo "<button class='delete' data-id='".$row['request_id']."'>Delete</button>";
        } else {
            echo "<button class='delete' data-id='".$row['request_id']."'>Delete</button>";
        }
        echo "</td></tr>";
        $cnt++;
    }
}else{
    echo "<tr><td colspan='9' style='text-align:center;color:#6b7280;'>No booking requests assigned.</td></tr>";
}
?>
</tbody>
</table>
</div>

<script>
$(document).ready(function(){
    function handleAction(request_id, action){
        $.post('', {ajax_action: action, request_id: request_id}, function(res){
            let data = JSON.parse(res);
            $('#msg').text(data.msg).fadeIn().delay(2000).fadeOut();
            if(data.status===1){
                if(action==='delete'){
                    $('#row-'+request_id).fadeOut();
                } else if(action==='approve'){
                    $('#row-'+request_id+' td:nth-child(7) span').text('Accepted').attr('class','badge badge-accepted');
                    $('#row-'+request_id+' td:nth-child(9) button').remove();
                    $('#booking-table tbody tr').each(function(){
                        if($(this).attr('id')!='row-'+request_id){
                            $(this).find('td:nth-child(7) span').text('Disabled').attr('class','badge badge-disabled');
                            $(this).find('td:nth-child(9) button').remove();
                        }
                    });
                } else if(action==='reject'){
                    $('#row-'+request_id+' td:nth-child(7) span').text('Rejected').attr('class','badge badge-rejected');
                    $('#row-'+request_id+' td:nth-child(9) button').remove();
                }
            }
        });
    }
    $(document).on('click', '.approve', function(){ handleAction($(this).data('id'),'approve'); });
    $(document).on('click', '.reject', function(){ handleAction($(this).data('id'),'reject'); });
    $(document).on('click', '.delete', function(){ handleAction($(this).data('id'),'delete'); });
});
</script>

</body>
</html>
