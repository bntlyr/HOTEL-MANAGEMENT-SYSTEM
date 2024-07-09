
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Goodwill Hotel</title>
    <link rel="icon" type="image/x-icon" href="./img/logo.svg">
    <link rel="shortcut icon" href="logo.svg" type="image/x-icon">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/templates.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-J2Sp4s4stwrbI8Rg+Rv5ZgDlDxV0cpFk1MPXeha4Wf8t3NUF7/1rCD2nF7DoCf/l0h7p1PPTd79MDcdJwHK3Nw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body> 
    <!-- Navbar -->
    <?php include('./templates/header.php');?>
    <?php 
         require('./homepage/hero.php');
         require('./homepage/services.php');
         require('./homepage/rooms.php');
         require('./homepage/contacts.php');
    ?>
    <!-- Footer -->
    <?php include('./templates/footer.php');?>
</body>
</html>

