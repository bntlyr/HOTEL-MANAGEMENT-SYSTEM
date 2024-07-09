<?php
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
?>
<link rel="stylesheet" type="text/css" href="styles/header.css">
<link rel="stylesheet" type="text/css" href="styles/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">


<div class="header">
    <h1 class="title">HOTEL MANAGEMENT SYSTEM</h1>
    <div class="user-menu">
        <i class="fas fa-user user-icon"></i>
        <div class="dropdown-content">
            <a href="logout.php">Logout</a>
            <a href="#">Settings</a> <!-- lalagyan pa ng settings.php -->
        </div>
    </div>
</div>