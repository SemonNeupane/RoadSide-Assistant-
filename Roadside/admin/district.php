<?php
$result = mysqli_query($con, "
SELECT d.district_name, p.province_name
FROM district d
JOIN province p ON d.province_id=p.province_id
");
?>
