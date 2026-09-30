<?php
session_start();

include_once("connection.php");

// Check if session exists and role is 'customer'
if (!isset($_SESSION["email"]) || $_SESSION["role"] !== 'customer') {
    header("location:../index.php");
    exit();
}

// Get the order ID from the query parameter
$OrderID = isset($_GET['OrderID']) ? intval($_GET['OrderID']) : 0;

// Fetch the order details from the database
$query = "SELECT o.OrderID, o.CustomerID, of.FoodID, f.FoodName, f.price, f.Description 
          FROM orders o 
          JOIN orderfood of ON o.OrderID = of.OrderID 
          JOIN food f ON of.FoodID = f.FoodID 
          WHERE o.OrderID = :OrderID";
$stmt = $conn->prepare($query);
$stmt->bindParam(':OrderID', $OrderID, PDO::PARAM_INT);
$stmt->execute();
$orderItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch the payment details
$query = "SELECT PaymentDate, Amount, paymentMethod FROM payment WHERE OrderID = :OrderID";
$stmt = $conn->prepare($query);
$stmt->bindParam(':OrderID', $OrderID, PDO::PARAM_INT);
$stmt->execute();
$paymentDetails = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<!doctype html>
<html lang = 'en'>
<head>
<meta charset = 'utf-8'>
<meta name = 'viewport' content = 'width=device-width, initial-scale=1'>
<meta name = 'description' content = ''>
<meta name = 'author' content = 'Mark Otto, Jacob Thornton, and Bootstrap contributors'>
<meta name = 'generator' content = 'Hugo 0.84.0'>
<title>PartyPal_catering_system</title>

<link rel = 'canonical' href = 'https://getbootstrap.com/docs/5.0/examples/dashboard/'>

<!-- Bootstrap core CSS -->
<link href = 'assets/dist/css/bootstrap.min.css' rel = 'stylesheet'>

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
<link href = 'assets/dashboard.css' rel = 'stylesheet'>
</head>
<body>

<?php
include_once( 'header.php' );
?>

<div class = 'container-fluid'>
<div class = 'row'>
<?php
include_once( 'sidenav.php' );
?>
<main class = 'col-md-9 ms-sm-auto col-lg-10 px-md-4'>
<div class = 'd-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom'>
<div class = 'btn-toolbar mb-2 mb-md-0'>
<div class = 'btn-group me-2'>
</div>
</div>
</div>
<div class="container">
    <h2 class="mt-5">Order Summary</h2>

    <!-- Display Payment Details --> 
    <h4>Payment Details</h4>
    <p><strong>Payment Date:</strong> <?php echo htmlspecialchars($paymentDetails['PaymentDate']); ?></p>
    <p><strong>Amount Paid:</strong> $<?php echo number_format($paymentDetails['Amount'], 2); ?></p>
    <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($paymentDetails['paymentMethod']); ?></p>

    <!-- Display Order Items -->
    <h4 class="mt-4">Ordered Items</h4>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Food Name</th>
                <th>Price</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orderItems as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['FoodName']); ?></td>
                    <td>$<?php echo number_format($item['price'], 2); ?></td>
                    <td><?php echo htmlspecialchars($item['Description']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Total Price -->
    <div class="mt-4">
        <h4>Total Price: $<?php echo number_format($paymentDetails['Amount'], 2); ?></h4>
    </div>
</div>

<!-- Bootstrap core JavaScript -->
<script src="assets/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
