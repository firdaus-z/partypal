<?php
session_start();
include_once("connection.php");

// Check if session exists and role is 'customer'
if (!isset($_SESSION["email"]) || $_SESSION["role"] !== 'customer') {
    header("location:../index.php");
    exit();
}

// Get the customer's email from the session
$email = $_SESSION['email'];

// Fetch the CustomerID based on the email
$query = "SELECT customerID FROM customer WHERE email = :email";
$stmt = $conn->prepare($query);
$stmt->bindParam(':email', $email, PDO::PARAM_STR);
$stmt->execute();
$customerID = $stmt->fetchColumn();

// If customerID is not found, redirect to customer orders page
if (!$customerID) {
    header("Location: customer_order.php");
    exit();
}

// Get the order ID from the query parameter
$OrderID = isset($_GET['OrderID']) ? intval($_GET['OrderID']) : 0;

// Fetch order details from the database, including status
$query = "SELECT o.OrderID, o.OrderDate, o.Status, f.FoodName, of.quantity, f.price, (of.quantity * f.price) AS TotalPrice
          FROM orders o
          JOIN orderfood of ON o.OrderID = of.OrderID
          JOIN food f ON of.FoodID = f.FoodID
          WHERE o.OrderID = :OrderID AND o.CustomerID = :CustomerID";
$stmt = $conn->prepare($query);
$stmt->bindParam(':OrderID', $OrderID, PDO::PARAM_INT);
$stmt->bindParam(':CustomerID', $customerID, PDO::PARAM_INT);
$stmt->execute();
$orderDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);

// If no order details found, redirect or show an error message
if (!$orderDetails) {
    header("Location: customer_order.php");
    exit();
}

// Set status to "Pending" if not "Received"
foreach ($orderDetails as &$detail) {
    if (empty($detail['Status']) || $detail['Status'] !== 'Received') {
        $detail['Status'] = 'Pending';
    }
}
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

        .remove-item-btn {
            margin: 0;
        }

        .confirm-order-btn {
            margin-top: 20px;
        }

        .total-price {
            font-size: 1.25rem;
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<?php include_once('header.php'); ?>
    <div class="container-fluid">
        <div class="row">
            <?php include_once('sidenav.php'); ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <h2 class="mt-5">My Orders</h2>
            
                <!-- Display Orders -->
                <h4>Order ID: <?php echo htmlspecialchars($OrderID); ?></h4>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Food Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total Price</th>
                            <th>Order Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orderDetails as $detail): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($detail['FoodName']); ?></td>
                                <td><?php echo htmlspecialchars($detail['quantity']); ?></td>
                                <td>Tsh <?php echo number_format($detail['price'], 2); ?></td>
                                <td>Tsh <?php echo number_format($detail['TotalPrice'], 2); ?></td>
                                <td><?php echo htmlspecialchars($detail['OrderDate']); ?></td>
                                <td><?php echo htmlspecialchars($detail['Status']); ?></td>
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
