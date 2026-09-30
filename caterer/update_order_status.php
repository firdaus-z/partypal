<?php
session_start();
include_once("connection.php");

// Check if the caterer is logged in
if (!isset($_SESSION["email"]) || $_SESSION["role"] !== 'caterers') {
    header("location:../index.php");
    exit();
}

// Get the OrderID from the POST request
if (isset($_POST['OrderID'])) {
    $OrderID = intval($_POST['OrderID']);

    try {
        // Update the  order status to 'Received'
        $query = "UPDATE orders SET status = 'Received' WHERE OrderID = :OrderID";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':OrderID', $OrderID, PDO::PARAM_INT);
        $stmt->execute();

        // Redirect to the order details page
        header("Location: view_order.php?OrderID=" . $OrderID);
        exit();

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    header("Location: caterer_order.php");
    exit();
}
?>
