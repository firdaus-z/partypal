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
?>
<!doctype html>
<html lang='en'>
<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <meta name='description' content=''>
    <meta name='author' content='Mark Otto, Jacob Thornton, and Bootstrap contributors'>
    <meta name='generator' content='Hugo 0.84.0'>
    <title>Customer Orders</title>

    <link rel='canonical' href='https://getbootstrap.com/docs/5.0/examples/dashboard/'>

    <!-- Bootstrap core CSS -->
    <link href='assets/dist/css/bootstrap.min.css' rel='stylesheet'>

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

    <!-- Custom styles for this template -->
    <link href='assets/dashboard.css' rel='stylesheet'>
</head>
<body>

<?php include_once('header.php'); ?>

<div class='container-fluid'>
    <div class='row'>
        <?php include_once('sidenav.php'); ?>

        <main class='col-md-9 ms-sm-auto col-lg-10 px-md-4'>
            <div class='d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom'>
                <h1 class='h2'>Customer Orders</h1>
            </div>

            <div class='table-responsive'>
                <table class='table table-striped table-sm'>
                    <thead>
                    <tr>
                        <th scope='col'>CustomerID</th>
                        <th scope='col'>First Name</th>
                        <th scope='col'>Last Name</th>
                        <th scope='col'>Phone</th>
                        <th scope='col'>Email</th>
                        <th scope='col'>Address</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    // Fetch customers who have placed orders with this caterer
                    $query = "
                        SELECT DISTINCT c.customerID, c.firstName, c.lastName, c.phone, c.email, c.address
                        FROM customer c
                        JOIN orders o ON c.customerID = o.CustomerID
                        JOIN orderfood of ON o.OrderID = of.OrderID
                        JOIN food f ON of.FoodID = f.FoodID
                        WHERE f.catererID = :catererID
                    ";
                    $stmt = $conn->prepare($query);
                    $stmt->bindParam(':catererID', $catererID, PDO::PARAM_INT);
                    $stmt->execute();
                    $n = 1;
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo '<tr>';
                        echo '<td>'.$n.'</td>';
                        echo '<td>'.htmlspecialchars($row['firstName']).'</td>';
                        echo '<td>'.htmlspecialchars($row['lastName']).'</td>';
                        echo '<td>'.htmlspecialchars($row['phone']).'</td>';
                        echo '<td>'.htmlspecialchars($row['email']).'</td>';
                        echo '<td>'.htmlspecialchars($row['address']).'</td>';
                        echo '</tr>';
                        $n++;
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>

<script src='assets/dist/js/bootstrap.bundle.min.js'></script>
</body>
</html>
