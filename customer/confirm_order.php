<?php
session_start();
include_once("connection.php");

if (!isset($_SESSION["email"]) || $_SESSION["role"] !== 'customer') {
    header("location:../index.php");
    exit();
}

$email = $_SESSION['email'];
$query = "SELECT CustomerID FROM customer WHERE email = :email";
$stmt = $conn->prepare($query);
$stmt->bindParam(':email', $email, PDO::PARAM_STR);
$stmt->execute();
$customerID = $stmt->fetchColumn();

if (!$customerID) {
    $_SESSION['message'] = "Customer not found.";
    header("Location: view_order.php");
    exit();
}

if (empty($_SESSION['order'])) {
    $_SESSION['message'] = "Your order is empty.";
    header("Location: view_order.php");
    exit();
}

// Calculate total quantity from session order items
$totalQuantity = 0;
foreach ($_SESSION['order'] as $item) {
    $totalQuantity += isset($item['quantity']) ? $item['quantity'] : 1;
}

// Insert the order into the orders table
$stmt = $conn->prepare("INSERT INTO orders (CustomerID, totalPrice, quantity, Status) VALUES (:CustomerID, :totalPrice, :quantity, :Status)");
$stmt->bindParam(':CustomerID', $customerID, PDO::PARAM_INT);
$stmt->bindParam(':totalPrice', $_SESSION['totalPrice'], PDO::PARAM_INT);
$stmt->bindParam(':quantity', $totalQuantity, PDO::PARAM_INT); // Use the calculated total quantity
$status = 'unpaid'; // Set the default status to 'unpaid'
$stmt->bindParam(':Status', $status, PDO::PARAM_STR);
$stmt->execute();
$orderID = $conn->lastInsertId();

$_SESSION['orderID'] = $orderID;

foreach ($_SESSION['order'] as $item) {
    $foodID = $item['FoodID'];

    // Check if the food item exists in the food table
    $checkFoodQuery = "SELECT COUNT(*) FROM food WHERE FoodID = :FoodID";
    $checkStmt = $conn->prepare($checkFoodQuery);
    $checkStmt->bindParam(':FoodID', $foodID, PDO::PARAM_INT);
    $checkStmt->execute();
    if ($checkStmt->fetchColumn() == 0) {
        $_SESSION['message'] = "Food item with ID $foodID does not exist.";
        header("Location: view_order.php");
        exit();
    }

    $quantity = isset($item['quantity']) ? $item['quantity'] : 1;
    $price = $item['price'];
    $totalPrice = $price * $quantity;

    // Insert the food item into the orderfood table
    $stmt = $conn->prepare("INSERT INTO orderfood (OrderID, FoodID, totalPrice, quantity) VALUES (:OrderID, :FoodID, :totalPrice, :quantity)");
    $stmt->bindParam(':OrderID', $orderID, PDO::PARAM_INT);
    $stmt->bindParam(':FoodID', $foodID, PDO::PARAM_INT);
    $stmt->bindParam(':totalPrice', $totalPrice, PDO::PARAM_INT);
    $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
    $stmt->execute();
}

// Clear the order from the session after successful insertion
unset($_SESSION['order']);


 header("Location: payment.php");
 exit();
?>