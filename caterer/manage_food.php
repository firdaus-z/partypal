<?php
session_start();
require_once('connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    $statement = $conn->prepare('INSERT INTO food (name, description, price, quantity) VALUES (:name, :description, :price, :quantity)');
    $statement->execute([
        ':name' => $name,
        ':description' => $description,
        ':price' => $price,
        ':quantity' => $quantity
    ]);

    $_SESSION['message'] = 'Food item added successfully.';
    header('Location: dashboard.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang='en'>
<head>
<meta charset='UTF-8'>
<meta name='viewport' content='width=device-width, initial-scale=1.0'>
<title>Add Food</title>
<link href='assets/dist/css/bootstrap.min.css' rel='stylesheet'>
</head>
<body>
<div class='container'>
<h2>Add Food</h2>
<form method='POST'>
    <div class='mb-3'>
        <label for='name' class='form-label'>Name</label>
        <input type='text' class='form-control' id='name' name='name' required>
    </div>
    <div class='mb-3'>
        <label for='description' class='form-label'>Description</label>
        <textarea class='form-control' id='description' name='description' rows='3' required></textarea>
    </div>
    <div class='mb-3'>
        <label for='price' class='form-label'>Price</label>
        <input type='number' step='0.01' class='form-control' id='price' name='price' required>
    </div>
    <div class='mb-3'>
        <label for='quantity' class='form-label'>Quantity</label>
        <input type='number' class='form-control' id='quantity' name='quantity' required>
    </div>
    <button type='submit' class='btn btn-primary'>Add Food</button>
</form>
</div>
</body>
</html>
