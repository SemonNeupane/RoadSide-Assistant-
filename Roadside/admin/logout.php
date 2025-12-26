<?php
session_start();
session_destroy();
header('Location: index.php'); // back to login page
exit();
?>
