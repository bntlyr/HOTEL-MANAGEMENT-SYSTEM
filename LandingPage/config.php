<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'hotel_management');

$servername = "localhost";
$username = "root";
$password = ""; // Make sure this matches your MySQL root password if set
$dbname = "hotel_management";

// Function to establish a connection to the database
function connect_to_db() {
    global $servername, $username, $password, $dbname;
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}
?>
