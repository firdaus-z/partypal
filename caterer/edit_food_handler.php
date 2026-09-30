<?php
session_start();
require_once('connection.php');

if (isset($_POST['FoodID'])) {
    // Retrieve form data
    $FoodID = $_POST['FoodID']; // Ensure consistency with the correct capitalization
    $FoodName = $_POST['FoodName'];
    $price = $_POST['price'];
    $Description = $_POST['Description'];

    try {
        // Prepare the SQL statement for updating the food item
        $statement = $conn->prepare('UPDATE `food` 
                                     SET `FoodName` = :FoodName, 
                                         `price` = :price, 
                                         `Description` = :Description 
                                     WHERE `FoodID` = :FoodID');

        // Execute the statement with the provided data
        $statement->execute(array(
            ':FoodName' => $FoodName,
            ':price' => $price,
            ':Description' => $Description,
            ':FoodID' => $FoodID
        ));

        // Set a success message in the session
        $_SESSION['message'] = 'Food item has been updated successfully.';
    } catch (Exception $e) {
        // Set an error message in case of failure
        $_SESSION['message'] = 'Failed to update food item: ' . $e->getMessage();
    }

    // Redirect back to the food list page
    header('location: food.php');
    exit;
} else {
    // Redirect to the edit food page if FoodID is not set
    header('location: edit_food.php');
    exit;
}
?>
