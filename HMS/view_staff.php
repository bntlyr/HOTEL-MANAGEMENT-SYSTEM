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

$staff_id = $_GET['id'];

// Fetch staff details from database
$query = "SELECT s.*, st.staff_type, sh.shift_timing, ic.id_card_type
FROM staffs s
INNER JOIN staff_type st ON s.staff_type_id = st.staff_type_id
INNER JOIN shift sh ON s.shiftid = sh.shift_id
INNER JOIN id_card_type ic ON s.id_card_type = ic.id_card_type_id
WHERE s.id = $staff_id";

$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    echo "<h2>Staff not found</h2>";
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
    <title>View Staff - Hotel Management System</title>
    <link rel="stylesheet" href="styles/view_staff.css">
</head>
<body>
<div class="main-content">
    <h1 class="dash"><i class="fas fa-id-card"></i> Staff Details</h1>
    <div class="staff-details">
        <div class="full-name">
            <span><?php echo $row['fname'] . ' ' . $row['lname']; ?></span>
        </div>
        <div class="detail-row">
            <label>Birthday:</label>
            <span><?php echo $row['bod']; ?></span>
        </div>
            <div class="detail-row">
                <label>Gender:</label>
                <span><?php echo $row['gender']; ?></span>
            </div>
            <div class="detail-row">
                <label>Email:</label>
                <span><?php echo $row['email']; ?></span>
            </div>
            <div class="detail-row">
                <label>Phone:</label>
                <span><?php echo $row['phone']; ?></span>
            </div>
            <div class="detail-row">
                <label>Address:</label>
                <span><?php echo $row['street_address'] . ', ' . $row['street_address2'] . ', ' . $row['city'] . ', ' . $row['region'] . ', ' . $row['country'] . ', ' . $row['postal_code']; ?></span>
            </div>
            <div class="detail-row">
                <label>ID Card Type:</label>
                <span><?php echo $row['id_card_type']; ?></span>
            </div>
            <div class="detail-row">
                <label>ID Card Number:</label>
                <span><?php echo $row['id_card_no']; ?></span>
            </div>
            <div class="detail-row">
                <label>Hiring Date:</label>
                <span><?php echo $row['hiring_date']; ?></span>
            </div>
            <div class="detail-row">
                <label>Shift:</label>
                <span><?php echo $row['shift_timing']; ?></span>
            </div>
            <div class="detail-row">
                <label>Staff Type:</label>
                <span><?php echo $row['staff_type']; ?></span>
            </div>
            <div class="detail-row">
                <label>Salary:</label>
                <span><?php echo '₱' . number_format($row['salary'], 2); ?></span>
            </div>
        </div>
    </div>
</body>
</html>
