<?php
session_start();
include('../includes/dbconnection.php');

if (empty($_SESSION['admin_id']) || !isset($_GET['id'])) {
    header('location:agent-locations.php');
    exit();
}

$id = (int) $_GET['id'];

if (isset($_POST['update'])) {
    mysqli_query($con, "
        UPDATE agent_location SET
            province_id='{$_POST['province_id']}',
            district_id='{$_POST['district_id']}',
            city_id='{$_POST['city_id']}',
            landmark='{$_POST['landmark']}'
        WHERE agent_location_id='$id'
    ");

    header('location:agent-locations.php');
    exit();
}

$data = mysqli_fetch_assoc(
    mysqli_query($con, "SELECT * FROM agent_location WHERE agent_location_id='$id'")
);
?>

<h2>Edit Agent Location</h2>

<form method="post">
<input type="text" name="province_id" value="<?= $data['province_id'] ?>" required><br>
<input type="text" name="district_id" value="<?= $data['district_id'] ?>" required><br>
<input type="text" name="city_id" value="<?= $data['city_id'] ?>" required><br>
<input type="text" name="landmark" value="<?= $data['landmark'] ?>"><br><br>

<button name="update">Update</button>
</form>
