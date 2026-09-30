<?php
session_start();

if (!isset($_SESSION["email"]) || $_SESSION["role"] !== 'customer') {
    header("location:../index.php");
    exit();
}

$totalAmount = isset($_SESSION['totalPrice']) ? $_SESSION['totalPrice'] : 0;
$orderID = isset($_SESSION['orderID']) ? $_SESSION['orderID'] : 0; // Ensure correct session variable name
?>

<!doctype html>
<html lang = 'en'>
<head>
<meta charset = 'utf-8'>
<meta name = 'viewport' content = 'width=device-width, initial-scale=1'>
<meta name = 'description' content = ''>
<meta name = 'author' content = 'Mark Otto, Jacob Thornton, and Bootstrap contributors'>
<meta name = 'generator' content = 'Hugo 0.84.0'>
<title>PartyPal_catering_system</title>

<link rel = 'canonical' href = 'https://getbootstrap.com/docs/5.0/examples/dashboard/'>

<!-- Bootstrap core CSS -->
<link href = 'assets/dist/css/bootstrap.min.css' rel = 'stylesheet'>

<style>
form
 {
    width: 100%;
    margin: 0 auto;
    background-color: rgb(238, 234, 247);
    padding: 20px;
    border: 1px solid #ccc;
    box-shadow: 0px 0px 10px #aaa;
    border-radius: 5px; 
    
}
input[type="submit"] ,
input[type="button"]{
    background-color: rgb(15, 15, 168,0.6);
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 30px;
    width: 90%;
    cursor: pointer;
}
textarea {
    width:100%;
    height: 100%;
    resize: vertical;
}

input[type="text"],
input[type="number"],
input[type="datetime-local"],
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
<link href = 'assets/dashboard.css' rel = 'stylesheet'>
</head>
<body>

<?php
include_once( 'header.php' );
?>

<div class = 'container-fluid'>
<div class = 'row'>
<?php
include_once( 'sidenav.php' );
?>
<main class = 'col-md-9 ms-sm-auto col-lg-10 px-md-4'>
<div class = 'd-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom'>
<div class = 'btn-toolbar mb-2 mb-md-0'>
<div class = 'btn-group me-2'>
</div>
</div>
</div>
    <h2 class="mt-5">Make Payment</h2>
    <form action="process_payment.php" method="POST">
            <label for="paymentDate">Payment Date</label><br>
           <br> <input type="datetime-local"  id="paymentDate" name="paymentDate" value="<?php echo date('Y-m-d\TH:i'); ?>" readonly>
            <br><br><label for="totalAmount" class="form-label">Amount</label>
            <input type="text" class="form-control" id="totalAmount" name="Amount" value="<?php echo number_format($totalAmount, 2); ?>" readonly>

            <br><label for="paymentMethod">Payment Method</label>
            <select  id="paymentMethod" name="paymentMethod" required>
                <option value="">Select Payment Method</option>
                <option value="Credit Card">Credit Card</option>
                <option value="Debit Card">Debit Card</option>
                <option value="PayPal">PayPal</option>
                <option value="Bank Transfer">Bank Transfer</option>
            </select>
        <input type="hidden" name="OrderID" value="<?php echo $orderID; ?>">
        <button type="submit" class="btn btn-primary">Pay Now</button>
    </form>
</div>
</body>
</html>
