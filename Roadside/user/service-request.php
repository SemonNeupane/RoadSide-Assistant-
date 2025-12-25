<?php
session_start();
error_reporting(E_ALL);
include(__DIR__ . '/../includes/dbconnection.php'); // adjust path if needed

if(strlen($_SESSION['sid'])==0){
    header('location:logout.php');
    exit();
}

if(isset($_POST['submit'])){

    $user_id       = $_SESSION['sid'];
    $vehicle_type  = $_POST['vehicletype'];
    $model         = $_POST['vehilemodel'];
    $registration  = $_POST['vehicleregno'];
    $service_id    = $_POST['service'];
    $city_id       = $_POST['city'];
    $landmark      = $_POST['pickupadd'];
    $created_at    = date('Y-m-d');
    $status        = 'active';
    $agent_id      = 1; // default agent

    // INSERT VEHICLE
    $stmt = $con->prepare("INSERT INTO vehicle(vehicle_type, model, registration_no, user_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param('sssi', $vehicle_type, $model, $registration, $user_id);
    if(!$stmt->execute()){
        echo "<script>alert('Error inserting vehicle: ".$stmt->error."');</script>";
        exit;
    }
    $vehicle_id = $stmt->insert_id;
    $stmt->close();

    // INSERT USER LOCATION
    $stmt = $con->prepare("INSERT INTO user_location(created_at, user_id, city_id, landmark) VALUES (NOW(), ?, ?, ?)");
    $stmt->bind_param('iis', $user_id, $city_id, $landmark);
    if(!$stmt->execute()){
        echo "<script>alert('Error inserting location: ".$stmt->error."');</script>";
        exit;
    }
    $user_location_id = $stmt->insert_id;
    $stmt->close();

    // INSERT BOOKING
    $stmt = $con->prepare("
        INSERT INTO booking(
            user_id, agent_id, vehicle_id, service_id,
            city_id, created_at, status, landmark, user_location_id
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param(
        'iiiiisssi',
        $user_id,
        $agent_id,
        $vehicle_id,
        $service_id,
        $city_id,
        $created_at,
        $status,
        $landmark,
        $user_location_id
    );
    if($stmt->execute()){
        echo "<script>alert('Service Request Submitted Successfully');</script>";
        echo "<script>window.location='service-request.php'</script>";
    } else {
        echo "<script>alert('Error submitting booking: ".$stmt->error."');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Roadside Assistance - Service Request</title>
<link href="../assets/css/bootstrap.min.css" rel="stylesheet">
<link href="../assets/css/style.css" rel="stylesheet">
<style>
html, body {height:100%; margin:0; font-family:Arial,Helvetica,sans-serif; background:#f4f6f8;}
#wrapper {display:flex; min-height:100vh; flex-direction:column;}
.content-page {flex:1; padding:30px; margin-left:260px;}
.card-box {background:#fff; border:1px solid #e5e7eb; border-radius:4px; padding:25px 30px; max-width:900px; margin:auto;}
.header-title {font-size:16px; font-weight:600; text-transform:uppercase; border-bottom:1px solid #e5e7eb; padding-bottom:10px; margin-bottom:25px;}
.form-group {display:flex; align-items:center; margin-bottom:15px;}
.col-form-label {width:220px; font-size:13px; font-weight:600;}
.form-control {flex:1; height:36px; padding:6px 10px; border:1px solid #cbd5e1; border-radius:3px; font-size:13px;}
.form-control:focus {outline:none; border-color:#60a5fa; box-shadow:0 0 0 2px rgba(96,165,250,.2);}
.btn-info {background:#38bdf8; border:none; color:#fff; font-size:13px; padding:8px 24px; border-radius:3px;}
.btn-info:hover {background:#0ea5e9;}
.text-center {text-align:center;}
footer {margin-top:auto; padding:12px 0; text-align:center; font-size:13px; color:#64748b; border-top:1px solid #e5e7eb; background:#fff;}
@media(max-width:768px){
    .content-page{margin-left:0;}
    .form-group{flex-direction:column; align-items:flex-start;}
    .col-form-label{width:100%; margin-bottom:6px;}
}
</style>
</head>
<body>

<div id="wrapper">

<?php include('includes/sidebar.php'); ?>
<div class="content-page">
<?php include('includes/header.php'); ?>

<div class="content">
<div class="container-fluid">
<div class="card-box">
<h4 class="header-title">Service Request Form</h4>
<form method="post">
    <div class="form-group">
        <label class="col-form-label">Vehicle Type</label>
        <input type="text" name="vehicletype" class="form-control" required>
    </div>

    <div class="form-group">
        <label class="col-form-label">Vehicle Model</label>
        <input type="text" name="vehilemodel" class="form-control" required>
    </div>

    <div class="form-group">
        <label class="col-form-label">Registration No</label>
        <input type="text" name="vehicleregno" class="form-control" required>
    </div>

    <div class="form-group">
        <label class="col-form-label">Service</label>
        <select name="service" class="form-control" required>
            <option value="">Select Service</option>
            <?php
            $q = mysqli_query($con,"SELECT * FROM services");
            while($r = mysqli_fetch_array($q)){
                echo "<option value='{$r['service_id']}'>{$r['service_name']}</option>";
            }
            ?>
        </select>
    </div>

    <div class="form-group">
        <label class="col-form-label">City</label>
        <select name="city" class="form-control" required>
            <option value="">Select City</option>
            <?php
            $q = mysqli_query($con,"SELECT * FROM city");
            while($r = mysqli_fetch_array($q)){
                echo "<option value='{$r['city_id']}'>{$r['city_name']}</option>";
            }
            ?>
        </select>
    </div>

    <div class="form-group">
        <label class="col-form-label">Landmark / Pickup Location</label>
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

<script src="../assets/js/jquery.min.js"></script>
<script src="../assets/js/bootstrap.bundle.min.js"></script>

</body>
</html>
