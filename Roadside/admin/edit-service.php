<?php
session_start();
include('../includes/dbconnection.php');
if (empty($_SESSION['admin_id'])) {
    header('location:index.php');
    exit();
}

$id = intval($_GET['id']);
$msg = '';

$service = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM services WHERE service_id='$id'"));

if(!$service){
    die("Service not found!");
}

if(isset($_POST['update'])){
    $name = mysqli_real_escape_string($con, $_POST['service_name']);
    $desc = mysqli_real_escape_string($con, $_POST['description']);

    mysqli_query($con, "
        UPDATE services 
        SET service_name='$name', description='$desc' 
        WHERE service_id='$id'
    ");

    $msg = "Service updated successfully!";
    header("Location: services.php");
    exit();
}
?>

<h2>Edit Service</h2>
<?php if($msg) echo "<p>$msg</p>"; ?>
<form method="post">
    <label>Service Name:</label><br>
    <input type="text" name="service_name" value="<?= htmlspecialchars($service['service_name']) ?>" required><br><br>

    <label>Description:</label><br>
    <textarea name="description" required><?= htmlspecialchars($service['description']) ?></textarea><br><br>

    <input type="submit" name="update" value="Update Service">
</form>
<a href="services.php">Back to Services</a>
