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
if (isset($_GET['FoodID'])) {
    $FoodID = $_GET['FoodID'];
    
    try {
        // Start a transaction
        $conn->beginTransaction();
        
        // Delete from caterers table
        $statement = $conn->prepare("DELETE FROM food WHERE FoodID=:FoodID");
        $statement->execute(array(':FoodID' => $FoodID));
        
        // Commit the transaction
        $conn->commit();
        
        $_SESSION['message'] = 'food has been deleted successfully.';
    } catch (Exception $e) {
        // Rollback the transaction if something failed
        $conn->rollBack();
        $_SESSION['message'] = 'Failed to delete food: ' . $e->getMessage();
    }
    
    header('Location: food.php');
} else {
    header('Location: ../logout.php');
}
?>
