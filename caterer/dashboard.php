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

// Fetch the number of food items uploaded by the caterer
$food_sql = "SELECT COUNT(*) AS food_count FROM food WHERE catererID = :catererID";
$food_stmt = $conn->prepare($food_sql);
$food_stmt->bindParam(':catererID', $catererID, PDO::PARAM_INT);
$food_stmt->execute();
$food_row = $food_stmt->fetch(PDO::FETCH_ASSOC);
$food_count = $food_row['food_count'];

// Fetch the number of orders placed by customers for this caterer
$orders_sql = "SELECT COUNT(DISTINCT o.OrderID) AS order_count 
               FROM orders o
               JOIN orderfood of ON o.OrderID = of.OrderID
               WHERE of.FoodID IN (SELECT FoodID FROM food WHERE catererID = :catererID)";
$orders_stmt = $conn->prepare($orders_sql);
$orders_stmt->bindParam(':catererID', $catererID, PDO::PARAM_INT);
$orders_stmt->execute();
$orders_row = $orders_stmt->fetch(PDO::FETCH_ASSOC);
$order_count = $orders_row['order_count'];

// Fetch the number of unique customers who placed orders with this caterer
$customers_sql = "SELECT COUNT(DISTINCT o.CustomerID) AS customer_count
                   FROM orders o
                   JOIN orderfood of ON o.OrderID = of.OrderID
                   WHERE of.FoodID IN (SELECT FoodID FROM food WHERE catererID = :catererID)";
$customers_stmt = $conn->prepare($customers_sql);
$customers_stmt->bindParam(':catererID', $catererID, PDO::PARAM_INT);
$customers_stmt->execute();
$customers_row = $customers_stmt->fetch(PDO::FETCH_ASSOC);
$customer_count = $customers_row['customer_count'];
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PartyPal Catering System</title>
    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/dashboard/">
    <link href="assets/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/dashboard.css" rel="stylesheet">
    <style>
        a{
            text-decoration:none;
        }
    </style>
</head>
<body>
    <?php include_once("header.php"); ?>
    
    <div class="container-fluid">
        <div class="row">
            <?php include_once("sidenav.php"); ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Dashboard</h1>
                </div>

                <!-- Cards section -->
                <div class="row">
                    <div class="col-md-6 col-lg-3 mb-4">
                        <div class="card text-white bg-primary">
                            <div class="card-body">
                                <div class="card-title">Food Items Uploaded</div>
                                <h2 class="card-text"><?php echo $food_count; ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3 mb-4">
                        <div class="card text-white bg-success">
                            <div class="card-body">
                                <div class="card-title">Orders Placed</div>
                                <h2 class="card-text"><?php echo $order_count; ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3 mb-4">
                        <div class="card text-white bg-warning">
                            <div class="card-body">
                                <div class="card-title">Customers Who Placed Orders</div>
                                <h2 class="card-text"><?php echo $customer_count; ?></h2>
                            </div>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <script src="assets/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script>
    <script src="dashboard.js"></script>
</body>
</html>
