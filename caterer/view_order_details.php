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

// Get the order ID from the query parameter
$OrderID = isset($_GET['OrderID']) ? intval($_GET['OrderID']) : 0;

// Fetch order details along with payment status
$query = "SELECT o.OrderID, o.OrderDate, o.paymentStatus, c.firstName, c.lastName, f.FoodName, of.quantity, f.price, (of.quantity * f.price) AS TotalPrice
          FROM orders o
          JOIN customer c ON o.CustomerID = c.customerID
          JOIN orderfood of ON o.OrderID = of.OrderID
          JOIN food f ON of.FoodID = f.FoodID
          WHERE o.OrderID = :OrderID AND f.catererID = :catererID";
$stmt = $conn->prepare($query);
$stmt->bindParam(':OrderID', $OrderID, PDO::PARAM_INT);
$stmt->bindParam(':catererID', $catererID, PDO::PARAM_INT);
$stmt->execute();
$orderDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);

// If no order details found, redirect or show an error message
if (!$orderDetails) {
    header("Location: view_orders.php");
    exit();
}

// Extract the first row to get summary details
$summary = $orderDetails[0];
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>View Your Order</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="assets/dashboard.css" rel="stylesheet">

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

        .confirm-order-btn {
            margin-top: 20px;
        }

        .total-price {
            font-size: 1.25rem;
            font-weight: bold;
            margin-top: 20px;
        }

        .order-table th, .order-table td {
            text-align: center;
        }
    </style>
</head>
<body>

<?php include_once('header.php'); ?>

<div class="container-fluid">
    <div class="row">
        <?php include_once('sidenav.php'); ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <h1 class="my-4">Order Details</h1>

            <!-- Display All Order Information in a Single Table -->
            <table class="table table-striped order-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer Name</th>
                        <th>Order Date</th>
                        <th>Payment Status</th>
                        <th>Food Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orderDetails as $detail): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($detail['OrderID']); ?></td>
                            <td><?php echo htmlspecialchars($detail['firstName'] . ' ' . $detail['lastName']); ?></td>
                            <td><?php echo htmlspecialchars($detail['OrderDate']); ?></td>
                            <td><?php echo htmlspecialchars($detail['paymentStatus']); ?></td>
                            <td><?php echo htmlspecialchars($detail['FoodName']); ?></td>
                            <td><?php echo htmlspecialchars($detail['quantity']); ?></td>
                            <td>$<?php echo number_format($detail['price'], 2); ?></td>
                            <td>$<?php echo number_format($detail['TotalPrice'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>
    </div>
</div>

<!-- Bootstrap core JavaScript -->
<script src="assets/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
