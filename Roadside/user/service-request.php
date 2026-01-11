<?php
include(__DIR__ . '/../includes/dbconnection.php');

// --- SAMPLE USER ID for testing ---
$user_id = 1;  // replace with session user_id if needed

// Fetch Provinces, Districts, Cities, Services
$province_q = mysqli_query($con, "SELECT province_id, province_name FROM province ORDER BY province_name ASC");
$district_q = mysqli_query($con, "SELECT district_id, district_name, province_id FROM district ORDER BY district_name ASC");
$city_q     = mysqli_query($con, "SELECT city_id, city_name, district_id FROM city ORDER BY city_name ASC");
$service_q  = mysqli_query($con, "SELECT service_id, service_name FROM services ORDER BY service_name ASC");

// Handle Service Request Submission
$msg = '';
if(isset($_POST['submit'])){
    $vehicle_type  = $_POST['vehicletype'];
    $vehicle_model = $_POST['vehiclemodel'];
    $service_id    = intval($_POST['service_id']);
    $province_id   = intval($_POST['province_id']);
    $district_id   = intval($_POST['district_id']);
    $city_id       = intval($_POST['city_id']);
    $landmark      = trim($_POST['pickupadd']);

    // Insert vehicle
    $stmt = $con->prepare("INSERT INTO vehicle(vehicle_type, model, user_id) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $vehicle_type, $vehicle_model, $user_id);
    $stmt->execute();
    $vehicle_id = $stmt->insert_id;
    $stmt->close();

    // Insert user location
    $stmt = $con->prepare("INSERT INTO user_location(created_at, user_id, province_id, district_id, city_id, landmark)
                           VALUES(NOW(), ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiis", $user_id, $province_id, $district_id, $city_id, $landmark);
    $stmt->execute();
    $user_location_id = $stmt->insert_id;
    $stmt->close();

    // Insert booking
    $stmt = $con->prepare("INSERT INTO booking(user_id, agent_id, vehicle_id, service_id, city_id, created_at, status, landmark, user_location_id)
                           VALUES (?, NULL, ?, ?, ?, NOW(), 'pending', ?, ?)");
    $stmt->bind_param("iiiisi", $user_id, $vehicle_id, $service_id, $city_id, $landmark, $user_location_id);
    $stmt->execute();
    $booking_id = $stmt->insert_id;
    $stmt->close();

    // Send request to active agents in the same city offering this service
    $agents = mysqli_query($con, "
        SELECT a.agent_id 
        FROM agent a
        JOIN agent_service asv ON a.agent_id = asv.agent_id
        JOIN agent_location al ON a.agent_id = al.agent_id
        WHERE a.status='active'
          AND al.city_id='$city_id'
          AND asv.service_id='$service_id'
    ");

    if(mysqli_num_rows($agents) > 0){
        while($agent = mysqli_fetch_assoc($agents)){
            $aid = $agent['agent_id'];
            mysqli_query($con, "INSERT INTO booking_requests(booking_id, agent_id, status, created_at)
                                VALUES ('$booking_id', '$aid', 'pending', NOW())");
        }
        $msg = "Service request sent to available agents in your city!";
    } else {
        $msg = "No active agents available in your city for this service.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Service Request</title>
<style>
body{font-family:Arial,sans-serif;background:#f4f7fb;margin:0;padding:0;}
.container{width:90%;max-width:700px;margin:50px auto;background:#fff;padding:30px;border-radius:10px;box-shadow:0 4px 15px rgba(0,0,0,0.1);}
h2{text-align:center;color:#0A924E;margin-bottom:20px;}
label{display:block;margin-bottom:5px;font-weight:bold;color:#64748b;}
input, select{width:100%;padding:10px;margin-bottom:15px;border-radius:8px;border:1px solid #d1d5db;font-size:15px;}
input[type="submit"]{background:#0A924E;color:#fff;border:none;padding:12px;font-size:16px;cursor:pointer;border-radius:8px;transition:0.3s;}
input[type="submit"]:hover{background:#0b2e59;}
.msg{font-weight:bold;margin-bottom:15px;color:green;text-align:center;}
</style>
</head>
<body>

<div class="container">
<h2>Service Request Form</h2>
<?php if($msg != ''): ?><p class="msg"><?= $msg ?></p><?php endif; ?>

<form method="post">
<label>Vehicle Type</label>
<select name="vehicletype" required>
<option value="">Select Vehicle Type</option>
<option value="Fuel Vehicle">Fuel Vehicle</option>
<option value="EV Vehicle">EV Vehicle</option>
</select>

<label>Vehicle Model</label>
<input type="text" name="vehiclemodel" required>

<label>Service</label>
<select name="service_id" required>
<option value="">Select Service</option>
<?php while($s = mysqli_fetch_assoc($service_q)): ?>
<option value="<?= $s['service_id'] ?>"><?= htmlspecialchars($s['service_name']) ?></option>
<?php endwhile; ?>
</select>

<label>Province</label>
<select name="province_id" required>
<option value="">Select Province</option>
<?php
mysqli_data_seek($province_q,0);
while($p=mysqli_fetch_assoc($province_q)){
    echo "<option value='{$p['province_id']}'>{$p['province_name']}</option>";
}
?>
</select>

<label>District</label>
<select name="district_id" required>
<option value="">Select District</option>
<?php
mysqli_data_seek($district_q,0);
while($d=mysqli_fetch_assoc($district_q)){
    echo "<option value='{$d['district_id']}'>{$d['district_name']}</option>";
}
?>
</select>

<label>City</label>
<select name="city_id" required>
<option value="">Select City</option>
<?php
mysqli_data_seek($city_q,0);
while($c=mysqli_fetch_assoc($city_q)){
    echo "<option value='{$c['city_id']}'>{$c['city_name']}</option>";
}
?>
</select>

<label>Landmark / Pickup Location</label>
<input type="text" name="pickupadd" required>

<input type="submit" name="submit" value="Submit Request">
</form>
</div>

</body>
</html>
