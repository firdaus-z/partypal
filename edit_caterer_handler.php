<?php
session_start();
require_once('connection.php');

if (isset($_POST['catererID'])) {
    $catererID = $_POST['catererID'];
    $Name = $_POST['Name'];
    $address = $_POST['address'];
    $password = sha1($_POST['password']);
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    try {
        $conn->beginTransaction();

        $checkEmailQuery = $conn->prepare("SELECT COUNT(*) FROM `caterers` WHERE `email` = :email AND `catererID` != :catererID");
        $checkEmailQuery->execute(array(':email' => $email, ':catererID' => $catererID));
        $emailCount = $checkEmailQuery->fetchColumn();

        if ($emailCount > 0) {
     
            $_SESSION['message'] = 'Email already exists.';
            $conn->rollBack();
            header('location: edit_caterer.php');
            exit;
        }

        $checkPhoneQuery = $conn->prepare("SELECT COUNT(*) FROM `caterers` WHERE `phone` = :phone AND `catererID` != :catererID");
        $checkPhoneQuery->execute(array(':phone' => $phone, ':catererID' => $catererID));
        $phoneCount = $checkPhoneQuery->fetchColumn();

        if ($phoneCount > 0) {
    
            $_SESSION['message'] = 'Phone number already exists.';
            $conn->rollBack();
            header('location: edit_caterer.php');
            exit;
        }

    
        $statement = $conn->prepare('UPDATE `caterers` SET `Name`=:Name, 
        `address`=:address, `password`=:password, `phone`=:phone, `email`=:email 
        WHERE `catererID`=:catererID');
        $statement->execute(array(
            ':Name' => $Name,
            ':address' => $address,
            ':password' => $password,
            ':phone' => $phone,
            ':email' => $email,
            ':catererID' => $catererID
        ));

   
        $conn->commit();

        $_SESSION['message'] = 'Caterer has been updated successfully.';
    } catch (Exception $e) {

        $conn->rollBack();
        $_SESSION['message'] = 'Failed to update caterer: ' . $e->getMessage();
    }

    header('location: caterer.php');
    exit;
} else {
    header('location: edit_caterer.php');
    exit;
}
?>
