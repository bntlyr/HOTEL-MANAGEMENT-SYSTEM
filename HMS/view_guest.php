<?php
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
include_once('includes/db.php');

// Ensure the ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<h2>Invalid request</h2>";
    exit;
}

$guest_id = $_GET['id'];

// Fetch guest details from database
$query = "SELECT *
          FROM Guests
          WHERE guestID = $guest_id";

$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    echo "<h2>Guest not found</h2>";
    exit;
}

$row = mysqli_fetch_assoc($result);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Guest - Hotel Management System</title>
    <link rel="stylesheet" href="styles/view_guest.css">
</head>
<body>
<div class="main-content">
    <h1 class="dash"><i class="fas fa-user"></i> Guest Details</h1>
    <div class="guest-details">
        <div class="full-name">
            <span><?php echo $row['firstName'] . ' ' . $row['lastName']; ?></span>
        </div>
        <div class="detail-row">
            <label>Address:</label>
            <span><?php echo $row['address']; ?></span>
        </div>
        <div class="detail-row">
            <label>City:</label>
            <span><?php echo $row['city']; ?></span>
        </div>
        <div class="detail-row">
            <label>State:</label>
            <span><?php echo $row['state']; ?></span>
        </div>
        <div class="detail-row">
            <label>Zip Code:</label>
            <span><?php echo $row['zipCode']; ?></span>
        </div>
        <div class="detail-row">
            <label>Country:</label>
            <span><?php echo $row['country']; ?></span>
        </div>
        <div class="detail-row">
            <label>Phone Number:</label>
            <span><?php echo $row['phoneNumber']; ?></span>
        </div>
        <div class="detail-row">
            <label>Email:</label>
            <span><?php echo $row['emailAddress']; ?></span>
        </div>
        <div class="detail-row">
            <label>Gender:</label>
            <span><?php echo $row['gender']; ?></span>
        </div>
    </div>
</div>
</body>
</html>
