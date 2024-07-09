<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
include_once('includes/db.php');

if (isset($_GET['id'])) {
    $roomID = intval($_GET['id']);

    // Check if the room exists
    $checkQuery = "SELECT * FROM Rooms WHERE roomID = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param('i', $roomID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Room exists, proceed with deletion
        $deleteQuery = "DELETE FROM Rooms WHERE roomID = ?";
        $stmt = $conn->prepare($deleteQuery);
        $stmt->bind_param('i', $roomID);
        if ($stmt->execute()) {
            // Successfully deleted
            header("Location: index.php?page=rooms");
        } else {
            // Error during deletion
            header("Location: index.php?page=rooms");
        }
    } else {
        // Room not found
        header("Location: index.php?page=rooms");
    }

    $stmt->close();
} else {
    // Invalid request
    header("Location: index.php?page=rooms");
}

$conn->close();
?>
