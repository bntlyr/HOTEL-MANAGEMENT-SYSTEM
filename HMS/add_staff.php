<?php
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
include_once('includes/db.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    //$empid = $_POST['empid'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $bod = $_POST['bod'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $street_address = $_POST['street_address'];
    $street_address2 = $_POST['street_address2'];
    $country = $_POST['country'];
    $city = $_POST['city'];
    $region = $_POST['region'];
    $postal_code = $_POST['postal_code'];
    $id_card_type = $_POST['id_card_type'];
    $id_card_no = $_POST['id_card_no'];
    $hiring_date = $_POST['hiring_date'];
    $shiftid = $_POST['shiftid'];
    $staff_type_id = $_POST['staff_type_id'];
    $salary = $_POST['salary'];

    // Insert data into database
    $sql = "INSERT INTO staffs (fname, lname, bod, gender, email, phone, street_address, street_address2, country, city, region, postal_code, id_card_type, id_card_no, hiring_date, shiftid, staff_type_id, salary) 
            VALUES ('$fname', '$lname', '$bod', '$gender', '$email', '$phone', '$street_address', '$street_address2', '$country', '$city', '$region', '$postal_code', $id_card_type, '$id_card_no', '$hiring_date', $shiftid, $staff_type_id, $salary)";

    if (mysqli_query($conn, $sql)) {
        echo '<div class="popup">
                Staff added successfully!
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
    <title>Registration Form</title>
    <link rel="stylesheet" href="styles/add_staff.css">
</head>
<body>
    <div class="container">
        <form id="registrationForm" action="" method="post" class="form">
            <header class="header-registration"><b>Staff Registration Form</b></header>
            <h3>Basic Information</h3>
            <div class="column">
                <div class="input-box">
                    <label>First Name</label>
                    <input type="text" placeholder="Enter first name" name="fname" required>
                </div>
                <div class="input-box">
                    <label>Last Name</label>
                    <input type="text" placeholder="Enter last name" name="lname" required>
                </div>
            </div>
            <div class="column">
                <div class="input-box">
                    <label>Birthday</label>
                    <input type="date" name="bod" required>
                </div>
                <div class="gender-box">
                    <label>Gender</label>
                    <div class="gender-option">
                        <div class="gender">
                            <input type="radio" id="check-male" name="gender" value="Male" checked>
                            <label for="check-male">Male</label>
                        </div>
                        <div class="gender">
                            <input type="radio" id="check-female" name="gender" value="Female">
                            <label for="check-female">Female</label>
                        </div>
                        <div class="gender">
                            <input type="radio" id="check-other" name="gender" value="Prefer not to say">
                            <label for="check-other">Prefer not to say</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column">
                <div class="select-box with-label">
                    <label>ID Card Type</label>
                    <select name="id_card_type" required>
                        <option hidden>Choose ID Type</option>
                        <option value="1">National Identity Card</option>
                        <option value="2">Voter Id Card</option>
                        <option value="3">Pan Card</option>
                        <option value="4">Driving License</option>
                    </select>
                </div>
                <div class="input-box">
                    <label>ID Card Number</label>
                    <input type="text" placeholder="Enter ID Card Number" name="id_card_no" required>
                </div>
            </div>
            <h3>Contact Information</h3>
            <div class="column">
                <div class="input-box">
                    <label>Email address</label>
                    <input type="email" placeholder="Enter email address" name="email" required>
                </div>
                <div class="input-box">
                    <label>Phone Number</label>
                    <input type="text" placeholder="Enter phone number" name="phone" required>
                </div>
            </div>
            <h3>Address</h3>
            <div class="input-box address">
                <input type="text" placeholder="Enter street address" name="street_address" required>
                <input type="text" placeholder="Enter street address line 2" name="street_address2">
                <div class="column">
                    <div class="select-box country-select-box">
                        <select name="country" required>
                            <option hidden>Country</option>
                            <option value="United States of America">United States of America</option>
                            <option value="Japan">Japan</option>
                            <option value="Philippines">Philippines</option>
                            <option value="Nepal">Nepal</option>
                            <option value="India">India</option>
                        </select>
                    </div>
                    <input type="text" placeholder="Enter city" name="city" required>
                </div>
                <div class="column">
                    <input type="text" placeholder="Enter region" name="region" required>
                    <input type="text" placeholder="Enter postal code" name="postal_code" required>
                </div>
            </div>
            <h3>Job Information</h3>
            <div class="column">
            <div class="select-box with-label">
                    <label>Staff Type</label>
                    <select name="staff_type_id" required>
                        <option hidden>Choose Staff Type</option>
                        <option value="1">Manager</option>
                        <option value="2">Housekeeping Manager</option>
                        <option value="3">Front Desk Receptionist</option>
                        <option value="4">Chef</option>
                        <option value="5">Waiter</option>
                        <option value="6">Room Attendant</option>
                        <option value="7">Concierge</option>
                        <option value="8">Hotel Maintenance Engineer</option>
                        <option value="9">Hotel Sales Manager</option>
                    </select>
                </div>
                <div class="input-box">
                    <label>Salary</label>
                    <input type="text" placeholder="Enter salary" name="salary" required>
                </div>
            </div>
            <div class="column">
                <div class="input-box">
                    <label>Hiring Date</label>
                    <input type="date" name="hiring_date" required>
                </div>
                <div class="select-box with-label">
                    <label>Shift</label>
                    <select name="shiftid" required>
                        <option hidden>Choose Shift</option>
                        <option value="1">Morning (5:00 AM - 10:00 AM)</option>
                        <option value="2">Day (10:00 AM - 4:00PM)</option>
                        <option value="3">Evening (4:00 PM - 10:00 PM)</option>
                        <option value="4">Night (10:00PM - 5:00AM)</option>
                    </select>
                </div>
                
            </div>
            <button id="registerButton" type="submit">Register</button>
        </form>
    </div>
</body>
</html>
