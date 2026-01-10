<?php
include('../includes/dbconnection.php');
$msg = '';

// DELETE Booking
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    mysqli_query($con, "DELETE FROM booking WHERE booking_id='$id'");
    $msg = "Booking deleted successfully!";
}

// UPDATE Booking status
if(isset($_POST['update_status'])){
    $id = intval($_POST['booking_id']);
    $status = $_POST['status'];
    mysqli_query($con, "UPDATE booking SET status='$status' WHERE booking_id='$id'");
    $msg = "Booking status updated!";
}

// FETCH ALL BOOKINGS
$bookings = mysqli_query($con, "
    SELECT b.*, u.username AS user_name, a.agent_id AS agent_id, v.registration_no AS vehicle_no, 
           s.service_name, c.city_name, ul.landmark
    FROM booking b
    JOIN users u ON b.user_id=u.user_id
    JOIN agent a ON b.agent_id=a.agent_id
    JOIN vehicle v ON b.vehicle_id=v.vehicle_id
    JOIN services s ON b.service_id=s.service_id
    JOIN city c ON b.city_id=c.city_id
    LEFT JOIN user_location ul ON b.user_location_id=ul.user_location_id
    ORDER BY b.created_at DESC
");
?>

<h2>All Bookings</h2>
<?php if($msg != '') echo "<p style='color:green'>$msg</p>"; ?>

<table border="1" cellpadding="5" cellspacing="0">
<tr>
    <th>ID</th>
    <th>User</th>
    <th>Agent ID</th>
    <th>Vehicle</th>
    <th>Service</th>
    <th>City</th>
    <th>Landmark</th>
    <th>Created At</th>
    <th>Status</th>
    <th>Completed At</th>
    <th>Report</th>
    <th>Action</th>
</tr>

<?php while($b=mysqli_fetch_assoc($bookings)): ?>
<tr>
    <td><?php echo $b['booking_id']; ?></td>
    <td><?php echo $b['user_name']; ?></td>
    <td><?php echo $b['agent_id']; ?></td>
    <td><?php echo $b['vehicle_no']; ?></td>
    <td><?php echo $b['service_name']; ?></td>
    <td><?php echo $b['city_name']; ?></td>
    <td><?php echo $b['landmark']; ?></td>
    <td><?php echo $b['created_at']; ?></td>
    <td>
        <form method="post" style="display:inline;">
            <input type="hidden" name="booking_id" value="<?php echo $b['booking_id']; ?>">
            <select name="status" onchange="this.form.submit()">
                <option value="active" <?php if($b['status']=='active') echo 'selected'; ?>>Active</option>
                <option value="inactive" <?php if($b['status']=='inactive') echo 'selected'; ?>>Inactive</option>
            </select>
        </form>
    </td>
    <td><?php echo $b['completed_at']; ?></td>
    <td><?php echo $b['report_details']; ?></td>
    <td>
        <a href="all-bookings.php?delete=<?php echo $b['booking_id']; ?>" onclick="return confirm('Delete this booking?')">Delete</a>
    </td>
</tr>
<?php endwhile; ?>
</table>
