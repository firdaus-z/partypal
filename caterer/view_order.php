<?php
session_start();

include_once("connection.php");

// Check if the caterer is logged in
if (!isset($_SESSION["email"]) || $_SESSION["role"] !== 'caterers') {
    header("location:../index.php");
    exit();
}

$email = $_SESSION['email'];

// Fetch catererID based on the email
$query = "SELECT catererID, Name FROM caterers WHERE email = :email";
$stmt = $conn->prepare($query);
$stmt->bindParam(':email', $email, PDO::PARAM_STR);
$stmt->execute();
$caterer = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$caterer) {
    echo "Caterer not found.";
    exit();
}

$catererID = $caterer['catererID'];
$name = $caterer['Name'];

?>
<!doctype html>
<html lang='en'>
<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>Partypal Catering System</title>
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
                <div class='btn-toolbar mb-2 mb-md-0'></div>
            </div>

            <div class='table-responsive'>
                <h3>Customer Orders</h3>
                <table class='table table-striped table-sm'>
                    <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer Name</th>
                        <th>Order Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
<?php
if ($catererID) {
    try {
        // Fetch orders related to the caterer's food items
        $query = "SELECT o.OrderID, o.OrderDate, c.firstName, c.lastName, o.status
                  FROM orders o
                  JOIN customer c ON o.CustomerID = c.customerID
                  JOIN orderfood of ON o.OrderID = of.OrderID
                  JOIN food f ON of.FoodID = f.FoodID
                  WHERE f.catererID = :catererID
                  GROUP BY o.OrderID, o.OrderDate, c.firstName, c.lastName, o.status
                  ORDER BY o.OrderDate DESC";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':catererID', $catererID, PDO::PARAM_INT);
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($orders) {
            foreach ($orders as &$order) {
                // Set status to "Pending" if not "Received"
                if (empty($order['status']) || $order['status'] !== 'Received') {
                    $order['status'] = 'Pending';
                }
                echo '<tr>';
                echo '<td>' . htmlspecialchars($order['OrderID']) . '</td>';
                echo '<td>' . htmlspecialchars($order['firstName'] . ' ' . $order['lastName']) . '</td>';
                echo '<td>' . htmlspecialchars($order['OrderDate']) . '</td>';
                echo '<td>' . htmlspecialchars($order['status']) . '</td>';
                echo "<td>
                        <form method='post' action='update_order_status.php' style='display:inline;'>
                            <input type='hidden' name='OrderID' value='{$order['OrderID']}'>
                            <button type='submit' class='btn btn-sm btn-outline-primary'>Received</button>
                        </form>
                        <form method='get' action='view_order_details.php' style='display:inline;'>
                            <input type='hidden' name='OrderID' value='{$order['OrderID']}'>
                            <button type='submit' class='btn btn-sm btn-outline-secondary'>View Order</button>
                        </form>
                      </td>";
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="5" class="text-center">No orders found.</td></tr>';
        }
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
} else {
    echo 'Error: Caterer ID not found.';
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
