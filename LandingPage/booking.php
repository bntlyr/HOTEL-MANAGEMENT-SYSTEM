<?php
include 'includes/db.php';

// Initialize error message
$error_message = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Retrieve and sanitize input data
    $roomType = isset($_POST['roomType']) ? $_POST['roomType'] : '';
    $dateFrom = isset($_POST['dateFrom']) ? $_POST['dateFrom'] : '';
    $dateTo = isset($_POST['dateTo']) ? $_POST['dateTo'] : '';
    $guestFirstName = isset($_POST['guestFirstName']) ? $conn->real_escape_string($_POST['guestFirstName']) : '';
    $guestLastName = isset($_POST['guestLastName']) ? $conn->real_escape_string($_POST['guestLastName']) : '';
    $address = isset($_POST['address']) ? $conn->real_escape_string($_POST['address']) : '';
    $city = isset($_POST['city']) ? $conn->real_escape_string($_POST['city']) : '';
    $state = isset($_POST['state']) ? $conn->real_escape_string($_POST['state']) : '';
    $zipCode = isset($_POST['zipCode']) ? $conn->real_escape_string($_POST['zipCode']) : '';
    $country = isset($_POST['country']) ? $conn->real_escape_string($_POST['country']) : '';
    $phoneNumber = isset($_POST['phoneNumber']) ? $conn->real_escape_string($_POST['phoneNumber']) : '';
    $emailAddress = isset($_POST['emailAddress']) ? $conn->real_escape_string($_POST['emailAddress']) : '';
    $gender = isset($_POST['gender']) ? $conn->real_escape_string($_POST['gender']) : '';

    // Check if all required fields are filled
    if (empty($roomType) || empty($dateFrom) || empty($dateTo) || empty($guestFirstName) || empty($guestLastName)) {
        $error_message = "Please fill in all required fields.";
    } else {
        // Retrieve roomTypeID based on roomType selected
        $queryRoomTypeID = "SELECT room_type_id FROM room_type WHERE room_type = ?";
        $stmtRoomTypeID = $conn->prepare($queryRoomTypeID);
        $stmtRoomTypeID->bind_param("s", $roomType);
        $stmtRoomTypeID->execute();
        $resultRoomTypeID = $stmtRoomTypeID->get_result();

        if ($resultRoomTypeID->num_rows > 0) {
            $rowRoomTypeID = $resultRoomTypeID->fetch_assoc();
            $roomTypeID = $rowRoomTypeID['room_type_id'];

            // Check room availability
            $queryAvailability = "SELECT roomID
                                  FROM Rooms
                                  WHERE roomTypeID = ?
                                  AND roomStatus = 'available'
                                  AND roomID NOT IN (
                                      SELECT roomID FROM RoomsBooked
                                      WHERE (? <= dateTo) AND (? >= dateFrom)
                                  ) LIMIT 1";

            $stmtAvailability = $conn->prepare($queryAvailability);
            $stmtAvailability->bind_param("iss", $roomTypeID, $dateTo, $dateFrom);
            $stmtAvailability->execute();
            $resultAvailability = $stmtAvailability->get_result();

            if ($resultAvailability->num_rows > 0) {
                $rowAvailability = $resultAvailability->fetch_assoc();
                $roomID = $rowAvailability['roomID'];

                // Insert guest details into Guests table
                $insertGuestQuery = "INSERT INTO Guests (firstName, lastName, address, city, state, zipCode, country, phoneNumber, emailAddress, gender)
                                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmtInsertGuest = $conn->prepare($insertGuestQuery);
                $stmtInsertGuest->bind_param("ssssssssss", $guestFirstName, $guestLastName, $address, $city, $state, $zipCode, $country, $phoneNumber, $emailAddress, $gender);
                if ($stmtInsertGuest->execute()) {
                    $guestID = $conn->insert_id; // Retrieve the auto-generated guest ID

                    // Insert booking details into Bookings table   
                    $insertBookingQuery = "INSERT INTO Bookings (hotelID, guestID, bookingStatusID, dateFrom, dateTo, roomCount)
                                           VALUES (?, ?, 1, ?, ?, 1)";
                    $stmtInsertBooking = $conn->prepare($insertBookingQuery);
                    $stmtInsertBooking->bind_param("iiss", $hotelID, $guestID, $dateFrom, $dateTo);
                    if ($stmtInsertBooking->execute()) {
                        $bookingID = $conn->insert_id; // Retrieve the auto-generated booking ID

                        // Insert into RoomsBooked table
                        $insertRoomsBookedQuery = "INSERT INTO RoomsBooked (bookingID, roomID, dateFrom, dateTo)
                                                   VALUES (?, ?, ?, ?)";
                        $stmtInsertRoomsBooked = $conn->prepare($insertRoomsBookedQuery);
                        $stmtInsertRoomsBooked->bind_param("iiss", $bookingID, $roomID, $dateFrom, $dateTo);
                        if ($stmtInsertRoomsBooked->execute()) {
                            // Update room status to 'occupied'
                            $updateRoomStatusQuery = "UPDATE Rooms SET roomStatus = 'Occupied', roomStatusID = 2 WHERE roomID = ?";
                            $stmtUpdateRoomStatus = $conn->prepare($updateRoomStatusQuery);
                            $stmtUpdateRoomStatus->bind_param("i", $roomID);
                            if ($stmtUpdateRoomStatus->execute()) {
                                // Insert payment details into Payments table
                                $paymentMethod = isset($_POST['paymentMethod']) ? $_POST['paymentMethod'] : '';
                                $amount = isset($_POST['amount']) ? $_POST['amount'] : '';

                                // Initialize additional payment details
                                $cardType = '';
                                $cardNumber = '';
                                $expiryDate = '';
                                $cvv = '';
                                $gcashAccount = '';
                                $gcashNumber = '';

                                if ($paymentMethod === 'Credit Card') {
                                    $cardType = isset($_POST['cardType']) ? $_POST['cardType'] : '';
                                    $cardNumber = isset($_POST['cardNumber']) ? $_POST['cardNumber'] : '';
                                    $expiryDate = isset($_POST['expiryDate']) ? $_POST['expiryDate'] : '';
                                    $cvv = isset($_POST['cvv']) ? $_POST['cvv'] : '';
                                } elseif ($paymentMethod === 'GCash') {
                                    $gcashAccount = isset($_POST['gcashAccount']) ? $_POST['gcashAccount'] : '';
                                    $gcashNumber = isset($_POST['gcashNumber']) ? $_POST['gcashNumber'] : '';
                                }

                                // Insert payment details into Payments table
                                $insertPaymentQuery = "INSERT INTO Payments (bookingID, paymentMethod, amount, cardType, cardNumber, expiryDate, cvv, gcashAccount, gcashNumber)
                                                       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                                $stmtInsertPayment = $conn->prepare($insertPaymentQuery);
                                $stmtInsertPayment->bind_param("issssssss", $bookingID, $paymentMethod, $amount, $cardType, $cardNumber, $expiryDate, $cvv, $gcashAccount, $gcashNumber);
                                if ($stmtInsertPayment->execute()) {
                                    $success_message = '<div class=popup>
                                                            Booking confirmed!
                                                            <span class="closebtn" onclick="this.parentElement.style.display=\'none\';">&times;</span>
                                                        </div>';
                                } else {
                                    $error_message = "Error inserting payment details: " . $conn->error;
                                }
                            } else {
                                $error_message = "Error updating room status: " . $conn->error;
                            }
                        } else {
                            $error_message = "Error inserting into RoomsBooked: " . $conn->error;
                        }
                    } else {
                        $error_message = "Error inserting booking details: " . $conn->error;
                    }
                } else {
                    $error_message = "Error inserting guest details: " . $conn->error;
                }
            } else {
                $error_message = "Selected room type is not available for the selected dates.";
            }
        } else {
            $error_message = "Invalid room type selected.";
        }
    }

    // Close database connection
    $conn->close();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hotel Booking Form</title>
    <style>
        /* Add your CSS styles here */
    </style>
    <script>
        function togglePaymentDetails() {
            var paymentMethod = document.getElementById("paymentMethod").value;
            var creditCardDetails = document.getElementById("creditCardDetails");
            var gcashDetails = document.getElementById("gcashDetails");

            if (paymentMethod === "Credit Card") {
                creditCardDetails.style.display = "block";
                gcashDetails.style.display = "none";
            } else if (paymentMethod === "GCash") {
                creditCardDetails.style.display = "none";
                gcashDetails.style.display = "block";
            }
        }
        // Function to restrict date input to future dates
        function restrictDate() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById("checkin").setAttribute("min", today);
            document.getElementById("checkout").setAttribute("min", today);
        }

        // Function to set the minimum check-out date based on check-in date
        function setCheckoutMin() {
            const checkinDate = document.getElementById("checkin").value;
            document.getElementById("checkout").setAttribute("min", checkinDate);
        }

        // Function to highlight the selected room type
        // Function to highlight the selected room type
        function highlightRoomBox() {
            const roomType = document.getElementById("roomType").value;
            const roomBoxes = document.getElementsByClassName("room-box");

            // Reset all room-boxes to default styles
            for (let i = 0; i < roomBoxes.length; i++) {
                roomBoxes[i].style.backgroundColor = "#1e1d1d"; // Default color
            }

            // Apply highlight styles based on selected room type
            if (roomType === "Standard") {
                document.getElementById("standardRoom").style.backgroundColor = "#660000"; // Highlight color
                document.getElementById("standardRoom").style.boxShadow = "rgba(0, 0, 0, 0.56) 0px 22px 70px 4px;"; // Highlight box-shadow
            } else if (roomType === "Deluxe") {
                document.getElementById("deluxeRoom").style.backgroundColor = "#660000"; // Hrgighlight color
                document.getElementById("deluxeRoom").style.boxShadow = "rgba(0, 0, 0, 0.56) 0px 22px 70px 4px;"; // Highlight box-shadow
            } else if (roomType === "Suite") {
                document.getElementById("suiteRoom").style.backgroundColor = "#660000"; // Highlight color
                document.getElementById("suiteRoom").style.boxShadow = "rgba(0, 0, 0, 0.56) 0px 22px 70px 4px;"; // Highlight box-shadow
            }
        }

    </script>
    <link rel="stylesheet" href="./css/templates.css">
    <link rel="stylesheet" href="./css/booking.css">
    <link rel="icon" type="image/x-icon" href="./img/logo.svg">
</head>
<body>
    <?php include('./templates/header.php');?>
    <section class="container">
        
        <div class="main-wrapper">
            <h2 class="header2">Book a Room</h2>
            <hr>
            <h2>Room Details</h2>
            <?php
            if (!empty($error_message)) {
                echo "<p style='color:red;'>$error_message</p>";
            }
            if (!empty($success_message)) {
                echo "<p style='color:green;'>$success_message</p>";
            }
            ?>
            <div class="wrapper-box">
                <div id="standardRoom" class="room-box">
                    <img src="./img/room-2.jpg" alt="Room Image" class="room-image">
                    <div class="room-details">
                        <span class="room-type">Standard Room</span>
                        <span class="room-desc">Comfortable for business or leisure.</span>
                        <hr>
                        <span class="room-price">₱10,000.00 / night</span>
                    </div>
                </div>
                <div id="deluxeRoom" class="room-box">
                    <img src="./img/room-1.jpg" alt="Room Image" class="room-image">
                    <div class="room-details">
                        <span class="room-type">Deluxe Room</span>
                        <span class="room-desc">Spacious and elegant with a view.</span>
                        <hr>
                        <span class="room-price">₱20,000.00 / night</span>
                    </div>
                </div>
                <div id="suiteRoom" class="room-box">
                    <img src="./img/room-3.jpg" alt="Room Image" class="room-image">
                    <div class="room-details">
                        <span class="room-type">Suite</span>
                        <span class="room-desc">Luxurious with separate living areas.</span>
                        <hr>
                        <span class="room-price">₱30,000.00 / night</span>
                    </div>
                </div>
            </div>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-box">
                    <span>
                    <label for="roomType">Room Type:</label>
                    <select id="roomType" name="roomType" required onchange="highlightRoomBox()">
                        <option value="None">-- Select Rooms --</option>    
                        <option value="Standard">Standard</option>
                        <option value="Deluxe">Deluxe</option>
                        <option value="Suite">Suite</option>
                    </select><br><br>
                    </span>
                    <span>
                    <label for="dateFrom">Check-in Date:</label>
                    <input id="checkin" type="date" name="dateFrom" value="<?php echo isset($_POST['dateFrom']) ? $_POST['dateFrom'] : ''; ?>" required><br><br>
                    </span>
                    <span>
                    <label for="dateTo">Check-out Date:</label>
                    <input id="checkout" type="date" name="dateTo" value="<?php echo isset($_POST['dateTo']) ? $_POST['dateTo'] : ''; ?>" required><br><br>
                    </span>
                </div>
                <hr>
                <h2>Checkout Details</h2>
                <div class="form-box">
                    <span>
                    <label for="guestFirstName">First Name:</label>
                    <input placeholder="Enter First Name" type="text" id="guestFirstName" name="guestFirstName" value="<?php echo isset($_POST['guestFirstName']) ? $_POST['guestFirstName'] : ''; ?>" required><br><br>
                    </span>
                    <span>
                    <label for="guestLastName">Last Name:</label>
                    <input placeholder="Enter Last Name" type="text" id="guestLastName" name="guestLastName" value="<?php echo isset($_POST['guestLastName']) ? $_POST['guestLastName'] : ''; ?>" required><br><br>
                    </span>
                </div>
                <div class="form-box">
                    <span>
                    <label for="address">Address:</label>
                    <input placeholder="Enter Your Address" type="text" id="address" name="address" value="<?php echo isset($_POST['address']) ? $_POST['address'] : ''; ?>"><br><br>
                    </span>
                </div>
                <div class="form-box">
                    <span>
                    <label for="city">City:</label>
                    <input placeholder="Enter City" type="text" id="city" name="city" value="<?php echo isset($_POST['city']) ? $_POST['city'] : ''; ?>"><br><br>
                    </span>
                    <span>
                    <label for="state">State:</label>
                    <input placeholder="Enter State" type="text" id="state" name="state" value="<?php echo isset($_POST['state']) ? $_POST['state'] : ''; ?>"><br><br>
                    </span>
                    <span>
                    <label for="zipCode">Zip Code:</label>
                    <input  placeholder="Enter Zip Code" type="text" id="zipCode" name="zipCode" value="<?php echo isset($_POST['zipCode']) ? $_POST['zipCode'] : ''; ?>"><br><br>
                    </span>
                </div>  
                <div class="form-box">
                    <span>
                    <label for="country">Country:</label>
                    <input placeholder="Enter Country" type="text" id="country" name="country" value="<?php echo isset($_POST['country']) ? $_POST['country'] : ''; ?>"><br><br>
                    </span>
                    <span>
                    <label for="gender">Gender:</label>
                    <input placeholder="Enter Gender" type="text" id="gender" name="gender" value="<?php echo isset($_POST['gender']) ? $_POST['gender'] : ''; ?>"><br><br>
                    </span>
                </div>
                <div class="form-box">
                    <span>
                    <label for="emailAddress">Email Address:</label>
                    <input placeholder="Enter Email" type="email" id="emailAddress" name="emailAddress" value="<?php echo isset($_POST['emailAddress']) ? $_POST['emailAddress'] : ''; ?>"><br><br>
                    </span>
                    <span>
                    <label for="phoneNumber">Phone Number:</label>
                    <input placeholder="Enter Phone Number" type="text" id="phoneNumber" name="phoneNumber" value="<?php echo isset($_POST['phoneNumber']) ? $_POST['phoneNumber'] : ''; ?>"><br><br>
                    </span>
                </div>
                <hr>
                <h2>Payment Details</h2>
                <div class ="form-box">
                <span>
                <label for="paymentMethod">Payment Method:</label>
                <select id="paymentMethod" name="paymentMethod" onchange="togglePaymentDetails()" required>
                    <option value="Credit Card">Credit Card</option>
                    <option value="GCash">GCash</option>
                </select><br><br>
                </span>
            </div>
        
        <div id="creditCardDetails">
            <div class ="form-box">
                <span>
                <label for="cardNumber">Card Number:</label>
                <input placeholder="Enter Card Number" type="text" id="cardNumber" name="cardNumber"><br><br>
                </span>
            </div>

            <div class ="form-box">
                <span>
                <label for="cardType">Card Type:</label>
                <input  placeholder="Enter Card Type" type="text" id="cardType" name="cardType"><br><br>
                </span>

                <span>
                <label for="expiryDate">Expiry Date:</label>
                <input placeholder="Enter Expiry" type="text" id="expiryDate" name="expiryDate"><br><br>
                </span>
                
                <span>
                <label for="cvv">CVV:</label>
                <input placeholder="Enter CVV" type="text" id="cvv" name="cvv"><br><br>
                </span>
            </div>
        </div>
        <div id="gcashDetails" style="display:none;">
            <div class ="form-box">
                <span>
                <label for="gcashAccount">GCash Account:</label>
                <input placeholder="Enter Account Name" type="text" id="gcashAccount" name="gcashAccount"><br><br>
                </span>
            </div>
            
            <div class ="form-box">
            <span>
            <label for="gcashNumber">GCash Number:</label>
            <input placeholder="Enter 9-Digit Account Number" type="text" id="gcashNumber" name="gcashNumber"><br><br>
            </span>
            </div>
        </div>
        <div class ="form-box">
            <span>
            <label for="amount">Amount:</label>
            <input placeholder="Enter Amount" type="text" id="amount" name="amount" required><br><br>
            </span>
        </div>
        <input class="submit-btn" type="submit" name="submit" value="Book Now">
        </form>
        </div>
    </section>
    <?php include('./templates/footer.php');?>
</body>
</html>
