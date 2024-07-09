<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include 'includes/db.php';

    if (isset($conn) && $conn->connect_error == null) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM admins WHERE username='$username' AND password='$password'";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $_SESSION['admin'] = $row['id'];
            header('Location: index.php?page=dashboard');
        } else {
            $error = "Invalid username or password";
        }
    } else {
        $error = "Database connection failed: " . $conn->connect_error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link rel="stylesheet" href="styles/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="container">
        <input type="checkbox" id="flip">
        <div class="cover">
            <div class="front">
                <img src="includes/image1.jpg" alt="">
                <div class="text">
                    <span class="text-1" style="font-size: 30px;">Hotel Management <br> System</span>
                    <span class="text-2" style="font-size: 20px;">Goodwill Hotel</span>
                </div>
            </div>
            <div class="back">
                <div class="text">
                    <span class="text-1"><br></span>
                    <span class="text-2"></span>
                </div>
            </div>
        </div>
        <div class="forms">
            <div class="form-content">
                <div class="login-form">
                    <form method="POST" action="">
                        <h2 class="title">Admin Login</h2>
                        <?php if (isset($error)) echo "<p>$error</p>"; ?>
                        <div class="input-box">
                            <i class="fas fa-user"></i>
                            <input type="text" id="username" name="username" required autocomplete="off" placeholder="Username">
                        </div>
                        <div class="input-box">
                            <i class="fas fa-lock"></i>
                            <input type="password" id="password" name="password" required autocomplete="off" placeholder="Password">
                        </div>
                        <div class="button input-box">
                            <input type="submit" value="Login">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
