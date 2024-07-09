<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Goodwill Hotel</title>
    <link rel="stylesheet" href="./css/style.css"> <!-- Link to your external CSS file -->
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/animation.css">
</head>
<body>
    <section id="hero-section" class="hero">
        <div class="text">
            <h1><h class="animate">Welcome to Goodwill Hotel!</h><br>"Where Serenity Meets <b>Exceptional Service.</b>"</h1>
            <p>Experience Peaceful Comfort and Personalized Service in Our Exclusive Retreat at Goodwill Hotel.</p>
        </div>  
        <div class="searchform">
            <h2>Check Room For Availability</h2>
            <form action="index.php" method="POST">
                <div>
                    <span>
                        <label>Room Type</label>
                        <select name="room_type" id="roomtypes">
                            <option value="None">--Select Type--</option>
                            <option value="Standard">Standard</option>
                            <option value="Deluxe">Deluxe</option>
                            <option value="Suite">Suite</option>
                        </select>
                    </span>
                    <span>
                        <label>Check-In</label>
                        <input type="date" name="check_in" required>
                    </span>
                    <span>
                        <label>Check-Out</label>
                        <input type="date" name="check_out" required>
                    </span>
                    <input class="search" type="submit" name="search" value="Search">
                </div>
            </form>
            <div id="results">
            <?php
            include 'includes/db.php';

            // PHP code to check room availability and display results
            if(isset($_POST['search'])) {
                // Database connection setup (already connected in your actual code)
                
                // Sanitize input (prevent SQL injection)
                $room_type = $_POST['room_type'];
                $check_in = $_POST['check_in'];
                $check_out = $_POST['check_out'];

                // SQL query to check room availability
                $sql = "SELECT COUNT(*) AS available_rooms
                FROM Rooms
                WHERE roomType = '$room_type'
                AND roomID NOT IN (
                    SELECT roomID
                    FROM RoomsBooked
                    WHERE (dateFrom <= '$check_out' AND dateTo >= '$check_in')
                )
                AND roomStatus = 'Available'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $available_rooms = $row['available_rooms'];
                    
                    if ($available_rooms > 0) {
                        echo "<p>There are  $room_type rooms available for your selected dates.</p>";
                    } else {
                        echo "<p>Sorry, no $room_type rooms available for your selected dates.</p>";
                    }
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }

                // Close database connection
                $conn->close();
            }
            ?>
        </div>
    </div>
</section>