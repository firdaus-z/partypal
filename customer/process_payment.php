<?php
session_start();
include_once("connection.php");

if (!isset($_SESSION["email"]) || $_SESSION["role"] !== 'customer') {
    header("location:../index.php");
    exit();
}

$orderID = isset($_POST['OrderID']) ? intval($_POST['OrderID']) : 0;
$paymentDate = $_POST['paymentDate'];
$amount = $_POST['Amount'];
$paymentMethod = $_POST['paymentMethod'];

// Validate OrderID existence
$query = "SELECT COUNT(*) FROM orders WHERE OrderID = :OrderID";
$stmt = $conn->prepare($query);
$stmt->bindParam(':OrderID', $orderID, PDO::PARAM_INT);
$stmt->execute();
$orderExists = $stmt->fetchColumn();

if (!$orderExists) {
    $_SESSION['message'] = "OrderID $orderID does not exist.";
    header("Location: view_order.php");
    exit();
}

try {
    // Insert payment details into the payment table
    $query = "INSERT INTO payment (OrderID, PaymentDate, Amount, paymentMethod) 
              VALUES (:OrderID, :PaymentDate, :Amount, :paymentMethod)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':OrderID', $orderID, PDO::PARAM_INT);
    $stmt->bindParam(':PaymentDate', $paymentDate, PDO::PARAM_STR);
    $stmt->bindParam(':Amount', $amount, PDO::PARAM_STR);
    $stmt->bindParam(':paymentMethod', $paymentMethod, PDO::PARAM_STR);
    $stmt->execute();

    // Update the paymentStatus in the orders table
    $updateQuery = "UPDATE orders SET paymentStatus = 'Paid' WHERE OrderID = :OrderID";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bindParam(':OrderID', $orderID, PDO::PARAM_INT);
    $updateStmt->execute();

    // Clear the session variables related to the order
    unset($_SESSION['order']);
    unset($_SESSION['totalPrice']);
    unset($_SESSION['orderID']);

    // Redirect to the order summary page
    header("Location: order_summary.php?OrderID=" . urlencode($orderID));
    exit();

} catch (PDOException $e) {
    // Handle database errors
    $_SESSION['message'] = "Error processing payment: " . $e->getMessage();
    header("Location: view_order.php");
    exit();
}
?>
