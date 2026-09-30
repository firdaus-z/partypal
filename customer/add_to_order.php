<?php
session_start();

include_once("connection.php");

if (!isset($_SESSION["email"]) || $_SESSION["role"] !== 'customer') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access.']);
    exit();
}

if (!isset($_SESSION['order'])) {
    $_SESSION['order'] = array();
}

$foodID = isset($_POST['FoodID']) ? intval($_POST['FoodID']) : 0;
$foodName = isset($_POST['FoodName']) ? $_POST['FoodName'] : '';
$price = isset($_POST['price']) ? floatval($_POST['price']) : 0.0;
$description = isset($_POST['Description']) ? $_POST['Description'] : '';
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1; 

if ($foodID > 0 && !empty($foodName)) {
    $_SESSION['order'][] = array(
        'FoodID' => $foodID,
        'FoodName' => $foodName,
        'price' => $price,
        'Description' => $description,
        'quantity' => $quantity
    );

    echo json_encode(['status' => 'success', 'message' => 'Item added to your order.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid food item.']);
}
exit();
