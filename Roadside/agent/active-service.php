<?php
session_start();
include('../includes/dbconnection.php');

// Check if agent is logged in
if(empty($_SESSION['agent_id'])) {
    header('location:../login.php'); 
    exit();
}
$agent_id = $_SESSION['agent_id'];

// Fetch active services (assigned & not completed)
$active_services = mysqli_query($con, "
    SELECT b.booking_id, u.username AS user_name, v.model AS vehicle_model, 
           s.service_name, c.city_name, b.status, b.created_at
    FROM booking b
    JOIN users u ON b.user_id = u.user_id
    JOIN vehicle v ON b.vehicle_id = v.vehicle_id
    JOIN services s ON b.service_id = s.service_id
    JOIN city c ON b.city_id = c.city_id
    WHERE b.agent_id='$agent_id' AND b.status='active'
    ORDER BY b.created_at DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Active Services | RSA Nepal</title>

<!-- Bootstrap -->
<link href="../assets/css/bootstrap.min.css" rel="stylesheet">
<link href="../assets/css/style.css" rel="stylesheet">

<style>
/* Main Content */
.main-content {
    margin-left: 260px; /* sidebar width */
    margin-top: 60px;   /* header height */
    padding: 20px;
    background: #f3f4f6;
    min-height: 100vh;
    font-family: Arial, Helvetica, sans-serif;
}

/* Page Title */
.main-content h3 {
    font-size: 20px;
    font-weight: 600;
    color: #203a4a;
    margin-bottom: 20px;
}

/* Table Styling */
table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

/* Table Head */
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
    font-size: 13px;
    color: #1f2937;
}

/* Status Badge */
table tbody td span {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
}

table tbody td span.active {
    background: #22c55e;
    color: #fff;
}

table tbody td span.inactive {
    background: #9ca3af;
    color: #fff;
}

/* Responsive Table */
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
        <h3>Active Services</h3>
        <div class="table-responsive">
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
                <?php if(mysqli_num_rows($active_services) > 0): ?>
                    <?php while($s = mysqli_fetch_assoc($active_services)): ?>
                        <tr>
                            <td data-label="ID"><?php echo $s['booking_id']; ?></td>
                            <td data-label="User"><?php echo htmlspecialchars($s['user_name']); ?></td>
                            <td data-label="Vehicle"><?php echo htmlspecialchars($s['vehicle_model']); ?></td>
                            <td data-label="Service"><?php echo htmlspecialchars($s['service_name']); ?></td>
                            <td data-label="City"><?php echo htmlspecialchars($s['city_name']); ?></td>
                            <td data-label="Status">
                                <span class="<?php echo $s['status']=='active'?'active':'inactive';?>">
                                    <?php echo ucfirst($s['status']); ?>
                                </span>
                            </td>
                            <td data-label="Created At"><?php echo $s['created_at']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align:center; color:#6b7280;">No Active Services Found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        </div>
    </div>
</div>

</body>
</html>
