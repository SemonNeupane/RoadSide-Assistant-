<?php
session_start();
include(__DIR__ . '/../includes/dbconnection.php');

// Only allow logged-in users
if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header('location:../login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$msg = '';

// Fetch Provinces, Districts, Cities, Services
$province_q = mysqli_query($con, "SELECT province_id, province_name FROM province ORDER BY province_name ASC");
$district_q = mysqli_query($con, "SELECT district_id, district_name, province_id FROM district ORDER BY district_name ASC");
$city_q     = mysqli_query($con, "SELECT city_id, city_name, district_id FROM city ORDER BY city_name ASC");
$service_q  = mysqli_query($con, "SELECT service_id, service_name, description FROM services ORDER BY service_name ASC");

// Handle form submission
if (isset($_POST['submit'])) {
    $vehicle_type = $_POST['vehicletype'];
    $vehicle_model = $_POST['vehiclemodel'];
    $service_id = $_POST['service_id'];
    $province_id = $_POST['province_id'];
    $district_id = $_POST['district_id'];
    $city_id = $_POST['city_id'];
    $landmark = trim($_POST['pickupadd']);
    $status = 'active';

    // ===== Insert Vehicle =====
    $stmt = $con->prepare("INSERT INTO vehicle(vehicle_type, model, user_id) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $vehicle_type, $vehicle_model, $user_id);
    if (!$stmt->execute()) {
        $msg = "Error inserting vehicle: " . $stmt->error;
    } else {
        $vehicle_id = $stmt->insert_id;
        $stmt->close();

        // ===== Insert User Location =====
        $stmt = $con->prepare("
            INSERT INTO user_location(created_at, user_id, province_id, district_id, city_id, landmark)
            VALUES (NOW(), ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("iiiis", $user_id, $province_id, $district_id, $city_id, $landmark);
        if (!$stmt->execute()) {
            $msg = "Error inserting location: " . $stmt->error;
        } else {
            $user_location_id = $stmt->insert_id;
            $stmt->close();

            // ===== Assign Agent =====
            $agent_q = mysqli_query($con, "
                SELECT a.agent_id 
                FROM agent a
                JOIN agent_location al ON a.agent_id = al.agent_id
                JOIN agent_service asv ON a.agent_id = asv.agent_id
                WHERE a.status='active' 
                  AND al.city_id='$city_id'
                  AND asv.service_id='$service_id'
                LIMIT 1
            ");

            if (mysqli_num_rows($agent_q) > 0) {
                $agent_row = mysqli_fetch_assoc($agent_q);
                $agent_id = $agent_row['agent_id'];
            } else {
                $agent_id = null;
                $msg = "No agent available in this city for the selected service.";
            }

            // ===== Insert Booking =====
            if ($agent_id) {
                $stmt = $con->prepare("
                    INSERT INTO booking(user_id, agent_id, vehicle_id, service_id, city_id, created_at, status, landmark, user_location_id)
                    VALUES (?, ?, ?, ?, ?, NOW(), 'active', ?, ?)
                ");
                $stmt->bind_param("iiiisis", $user_id, $agent_id, $vehicle_id, $service_id, $city_id, $landmark, $user_location_id);
                if ($stmt->execute()) {
                    header("Location: service-history.php?msg=success");
                    exit();
                } else {
                    $msg = "Error submitting booking: " . $stmt->error;
                }
                $stmt->close();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>User Service Request</title>
<link rel="icon" type="image/x-icon" href="../../favicon.ico">
<style>
:root { --sidebar-width: 260px; --header-height: 60px; }
.content { margin-left: var(--sidebar-width); margin-top: var(--header-height); padding: 25px; min-height: 100vh; background: #f3f4f6; }
.card-box { background: #fff; padding: 25px 30px; border-radius: 14px; box-shadow: 0 10px 25px rgba(0,0,0,0.08); max-width: 700px; margin: 0 auto; }
.card-box h4.header-title { font-size: 20px; font-weight: 600; margin-bottom: 20px; color: #0f172a; text-align: center; }
.form-group { margin-bottom: 18px; }
.form-group label { font-weight: 500; color: #334155; margin-bottom: 6px; display: block; }
.form-control { width: 100%; padding: 10px 12px; font-size: 14px; border-radius: 10px; border: 1px solid #d1d5db; transition: all 0.3s ease; }
.form-control:focus { border-color: #38bdf8; box-shadow: 0 0 5px rgba(56, 189, 248, 0.4); outline: none; }
.btn-info { background: #38bdf8; color: #fff; padding: 10px 20px; font-size: 14px; border-radius: 10px; border: none; cursor: pointer; transition: all 0.3s ease; }
.btn-info:hover { background: #0ea5e9; }
p[style*="color:green"] { font-weight: 500; color: #16a34a !important; margin-bottom: 15px; }
footer { width: calc(100% - 260px); margin-left: 260px; background: #1e293b; color: #fff; padding: 15px 20px; text-align: center; font-size: 13px; position: relative; }
</style>
</head>
<body>
<div id="wrapper">
<?php include('includes/sidebar.php'); ?>
<div class="content-page">
<?php include('includes/header.php'); ?>

<div class="content">
<div class="card-box">
<h4 class="header-title">Service Request Form</h4>

<?php if($msg != ''): ?>
<p style="color:red;"><?php echo $msg; ?></p>
<?php endif; ?>

<form method="post">

<!-- Vehicle Type -->
<div class="form-group">
<label>Vehicle Type</label>
<select name="vehicletype" class="form-control" required>
<option value="">Select Vehicle Type</option>
<option value="Fuel Vehicle">Fuel Vehicle</option>
<option value="EV Vehicle">EV Vehicle</option>
</select>
</div>

<!-- Vehicle Model -->
<div class="form-group">
<label>Vehicle Model</label>
<input type="text" name="vehiclemodel" class="form-control" required>
</div>

<!-- Service Type -->
<div class="form-group">
<label>Service</label>
<select name="service_id" class="form-control" required>
<option value="">Select Service</option>
<?php while($s = mysqli_fetch_assoc($service_q)): ?>
<option value="<?php echo $s['service_id']; ?>" title="<?php echo $s['description']; ?>">
<?php echo $s['service_name']; ?>
</option>
<?php endwhile; ?>
</select>
</div>

<!-- Province -->
<div class="form-group">
<label>Province</label>
<select name="province_id" class="form-control" required>
<option value="">Select Province</option>
<?php
mysqli_data_seek($province_q,0);
while($p = mysqli_fetch_assoc($province_q)) {
    echo "<option value='{$p['province_id']}'>{$p['province_name']}</option>";
}
?>
</select>
</div>

<!-- District -->
<div class="form-group">
<label>District</label>
<select name="district_id" class="form-control" required>
<option value="">Select District</option>
<?php
mysqli_data_seek($district_q,0);
while($d = mysqli_fetch_assoc($district_q)) {
    echo "<option value='{$d['district_id']}' data-province='{$d['province_id']}'>{$d['district_name']}</option>";
}
?>
</select>
</div>

<!-- City -->
<div class="form-group">
<label>City</label>
<select name="city_id" class="form-control" required>
<option value="">Select City</option>
<?php
mysqli_data_seek($city_q,0);
while($c = mysqli_fetch_assoc($city_q)) {
    echo "<option value='{$c['city_id']}' data-district='{$c['district_id']}'>{$c['city_name']}</option>";
}
?>
</select>
</div>

<!-- Landmark -->
<div class="form-group">
<label>Landmark / Pickup Location</label>
<input type="text" name="pickupadd" class="form-control" required>
</div>

<div class="form-group text-center">
<button type="submit" name="submit" class="btn btn-info">Submit Request</button>
</div>

</form>
</div>
</div>
</div>
<?php include('includes/footer.php'); ?>
</div>
</div>

</body>
</html>
