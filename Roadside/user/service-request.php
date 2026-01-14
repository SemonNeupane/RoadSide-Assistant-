<?php
session_start();
include(__DIR__ . '/../includes/dbconnection.php');

$user_id = $_SESSION['user_id'] ?? 1;
$msg = '';

/* ================= SUBMIT REQUEST ================= */
if (isset($_POST['submit'])) {

    $vehicle_type  = $_POST['vehicletype'];
    $vehicle_model = $_POST['vehiclemodel'];
    $service_id    = $_POST['service_id'];
    $province_id   = $_POST['province_id'];
    $district_id   = $_POST['district_id'];
    $city_id       = $_POST['city_id'];
    $landmark      = $_POST['pickupadd'];

    // ---- INSERT VEHICLE ----
    $stmt = $con->prepare("INSERT INTO vehicle (vehicle_type, model, user_id) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $vehicle_type, $vehicle_model, $user_id);
    $stmt->execute();
    $vehicle_id = $stmt->insert_id;
    $stmt->close();

    // ---- INSERT USER LOCATION ----
    $stmt = $con->prepare("INSERT INTO user_location (created_at, user_id, province_id, district_id, city_id, landmark) VALUES (NOW(), ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiis", $user_id, $province_id, $district_id, $city_id, $landmark);
    $stmt->execute();
    $user_location_id = $stmt->insert_id;
    $stmt->close();

    // ---- INSERT BOOKING ----
    $stmt = $con->prepare("INSERT INTO booking (user_id, agent_id, vehicle_id, service_id, city_id, status, landmark, user_location_id, created_at) VALUES (?, NULL, ?, ?, ?, 'pending', ?, ?, NOW())");
    $stmt->bind_param("iiiisi", $user_id, $vehicle_id, $service_id, $city_id, $landmark, $user_location_id);
    $stmt->execute();
    $booking_id = $stmt->insert_id;
    $stmt->close();

    // ---- SEND REQUEST TO ALL AGENTS IN SAME CITY ----
    $stmt = $con->prepare("SELECT a.agent_id FROM agent a JOIN agent_location al ON a.agent_id = al.agent_id WHERE a.status='active' AND al.city_id=?");
    $stmt->bind_param("i", $city_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $stmt_insert = $con->prepare("INSERT IGNORE INTO booking_requests (booking_id, agent_id, status, created_at) VALUES (?, ?, 'pending', NOW())");
        while ($a = $result->fetch_assoc()) {
            $stmt_insert->bind_param("ii", $booking_id, $a['agent_id']);
            $stmt_insert->execute();
        }
        $stmt_insert->close();
        $msg = "Service request sent to all agents in your city.";
    } else {
        $msg = "No agents available in your city.";
    }
    $stmt->close();
}

/* ================= FETCH FORM DATA ================= */
$province_q = $con->query("SELECT province_id, province_name FROM province");
$district_q = $con->query("SELECT district_id, district_name FROM district");
$city_q     = $con->query("SELECT city_id, city_name FROM city");
$service_q  = $con->query("SELECT service_id, service_name FROM services");
?>

<!DOCTYPE html>
<html>
<head>
<title>Service Request</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
<h2>Service Request</h2>

<?php if($msg): ?>
<p class="msg"><?= $msg ?></p>
<?php endif; ?>

<form method="post">

<label>Vehicle Type</label>
<select name="vehicletype" required>
<option value="">Select</option>
<option value="Fuel Vehicle">Fuel Vehicle</option>
<option value="EV Vehicle">EV Vehicle</option>
</select>

<label>Vehicle Model</label>
<input type="text" name="vehiclemodel" required>

<label>Service</label>
<select name="service_id" required>
<option value="">Select</option>
<?php while($s=mysqli_fetch_assoc($service_q)): ?>
<option value="<?= $s['service_id'] ?>"><?= $s['service_name'] ?></option>
<?php endwhile; ?>
</select>

<label>Province</label>
<select name="province_id" required>
<option value="">Select</option>
<?php while($p=mysqli_fetch_assoc($province_q)): ?>
<option value="<?= $p['province_id'] ?>"><?= $p['province_name'] ?></option>
<?php endwhile; ?>
</select>

<label>District</label>
<select name="district_id" required>
<option value="">Select</option>
<?php while($d=mysqli_fetch_assoc($district_q)): ?>
<option value="<?= $d['district_id'] ?>"><?= $d['district_name'] ?></option>
<?php endwhile; ?>
</select>

<label>City</label>
<select name="city_id" required>
<option value="">Select</option>
<?php while($c=mysqli_fetch_assoc($city_q)): ?>
<option value="<?= $c['city_id'] ?>"><?= $c['city_name'] ?></option>
<?php endwhile; ?>
</select>

<label>Pickup Landmark</label>
<input type="text" name="pickupadd" required>

<input type="submit" name="submit" value="Submit Request">

</form>
</div>

</body>
</html>