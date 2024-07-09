<?php
include('includes/db.php');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="styles/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-J2Sp4s4stwrbI8Rg+Rv5ZgDlDxV0cpFk1MPXeha4Wf8t3NUF7/1rCD2nF7DoCf/l0h7p1PPTd79MDcdJwHK3Nw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>

<div class="main-content">
<h1 class="dash"><i class="fas fa-home"></i> Dashboard</h1>
    <div class="container">

        <?php
        $total_guests = $conn->query("SELECT COUNT(*) AS count FROM guests")->fetch_assoc()['count'];
        $total_bookings = $conn->query("SELECT COUNT(*) AS count FROM bookings")->fetch_assoc()['count'];
        $total_rooms = $conn->query("SELECT COUNT(*) AS count FROM rooms")->fetch_assoc()['count'];
        $available_rooms = $conn->query("SELECT COUNT(*) AS count FROM rooms WHERE roomStatus='available'")->fetch_assoc()['count'];
        $total_staffs = $conn->query("SELECT COUNT(*) AS count FROM staffs")->fetch_assoc()['count'];
        $total_earnings = $conn->query("SELECT SUM(amount) AS total FROM payments")->fetch_assoc()['total'];
        ?>

        <div class="dashboard-statistics">
            <div>
                <i class="fas fa-users fa-3x"></i>
                <div class="stat-number"><?php echo $total_guests; ?></div>
                <div class="stat-text">Total Guests</div>
            </div>
            <div>
                <i class="fas fa-book fa-3x"></i>
                <div class="stat-number"><?php echo $total_bookings; ?></div>
                <div class="stat-text">Total Bookings</div>
            </div>
            <div>
                <i class="fas fa-bed fa-3x"></i>
                <div class="stat-number"><?php echo $total_rooms; ?></div>
                <div class="stat-text">Total Rooms</div>
            </div>
            <div>
                <i class="fas fa-door-open fa-3x"></i>
                <div class="stat-number"><?php echo $available_rooms; ?></div>
                <div class="stat-text">Available Rooms</div>
            </div>
            <div>
                <i class="fas fa-users-cog fa-3x"></i>
                <div class="stat-number"><?php echo $total_staffs; ?></div>
                <div class="stat-text">Total Staffs</div>
            </div>
            <div>
            <i class="fas fa-money-bill-wave fa-3x"></i>
                <div class="stat-number">₱<?php echo number_format($total_earnings, 2); ?></div>
                <div class="stat-text">Total Earnings</div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
