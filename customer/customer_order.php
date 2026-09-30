<?php
session_start();
include_once("connection.php");

// Check if session exists and role is 'customer'
if (!isset($_SESSION["email"]) || $_SESSION["role"] !== 'customer') {
    header("location:../index.php");
    exit();
}

// Retrieve customer email from session
$customerEmail = $_SESSION['email'];

// Fetch customer ID using email
$query = "SELECT CustomerID FROM customer WHERE email = :email";
$stmt = $conn->prepare($query);
$stmt->bindParam(':email', $customerEmail, PDO::PARAM_STR);
$stmt->execute();
$customer = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$customer) {
    die("Customer not found.");
}

$customerID = $customer['CustomerID'];

// Fetch customer orders from the database
$query = "SELECT o.OrderID, o.OrderDate, SUM(of.quantity * f.price) AS TotalAmount
          FROM orders o
          JOIN orderfood of ON o.OrderID = of.OrderID
          JOIN food f ON of.FoodID = f.FoodID
          WHERE o.CustomerID = :customerID
          GROUP BY o.OrderID, o.OrderDate";
$stmt = $conn->prepare($query);
$stmt->bindParam(':customerID', $customerID, PDO::PARAM_INT);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Orders</title>

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
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Order Date</th>
                            <th>Total Amount</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['OrderID']); ?></td>
                        <td><?php echo htmlspecialchars($order['OrderDate']); ?></td>
                        <td>Tsh<?php echo number_format($order['TotalAmount'], 2); ?></td>
                        <td><a href="order_details.php?OrderID=<?php echo urlencode($order['OrderID']); ?>" class="btn btn-info btn-sm">View Details</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap core JavaScript -->
    <script src="assets/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
