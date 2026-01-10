<?php
include('../includes/dbconnection.php');

$msg='';

// Fetch provinces & districts
$provinces = mysqli_query($con, "SELECT * FROM province ORDER BY province_name ASC");
$districts = mysqli_query($con, "SELECT * FROM district ORDER BY district_name ASC");

// ADD City
if(isset($_POST['add_city'])){
    $name = mysqli_real_escape_string($con, $_POST['city_name']);
    $district_id = intval($_POST['district_id']);
    mysqli_query($con, "INSERT INTO city(city_name,district_id) VALUES('$name','$district_id')");
    $msg = "City added successfully!";
}

// EDIT City
if(isset($_POST['edit_city'])){
    $id = intval($_POST['city_id']);
    $name = mysqli_real_escape_string($con, $_POST['city_name']);
    $district_id = intval($_POST['district_id']);
    mysqli_query($con, "UPDATE city SET city_name='$name', district_id='$district_id' WHERE city_id='$id'");
    $msg = "City updated successfully!";
}

// DELETE City
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    mysqli_query($con, "DELETE FROM city WHERE city_id='$id'");
    $msg = "City deleted successfully!";
}

// Fetch all cities with district & province
$cities = mysqli_query($con, "
    SELECT c.*, d.district_name, p.province_name
    FROM city c
    JOIN district d ON c.district_id=d.district_id
    JOIN province p ON d.province_id=p.province_id
    ORDER BY c.city_name ASC
");
?>

<h2>Cities</h2>
<?php if($msg != '') echo "<p style='color:green'>$msg</p>"; ?>

<form method="post">
    <input type="hidden" name="city_id">
    <select name="district_id" required>
        <option value="">Select District</option>
        <?php while($d=mysqli_fetch_assoc($districts)) echo "<option value='{$d['district_id']}'>{$d['district_name']}</option>"; ?>
    </select>
    <input type="text" name="city_name" placeholder="City Name" required>
    <input type="submit" name="add_city" value="Add City">
</form>

<table border="1" cellpadding="5" cellspacing="0">
<tr><th>ID</th><th>City</th><th>District</th><th>Province</th><th>Action</th></tr>
<?php while($c=mysqli_fetch_assoc($cities)): ?>
<tr>
    <td><?php echo $c['city_id']; ?></td>
    <td><?php echo $c['city_name']; ?></td>
    <td><?php echo $c['district_name']; ?></td>
    <td><?php echo $c['province_name']; ?></td>
    <td>
        <a href="city.php?edit=<?php echo $c['city_id']; ?>">Edit</a> |
        <a href="city.php?delete=<?php echo $c['city_id']; ?>" onclick="return confirm('Delete?')">Delete</a>
    </td>
</tr>
<?php endwhile; ?>
</table>
