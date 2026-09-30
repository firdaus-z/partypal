<?php
session_start();


include_once("connection.php");
if(!isset($_SESSION["email"]) && $_SESSION["role"] = 'caterers'){
    header("location:./index.php");
}
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
<?php
if ( isset( $_SESSION[ 'message' ] ) ) {
    ?>
    <p class = 'text-center text-success'><strong><?php echo $_SESSION[ 'message' ];
    ?></strong></p>
    <?php
    unset( $_SESSION[ 'message' ] );
}
?>

<form method="POST" action="add_food_handler.php">

<h2>Add Food</h2>
    <label for="FoodName">Food Name:</label>
    <input type="text" name="FoodName" maxlength="50" minlength="3" required 
        pattern="[A-Za-z\s]+" title="First Food Name should only contain letters and spaces.><br><br>
    
    <label for="price">Price:</label>
    <input type="number" name="price" required><br><br>
    
    <label for="Description">Description:</label>
    <textarea name="Description" required></textarea><br><br>
    

    
    <input type="submit" value="Add Food">
</form>

</div>
</body>
</html>
