<?php
session_start();

// Check if the user is logged in as a caterer
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'caterers') {
    header("location:../index.php");
    exit;
}
?>

<!doctype html>
<html lang='en'>
<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>Order Details - Partypal Catering System</title>
    <link href='assets/dist/css/bootstrap.min.css' rel='stylesheet'>
    <link href='assets/dashboard.css' rel='stylesheet'>

    <link href='assets/dashboard.css' rel='stylesheet'>
    <style>
input[type="submit"]
{

    padding: 10px 20px;
    border: none;
    border-radius: 20px;
    width: 15%;
    background-color:rgb(1,0,255);
    color:#fff;

}
.print{
    padding: 10px ;
    border: 20px;
    border-radius: 20px;
    width: 15%;
    background-color:rgb(1,0,255);
    color:#fff
}
input[type="date"]
{
    width: 100%;
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
}
    </style>
</head>
<body>

<?php include_once('header.php'); ?>

<div class='container-fluid'>
    <div class='row'>
        <?php include_once('sidenav.php'); ?>
        <main class='col-md-9 ms-sm-auto col-lg-10 px-md-4'>

            <!-- <div class="container"> -->
                <h1>Orders</h1>
                <form method="POST">
                    <label for="start_date">Start Date:</label>
                    <input type="date" id="start_date" name="start_date"  required>
                   
                    <label for="end_date">End Date:</label>
                    <input type="date" id="end_date" name="end_date"  required>
                  
                    <input type="submit" name="generate_report"  value="Generate Report ">  
                 </form>

                <?php
                if (isset($_POST['generate_report'])) {
                    include_once('connection.php');

                    // Get the caterer's email from the session
                    $email = $_SESSION['email'];

                    // Fetch the catererID using the email
                    $stmt = $conn->prepare("SELECT catererID FROM caterers WHERE email = :email");
                    $stmt->bindParam(':email', $email);
                    $stmt->execute();
                    $caterer = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    // Check if caterer exists
                    if ($caterer) {
                        $catererID = $caterer['catererID'];

                        // Fetch the start and end dates
                        $startDate = $_POST['start_date'];
                        $endDate = $_POST['end_date'];

                        // Query to get orders for the specific caterer within the selected date range
                        $query = "SELECT customer.firstName, food.FoodName, orderfood.quantity, food.price, orders.totalPrice, orders.OrderDate
                                  FROM orders
                                  JOIN customer ON orders.CustomerID = customer.CustomerID
                                  JOIN orderfood ON orders.OrderID = orderfood.OrderID
                                  JOIN food ON orderfood.foodID = food.foodID
                                  WHERE orders.OrderDate BETWEEN :startDate AND :endDate
                                  AND food.catererID = :catererID";
                        
                        $stmt = $conn->prepare($query);
                        $stmt->bindParam(':startDate', $startDate);
                        $stmt->bindParam(':endDate', $endDate);
                        $stmt->bindParam(':catererID', $catererID);
                        $stmt->execute();
                        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        $totalAmount = 0;
                    } else {
                        echo "<p class='alert alert-danger'>Caterer not found.</p>";
                    }
                }
                ?>

                <?php if (isset($orders) && !empty($orders)): ?>
                    <table class="table table-bordered table-hover table-custom mt-4">
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
                                    <td><?php echo $order['quantity']; ?> pcs</td>
                                    <td><?php echo number_format($order['price'], 2); ?> Tsh</td>
                                    <td><?php echo number_format($order['totalPrice'], 2); ?> Tsh</td>
                                    <td><?php echo $order['OrderDate']; ?></td>
                                </tr>
                                <?php $totalAmount += $order['totalPrice']; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div class="total-amount mt-3">
                        <strong>Total Amount: <?php echo number_format($totalAmount, 2); ?> Tsh</strong>
                    </div>
                <?php elseif (isset($_POST['generate_report'])): ?>
                    <p class="alert alert-warning mt-3">No orders found for the selected date range.</p>
                <?php endif; ?>
                <br><button class="print" onclick="window.print()" >Print Report</button>
            </div>
        </main>
    </div>
</div>

<script src="assets/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
