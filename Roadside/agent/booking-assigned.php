<?php
session_start();
include(__DIR__ . '/../includes/dbconnection.php');

$agent_id = $_SESSION['agent_id'] ?? 1;

/* ================= HANDLE ACTION ================= */
if (isset($_POST['ajax_action'], $_POST['request_id'])) {

    $request_id = $_POST['request_id'];
    $action = $_POST['ajax_action'];

    // Get booking linked with this request
    $req = mysqli_query($con,"
        SELECT booking_id
        FROM booking_requests
        WHERE request_id='$request_id'
          AND agent_id='$agent_id'
    ");

    if (!mysqli_num_rows($req)) {
        echo json_encode(['status'=>0,'msg'=>'Request not found']);
        exit;
    }

    $data = mysqli_fetch_assoc($req);
    $booking_id = $data['booking_id'];

    /* ===== APPROVE ===== */
    if ($action == 'approve') {

        // Check if booking already accepted
        $check = mysqli_query($con,"
            SELECT status FROM booking
            WHERE booking_id='$booking_id'
        ");
        $b = mysqli_fetch_assoc($check);

        if ($b['status'] == 'active') {
            echo json_encode(['status'=>0,'msg'=>'Another agent already accepted this request']);
            exit;
        }

        // Accept this agent
        mysqli_query($con,"
            UPDATE booking_requests
            SET status='accepted', responded_at=NOW()
            WHERE request_id='$request_id'
        ");

        // Disable other agents
        mysqli_query($con,"
            UPDATE booking_requests
            SET status='disabled'
            WHERE booking_id='$booking_id'
              AND agent_id!='$agent_id'
        ");

        // Assign booking
        mysqli_query($con,"
            UPDATE booking
            SET agent_id='$agent_id', status='active'
            WHERE booking_id='$booking_id'
        ");

        echo json_encode(['status'=>1,'msg'=>'You accepted the request']);
        exit;
    }

    /* ===== REJECT ===== */
    if ($action == 'reject') {
        mysqli_query($con,"
            UPDATE booking_requests
            SET status='rejected', responded_at=NOW()
            WHERE request_id='$request_id'
        ");
        echo json_encode(['status'=>1,'msg'=>'Request rejected']);
        exit;
    }

    /* ===== DELETE ===== */
    if ($action == 'delete') {
        mysqli_query($con,"
            DELETE FROM booking_requests
            WHERE request_id='$request_id'
              AND agent_id='$agent_id'
        ");
        echo json_encode(['status'=>1,'msg'=>'Request removed']);
        exit;
    }
}

/* ================= FETCH REQUESTS ================= */
$requests = mysqli_query($con,"
    SELECT br.request_id, br.status, br.responded_at,
           b.landmark, u.username, v.model, s.service_name, c.city_name
    FROM booking_requests br
    JOIN booking b ON b.booking_id = br.booking_id
    JOIN users u ON u.user_id = b.user_id
    JOIN vehicle v ON v.vehicle_id = b.vehicle_id
    JOIN services s ON s.service_id = b.service_id
    JOIN city c ON c.city_id = b.city_id
    WHERE br.agent_id='$agent_id'
    ORDER BY br.request_id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Assigned Requests</title>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
<h2>Assigned Booking Requests</h2>
<div id="msg"></div>

<table>
<tr>
<th>#</th>
<th>User</th>
<th>Vehicle</th>
<th>Service</th>
<th>City</th>
<th>Landmark</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php
$count = 1;
if (mysqli_num_rows($requests)) {
    while ($r = mysqli_fetch_assoc($requests)) {
        echo "<tr id='row-{$r['request_id']}'>";
        echo "<td>$count</td>";
        echo "<td>{$r['username']}</td>";
        echo "<td>{$r['model']}</td>";
        echo "<td>{$r['service_name']}</td>";
        echo "<td>{$r['city_name']}</td>";
        echo "<td>{$r['landmark']}</td>";
        echo "<td>{$r['status']}</td>";
        echo "<td>";

        if ($r['status'] == 'pending') {
            echo "<button onclick=\"actionReq({$r['request_id']},'approve')\">Approve</button>";
            echo "<button onclick=\"actionReq({$r['request_id']},'reject')\">Reject</button>";
        }
        echo "<button onclick=\"actionReq({$r['request_id']},'delete')\">Delete</button>";

        echo "</td></tr>";
        $count++;
    }
} else {
    echo "<tr><td colspan='8'>No requests</td></tr>";
}
?>
</table>
</div>

<script>
function actionReq(id, action) {
    $.post('', { request_id: id, ajax_action: action }, function(res){
        let data = JSON.parse(res);
        alert(data.msg);
        if (data.status == 1) location.reload();
    });
}
</script>

</body>
</html>
