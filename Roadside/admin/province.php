<?php
session_start();
include('../includes/dbconnection.php'); // Make sure the path is correct

if (empty($_SESSION['admin_id'])) {
    header('location:index.php');
    exit();
}

// Fetch provinces
$result = mysqli_query($con, "SELECT * FROM province");
?>
<?php include('includes/sidebar.php'); ?>
<?php include('includes/header.php'); ?>

<div class="main-content">
    <h2>Provinces</h2>
    <ul>
        <?php while($p = mysqli_fetch_assoc($result)) { ?>
            <li><?= htmlspecialchars($p['province_name']); ?></li>
        <?php } ?>
    </ul>
</div>

<?php include('includes/footer.php'); ?>

<style>
.main-content {
    margin-left: 260px; /* width of sidebar */
    margin-top: 60px;   /* height of header */
    padding: 20px;
    min-height: calc(100vh - 60px);
    font-family: Arial, sans-serif;
}

ul {
    background: #fff;
    padding: 15px 20px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    list-style: disc inside;
}

ul li {
    padding: 8px 0;
    font-size: 14px;
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
</style>
