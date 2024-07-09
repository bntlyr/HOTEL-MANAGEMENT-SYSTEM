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

$room_id = $_GET['id'];

// Fetch room details from database
$query = "
    SELECT Rooms.*, Hotels.hotelName
    FROM Rooms
    LEFT JOIN Hotels ON Rooms.hotelID = Hotels.hotelID
    WHERE Rooms.roomID = $room_id
";

$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    echo "<h2>Room not found</h2>";
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
    <title>View Room - Hotel Management System</title>
    <link rel="stylesheet" href="styles/view_room.css">
</head>
<body>
    <div class="main-content">
        <h1 class="dash"><i class="fas fa-bed"></i> Room Details</h1>
        <div class="room-details">
            <div class="room-number">
                <span>Room Number: <?php echo $row['roomNumber']; ?></span>
            </div>
            <div class="detail-row">
                <label>Room Type:</label>
                <span><?php echo $row['roomType']; ?></span>
            </div>
            <div class="detail-row">
                <label>Floor:</label>
                <span><?php echo $row['floor']; ?></span>
            </div>
            <div class="detail-row">
                <label>Description:</label>
                <span><?php echo $row['description']; ?></span>
            </div>
            <div class="detail-row">
                <label>Rate:</label>
                <span><?php echo '₱' . number_format($row['rate'], 2); ?></span>
            </div>
            <div class="detail-row">
                <label>Status:</label>
                <span><?php echo $row['roomStatus']; ?></span>
            </div>
        </div>
    </div>
</body>
</html>
