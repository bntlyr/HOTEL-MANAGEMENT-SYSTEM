<link rel="stylesheet" type="text/css" href="styles/sidebar.css">
<link rel="stylesheet" type="text/css" href="styles/style.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

<div class="sidebar">
    <h2>ADMIN</h2>
    <ul>
        <li <?php if ($_GET['page'] == 'dashboard') echo 'class="active"'; ?>>
            <a href="index.php?page=dashboard"><i class="fas fa-home"></i><span>Dashboard</span></a>
        </li>
        <li <?php if ($_GET['page'] == 'guests') echo 'class="active"'; ?>>
            <a href="index.php?page=guests"><i class="fas fa-user-friends"></i><span>Guest Section</span></a>
        </li>
        <li <?php if ($_GET['page'] == 'staffs') echo 'class="active"'; ?>>
            <a href="index.php?page=staffs"><i class="fas fa-user-tie"></i><span>Staff Section</span></a>
        </li>
        <li <?php if ($_GET['page'] == 'rooms') echo 'class="active"'; ?>>
            <a href="index.php?page=rooms"><i class="fas fa-bed"></i><span>Manage Rooms</span></a>
        </li>
    </ul>
</div>

