<?php
session_start();


include_once("connection.php");
if(!isset($_SESSION["email"]) && $_SESSION["role"] = 'caterers'){
    header("location:../index.php");
}
$email = $_SESSION['email'];

$query = "SELECT Name FROM caterers WHERE email = :email";
$stmt = $conn->prepare($query);
$stmt->bindParam(':email', $email, PDO::PARAM_STR);
$stmt->execute();
$name = $stmt->fetchColumn();


try {
    $catererQuery = $conn->prepare('SELECT catererID FROM caterers WHERE email = :email');
    $catererQuery->bindParam(':email', $email, PDO::PARAM_STR);
    $catererQuery->execute();
    
    $caterer = $catererQuery->fetch(PDO::FETCH_ASSOC);
    if (!$caterer) {
        echo 'Error: Caterer not found.';
        exit;
    }

    $catererID = $caterer['catererID'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $FoodName = $_POST['FoodName'];
        $price = $_POST['price'];
        $Description = $_POST['Description'];
        if (empty($FoodName) || empty($price) || empty($Description)) {
            echo 'Error: All fields are required.';
            exit;
        }

     
        $statement = $conn->prepare("INSERT INTO food (FoodName, price, Description, catererID) VALUES (:FoodName, :price, :Description, :catererID)");
        $statement->execute(array(
            ':FoodName' => $FoodName,
            ':price' => $price, 
            ':Description' => $Description,
            ':catererID' => $catererID
        ));

        header('Location: food.php');
        exit;
    } else {
        header("locatio add_foo.php");
    }
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
?>

