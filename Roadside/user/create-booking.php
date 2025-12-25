<?php
session_start(); include('includes/dbconnection.php');
if (!isset($_SESSION['sid'])) header('location:../logout.php');

if(isset($_POST['submit'])){
  $stmt=$con->prepare("INSERT INTO booking(user_id, agent_id, vehicle_id, service_id, city_id, created_at, landmark, status)
                       VALUES(?, NULL, ?, ?, ?, CURDATE(), ?, 'active')");
  $stmt->bind_param("iiiis",$_SESSION['sid'],$_POST['vehicle_id'],$_POST['service_id'],$_POST['city_id'],$_POST['landmark']);
  $stmt->execute();
  echo "<script>alert('Booking Created');window.location='view-booking.php';</script>";
}
?>
<html><body>
<h3>Create Booking</h3>
<form method="post">
Service:
<label for="service_id">Select Service:</label>
<select name="service_id" id="service_id" required>
  <option value="">-- Select Service --</option>
  <?php 
  $r = mysqli_query($con, "SELECT * FROM services");
  if (mysqli_num_rows($r) > 0) {
    while ($s = mysqli_fetch_assoc($r)) {
      echo "<option value='{$s['service_id']}'>{$s['service_name']}</option>";
    }
  } else {
    echo "<option value=''>No services available</option>";
  }
  ?>
</select><br>

Vehicle:
<select name="vehicle_id"><?php $r=mysqli_query($con,"SELECT * FROM vehicle WHERE user_id='{$_SESSION['sid']}'");
while($v=mysqli_fetch_assoc($r)){echo "<option value='{$v['vehicle_id']}'>{$v['model']}</option>";}?></select><br>
City ID:<input type="number" name="city_id" required><br>
Landmark:<input type="text" name="landmark" required><br>
<button name="submit">Book</button>
</form></body></html>
