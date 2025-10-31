<?php
$servername = "localhost";
$dbusername = "root";
$dbpassword = ""; // your MySQL password
$dbname     = "db_rsa"; // your database name

// Create connection
$con = mysqli_connect($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>


