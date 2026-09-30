<?php
session_start();

include_once("connection.php");

// Ensure the caterer is logged in
if (!isset($_SESSION["email"]) || $_SESSION["role"] !== 'caterers') {
    header("location:../index.php");
    exit();
}

// Check if OrderID is set
if (!isset($_GET['OrderID'])) {
    echo "No order ID provided.";
    exit();
}

$orderID = $_GET['OrderID'];

// Fetch the order details
$query = "SELECT o.OrderID, o.OrderDate, o.status, c.firstName, c.lastName, c.phone, c.email, c.address
          FROM orders o
          JOIN customer c ON o.CustomerID = c.customerID
          WHERE o.OrderID = :OrderID";
$stmt = $conn->prepare($query);
$stmt->bindParam(':OrderID', $orderID, PDO::PARAM_INT);
$stmt->execute();
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    echo "Order not found.";
    exit();
}

// Fetch the food items associated with the order
$query = "SELECT f.FoodName, of.Quantity, of.Price
          FROM orderfood of
          JOIN food f ON of.FoodID = f.FoodID
          WHERE of.OrderID = :OrderID";
$stmt = $conn->prepare($query);
$stmt->bindParam(':OrderID', $orderID, PDO::PARAM_INT);
$stmt->execute();
$foodItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang='en'>
<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>Order Details - Partypal Catering System</title>
    <link href='assets/dist/css/bootstrap.min.css' rel='stylesheet'>
    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
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
                <h2>Order Details</h2>
            </div>

            <div class='order-details'>
                <h4>Order Information</h4>
                <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order['OrderID']); ?></p>
                <p><strong>Order Date:</strong> <?php echo htmlspecialchars($order['OrderDate']); ?></p>
                <p><strong>Status:</strong> <?php echo htmlspecialchars($order['status']); ?></p>

                <h4>Customer Information</h4>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($order['firstName'] . ' ' . $order['lastName']); ?></p>
                <p><strong>Phone:</strong> <?php echo htmlspecialchars($order['phone']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>
                <p><strong>Address:</strong> <?php echo htmlspecialchars($order['address']); ?></p>

                <h4>Ordered Food Items</h4>
                <table class='table table-striped table-sm'>
                    <thead>
                        <tr>
                            <th>Food Item</th>
                            <th>Quantity</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($foodItems) {
                            foreach ($foodItems as $item) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($item['FoodName']) . '</td>';
                                echo '<td>' . htmlspecialchars($item['Quantity']) . '</td>';
                                echo '<td>' . htmlspecialchars($item['Price']) . '</td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="3" class="text-center">No food items found for this order.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>

                <h4>Actions</h4>
                <?php if ($order['status'] === 'Pending'): ?>
                    <form method='post' action='update_order_status.php'>
                        <input type='hidden' name='OrderID' value='<?php echo htmlspecialchars($order['OrderID']); ?>'>
                        <button type='submit' class='btn btn-primary'>Mark as Received</button>
                    </form>
                <?php else: ?>
                    <p>This order has already been marked as received.</p>
                <?php endif; ?>
            </div>
        </main>
    </div>
</div>

<script src='assets/dist/js/bootstrap.bundle.min.js'></script>
</body>
</html>
