<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include_once('includes/db.php');

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    $query = "DELETE FROM staffs WHERE id = $id";

    // Execute query
    if (mysqli_query($conn, $query)) {
        header("Location: index.php?page=staffs");
        exit();
    } else {
        // Error handling if deletion fails
        echo "Error deleting record: " . mysqli_error($conn);
    }
} else {
    // Redirect to manage_staff.php if id parameter is not provided
    header("Location: index.php?page=staffs");
    exit();
}
?>
