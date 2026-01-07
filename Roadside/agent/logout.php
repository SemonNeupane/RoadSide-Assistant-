<?php
session_start();
session_unset();
session_destroy();

// Since logout.php is inside 'agent' folder, we need to go one folder up to 'Roadside'
header("Location: ../login.php"); 
exit;
