<?php
session_start();
include('../includes/dbconnection.php');

// Check admin login
if (empty($_SESSION['admin_id'])) {
    header('location:index.php');
    exit();
}

// Correct query according to database
$result = mysqli_query($con, "
    SELECT user_id, username, email, phone, registration_date
    FROM users
    WHERE role = 'user'
");
?>
<div class="main-content">
    <h2>Registered Users</h2>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Joined On</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td data-label="ID"><?= $row['user_id']; ?></td>
                        <td data-label="Name"><?= htmlspecialchars($row['username']); ?></td>
                        <td data-label="Email"><?= htmlspecialchars($row['email']); ?></td>
                        <td data-label="Phone"><?= htmlspecialchars($row['phone']); ?></td>
                        <td data-label="Joined On"><?= $row['registration_date']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

    <style>
:root {
    --sidebar-width: 260px;
    --header-height: 60px;
}

/* ===== MAIN CONTENT ===== */
.main-content {
    margin-left: var(--sidebar-width);
    margin-top: var(--header-height);
    padding: 25px;
    min-height: 100vh;
    background: #f3f4f6; /* light dashboard background */
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

/* Table */
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

   

 

 <?php include('includes/sidebar.php'); ?>
 <?php include('includes/header.php'); ?> 



<?php include('includes/footer.php'); ?>
