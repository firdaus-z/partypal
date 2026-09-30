<?php
session_start();

include_once("connection.php");

if (!isset($_SESSION["email"]) || $_SESSION["role"] !== 'customer') {
    header("location:../index.php");
    exit();
}

$email = $_SESSION['email'];

// Fetch customer name
$query = "SELECT firstName FROM customer WHERE email = :email";
$stmt = $conn->prepare($query);

$stmt->bindParam(':email', $email, PDO::PARAM_STR);
$stmt->execute();
$name = $stmt->fetchColumn();

$catererID = isset($_GET['catererID']) ? intval($_GET['catererID']) : 0;

// Function to fetch food items from the database for the selected caterer
function fetchFoodItemsByCaterer($conn, $catererID) {
    $stmt = $conn->prepare("SELECT * FROM food WHERE catererID = :catererID");
    $stmt->bindParam(':catererID', $catererID, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch food items for the selected caterer
$foodItems = fetchFoodItemsByCaterer($conn, $catererID);

// Debugging: Check the values of $catererID and $foodItems
error_log("CatererID: $catererID");
error_log("Food Items: " . print_r($foodItems, true));
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.84.0">
    <title>Partypal Catering System</title>

   
    <link href="assets/dist/css/bootstrap.min.css" rel="stylesheet">

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

        .view-order-btn {
            margin-bottom: 20px;
        }
        table thead tr{
  color: #fff;
  background: #4593f8;
  text-align: left;
  font-weight: bold;
}
    </style>
    <script>
function addToOrder(event, foodID) {
    event.preventDefault(); 
    
    var form = document.getElementById('addToOrderForm_' + foodID);
    var formData = new FormData(form);

    fetch('add_to_order.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert(data.message);
        } else {
            alert('Failed to add item to order: ' + data.message);
        }
    })
    .catch(error => {
        alert('Error: ' + error.message);
    });
}
</script>

</head>
<body>

<?php include_once('header.php'); ?>

<div class="container-fluid">
    <div class="row">
        <?php include_once('sidenav.php'); ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                    </div>
                </div>
            </div>

            <?php if (isset($_SESSION['message'])): ?>
                <p class="text-center text-success"><strong><?php echo $_SESSION['message']; ?></strong></p>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>

  
            <div class="view-order-btn">
                <a href="view_order.php" class="btn btn-primary">order here</a>
            </div>

   
            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th scope="col">FoodID</th>
                            <th scope="col">FoodName</th>
                            <th scope="col">Price</th>
                            <th scope="col">Description</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
    <?php if (!empty($foodItems)): ?>
        <?php foreach ($foodItems as $index => $food): ?>
            <tr>
                <td><?php echo htmlspecialchars($food['FoodID']); ?></td>
                <td><?php echo htmlspecialchars($food['FoodName']); ?></td>
                <td>Tsh<?php echo number_format($food['price'], 2); ?></td>
                <td><?php echo htmlspecialchars($food['Description']); ?></td>
                <td>
                    <form id="addToOrderForm_<?php echo $food['FoodID']; ?>" onsubmit="addToOrder(event, <?php echo $food['FoodID']; ?>)">
                        <input type="number" name="quantity" value="1" min="1" class="form-control d-inline-block w-auto">
                </td>
                <td>
                        <input type="hidden" name="FoodID" value="<?php echo $food['FoodID']; ?>">
                        <input type="hidden" name="FoodName" value="<?php echo $food['FoodName']; ?>">
                        <input type="hidden" name="price" value="<?php echo $food['price']; ?>">
                        <input type="hidden" name="Description" value="<?php echo $food['Description']; ?>">
                        <button type="submit" class="btn btn-primary btn-sm">Add to Order</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="6" class="text-center">No food items found for this caterer.</td>
        </tr>
    <?php endif; ?>
</tbody>

                </table>
            </div>
        </main>
    </div>
</div>
<script src="assets/dist/js/bootstrap.bundle.min.js"></script>

<script src="dashboard.js"></script>
</body>
</html>
