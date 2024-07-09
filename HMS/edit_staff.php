<?php
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
include_once('includes/db.php');

$staff_id = $_GET['id'];

// Fetch existing staff data
$sql = "SELECT * FROM staffs WHERE id = $staff_id";
$result = mysqli_query($conn, $sql);
$staff = mysqli_fetch_assoc($result);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
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

    // Update data in database
    $sql = "UPDATE staffs SET 
            fname='$fname', lname='$lname', bod='$bod', gender='$gender', 
            email='$email', phone='$phone', street_address='$street_address', 
            street_address2='$street_address2', country='$country', city='$city', 
            region='$region', postal_code='$postal_code', id_card_type=$id_card_type, 
            id_card_no='$id_card_no', hiring_date='$hiring_date', shiftid=$shiftid, 
            staff_type_id=$staff_type_id, salary=$salary 
            WHERE id=$staff_id";

    if (mysqli_query($conn, $sql)) {
        echo '<div class="popup">
                Staff updated successfully!
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
    <title>Edit Staff Form</title>
    <link rel="stylesheet" href="styles/add_staff.css">
</head>
<body>
    <div class="container">
        <form id="editForm" action="" method="post" class="form">
            <header class="header-registration"><b>Update Staff Form</b></header>
            <h3>Basic Information</h3>
            <div class="column">
                <div class="input-box">
                    <label>First Name</label>
                    <input type="text" placeholder="Enter first name" name="fname" value="<?php echo $staff['fname']; ?>" required>
                </div>
                <div class="input-box">
                    <label>Last Name</label>
                    <input type="text" placeholder="Enter last name" name="lname" value="<?php echo $staff['lname']; ?>" required>
                </div>
            </div>
            <div class="column">
                <div class="input-box">
                    <label>Birthday</label>
                    <input type="date" name="bod" value="<?php echo $staff['bod']; ?>" required>
                </div>
                <div class="gender-box">
                    <label>Gender</label>
                    <div class="gender-option">
                        <div class="gender">
                            <input type="radio" id="check-male" name="gender" value="Male" <?php echo ($staff['gender'] == 'Male') ? 'checked' : ''; ?>>
                            <label for="check-male">Male</label>
                        </div>
                        <div class="gender">
                            <input type="radio" id="check-female" name="gender" value="Female" <?php echo ($staff['gender'] == 'Female') ? 'checked' : ''; ?>>
                            <label for="check-female">Female</label>
                        </div>
                        <div class="gender">
                            <input type="radio" id="check-other" name="gender" value="Prefer not to say" <?php echo ($staff['gender'] == 'Prefer not to say') ? 'checked' : ''; ?>>
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
                        <option value="1" <?php echo ($staff['id_card_type'] == 1) ? 'selected' : ''; ?>>National Identity Card</option>
                        <option value="2" <?php echo ($staff['id_card_type'] == 2) ? 'selected' : ''; ?>>Voter Id Card</option>
                        <option value="3" <?php echo ($staff['id_card_type'] == 3) ? 'selected' : ''; ?>>Pan Card</option>
                        <option value="4" <?php echo ($staff['id_card_type'] == 4) ? 'selected' : ''; ?>>Driving License</option>
                    </select>
                </div>
                <div class="input-box">
                    <label>ID Card Number</label>
                    <input type="text" placeholder="Enter ID Card Number" name="id_card_no" value="<?php echo $staff['id_card_no']; ?>" required>
                </div>
            </div>
            <h3>Contact Information</h3>
            <div class="column">
                <div class="input-box">
                    <label>Email address</label>
                    <input type="email" placeholder="Enter email address" name="email" value="<?php echo $staff['email']; ?>" required>
                </div>
                <div class="input-box">
                    <label>Phone Number</label>
                    <input type="text" placeholder="Enter phone number" name="phone" value="<?php echo $staff['phone']; ?>" required>
                </div>
            </div>
            <h3>Address</h3>
            <div class="input-box address">
                <input type="text" placeholder="Enter street address" name="street_address" value="<?php echo $staff['street_address']; ?>" required>
                <input type="text" placeholder="Enter street address line 2" name="street_address2" value="<?php echo $staff['street_address2']; ?>">
                <div class="column">
                    <div class="select-box country-select-box">
                        <select name="country" required>
                            <option hidden>Country</option>
                            <option value="United States of America" <?php echo ($staff['country'] == 'United States of America') ? 'selected' : ''; ?>>United States of America</option>
                            <option value="Japan" <?php echo ($staff['country'] == 'Japan') ? 'selected' : ''; ?>>Japan</option>
                            <option value="Philippines" <?php echo ($staff['country'] == 'Philippines') ? 'selected' : ''; ?>>Philippines</option>
                            <option value="Nepal" <?php echo ($staff['country'] == 'Nepal') ? 'selected' : ''; ?>>Nepal</option>
                            <option value="India" <?php echo ($staff['country'] == 'India') ? 'selected' : ''; ?>>India</option>
                        </select>
                    </div>
                    <input type="text" placeholder="Enter city" name="city" value="<?php echo $staff['city']; ?>" required>
                </div>
                <div class="column">
                    <input type="text" placeholder="Enter region" name="region" value="<?php echo $staff['region']; ?>" required>
                    <input type="text" placeholder="Enter postal code" name="postal_code" value="<?php echo $staff['postal_code']; ?>" required>
                </div>
            </div>
            <h3>Job Information</h3>
            <div class="column">
                <div class="select-box with-label">
                    <label>Staff Type</label>
                    <select name="staff_type_id" required>
                        <option hidden>Choose Staff Type</option>
                        <option value="1" <?php echo ($staff['staff_type_id'] == 1) ? 'selected' : ''; ?>>Manager</option>
                        <option value="2" <?php echo ($staff['staff_type_id'] == 2) ? 'selected' : ''; ?>>Housekeeping Manager</option>
                        <option value="3" <?php echo ($staff['staff_type_id'] == 3) ? 'selected' : ''; ?>>Front Desk Receptionist</option>
                        <option value="4" <?php echo ($staff['staff_type_id'] == 4) ? 'selected' : ''; ?>>Chef</option>
                        <option value="5" <?php echo ($staff['staff_type_id'] == 5) ? 'selected' : ''; ?>>Waiter</option>
                        <option value="6" <?php echo ($staff['staff_type_id'] == 6) ? 'selected' : ''; ?>>Room Attendant</option>
                        <option value="7" <?php echo ($staff['staff_type_id'] == 7) ? 'selected' : ''; ?>>Concierge</option>
                        <option value="8" <?php echo ($staff['staff_type_id'] == 8) ? 'selected' : ''; ?>>Hotel Maintenance Engineer</option>
                        <option value="9" <?php echo ($staff['staff_type_id'] == 9) ? 'selected' : ''; ?>>Hotel Sales Manager</option>
                    </select>
                </div>
                <div class="input-box">
                    <label>Salary</label>
                    <input type="text" placeholder="Enter salary" name="salary" value="<?php echo $staff['salary']; ?>" required>
                </div>
            </div>
            <div class="column">
                <div class="input-box">
                    <label>Hiring Date</label>
                    <input type="date" name="hiring_date" value="<?php echo $staff['hiring_date']; ?>" required>
                </div>
                <div class="select-box with-label">
                    <label>Shift</label>
                    <select name="shiftid" required>
                        <option hidden>Choose Shift</option>
                        <option value="1" <?php echo ($staff['shiftid'] == 1) ? 'selected' : ''; ?>>Morning (5:00 AM - 10:00 AM)</option>
                        <option value="2" <?php echo ($staff['shiftid'] == 2) ? 'selected' : ''; ?>>Day (10:00 AM - 4:00PM)</option>
                        <option value="3" <?php echo ($staff['shiftid'] == 3) ? 'selected' : ''; ?>>Evening (4:00 PM - 10:00 PM)</option>
                        <option value="4" <?php echo ($staff['shiftid'] == 4) ? 'selected' : ''; ?>>Night (10:00PM - 5:00AM)</option>
                    </select>
                </div>
            </div>
            <button id="registerButton" type="submit">Update</button>
        </form>
    </div>
</body>
</html>
