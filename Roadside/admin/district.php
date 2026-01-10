<?php
include('../includes/dbconnection.php');
if (empty($_SESSION['admin_id'])) {
    header('location:/index.php');
    exit();
}
$msg = '';

// Fetch all provinces for dropdown
$provinces = mysqli_query($con, "SELECT * FROM province ORDER BY province_name ASC");

// ADD District
if(isset($_POST['add_district'])){
    $name = mysqli_real_escape_string($con, $_POST['district_name']);
    $province_id = intval($_POST['province_id']);
    mysqli_query($con, "INSERT INTO district(district_name, province_id) VALUES('$name','$province_id')");
    $msg = "District added successfully!";
}

// EDIT District
if(isset($_POST['edit_district'])){
    $id = intval($_POST['district_id']);
    $name = mysqli_real_escape_string($con, $_POST['district_name']);
    $province_id = intval($_POST['province_id']);
    mysqli_query($con, "UPDATE district SET district_name='$name', province_id='$province_id' WHERE district_id='$id'");
    $msg = "District updated successfully!";
}

// DELETE District
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    mysqli_query($con, "DELETE FROM district WHERE district_id='$id'");
    $msg = "District deleted successfully!";
}

// Fetch all districts
$districts = mysqli_query($con, "
    SELECT d.*, p.province_name 
    FROM district d
    JOIN province p ON d.province_id=p.province_id
    ORDER BY d.district_name ASC
");
?>

<h2>Districts</h2>
<?php if($msg != '') echo "<p style='color:green'>$msg</p>"; ?>

<form method="post">
    <input type="hidden" name="district_id">
    <select name="province_id" required>
        <option value="">Select Province</option>
        <?php while($p=mysqli_fetch_assoc($provinces)) echo "<option value='{$p['province_id']}'>{$p['province_name']}</option>"; ?>
    </select>
    <input type="text" name="district_name" placeholder="District Name" required>
    <input type="submit" name="add_district" value="Add District">
</form>

<table border="1" cellpadding="5" cellspacing="0">
<tr><th>ID</th><th>Name</th><th>Province</th><th>Action</th></tr>
<?php while($d=mysqli_fetch_assoc($districts)): ?>
<tr>
    <td><?php echo $d['district_id']; ?></td>
    <td><?php echo $d['district_name']; ?></td>
    <td><?php echo $d['province_name']; ?></td>
    <td>
        <a href="district.php?edit=<?php echo $d['district_id']; ?>">Edit</a> |
        <a href="district.php?delete=<?php echo $d['district_id']; ?>" onclick="return confirm('Delete?')">Delete</a>
    </td>
</tr>
<?php endwhile; ?>
</table>
