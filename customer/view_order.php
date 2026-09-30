<?php
session_start(); // Start session if not already started

include_once("connection.php");

// Check if session exists and role is 'customer'
if (!isset($_SESSION["email"]) || $_SESSION["role"] !== 'customer') {
    header("location:../index.php");
    exit();
}

// Fetch customer name
$query = "SELECT firstName FROM customer WHERE email = :email";
$stmt = $conn->prepare($query);
$stmt->bindParam(':email', $_SESSION['email'], PDO::PARAM_STR);
$stmt->execute();
$name = $stmt->fetchColumn();

// Initialize or retrieve the order session array
$orderItems = isset($_SESSION['order']) ? $_SESSION['order'] : array();

// Calculate total price
$totalPrice = 0;
foreach ($orderItems as $item) {
    $totalPrice += $item['price'] * $item['quantity'];
}
$_SESSION['totalPrice'] = $totalPrice;

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
            <h1 class="my-4">Your Order</h1>

            <?php if (isset($_SESSION['message'])): ?>
                <p class="text-center text-success"><strong><?php echo $_SESSION['message']; ?></strong></p>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>

            <!-- Order Items Table -->
            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th scope="col">FoodName</th>
                            <th scope="col">Price</th>
                            <th scope="col">Description</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Total</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($orderItems)): ?>
                            <?php foreach ($orderItems as $foodID => $item): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item['FoodName']); ?></td>
                                    <td>Tsh <?php echo number_format($item['price'], 2); ?></td>
                                    <td><?php echo htmlspecialchars($item['Description']); ?></td>
                                    <td><?php echo $item['quantity']; ?> Pish</td>
                                    <td>Tsh <?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                    <td>
                                        <form method="post" action="remove_from_order.php">
                                            <input type="hidden" name="FoodID" value="<?php echo $foodID; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm remove-item-btn">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">Your order is empty.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Total Price Display -->
            <?php if (!empty($orderItems)): ?>
                <div class="total-price">
                    Total Price: Tsh <?php echo number_format($totalPrice, 2); ?>
                </div>
            <?php endif; ?>

            <!-- Confirm Order Button -->
            <div class="confirm-order-btn">
                <a href="confirm_order.php" class="btn btn-success">Confirm Order</a>
            </div>
        </main>
    </div>
</div>

<!-- Bootstrap core JavaScript -->
<script src="assets/dist/js/bootstrap.bundle.min.js"></script>

<!-- Your custom script -->
<script src="dashboard.js"></script>
</body>
</html>
