<?php
session_start();

// Check if session exists and role is 'caterer'
if (!isset($_SESSION["email"]) || $_SESSION["role"] !== 'caterers') {
    header("location:../index.php");
    exit();
}

// Include database connection
include_once('connection.php');

// Caterer's email from session
$catererEmail = $_SESSION['email'];

// Fetch the catererID from the caterers table using the email
$query = "SELECT catererID FROM caterers WHERE email = :email";
$stmt = $conn->prepare($query);
$stmt->bindParam(':email', $catererEmail, PDO::PARAM_STR);
$stmt->execute();
$caterer = $stmt->fetch(PDO::FETCH_ASSOC);
$catererID = $caterer['catererID'];

// // Initialize report data
// // Initialize additional report data
// $totalOrdersToday = 0;
// $totalOrdersThisWeek = 0;
// $totalOrdersThisMonth = 0;

 try {

//   // Get total orders and total revenue
//   $query = "
//   SELECT COUNT(DISTINCT o.OrderID) as order_count, SUM(p.amount) as totalRevenue
//   FROM orders o
//   JOIN orderfood of ON o.OrderID = of.OrderID
//   JOIN food f ON of.foodID = f.foodID
//   JOIN payment p ON o.OrderID = p.OrderID
//   WHERE f.catererID = :catererID
// ";
// $stmt = $conn->prepare($query);
// $stmt->bindParam(':catererID', $catererID, PDO::PARAM_INT);
// $stmt->execute();
// $result = $stmt->fetch(PDO::FETCH_ASSOC);
// $order_count = $result['order_count'];
// $totalRevenue = $result['totalRevenue'];

//     // Get total orders received today
//     $queryToday = "
//         SELECT COUNT(DISTINCT o.OrderID) as totalOrdersToday
//         FROM orders o
//         JOIN orderfood of ON o.OrderID = of.OrderID
//         JOIN food f ON of.foodID = f.foodID
//         WHERE f.catererID = :catererID AND DATE(o.orderDate) = CURDATE()
//     ";
//     $stmt = $conn->prepare($queryToday);
//     $stmt->bindParam(':catererID', $catererID, PDO::PARAM_INT);
//     $stmt->execute();
//     $resultToday = $stmt->fetch(PDO::FETCH_ASSOC);
//     $totalOrdersToday = $resultToday['totalOrdersToday'];

//     // Get total orders received this week
//     $queryWeek = "
//         SELECT COUNT(DISTINCT o.OrderID) as totalOrdersThisWeek
//         FROM orders o
//         JOIN orderfood of ON o.OrderID = of.OrderID
//         JOIN food f ON of.foodID = f.foodID
//         WHERE f.catererID = :catererID AND YEARWEEK(o.orderDate, 1) = 
//         YEARWEEK(CURDATE(), 1)
//     ";
//     $stmt = $conn->prepare($queryWeek);
//     $stmt->bindParam(':catererID', $catererID, PDO::PARAM_INT);
//     $stmt->execute();
//     $resultWeek = $stmt->fetch(PDO::FETCH_ASSOC);
//     $totalOrdersThisWeek = $resultWeek['totalOrdersThisWeek'];

//     // Get total orders received this month
//     $queryMonth = "
//         SELECT COUNT(DISTINCT o.OrderID) as totalOrdersThisMonth
//         FROM orders o
//         JOIN orderfood of ON o.OrderID = of.OrderID
//         JOIN food f ON of.foodID = f.foodID
//         WHERE f.catererID = :catererID AND MONTH(o.orderDate) = MONTH(CURDATE()) 
//         AND YEAR(o.orderDate) = YEAR(CURDATE())
//     ";
//     $stmt = $conn->prepare($queryMonth);
//     $stmt->bindParam(':catererID', $catererID, PDO::PARAM_INT);
//     $stmt->execute();
//     $resultMonth = $stmt->fetch(PDO::FETCH_ASSOC);
//     $totalOrdersThisMonth = $resultMonth['totalOrdersThisMonth'];

    // Get feedback summary
    $query = "
        SELECT f.comment, f.feedbackDate, o.OrderID
        FROM feedback f
        JOIN orders o ON f.OrderID = o.OrderID
        JOIN orderfood of ON o.OrderID = of.OrderID
        JOIN food fd ON of.foodID = fd.foodID
        WHERE fd.catererID = :catererID
        ORDER BY f.feedbackDate DESC
    ";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':catererID', $catererID, PDO::PARAM_INT);
    $stmt->execute();
    $feedbackSummary = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Report - PartyPal Catering System</title>
    <link href="assets/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/dashboard.css" rel="stylesheet">
</head>
<body>
    <?php include_once("header.php"); ?>
    
    <div class="container-fluid">
        <div class="row">
            <?php include_once("sidenav.php"); ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <h1 class="my-4">Catering Report</h1>
            
            <!-- Summary Report
            <div class="card mb-4">
               <div class="card mb-4">
    <div class="card-header">
        <h2>Summary</h2>
    </div>
    <div class="card-body">
    <p><strong>Total Orders:</strong> 
    <?php //echo $order_count; ?></p>
    <p><strong>Total Revenue:</strong> Tsh
    <?php// echo number_format($totalRevenue, 2); ?></p>
    <p><strong>Total Orders Today:</strong> 
    <?php //echo $totalOrdersToday; ?></p>
    <p><strong>Total Orders This Week:</strong>
     <?php //echo $totalOrdersThisWeek; ?></p>
    <p><strong>Total Orders This Month:</strong> 
    <?php //echo $totalOrdersThisMonth; ?></p>
</div>

</div> -->


            <!-- Feedback Report -->
            <div class="card mb-4">
                <div class="card-header">
                    <h2>Customer Feedback</h2>
                </div>
                <div class="card-body">
                    <?php if (!empty($feedbackSummary)): ?>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Comment</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($feedbackSummary as $feedback): ?>
                                    <tr>
                                        <td><?php echo $feedback['OrderID']; ?></td>
                                        <td><?php echo htmlspecialchars($feedback['comment']); ?></td>
                                        <td><?php echo date('d-m-Y', strtotime($feedback['feedbackDate'])); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>No feedback available.</p>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="assets/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
