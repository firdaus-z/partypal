<?php
session_start();
include_once("connection.php");

if (!isset($_SESSION["email"]) || $_SESSION["role"] != 'caterers') {
    header("location:./index.php");
    exit;
}

// Fetch food details for editing
if (isset($_GET['FoodID'])) {
    $FoodID = $_GET['FoodID'];
    $statement = $conn->prepare('SELECT * FROM `food` WHERE `FoodID` = :FoodID');
    $statement->execute(array(':FoodID' => $FoodID));
    $row = $statement->fetch(PDO::FETCH_ASSOC);
} else {
    header('location: food.php');
    exit;
}
?>
<!doctype html>
<html lang='en'>
<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <meta name='description' content=''>
    <meta name='author' content='Your Name'>
    <title>PartyPal Catering System - Edit Food</title>
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
        input[type="number"],
        textarea {
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
                    <h1 class='h2'>Edit Food</h1>
                </div>
                
                <?php if (isset($_SESSION['message'])): ?>
                    <p class='text-center text-success'><strong><?php echo $_SESSION['message']; ?></strong></p>
                    <?php unset($_SESSION['message']); ?>
                <?php endif; ?>

                <form method='POST' action='edit_food_handler.php'>
                    <input type='hidden' name='FoodID' value="<?php echo htmlspecialchars($row['FoodID']); ?>">

                    <label for='foodName'>Food Name</label><br>
                    <input type='text' name='FoodName' maxlength="100" required 
                    pattern="[A-Za-z\s\-]+" title="Food name should only contain letters and spaces." 
                    value="<?php echo htmlspecialchars($row['FoodName']); ?>">
                    
                    <label for='price'>Price (Tsh)</label><br>
                    <input type='number' name='price' step='0.01' min='0' value="<?php echo htmlspecialchars($row['price']); ?>" required>
                   
                    <label for='description'>Description</label><br>
                    <textarea name='Description' rows='4' required><?php echo htmlspecialchars($row['Description']); ?></textarea>
                    
                    <input type="submit" value="Save">
                </form>
            </main>
        </div>
    </div>
    <script src='assets/dist/js/bootstrap.bundle.min.js'></script>
    <script src='dashboard.js'></script>
</body>
</html>
