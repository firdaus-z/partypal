<?php
session_start();
include_once("connection.php");

// Check if session exists and role is 'caterers'
if (!isset($_SESSION["email"]) || $_SESSION["role"] !== 'caterers') {
    header("location:../index.php");
    exit();
}

// Get the caterer's email from the session
$email = $_SESSION['email'];

// Fetch catererID based on the email
$query = "SELECT catererID FROM caterers WHERE email = :email";
$stmt = $conn->prepare($query);
$stmt->bindParam(':email', $email, PDO::PARAM_STR);
$stmt->execute();
$catererID = $stmt->fetchColumn();

if (!$catererID) {
    echo "Caterer not found.";
    exit();
}

// Fetch orders and related food details for the caterer
$query = "SELECT o.OrderID, o.OrderDate, c.firstName, c.lastName, f.FoodName, of.quantity, f.price, 
                 (of.quantity * f.price) AS TotalPrice, o.paymentStatus
          FROM orders o
          JOIN orderfood of ON o.OrderID = of.OrderID
          JOIN food f ON of.FoodID = f.FoodID
          JOIN customer c ON o.CustomerID = c.customerID
          WHERE f.catererID = :catererID
          ORDER BY o.OrderID DESC";
$stmt = $conn->prepare($query);
$stmt->bindParam(':catererID', $catererID, PDO::PARAM_INT);
$stmt->execute();
$orderFoods = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!doctype html>
<html lang='en'>
<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <meta name='description' content=''>
    <meta name='author' content='Mark Otto, Jacob Thornton, and Bootstrap contributors'>
    <meta name='generator' content='Hugo 0.84.0'>
    <title>PartyPal_catering_system</title>

    <link rel='canonical' href='https://getbootstrap.com/docs/5.0/examples/dashboard/'>
    <link href='assets/dist/css/bootstrap.min.css' rel='stylesheet'>

    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
       
    </style>
    <link href='assets/dashboard.css' rel='stylesheet'>
</head>
<body>

<?php include_once('header.php'); ?>

<div class='container-fluid'>
    <div class='row'>
        <?php include_once('sidenav.php'); ?>
        <main class='col-md-9 ms-sm-auto col-lg-10 px-md-4'>
            <div class='d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom'>
                <div class='btn-toolbar mb-2 mb-md-0'>
                </div>
            </div>
            <div class="container">
        <h1 class="my-4">order and food detail</h1>

        <?php
        if (isset($_SESSION['message'])) {
            echo "<p class='text-success'><strong>" . $_SESSION['message'] . "</strong>welcome back</p>";
            unset($_SESSION['message']);
        }
        ?>
<div class='table-responsive'>
<table class='table table-striped table-sm'>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer Name</th>
                <th>Order Date</th>
                <th>Food Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total Price</th>
                <th>Payment Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($orderFoods)): ?>
                <?php foreach ($orderFoods as $orderFood): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($orderFood['OrderID']); ?></td>
                        <td><?php echo htmlspecialchars($orderFood['firstName'] . ' ' . $orderFood['lastName']); ?></td>
                        <td><?php echo htmlspecialchars($orderFood['OrderDate']); ?></td>
                        <td><?php echo htmlspecialchars($orderFood['FoodName']); ?></td>
                        <td><?php echo htmlspecialchars($orderFood['quantity']); ?></td>
                        <td>$<?php echo number_format($orderFood['price'], 2); ?></td>
                        <td>$<?php echo number_format($orderFood['TotalPrice'], 2); ?></td>
                        <td><?php echo htmlspecialchars($orderFood['paymentStatus']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center">No orders found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Bootstrap core JavaScript -->
<script src="assets/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
