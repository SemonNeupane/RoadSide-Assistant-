<?php
$result = mysqli_query($con, "SELECT * FROM province");
?>
<h2>Provinces</h2>
<ul>
<?php while($p = mysqli_fetch_assoc($result)) echo "<li>".$p['province_name']."</li>"; ?>
</ul>
