<?php
session_start();
include(__DIR__ . '/../includes/dbconnection.php');

// ✅ Role-based access control: only logged-in users
if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header('location:../login.php');
    exit();
}

$uid = $_SESSION['user_id']; // Logged-in user ID
$rno = mt_rand(1000,9999);   // for URL encoding if needed
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>RSA Nepal | Service History</title>

<!-- Bootstrap -->
<link href="../assets/css/bootstrap.min.css" rel="stylesheet">
<link href="../assets/css/style.css" rel="stylesheet">

<style>
:root {
    --sidebar-width: 260px;
    --header-height: 60px;
}
.content { margin-left: var(--sidebar-width); margin-top: var(--header-height); padding: 25px; min-height: 100vh; background: #f3f4f6; }
.card-box { background: #fff; padding: 25px; border-radius: 14px; box-shadow: 0 10px 25px rgba(0,0,0,0.08); }
.card-box h4 { font-size: 20px; font-weight: 600; margin-bottom: 20px; color: #0f172a; }
.table { width: 100%; border-collapse: collapse; }
.table th, .table td { padding: 12px 15px; font-size: 14px; color: #334155; text-align: left; border-bottom: 1px solid #e5e7eb; }
.table th { background: #f1f5f9; font-weight: 600; }
.table tbody tr:hover { background: rgba(56, 189, 248, 0.1); transition: background 0.3s; }
.table a { color: #2563eb; text-decoration: none; font-weight: 500; }
.table a:hover { text-decoration: underline; }
.badge-success { background: #22c55e; color: #fff; padding: 4px 10px; font-size: 12px; border-radius: 8px; font-weight: 500; }
.badge-secondary { background: #6b7280; color: #fff; padding: 4px 10px; font-size: 12px; border-radius: 8px; font-weight: 500; }
.table-responsive { overflow-x: auto; margin-top: 15px; }
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
@media (max-width: 991px) { .content { margin-left: 0; padding: 15px; } .card-box { padding: 20px; } }
</style>
</head>

<body>

<div id="wrapper">

    <!-- Sidebar -->
    <?php include('includes/sidebar.php'); ?>

    <div class="content-page">
        <!-- Header -->
        <?php include('includes/header.php'); ?>

        <div class="content container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="card-box">
                        <h4 class="mb-3">Service History</h4>

                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Service</th>
                                        <th>Vehicle Model</th>
                                        <th>Request Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $cnt = 1;

                                    $query = "
                                    SELECT 
                                        b.booking_id,
                                        b.created_at AS request_date,
                                        b.status,
                                        v.model,
                                        s.service_name
                                    FROM booking b
                                    LEFT JOIN vehicle v ON b.vehicle_id = v.vehicle_id
                                    LEFT JOIN services s ON b.service_id = s.service_id
                                    WHERE b.user_id = '$uid'
                                    ORDER BY b.booking_id DESC
                                    ";

                                    $q = mysqli_query($con, $query);

                                    if(!$q){
                                        echo "<tr><td colspan='6' class='text-danger'>Query Error: ".mysqli_error($con)."</td></tr>";
                                    } elseif(mysqli_num_rows($q) > 0){
                                        while($row = mysqli_fetch_assoc($q)){
                                            ?>
                                            <tr>
                                                <td><?php echo $cnt;?></td>
                                                <td><?php echo htmlentities($row['service_name'] ?? 'N/A');?></td>
                                                <td><?php echo htmlentities($row['model'] ?? 'N/A');?></td>
                                                <td><?php echo htmlentities(date('d M Y', strtotime($row['request_date'])));?></td>
                                                <td>
                                                    <span class="badge badge-<?php echo ($row['status']=='active')?'success':'secondary';?>">
                                                        <?php echo ucfirst($row['status']);?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="service-view.php?bid=<?php echo base64_encode($row['booking_id'].$rno);?>">
                                                        View Detail
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php
                                            $cnt++;
                                        }
                                    } else{
                                        echo "<tr><td colspan='6' class='text-center text-muted'>No Service History Found</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
<?php include('includes/footer.php'); ?>
</body>
</html>
