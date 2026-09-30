<?php
session_start();
include("connection.php");

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $input_password = $_POST['password'];
    $password = sha1($input_password);

    $stmt = $conn->prepare("SELECT * FROM customer WHERE email = :email AND password = :password");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    if ($stmt->rowCount() == 1) {
        $_SESSION["email"] = $email;
        $_SESSION["role"] = 'customer';
        header('Location: customer/customer_dashboard.php');
        exit(); 
        
    } else {
        $stmt2 = $conn->prepare("SELECT * FROM admin WHERE email = :email AND password = :password");
        $stmt2->bindParam(':email', $email);
        $stmt2->bindParam(':password', $password);
        $stmt2->execute();

        if ($stmt2->rowCount() == 1) {
            $_SESSION["email"] = $email;
            $_SESSION["role"] = 'admin';
            header('Location: dashboard.php');
            exit();

        } else {
            $stmt3 = $conn->prepare("SELECT * FROM caterers WHERE email = :email AND password = :password");
            $stmt3->bindParam(':email', $email);
            $stmt3->bindParam(':password', $password);
            $stmt3->execute();

            if ($stmt3->rowCount() == 1) {
                $_SESSION["email"] = $email;
                $_SESSION["role"] = 'caterers';
                header('Location: caterer/dashboard.php');
                exit();

            } else {
                
                $_SESSION['login_error'] = 'Invalid email or password.';
                header('Location: index.php');
                exit();
            }
        }
    }
}

$conn = null;
?>
