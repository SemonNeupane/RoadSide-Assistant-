<?php
session_start();
include('../includes/dbconnection.php');

if (empty($_SESSION['admin_id']) || !isset($_GET['id'])) {
    header('location:user-locations.php');
    exit();
}

$id = (int)$_GET['id'];

$location = mysqli_query($con, "
    SELECT ul.*, c.city_name
    FROM user_location ul
    JOIN city c ON ul.city_id = c.city_id
    WHERE ul.user_location_id = '$id'
");

$data = mysqli_fetch_assoc($location);
$cities = mysqli_query($con, "SELECT city_id, city_name FROM city ORDER BY city_name ASC");
?>

<h2>Edit User Location</h2>

<form method="post" action="update-user-location.php">
    <input type="hidden" name="id" value="<?= $id ?>">

    <label>City</label>
    <select name="city_id" required>
        <?php while($c = mysqli_fetch_assoc($cities)): ?>
            <option value="<?= $c['city_id'] ?>"
                <?= $c['city_id'] == $data['city_id'] ? 'selected' : '' ?>>
                <?= $c['city_name'] ?>
            </option>
        <?php endwhile; ?>
    </select>

    <br><br>

    <label>Landmark</label>
    <input type="text" name="landmark" value="<?= htmlspecialchars($data['landmark']) ?>">

    <br><br>

    <button type="submit">Update Location</button>
</form>
