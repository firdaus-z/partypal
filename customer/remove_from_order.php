<?php
session_start(); // Start session if not already started

include_once("connection.php");

// Check if session exists and role is 'customer'
if (!isset($_SESSION["email"]) || $_SESSION["role"] !== 'customer') {
    header("location:../index.php");
    exit();
}

// Check if required POST data is present
if (!isset($_POST['FoodID'])) {
    $_SESSION['message'] = "Missing food item data.";
    header("Location: view_order.php");
    exit();
}

// Initialize or retrieve the order session array
if (isset($_SESSION['order'])) {
    $foodID = $_POST['FoodID'];

    // Remove the item from the session
    if (isset($_SESSION['order'][$foodID])) {
        unset($_SESSION['order'][$foodID]);
    }

    $_SESSION['message'] = "Item has been removed from your order.";
}

header("Location: view_order.php");
exit();
?>
