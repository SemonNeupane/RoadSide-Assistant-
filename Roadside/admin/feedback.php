<?php
$result = mysqli_query($con, "
SELECT f.feedback, u.username, f.created_at
FROM feedback f
JOIN users u ON f.user_id=u.user_id
");
?>
