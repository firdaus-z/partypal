<!doctype html>
<html lang='en'>
<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>Order Details - Partypal Catering System</title>
    <link href='assets/dist/css/bootstrap.min.css' rel='stylesheet'>
    <link href='assets/dashboard.css' rel='stylesheet'>
    <style>
.bd-placeholder-img {
    font-size: 1.125rem;
    text-anchor: middle;
    -webkit-user-select: none;
    -moz-user-select: none;
    user-select: none;
}

@media ( min-width: 768px ) {
    .bd-placeholder-img-lg {
        font-size: 3.5rem;
    }
}
</style>

<link href = 'assets/dashboard.css' rel = 'stylesheet'>
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

            <div class="container">
                <h1>Orders</h1>
                <form method="POST" >
                    
                        <label for="start_date">Start Date:</label>
                        <input type="date" id="start_date" name="start_date" class="form-control" required>
                   
                    
                        <label for="end_date">End Date:</label>
                        <input type="date" id="end_date" name="end_date" class="form-control" required>
                  
                    <button type="submit" name="generate_report" class="btn btn-primary">Generate Report</button>
                </form>

                <?php
                if (isset($_POST['generate_report'])) {
                    include_once('connection.php');

                    $startDate = $_POST['start_date'];
                    $endDate = $_POST['end_date'];

                    $query = "SELECT customer.firstName, food.FoodName, orderfood.quantity, food.price, orders.totalPrice, orders.OrderDate
                              FROM orders
                              JOIN customer ON orders.CustomerID = customer.CustomerID
                              JOIN orderfood ON orders.OrderID = orderfood.OrderID
                              JOIN food ON orderfood.foodID = food.foodID
                              WHERE orders.OrderDate BETWEEN :startDate AND :endDate";
                    
                    $stmt = $conn->prepare($query);
                    $stmt->bindParam(':startDate', $startDate);
                    $stmt->bindParam(':endDate', $endDate);
                    $stmt->execute();
                    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    $totalAmount = 0;
                }
                ?>

                <?php if (isset($orders) && !empty($orders)): ?>
                    <table class="table table-bordered table-hover table-custom">
                        <thead>
                            <tr>
                                <th>Customer Name</th>
                                <th>Food Name</th>
                                <th>Quantity</th>
                                <th>Price per Unit</th>
                                <th>Total Price</th>
                                <th>Date Ordered</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($order['firstName']); ?></td>
                                    <td><?php echo htmlspecialchars($order['FoodName']); ?></td>
                                    <td><?php echo $order['quantity']; ?> pish</td>
                                    <td><?php echo number_format($order['price'], 2); ?> Tsh</td>
                                    <td><?php echo number_format($order['totalPrice'], 2); ?> Tsh</td>
                                    <td><?php echo $order['OrderDate']; ?></td>
                                </tr>
                                <?php $totalAmount += $order['totalPrice']; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div class="total-amount">
                        Total Amount: <?php echo number_format($totalAmount, 2); ?> Tsh
                    </div>
                <?php else: ?>
                    <p class="alert alert-warning">No orders found for the selected date range.</p>
                <?php endif; ?>
                <button onclick="window.print()" class="btn btn-secondary mt-3">Print Report</button>
            </div>
        </main>
    </div>
</div>

<!-- Bootstrap core JavaScript -->
<script src="assets/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
