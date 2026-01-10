<?php
include('../includes/dbconnection.php');
if (empty($_SESSION['admin_id'])) {
    header('location:/index.php');
    exit();
}
$msg = '';

// ADD Province
if(isset($_POST['add_province'])){
    $name = mysqli_real_escape_string($con, $_POST['province_name']);
    mysqli_query($con, "INSERT INTO province(province_name) VALUES('$name')");
    $msg = "Province added successfully!";
}

// EDIT Province
if(isset($_POST['edit_province'])){
    $id = intval($_POST['province_id']);
    $name = mysqli_real_escape_string($con, $_POST['province_name']);
    mysqli_query($con, "UPDATE province SET province_name='$name' WHERE province_id='$id'");
    $msg = "Province updated successfully!";
}

// DELETE Province
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    mysqli_query($con, "DELETE FROM province WHERE province_id='$id'");
    $msg = "Province deleted successfully!";
}

// Fetch all provinces
$provinces = mysqli_query($con, "SELECT * FROM province ORDER BY province_name ASC");
?>

<h2>Provinces</h2>
<?php if($msg != '') echo "<p style='color:green'>$msg</p>"; ?>
<form method="post">
    <input type="hidden" name="province_id">
    <input type="text" name="province_name" placeholder="Province Name" required>
    <input type="submit" name="add_province" value="Add Province">
</form>

<table border="1" cellpadding="5" cellspacing="0">
    <tr><th>ID</th><th>Name</th><th>Action</th></tr>
    <?php while($p = mysqli_fetch_assoc($provinces)): ?>
    <tr>
        <td><?php echo $p['province_id']; ?></td>
        <td><?php echo $p['province_name']; ?></td>
        <td>
            <a href="province.php?edit=<?php echo $p['province_id']; ?>">Edit</a> |
            <a href="province.php?delete=<?php echo $p['province_id']; ?>" onclick="return confirm('Delete?')">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
<?php include('includes/sidebar.php'); ?>
<?php include('includes/header.php'); ?>