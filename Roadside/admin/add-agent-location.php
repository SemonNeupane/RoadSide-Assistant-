<?php
session_start();
include('../includes/dbconnection.php');

if (empty($_SESSION['admin_id'])) {
    header('location:/index.php');
    exit();
}

if (isset($_POST['submit'])) {
    $agent_id   = $_POST['agent_id'];
    $province   = $_POST['province_id'];
    $district   = $_POST['district_id'];
    $city       = $_POST['city_id'];
    $landmark   = $_POST['landmark'];

    mysqli_query($con, "
        INSERT INTO agent_location
        (agent_id, province_id, district_id, city_id, landmark)
        VALUES
        ('$agent_id','$province','$district','$city','$landmark')
    ");

    header('location:agent-locations.php');
    exit();
}
?>

<h2>Add Agent Location</h2>

<form method="post">

<select name="agent_id" required>
    <option value="">Select Agent</option>
    <?php
    $agents = mysqli_query($con, "
        SELECT a.agent_id, u.username 
        FROM agent a 
        JOIN users u ON a.user_id = u.user_id
        WHERE a.status='active'
    ");
    while($a = mysqli_fetch_assoc($agents)):
    ?>
    <option value="<?= $a['agent_id'] ?>"><?= $a['username'] ?></option>
    <?php endwhile; ?>
</select><br><br>

<input type="text" name="province_id" placeholder="Province ID" required><br>
<input type="text" name="district_id" placeholder="District ID" required><br>
<input type="text" name="city_id" placeholder="City ID" required><br>

<input type="text" name="landmark" placeholder="Landmark"><br><br>

<button type="submit" name="submit">Save</button>
</form>
<?php include('includes/sidebar.php'); ?>
<?php include('includes/header.php'); ?>
