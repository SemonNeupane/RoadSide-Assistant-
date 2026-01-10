<?php
session_start();
include('../includes/dbconnection.php');
if (empty($_SESSION['admin_id'])) {
    header('location:/index.php');
    exit();
}

$msg = '';

// Fetch agents and services for dropdown
$agents = mysqli_query($con, "
    SELECT a.agent_id, u.username 
    FROM agent a
    JOIN users u ON a.user_id = u.user_id
    WHERE a.status='active'
    ORDER BY u.username ASC
");
$services = mysqli_query($con, "SELECT service_id, service_name FROM services ORDER BY service_name ASC");

// Fetch agent locations
$locations = mysqli_query($con, "
    SELECT al.agent_city_id, u.username AS agent_name, c.city_name
    FROM agent_location al
    JOIN agent a ON al.agent_id = a.agent_id
    JOIN users u ON a.user_id = u.user_id
    JOIN city c ON al.city_id = c.city_id
");
if(isset($_POST['add'])){
    $agent_id = intval($_POST['agent_id']);
    $service_id = intval($_POST['service_id']);
    $agent_city_id = intval($_POST['agent_city_id']) ?: null;

    // Check duplicate
    $check = mysqli_query($con, "SELECT * FROM agent_service WHERE agent_id='$agent_id' AND service_id='$service_id' AND agent_city_id ".($agent_city_id?"='$agent_city_id'":"IS NULL"));
    if(mysqli_num_rows($check) > 0){
        $msg = "This agent already has this service in selected city!";
    } else {
        $insert = mysqli_query($con, "INSERT INTO agent_service(agent_id, service_id, agent_city_id) VALUES('$agent_id','$service_id', ".($agent_city_id ? "'$agent_city_id'" : "NULL").")");
        if($insert){
            header("Location: agent-services.php");
            exit();
        } else {
            $msg = "Error: ".mysqli_error($con);
        }
    }
}
?>

<h2>Add Agent Service</h2>
<?php if($msg) echo "<p style='color:red'>$msg</p>"; ?>

<form method="post" action="">
    <label>Agent:</label><br>
    <select name="agent_id" required>
        <option value="">Select Agent</option>
        <?php while($a = mysqli_fetch_assoc($agents)) { ?>
            <option value="<?php echo $a['agent_id']; ?>"><?php echo htmlspecialchars($a['username']); ?></option>
        <?php } ?>
    </select><br><br>

    <label>Service:</label><br>
    <select name="service_id" required>
        <option value="">Select Service</option>
        <?php while($s = mysqli_fetch_assoc($services)) { ?>
            <option value="<?php echo $s['service_id']; ?>"><?php echo htmlspecialchars($s['service_name']); ?></option>
        <?php } ?>
    </select><br><br>

    <label>City (Optional):</label><br>
    <select name="agent_city_id">
        <option value="">Select City</option>
        <?php while($l = mysqli_fetch_assoc($locations)) { ?>
            <option value="<?php echo $l['agent_city_id']; ?>"><?php echo htmlspecialchars($l['agent_name'].' - '.$l['city_name']); ?></option>
        <?php } ?>
    </select><br><br>

    <input type="submit" name="add" value="Add Service">
</form>
<a href="agent-services.php">Back</a>
<?php include('includes/sidebar.php'); ?>
<?php include('includes/header.php'); ?>
