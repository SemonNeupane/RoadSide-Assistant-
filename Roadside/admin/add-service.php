<?php
session_start();
include('../includes/dbconnection.php');
if (empty($_SESSION['admin_id'])) {
    header('location:index.php');
    exit();
}
$msg = '';

if(isset($_POST['add'])){
    $name = mysqli_real_escape_string($con, $_POST['service_name']);
    $desc = mysqli_real_escape_string($con, $_POST['description']);

    if($name != '' && $desc != ''){
        $query = mysqli_query($con, "INSERT INTO services(service_name, description) VALUES ('$name','$desc')");
        if($query){
            $msg = "Service added successfully!";
            header("Location: services.php"); // redirect after insert
            exit();
        } else {
            $msg = "Database error: " . mysqli_error($con);
        }
    } else {
        $msg = "Please fill all fields!";
    }
}
?>

<h2>Add Service</h2>
<?php if($msg) echo "<p style='color:red'>$msg</p>"; ?>

<form method="post" action="">
    <label>Service Name:</label><br>
    <input type="text" name="service_name" required><br><br>

    <label>Description:</label><br>
    <textarea name="description" required></textarea><br><br>

    <input type="submit" name="add" value="Add Service">
</form>

<a href="services.php">Back to Services</a>
<?php include('includes/sidebar.php'); ?>
<?php include('includes/header.php'); ?>
