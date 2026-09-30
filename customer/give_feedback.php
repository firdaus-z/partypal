<?php
session_start();
include_once("connection.php");

// Check if the customer is logged in
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'customer') {
    header("location:../index.php");
    exit();
}

$email = $_SESSION['email'];

// Fetch the customer's ID based on the session email
$query = "SELECT customerID FROM customer WHERE email = :email";
$stmt = $conn->prepare($query);
$stmt->bindParam(':email', $email, PDO::PARAM_STR);
$stmt->execute();
$customerID = $stmt->fetchColumn();

if (!$customerID) {
    echo "Customer not found.";
    exit();
}

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $OrderID = $_POST['OrderID'];
    $comment = $_POST['comment'];
    $customerName = $_POST['customerName']; // Retrieve customerName from POST data
    $feedbackDate = date('Y-m-d H:i:s'); 

    // Insert data
    $query = "INSERT INTO feedback (OrderID, customerName, comment, feedbackDate) VALUES (:OrderID, :customerName, :comment, :feedbackDate)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':OrderID', $OrderID, PDO::PARAM_INT);
    $stmt->bindParam(':customerName', $customerName, PDO::PARAM_STR); // Bind customerName
    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
    $stmt->bindParam(':feedbackDate', $feedbackDate, PDO::PARAM_STR);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Feedback submitted successfully!";
    } else {
        $_SESSION['message'] = "Failed to submit feedback.";
    }

    header("Location: give_feedback.php");
    exit();
}

// Fetch the orders placed by this customer
$query = "SELECT OrderID FROM orders WHERE CustomerID = :customerID";
$stmt = $conn->prepare($query);
$stmt->bindParam(':customerID', $customerID, PDO::PARAM_INT);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html lang='en'>
<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <meta name='description' content=''>
    <meta name='author' content='Mark Otto, Jacob Thornton, and Bootstrap contributors'>
    <meta name='generator' content='Hugo 0.84.0'>
    <title>PartyPal Catering System</title>

    <link rel='canonical' href='https://getbootstrap.com/docs/5.0/examples/dashboard/'>
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
        form {
            width: 100%;
            margin: 0 auto;
            background-color: rgb(238, 234, 247);
            padding: 20px;
            border: 1px solid #ccc;
            box-shadow: 0px 0px 10px #aaa;
            border-radius: 5px; 
        }
        input[type="submit"] ,
        input[type="button"] {
            background-color: rgb(15, 15, 168, 0.6);
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 30px;
            width: 10%;
            cursor: pointer;
        }
        textarea {
            width: 100%;
        }
        input[type="text"],
        input[type="tel"],
        select,
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
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
                <div class='btn-toolbar mb-2 mb-md-0'>
                </div>
            </div>
            <div class="container">
                <h1 class="my-4">Give Feedback</h1>

                <?php
                if (isset($_SESSION['message'])) {
                    echo "<p class='text-success'><strong>" . $_SESSION['message'] . "</strong></p>";
                    unset($_SESSION['message']);
                }
                ?>

                <form method="post" action="give_feedback.php">
                    <label for="OrderID">Select Order</label>
                    <select name="OrderID" required>
                        <option value="">Select your order</option>
                        <?php foreach ($orders as $order): ?>
                            <option value="<?php echo $order['OrderID']; ?>"><?php echo $order['OrderID']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <label for="customerName">Customer Name</label><br>
                    <input type="text" name="customerName" required><br>
                    <label for="comment">Your Feedback</label><br>
                    <textarea name="comment" rows="5" required></textarea><br>
                    <input type="submit" value="Submit">
                </form>
            </div>
            <div class='table-responsive'>
                <table class='table table-striped table-sm'>
                    <thead>
                        <tr>
                            <th scope='col'>FeedbackID</th>
                            <th scope='col'>OrderID</th>
                            <th scope='col'>Customer Name</th>
                            <th scope='col'>Feedback Description</th>
                            <th scope='col'>Feedback Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        require_once('connection.php');
                        $statement = $conn->prepare('SELECT * FROM feedback');
                        $statement->execute();
                        $n = 1;
                        while ($row = $statement->fetch()) {
                            echo '<tr>';
                            echo '<td>'.$n.'</td>';
                            echo '<td>'.$row['OrderID'].'</td>';
                            echo '<td>'.$row['customerName'].'</td>';
                            echo '<td>'.$row['comment'].'</td>';
                            echo '<td>'.$row['feedbackDate'].'</td>';
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
<script src='https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js' integrity='sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE' crossorigin='anonymous'></script>
<script src='https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js' integrity='sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha' crossorigin='anonymous'></script>
<script src='dashboard.js'></script>
</body>
</html>
