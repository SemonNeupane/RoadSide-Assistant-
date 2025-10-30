<?php 
// Include the database connection file
include 'SQL/dbconnection.php';// Assuming db_connect.php initializes $conn

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to check if the 'users' table exists
$sql = "SHOW TABLES LIKE 'users'"; // This checks for the existence of the 'users' table
$result = $conn->query($sql);

// Check if the 'users' table exists
if ($result->num_rows > 0) {
    echo "The 'users' table exists in the database.";
} else {
    echo "The 'users' table does not exist in the database.";
}

// Close the connection
$conn->close();
?>
