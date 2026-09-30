<?php
session_start();
include("connection.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="index.css">
</head>
<body>
    <form method="POST" action="login_handler.php">
        <center><img src="3.png" width="90" height="90"></center>
        <?php
        if (isset($_SESSION['login_error'])) {
            echo '<p style="color: red;">' . $_SESSION['login_error'] . '</p>';
            unset($_SESSION['login_error']);
        }
        ?>
        <label for="email">Email: </label>
        <input type="email" name="email" required><br><br>
        <label for="password">Password: </label>
        <input type="password" name="password" minlength="8" required><br><br>
        <input type="submit" name="login" value="Login">
        <p>If you don't have an account, <a href="sign-up.php">sign up here</a></p>
    </form>
</body>
</html>
