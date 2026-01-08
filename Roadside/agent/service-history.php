<?php
session_start();
include('../includes/dbconnection.php');

if(empty($_SESSION['agent_id'])) {
    header('location:../login.php'); 
    exit();
}
$agent_id = $_SESSION['agent_id'];

// Fetch completed bookings
$completed_services = mysqli_query($con, "
    SELECT b.booking_id, u.username AS user_name, v.model AS vehicle_model, s.service_name, c.city_name, b.completed_at
    FROM booking b
    JOIN users u ON b.user_id = u.user_id
    JOIN vehicle v ON b.vehicle_id = v.vehicle_id
    JOIN services s ON b.service_id = s.service_id
    JOIN city c ON b.city_id = c.city_id
    WHERE b.agent_id='$agent_id' AND b.completed_at IS NOT NULL
    ORDER BY b.completed_at DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Service History | RSA Nepal</title>

<link href="../assets/css/bootstrap.min.css" rel="stylesheet">
<link href="../assets/css/style.css" rel="stylesheet">

<style>
/* Main Content Area */
.main-content {
    margin-left: 260px; /* matches sidebar width */
    margin-top: 60px;   /* matches header height */
    padding: 20px;
    min-height: 100vh;
    background: #f3f4f6;
    font-family: Arial, Helvetica, sans-serif;
}

/* Page Title */
.main-content h3 {
    font-size: 20px;
    font-weight: 600;
    color: #203a4a;
    margin-bottom: 20px;
}

/* Table Container */
.table-responsive {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    overflow: hidden;
}

/* Table Styling */
table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}

/* Table Header */
table thead tr {
    background: linear-gradient(135deg, #2563eb, #1e40af);
    color: #fff;
    font-weight: 600;
    text-align: left;
}

table thead th {
    padding: 12px 15px;
}

/* Table Body Rows */
table tbody tr {
    border-bottom: 1px solid #e5e7eb;
    transition: background 0.2s;
}

table tbody tr:hover {
    background: #f0f4ff;
}

table tbody td {
    padding: 10px 15px;
    color: #203A4A;
}

/* Completed Badge */
table tbody td span {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
    color: #fff;
    background: #6b7280; /* gray for completed */
}

/* Responsive Table for Mobile */
@media(max-width: 768px){
    table thead {
        display: none;
    }
    table, table tbody, table tr, table td {
        display: block;
        width: 100%;
    }
    table tbody tr {
        margin-bottom: 15px;
        border-bottom: 2px solid #e5e7eb;
    }
    table tbody td {
        padding-left: 50%;
        position: relative;
        text-align: left;
    }
    table tbody td::before {
        content: attr(data-label);
        position: absolute;
        left: 15px;
        top: 10px;
        font-weight: 600;
        color: #2563eb;
        font-size: 13px;
    }
}

/* Scrollbar for long tables */
.table-responsive {
    max-height: calc(100vh - 120px);
    overflow-y: auto;
}
.table-responsive::-webkit-scrollbar {
    width: 6px;
}
.table-responsive::-webkit-scrollbar-track {
    background: #f3f4f6;
}
.table-responsive::-webkit-scrollbar-thumb {
    background: rgba(0,0,0,0.2);
    border-radius: 10px;
}
</style>
</head>
<body>

<?php include('includes/sidebar.php'); ?>
<?php include('includes/header.php'); ?>

<div class="main-content">
    <h3>Service History</h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Vehicle</th>
                    <th>Service</th>
                    <th>City</th>
                    <th>Completed At</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if(mysqli_num_rows($completed_services) > 0){
                    while($s=mysqli_fetch_assoc($completed_services)){ ?>
                        <tr>
                            <td data-label="ID"><?php echo $s['booking_id']; ?></td>
                            <td data-label="User"><?php echo htmlspecialchars($s['user_name']); ?></td>
                            <td data-label="Vehicle"><?php echo htmlspecialchars($s['vehicle_model']); ?></td>
                            <td data-label="Service"><?php echo htmlspecialchars($s['service_name']); ?></td>
                            <td data-label="City"><?php echo htmlspecialchars($s['city_name']); ?></td>
                            <td data-label="Completed At"><?php echo $s['completed_at']; ?></td>
                            <td data-label="Status">
                                <span>Completed</span>
                            </td>
                        </tr>
                <?php } } else { ?>
                    <tr><td colspan="7" class="text-center text-muted">No completed services found</td></tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
