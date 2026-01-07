<?php
session_start();
include('../includes/dbconnection.php');

// Admin authentication
if (empty($_SESSION['admin_id'])) {
    header('location:index.php');
    exit();
}

// Fetch all agent services with agent name, service name, and city
$result = mysqli_query($con, "
    SELECT 
        asv.agent_service_id,
        u.username AS agent_name,
        s.service_name,
        c.city_name
    FROM agent_service asv
    JOIN agent a ON asv.agent_id = a.agent_id
    JOIN users u ON a.user_id = u.user_id
    JOIN services s ON asv.service_id = s.service_id
    LEFT JOIN agent_location al ON asv.agent_city_id = al.agent_city_id
    LEFT JOIN city c ON al.city_id = c.city_id
    ORDER BY u.username ASC
");
?>

<?php include('includes/sidebar.php'); ?>
<?php include('includes/header.php'); ?>

<div class="main-content">
    <h2>Agent Services</h2>
    <table>
        <thead>
            <tr>
                <th>Agent Name</th>
                <th>Service</th>
                <th>City</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td data-label="Agent Name"><?= htmlspecialchars($row['agent_name']); ?></td>
                <td data-label="Service"><?= htmlspecialchars($row['service_name']); ?></td>
                <td data-label="City"><?= htmlspecialchars($row['city_name'] ?? 'All Cities'); ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include('includes/footer.php'); ?>

<style>
/* ===== MAIN CONTENT ===== */
.main-content {
    margin-left: 260px;  /* sidebar width */
    margin-top: 60px;    /* header height */
    padding: 20px;
    min-height: calc(100vh - 60px);
    background: #f3f4f6;
    font-family: Arial, sans-serif;
}

/* ===== TABLE ===== */
table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    margin-top: 20px;
}

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

/* Responsive table */
@media(max-width: 768px){
    table thead { display: none; }
    table, table tbody, table tr, table td { display: block; width: 100%; }
    table tbody tr { margin-bottom: 15px; border-bottom: 2px solid #e5e7eb; }
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

/* ===== FOOTER ===== */
footer {
    width: calc(100% - 260px);
    margin-left: 260px;
    background: #1e293b;
    color: #fff;
    padding: 15px 20px;
    text-align: center;
    font-size: 13px;
    position: relative;
}
</style>
