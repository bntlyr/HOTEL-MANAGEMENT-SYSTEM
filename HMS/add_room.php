<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
include_once('includes/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $roomNumber = $_POST['roomNumber'];
    $roomTypeID = $_POST['roomTypeID'];
    $roomType = $_POST['roomType'];
    $roomStatusID = $_POST['roomStatusID'];
    $roomStatus = $_POST['roomStatus'];
    $floor = $_POST['floor'];
    $description = $_POST['description'];
    $rate = $_POST['rate'];
    $hotelID = $_POST['hotelID'];

    // Insert data into database
    $sql = "INSERT INTO Rooms (roomNumber, roomTypeID, roomType, roomStatusID, roomStatus, floor, description, rate, hotelID) 
            VALUES ('$roomNumber', $roomTypeID, '$roomType', $roomStatusID, '$roomStatus', $floor, '$description', $rate, $hotelID)";

    if (mysqli_query($conn, $sql)) {
        echo '<div class="popup">
                Room added successfully!
                <span class="closebtn" onclick="this.parentElement.style.display=\'none\';">&times;</span> 
            </div>';
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Room - Hotel Management System</title>
    <link rel="stylesheet" href="styles/add_room.css">
    <script>
        function updateRoomType() {
            var roomTypeSelect = document.getElementById('roomTypeSelect');
            var selectedOption = roomTypeSelect.options[roomTypeSelect.selectedIndex];
            document.getElementById('roomTypeID').value = selectedOption.value;
            document.getElementById('roomType').value = selectedOption.text;
        }

        function updateRoomStatus() {
            var roomStatusSelect = document.getElementById('roomStatusSelect');
            var selectedOption = roomStatusSelect.options[roomStatusSelect.selectedIndex];
            document.getElementById('roomStatusID').value = selectedOption.value;
            document.getElementById('roomStatus').value = selectedOption.text;
        }
    </script>
</head>
<body>
    <div class="container">
        <form id="addRoomForm" action="" method="post" class="form">
            <header class="header-registration"><b>Add New Room</b></header>
            <h3>Basic Information</h3>
            <div class="column">
                <div class="input-box">
                    <label>Room Number</label>
                    <input type="text" placeholder="Enter room number" name="roomNumber" required>
                </div>
                <div class="select-box with-label">
                    <label>Room Type</label>
                    <select id="roomTypeSelect" name="roomTypeID" onchange="updateRoomType()" required>
                        <option hidden>Choose Room Type</option>
                        <option value="1">Standard</option>
                        <option value="2">Deluxe</option>
                        <option value="3">Suite</option>
                    </select>
                </div>
                <input type="hidden" id="roomTypeID" name="roomTypeID">
                <input type="hidden" id="roomType" name="roomType">
            </div>
            <div class="column">
                <div class="select-box with-label">
                    <label>Room Status</label>
                    <select id="roomStatusSelect" name="roomStatusID" onchange="updateRoomStatus()" required>
                        <option hidden>Choose Room Status</option>
                        <option value="1">Available</option>
                        <option value="2">Occupied</option>
                        <option value="3">Under Maintenance</option>
                    </select>
                </div>
                <input type="hidden" id="roomStatusID" name="roomStatusID">
                <input type="hidden" id="roomStatus" name="roomStatus">
                <div class="input-box">
                    <label>Floor</label>
                    <input type="text" placeholder="Enter floor number" name="floor" required>
                </div>
            </div>
            <div class="input-box">
                <label>Description</label>
                <textarea placeholder="Enter room description" name="description" required></textarea>
            </div>
            <div class="input-box">
                <label>Rate</label>
                <input type="text" placeholder="Enter rate" name="rate" required>
            </div>
            <input type="hidden" name="hotelID" value="1">
            <button id="addRoomButton" type="submit">Add Room</button>
        </form>
    </div>
</body>
</html>
        