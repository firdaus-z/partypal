<!-- login-handler.php -->
<?php
session_start();
require_once('connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $statement = $conn->prepare('SELECT * FROM caterers WHERE email = ?');
    $statement->execute([$email]);
    $caterer = $statement->fetch();

    if ($caterer && password_verify($password, $caterer['password'])) {
        $_SESSION['catererID'] = $caterer['catererID'];
        $_SESSION['catererName'] = $caterer['Name'];
        header('Location: dashboard.php');
    } else {
        $_SESSION['message'] = 'Invalid email or password';
        header('Location: login.php');
    }
}
?>
