<?php
session_start();
include_once("connection.php");

if (!isset($_SESSION["email"]) || $_SESSION["role"] != 'admin') {
    header("location:./index.php");
    exit;
}

// Fetch caterer details for editing
if (isset($_GET['catererID'])) {
    $catererID = $_GET['catererID'];
    $statement = $conn->prepare('SELECT * FROM `caterers` WHERE `catererID` = :catererID');
    $statement->execute(array(':catererID' => $catererID));
    $row = $statement->fetch(PDO::FETCH_ASSOC);
} else {
    header('location: caterer.php');
    exit;
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
<title>PartyPal Catering System</title>
<link rel='canonical' href='https://getbootstrap.com/docs/5.0/examples/dashboard/'>
<!-- Bootstrap core CSS -->
<link href='assets/dist/css/bootstrap.min.css' rel='stylesheet'>
<style>
form {
    width: 100%;
    margin: 0 auto;
    background-color: rgb(238, 234, 247);
    padding: 20px;
    border: 1px solid #ccc;
    box-shadow: 0px 0px 10px #aaa;
    border-radius: 5px; 
}
input[type="submit"],
input[type="button"] {
    background-color: rgb(15, 15, 168,0.6);
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 30px;
    width: 90%;
    cursor: pointer;
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
<div class='btn-toolbar mb-2 mb-md-0'>
<div class='btn-group me-2'></div>
</div>
</div>
<?php if (isset($_SESSION['message'])): ?>
    <p class='text-center text-success'><strong><?php echo $_SESSION['message']; ?></strong></p>
    <?php unset($_SESSION['message']); ?>
<?php endif; ?>

<form method='POST' action='edit_caterer_handler.php'>
    <input type='hidden' name='catererID' value="<?php echo htmlspecialchars($row['catererID']); ?>">
    <label for='name'>Name</label><br>
    <input type='text' name='Name'  maxlength="50" minlength="3" required 
    pattern="[A-Za-z\s\-]+" title="Name should only contain letters and spaces."value="<?php echo htmlspecialchars($row['Name']); ?>">
    
    <label for='address'>Address</label><br>
    <input type='text' name='address' value="<?php echo htmlspecialchars($row['address']); ?>">
   
    <label for='password'>Password</label><br>
    <input type='password' name='password' value="" minlength="8">
    
    <label for='phone'>Phone</label><br>
    <input type='tel' name='phone' value="<?php echo htmlspecialchars($row['phone']); ?>">
    
    <label for='email'>Email</label><br>
    <input type='email' name='email' value="<?php echo htmlspecialchars($row['email']); ?>">
    
    <input type="submit" value="Save">
</form>
</main>
</div>
</div>
<script src='assets/dist/js/bootstrap.bundle.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js' integrity='sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE' crossorigin='anonymous'></script>
<script src='https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js' integrity='sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha' crossorigin='anonymous'></script>
<script src='dashboard.js'></script>
</body>
</html>
