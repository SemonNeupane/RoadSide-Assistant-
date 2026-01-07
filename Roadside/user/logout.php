<?php
session_start();
session_unset();
session_destroy();

// Redirect to main index.php
header("Location: ../index.php"); // go up from 'user/' folder to 'Roadside/'
exit;
