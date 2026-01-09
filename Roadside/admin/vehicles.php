<?php
session_start();
include('../includes/dbconnection.php');

if (empty($_SESSION['admin_id'])) {
    header('location:index.php');
    exit();
}

$result = mysqli_query($con, "
    SELECT 
        v.vehicle_id,
        u.username,
        v.vehicle_type,
        v.model,
        v.registration_no
    FROM vehicle v
    LEFT JOIN users u ON v.user_id = u.user_id
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Vehicles | Admin</title>
    <link rel="icon" type="image/x-icon" href="../../favicon.ico">
    <style>

:root {
    --sidebar-width: 260px; /* match your sidebar */
    --header-height: 60px;  /* match your header */
}

/* ===== MAIN CONTENT ===== */
.main-content {
    margin-left: var(--sidebar-width);
    margin-top: var(--header-height);
    padding: 25px;
    min-height: 100vh;
    background: #f3f4f6; /* light background */
    font-family: Arial, Helvetica, sans-serif;
}

/* Page title */
.main-content h2 {
    font-size: 22px;
    font-weight: 600;
    color: #0f172a;
    margin-bottom: 20px;
}

/* Table container */
.table-container {
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    overflow: hidden;
}

/* Table styling */
.table-container table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}

.table-container thead tr {
    background: linear-gradient(135deg, #2563eb, #1e40af);
    color: #fff;
    font-weight: 600;
    text-align: left;
}

.table-container thead th {
    padding: 12px 15px;
}

.table-container tbody tr {
    border-bottom: 1px solid #e5e7eb;
    transition: background 0.2s;
}

.table-container tbody tr:hover {
    background: #f0f4ff;
}

.table-container tbody td {
    padding: 10px 15px;
    color: #1f2937;
}
footer {
    margin-left: 260px; /* align with sidebar */
    width: calc(100% - 260px);
    background: #1e293b;
    color: #fff;
    padding: 15px 20px;
    text-align: center;
    font-size: 13px;
    position: relative;
}
/* Responsive for small screens */
@media(max-width: 768px) {
    .table-container table,
    .table-container tbody,
    .table-container tr,
    .table-container td,
    .table-container th {
        display: block;
        width: 100%;
    }

    .table-container thead {
        display: none;
    }

    .table-container tbody tr {
        margin-bottom: 15px;
        border-bottom: 2px solid #e5e7eb;
        padding: 10px 0;
    }

    .table-container tbody td {
        padding-left: 50%;
        position: relative;
        text-align: left;
    }

    .table-container tbody td::before {
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
    <div class="main-content">
    <h2>User Vehicles</h2>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Vehicle Type</th>
                    <th>Model</th>
                    <th>Registration No</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td data-label="ID"><?= $row['vehicle_id']; ?></td>
                        <td data-label="User"><?= htmlspecialchars($row['username'] ?? 'N/A'); ?></td>
                        <td data-label="Vehicle Type"><?= htmlspecialchars($row['vehicle_type']); ?></td>
                        <td data-label="Model"><?= htmlspecialchars($row['model']); ?></td>
                        <td data-label="Registration No"><?= htmlspecialchars($row['registration_no']); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>



 <?php include('includes/sidebar.php'); ?>
 <?php include('includes/header.php'); ?>
</table>
<?php include('includes/footer.php'); ?>
