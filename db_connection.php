<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_DATABASE', 'coffe_shop');

// Create connection using mysqli
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

// Check the connection
if ($conn->connect_error) {
    // Log the error and terminate the script
    error_log("Connection failed: " . $conn->connect_error, 3, 'error_log.txt');
    die("Database connection failed! Please try again later.");
}

// Set character encoding for the connection to handle special characters
$conn->set_charset("utf8");

// Connection is successful, no need to return the connection object here
?>
