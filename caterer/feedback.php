<?php
session_start();

// Check if session exists and role is 'caterer'
if (!isset($_SESSION["email"]) || $_SESSION["role"] !== 'caterers') {
    header("location:../index.php");
    exit();
}

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
try{
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
    <title> PartyPal Catering System</title>
    <link href="assets/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/dashboard.css" rel="stylesheet">
</head>
<body>
    <?php include_once("header.php"); ?>
    
    <div class="container-fluid">
        <div class="row">
            <?php include_once("sidenav.php"); ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <h1 class="my-4">customer feedback</h1>
            

               
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
        </main>
    </div>
</div>

<script src="assets/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

