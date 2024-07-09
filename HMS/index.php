<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Management System</title>
    <link rel="icon" type="image/x-icon" href="./includes/logo.svg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="styles/index.css"> 

</head>
<body>
    <?php include('templates/header.php'); ?>
    <?php include('templates/sidebar.php'); ?>
    
    <div class="main-content">
        <?php
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
            $page = str_replace('/', '', $page);
            $page = str_replace('\\', '', $page);

            $allowed_pages = ['dashboard', 'guests', 'rooms', 'staffs','add_staff',
            'view_staff','edit_staff','add_room','edit_room','view_room','view_guest'];

            if (in_array($page, $allowed_pages)) {
                include("$page.php");
            } else {
                echo "<h2>404 Not Found</h2>";
            }
        } else {
            include("dashboard.php");
        }
        ?>
    </div>
</body>
</html>
