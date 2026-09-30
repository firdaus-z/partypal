<?php
session_start();

if (!isset($_SESSION["email"]) || $_SESSION["role"] !== 'customer') {
    header("location:../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirm'])) {
        header("location: payment.php");
        exit();
    } elseif (isset($_POST['cancel'])) {
        
        unset($_SESSION['order']);
        header("location: customer_dashboard.php");
        exit();
    }
}
?>
