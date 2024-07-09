<?php
include('config.php');

// Create connection to MySQL server
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL statement to create or replace the database
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    echo "Database created or updated successfully<br>";
} else {
    die("Error creating database: " . $conn->error);
}

// Select the database
$conn->select_db($dbname);

// Read the SQL file
$sql = file_get_contents('schema.sql'); // Replace with your SQL file name

// Execute the SQL commands
if ($conn->multi_query($sql)) {
    do {
        // Store first result set (if any)
        if ($result = $conn->store_result()) {
            $result->free();
        }
    } while ($conn->more_results() && $conn->next_result());
    echo "Schema imported successfully";
} else {
    die("Error importing schema: " . $conn->error);
}

// Close the connection
$conn->close();
?>
