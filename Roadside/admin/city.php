<?php
$result = mysqli_query($con, "
SELECT c.city_name, d.district_name
FROM city c
JOIN district d ON c.district_id=d.district_id
");
?>
