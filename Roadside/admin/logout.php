<?php
session_start();
session_unset();
session_destroy();

// Redirect to admin index page
header("Location: index.php"); // same folder
exit;
