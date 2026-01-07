<?php
session_start();
include(__DIR__ . '/../includes/dbconnection.php');

// ✅ Only allow logged-in users
if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header('location:../login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$msg = '';

if (isset($_POST['submit'])) {
    $vehicle_type = $_POST['vehicletype'];
    $model        = $_POST['vehiclemodel'];
    $registration = $_POST['vehicleregno'];
    $service_id   = $_POST['service'];
    $city_id      = $_POST['city'];
    $landmark     = $_POST['pickupadd'];
    $created_at   = date('Y-m-d H:i:s');
    $status       = 'active';

    // ===== Insert Vehicle =====
    $stmt = $con->prepare("INSERT INTO vehicle(vehicle_type, model, registration_no, user_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $vehicle_type, $model, $registration, $user_id);
    if (!$stmt->execute()) {
        $msg = "Error inserting vehicle: " . $stmt->error;
    } else {
        $vehicle_id = $stmt->insert_id;
        $stmt->close();

        // ===== Insert User Location =====
        $stmt = $con->prepare("INSERT INTO user_location(created_at, user_id, city_id, landmark) VALUES (NOW(), ?, ?, ?)");
        $stmt->bind_param("iis", $user_id, $city_id, $landmark);
        if (!$stmt->execute()) {
            $msg = "Error inserting location: " . $stmt->error;
        } else {
            $user_location_id = $stmt->insert_id;
            $stmt->close();

            // ===== Assign Agent =====
            // Pick first active agent for this city & service
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
                $agent_id = 1; // fallback default agent
            }

            // ===== Insert Booking =====
            $stmt = $con->prepare("
                INSERT INTO booking(user_id, agent_id, vehicle_id, service_id, city_id, created_at, status, landmark, user_location_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->bind_param("iiiiisssi", $user_id, $agent_id, $vehicle_id, $service_id, $city_id, $created_at, $status, $landmark, $user_location_id);

            if ($stmt->execute()) {
                $msg = "Service Request Submitted Successfully!";
            } else {
                $msg = "Error submitting booking: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>RSA Nepal | Service Request</title>

<style>

:root {
    --sidebar-width: 260px; /* same as sidebar */
    --header-height: 60px;  /* same as header */
}

/* ===== MAIN CONTENT ===== */
.content {
    margin-left: var(--sidebar-width);
    margin-top: var(--header-height);
    padding: 25px;
    min-height: 100vh;
    background: #f3f4f6; /* light background like user dashboard */
}

/* ===== CARD BOX ===== */
.card-box {
    background: #ffffff;
    padding: 25px 30px;
    border-radius: 14px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}

/* CARD TITLE */
.card-box h4.header-title {
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 20px;
    color: #0f172a;
}

/* ===== FORM ELEMENTS ===== */
.form-group {
    margin-bottom: 18px;
}

.form-group label {
    font-weight: 500;
    color: #334155;
    margin-bottom: 6px;
    display: block;
}

.form-control {
    width: 100%;
    padding: 10px 12px;
    font-size: 14px;
    border-radius: 10px;
    border: 1px solid #d1d5db;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #38bdf8;
    box-shadow: 0 0 5px rgba(56, 189, 248, 0.4);
    outline: none;
}

/* ===== BUTTONS ===== */
.btn-info {
    background: #38bdf8;
    color: #ffffff;
    padding: 10px 20px;
    font-size: 14px;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-info:hover {
    background: #0ea5e9;
}

/* ===== SUCCESS MESSAGE ===== */
p[style*="color:green"] {
    font-weight: 500;
    color: #16a34a !important;
    margin-bottom: 15px;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 991px) {
    .content {
        margin-left: 0;
        padding: 15px;
    }

    .card-box {
        padding: 20px;
    }
}

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
        <p style="color:green;"><?php echo $msg; ?></p>
    <?php endif; ?>

    <form method="post">
        <div class="form-group">
            <label>Vehicle Type</label>
            <input type="text" name="vehicletype" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Vehicle Model</label>
            <input type="text" name="vehiclemodel" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Registration No</label>
            <input type="text" name="vehicleregno" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Service</label>
            <select name="service" class="form-control" required>
                <option value="">Select Service</option>
                <?php
                $q = mysqli_query($con,"SELECT * FROM services");
                while($r = mysqli_fetch_assoc($q)){
                    echo "<option value='{$r['service_id']}'>{$r['service_name']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label>City</label>
            <select name="city" class="form-control" required>
                <option value="">Select City</option>
                <?php
                $q = mysqli_query($con,"SELECT * FROM city");
                while($r = mysqli_fetch_assoc($q)){
                    echo "<option value='{$r['city_id']}'>{$r['city_name']}</option>";
                }
                ?>
            </select>
        </div>

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
