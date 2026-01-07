<?php
session_start();
include('../includes/dbconnection.php');

if(empty($_SESSION['agent_id'])) {
    header('location:../login.php'); 
    exit();
}
$agent_id = $_SESSION['agent_id'];

// Fetch assigned bookings that are not completed
$assigned_bookings = mysqli_query($con, "
    SELECT b.booking_id, u.username AS user_name, v.model AS vehicle_model, s.service_name, c.city_name, b.status, b.created_at
    FROM booking b
    JOIN users u ON b.user_id = u.user_id
    JOIN vehicle v ON b.vehicle_id = v.vehicle_id
    JOIN services s ON b.service_id = s.service_id
    JOIN city c ON b.city_id = c.city_id
    WHERE b.agent_id='$agent_id' AND b.completed_at IS NULL
    ORDER BY b.created_at DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Assigned Bookings | RSA Nepal</title>

<link href="../assets/css/bootstrap.min.css" rel="stylesheet">
<link href="../assets/css/style.css" rel="stylesheet">

<style>
/* Main Content */
.main-content {
    margin-left: 260px; /* sidebar width */
    margin-top: 60px;   /* header height */
    padding: 25px;
    min-height: 100vh;
    background: #f3f4f6;
}

/* Page Title */
h3 {
    font-size: 20px;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 20px;
}

/* Table Wrapper */
.table-wrapper {
    overflow-x: auto;
    border-radius: 12px;
}

/* Table Styling */
table {
    width: 100%;
    border-collapse: collapse;
    background: #ffffff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 10px 25px rgba(0,0,0,0.05);
    font-family: Arial, Helvetica, sans-serif;
}

/* Table headers */
table thead tr {
    background: linear-gradient(135deg, #2563eb, #1e40af);
    color: #fff;
    font-weight: 600;
    text-align: left;
}

table thead th {
    padding: 12px 15px;
    font-size: 14px;
}

/* Table body rows */
table tbody tr {
    border-bottom: 1px solid #e5e7eb;
    transition: background 0.2s;
}

table tbody tr:hover {
    background: #f0f4ff;
}

table tbody td {
    padding: 10px 15px;
    font-size: 13px;
    color: #1f2937;
}

/* Status badges */
table tbody td span {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
}

table tbody td span.active {
    background: #2563eb;
    color: #fff;
}

table tbody td span.inactive {
    background: #6b7280;
    color: #fff;
}

/* Responsive for small screens */
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
</style>
</head>
<body>

<div id="wrapper">

    <!-- Sidebar -->
    <?php include('includes/sidebar.php'); ?>

    <!-- Header -->
    <?php include('includes/header.php'); ?>

    <!-- Main Content -->
    <div class="main-content">
        <h3>Assigned Bookings</h3>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Vehicle</th>
                        <th>Service</th>
                        <th>City</th>
                        <th>Status</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($assigned_bookings) > 0) {
                        while($b = mysqli_fetch_assoc($assigned_bookings)) { ?>
                            <tr>
                                <td data-label="ID"><?php echo $b['booking_id']; ?></td>
                                <td data-label="User"><?php echo htmlspecialchars($b['user_name']); ?></td>
                                <td data-label="Vehicle"><?php echo htmlspecialchars($b['vehicle_model']); ?></td>
                                <td data-label="Service"><?php echo htmlspecialchars($b['service_name']); ?></td>
                                <td data-label="City"><?php echo htmlspecialchars($b['city_name']); ?></td>
                                <td data-label="Status">
                                    <span class="<?php echo $b['status']=='active'?'active':'inactive'; ?>">
                                        <?php echo ucfirst($b['status']); ?>
                                    </span>
                                </td>
                                <td data-label="Created At"><?php echo $b['created_at']; ?></td>
                            </tr>
                    <?php }
                    } else { ?>
                        <tr>
                            <td colspan="7" style="text-align:center; padding:20px; color:#6b7280;">
                                No assigned bookings found.
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script src="../assets/js/jquery.min.js"></script>
<script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
