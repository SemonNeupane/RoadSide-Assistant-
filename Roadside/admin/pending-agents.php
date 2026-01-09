<?php
session_start();
include('../includes/dbconnection.php');
if (empty($_SESSION['admin_id'])) exit();

$result = mysqli_query($con, "SELECT a.agent_id, u.username, u.phone 
                              FROM agent a
                              JOIN users u ON a.user_id = u.user_id
                              WHERE a.status='pending'");
?>
<?php include('includes/sidebar.php'); ?>
<?php include('includes/header.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Agents | Admin</title>
    <link rel="icon" type="image/x-icon" href="../../favicon.ico">
    <style>
/* ===== MAIN CONTENT ===== */
.main-content {
    margin-left: 260px; /* sidebar width */
    margin-top: 60px;   /* header height */
    padding: 25px;
    min-height: calc(100vh - 60px - 60px); /* total height minus header & footer */
    background: #f3f4f6; /* dashboard background */
    font-family: Arial, Helvetica, sans-serif;
}

/* ===== PAGE TITLE ===== */
.main-content h2 {
    font-size: 22px;
    font-weight: 600;
    color: #0f172a;
    margin-bottom: 20px;
}

/* ===== TABLE STYLING ===== */
table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
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

/* Table rows */
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

/* ===== ACTION BUTTONS ===== */
.btn-action {
    padding: 6px 14px;
    border-radius: 8px;
    font-size: 13px;
    text-decoration: none;
    color: #fff;
    margin-right: 5px;
    transition: all 0.3s ease;
}

.btn-action.approve {
    background-color: #22c55e; /* green */
}

.btn-action.approve:hover {
    background-color: #16a34a;
}

.btn-action.reject {
    background-color: #ef4444; /* red */
}

.btn-action.reject:hover {
    background-color: #b91c1c;
}

/* ===== RESPONSIVE ===== */
@media(max-width: 768px){
    table thead { display: none; }
    table, table tbody, table tr, table td { display: block; width: 100%; }
    table tbody tr { margin-bottom: 15px; border-bottom: 2px solid #e5e7eb; }
    table tbody td { padding-left: 50%; position: relative; text-align: left; }
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
    margin-left: 260px; /* align with sidebar */
    width: calc(100% - 260px);
    background: #1e293b;
    color: #fff;
    padding: 15px 20px;
    text-align: center;
    font-size: 13px;
    position: relative;
}

/* ===== LINK STYLING ===== */
a {
    text-decoration: none;
}

</style>

</head>
<body>
    <div class="main-content" style="margin-left:260px; padding:20px; margin-top:60px;">
    <h2>Pending Agents</h2>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Phone</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td data-label="Name"><?= htmlspecialchars($row['username']); ?></td>
                <td data-label="Phone"><?= htmlspecialchars($row['phone']); ?></td>
                <td data-label="Action">
                    <a href="approve-agent.php?id=<?= $row['agent_id']; ?>" class="btn-action approve">Approve</a>
                    <a href="reject-agent.php?id=<?= $row['agent_id']; ?>" class="btn-action reject">Reject</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>

<?php include('includes/footer.php'); ?>
