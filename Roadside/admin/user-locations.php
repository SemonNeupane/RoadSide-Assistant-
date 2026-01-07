<?php
session_start();
include('../includes/dbconnection.php');

// Check admin login
if (empty($_SESSION['admin_id'])) {
    header('location:index.php');
    exit();
}

// Fetch user locations
$q = mysqli_query($con, "
    SELECT u.username, p.province_name, d.district_name, c.city_name
    FROM users u
    LEFT JOIN user_location ul ON u.user_id = ul.user_id
    LEFT JOIN city c ON ul.city_id = c.city_id
    LEFT JOIN district d ON ul.district_id = d.district_id
    LEFT JOIN province p ON ul.province_id = p.province_id
    WHERE u.role='user'
");
?>

<?php include('includes/sidebar.php'); ?>
<?php include('includes/header.php'); ?>

<div class="main-content" style="margin-left:260px; padding:20px; margin-top:60px;">
    <h2>User Locations</h2>
    <table>
        <thead>
            <tr>
                <th>User</th>
                <th>Province</th>
                <th>District</th>
                <th>City</th>
            </tr>
        </thead>
        <tbody>
            <?php while($r = mysqli_fetch_assoc($q)) { ?>
            <tr>
                <td><?= htmlspecialchars($r['username']); ?></td>
                <td><?= htmlspecialchars($r['province_name']); ?></td>
                <td><?= htmlspecialchars($r['district_name']); ?></td>
                <td><?= htmlspecialchars($r['city_name']); ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<style>
    body {
    display: flex;
    flex-direction: column;
    min-height: 100vh; /* full height of viewport */
    margin: 0;
    background: #f3f4f6;
}

/* Main content grows to fill space */
.main-content {
    flex: 1; /* push footer down */
    margin-left: 260px; /* sidebar width */
    margin-top: 60px;   /* header height */
    padding: 20px;
}

/* ===== Table ===== */
table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    font-family: Arial, sans-serif;
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
    margin-left: 260px; /* align with sidebar */
    width: calc(100% - 260px);
    background: #1e293b;
    color: #fff;
    padding: 15px 20px;
    text-align: center;
    font-size: 13px;
    position: relative;
}

/* ===== Responsive ===== */
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
</style>
<?php include('includes/footer.php'); ?>
