<?php
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
include_once('includes/db.php');

$room_id = $_GET['id'];

// Fetch existing room data
$sql = "SELECT * FROM Rooms WHERE roomID = $room_id";
$result = mysqli_query($conn, $sql);
$room = mysqli_fetch_assoc($result);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $roomNumber = $_POST['roomNumber'];
    $roomType = $_POST['roomType'];

    // Update data in database
    $sql = "UPDATE Rooms SET 
            roomNumber='$roomNumber', roomType='$roomType' 
            WHERE roomID=$room_id";

    if (mysqli_query($conn, $sql)) {
        echo '<div class="popup">
                Room updated successfully!
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
    <title>Edit Room Form</title>
    <link rel="stylesheet" href="styles/edit_room.css"> <!-- Adjust this path to your CSS file -->
</head>
<body>
    <div class="container">
        <form id="editForm" action="" method="post" class="form">
            <header class="header-registration"><b>Update Room Form</b></header>
            <div class="input-box">
                <label>Room Number</label>
                <input type="text" placeholder="Enter room number" name="roomNumber" value="<?php echo $room['roomNumber']; ?>" required>
            </div>
            <div class="select-box with-label">
                <label>Room Type</label>
                <select name="roomType" required>
                    <option hidden>Choose Room Type</option>
                    <option value="Standard" <?php echo ($room['roomType'] == 'Standard') ? 'selected' : ''; ?>>Standard</option>
                    <option value="Deluxe" <?php echo ($room['roomType'] == 'Deluxe') ? 'selected' : ''; ?>>Deluxe</option>
                    <option value="Suite" <?php echo ($room['roomType'] == 'Suite') ? 'selected' : ''; ?>>Suite</option>
                </select>
            </div>
            <button id="registerButton" type="submit">Update</button>
        </form>
    </div>
</body>
</html>
