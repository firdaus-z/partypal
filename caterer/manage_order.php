<!doctype html>
<html lang='en'>
<head>
<meta charset='utf-8'>
<meta name='viewport' content='width=device-width, initial-scale=1'>
<meta name='description' content=''>
<meta name='author' content='Mark Otto, Jacob Thornton, and Bootstrap contributors'>
<meta name='generator' content='Hugo 0.84.0'>
<title>Order Dashboard · Bootstrap v5.0</title>

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
<h1 class='h2'>Order Dashboard</h1>
<div class='btn-toolbar mb-2 mb-md-0'>
<div class='btn-group me-2'>
</div>
</div>
</div>

<?php
if (isset($_SESSION['message'])) {
    echo "<p class='text-center text-success'><strong>{$_SESSION['message']}</strong></p>";
    unset($_SESSION['message']);
}
?>

<div class='table-responsive'>
<table class='table table-striped table-sm'>
<thead>
<tr>
<th scope='col'>Order ID</th>
<th scope='col'>Customer Name</th>
<th scope='col'>Food Item</th>
<th scope='col'>Quantity</th>
<th scope='col'>Order Date</th>
<th scope='col'>Actions</th>
</tr>
</thead>
<tbody>
<?php
require_once('connection.php');

$query = 'SELECT orders.orderID, customer.firstName, customer.lastName, food.name, orders.quantity, orders.orderDate 
          FROM orders 
          JOIN customer ON orders.customerID = customer.CustomerID 
          JOIN food ON orders.foodID = food.foodID';
$statement = $conn->prepare($query);
$statement->execute();

while ($row = $statement->fetch()) {
    echo '<tr>';
    echo '<td>' . $row['orderID'] . '</td>';
    echo '<td>' . $row['firstName'] . ' ' . $row['lastName'] . '</td>';
    echo '<td>' . $row['name'] . '</td>';
    echo '<td>' . $row['quantity'] . '</td>';
    echo '<td>' . $row['orderDate'] . '</td>';
    echo "<td>
            <a class='btn btn-sm btn-outline-primary' href='view_order.php?orderID={$row['orderID']}'>View</a>
          </td>";
    echo '</tr>';
}
?>
</tbody>
</table>
</div>
</main>
</div>
</div>

<script src='assets/dist/js/bootstrap.bundle.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js' integrity='sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE' crossorigin='anonymous'></script>
<script src='https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js' integrity='sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha' crossorigin='anonymous'></script>
<script src='dashboard.js'></script>
</body>
</html>
