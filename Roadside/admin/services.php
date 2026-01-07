<?php
session_start();
include('../includes/dbconnection.php');

if (empty($_SESSION['admin_id'])) {
    header('location:index.php');
    exit();
}

$q = mysqli_query($con, "SELECT service_id, service_name, description FROM services");
?>

<?php include('includes/sidebar.php'); ?>
<?php include('includes/header.php'); ?>

<div class="main-content">
    <h2>Services List</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <?php while($r = mysqli_fetch_assoc($q)) { ?>
            <tr>
                <td data-label="ID"><?= $r['service_id'] ?></td>
                <td data-label="Name"><?= htmlspecialchars($r['service_name']) ?></td>
                <td data-label="Description"><?= htmlspecialchars($r['description']) ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include('includes/footer.php'); ?>

<style>
.main-content {
    margin-left: 260px; /* sidebar width */
    margin-top: 60px;   /* header height */
    padding: 20px;
    min-height: calc(100vh - 60px);
    background: #f3f4f6;
    font-family: Arial, sans-serif;
}

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

footer {
    width: calc(100% - 260px);
    margin-left: 260px;
    background: #1e293b;
    color: #fff;
    padding: 15px 20px;
    text-align: center;
    font-size: 13px;
}

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
</style>
