<?php
session_start();
include('../includes/dbconnection.php');

if (empty($_SESSION['admin_id'])) {
    header('location:index.php');
    exit();
}

// Fetch approved agents with their city (from agent_location if needed)
$result = mysqli_query($con, "
    SELECT a.agent_id, u.username, u.phone, c.city_name
    FROM agent a
    JOIN users u ON a.user_id = u.user_id
    LEFT JOIN agent_location al ON a.agent_id = al.agent_id
    LEFT JOIN city c ON al.city_id = c.city_id
    WHERE a.status='approved'
");
?>

<?php include('includes/sidebar.php'); ?>
<?php include('includes/header.php'); ?>

<div class="main-content">
    <h2>Approved Agents</h2>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Phone</th>
                <th>City</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td data-label="Name"><?= htmlspecialchars($row['username']); ?></td>
                <td data-label="Phone"><?= htmlspecialchars($row['phone']); ?></td>
                <td data-label="City"><?= htmlspecialchars($row['city_name'] ?? 'N/A'); ?></td>
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
    min-height: calc(100vh - 60px); /* full height minus header */
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

/* Table header */
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
    margin-left: 260px; /* align with sidebar */
    background: #1e293b;
    color: #fff;
    padding: 15px 20px;
    text-align: center;
    font-size: 13px;
    position: relative;
}
</style>
