<?php
session_start();
include(__DIR__ . '/../includes/dbconnection.php');
// adjust path if needed

// Check if agent is logged in
if (!isset($_SESSION['sid']) || $_SESSION['role'] != 'agent' || !isset($_SESSION['agent_id'])) {
    header('Location: ../login.php');
    exit();
}

$agent_id = $_SESSION['agent_id'];
$username = $_SESSION['username'];

// Fetch bookings for this agent
$bookingQuery = mysqli_query($con, "SELECT b.booking_id, b.created_at, b.status, s.service_name, u.username AS user_name, v.vehicle_type, v.model
                                    FROM booking b
                                    JOIN services s ON b.service_id = s.service_id
                                    JOIN users u ON b.user_id = u.user_id
                                    JOIN vehicle v ON b.vehicle_id = v.vehicle_id
                                    WHERE b.agent_id = '$agent_id'
                                    ORDER BY b.created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Agent Dashboard | RSA Nepal</title>
<style>
body {
    font-family: Arial, sans-serif;
    background: #f4f6f8;
    margin: 0;
}
header {
    background: #007bff;
    color: #fff;
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
header h1 { margin: 0; font-size: 22px; }
header a { color: #fff; text-decoration: none; padding: 8px 12px; background: #0056b3; border-radius: 5px; }
header a:hover { background: #003f7f; }
.container { padding: 20px; }
h2 { margin-bottom: 15px; }
table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
}
table th, table td {
    padding: 12px 15px;
    border-bottom: 1px solid #ddd;
    text-align: left;
}
table th {
    background: #007bff;
    color: #fff;
}
table tr:hover { background: #f1f1f1; }
.status-active { color: green; font-weight: bold; }
.status-inactive { color: red; font-weight: bold; }
</style>
</head>
<body>

<header>
    <h1>Agent Dashboard</h1>
    <div>
        Welcome, <?php echo htmlspecialchars($username); ?> |
        <a href="../logout.php">Logout</a>
    </div>
</header>

<div class="container">
    <h2>Your Bookings</h2>
    <table>
        <tr>
            <th>Booking ID</th>
            <th>User</th>
            <th>Vehicle</th>
            <th>Service</th>
            <th>Date</th>
            <th>Status</th>
        </tr>
        <?php if(mysqli_num_rows($bookingQuery) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($bookingQuery)): ?>
                <tr>
                    <td><?php echo $row['booking_id']; ?></td>
                    <td><?php echo htmlspecialchars($row['user_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['vehicle_type'] . ' - ' . $row['model']); ?></td>
                    <td><?php echo htmlspecialchars($row['service_name']); ?></td>
                    <td><?php echo $row['created_at']; ?></td>
                    <td class="status-<?php echo strtolower($row['status']); ?>"><?php echo ucfirst($row['status']); ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" style="text-align:center;">No bookings found.</td>
            </tr>
        <?php endif; ?>
    </table>
</div>

</body>
</html>
