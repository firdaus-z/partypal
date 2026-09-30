<?php
include('connection.php');

if (isset($_POST['Name'])) {
    try {
        // Start a transaction
        $conn->beginTransaction();

        $Name = $_POST['Name'];
        $address = $_POST['address'];
        $password = md5($_POST['password']);
        $phone = $_POST['phone'];
        $email = $_POST['email']; 


        $checkEmailQuery = $conn->prepare("SELECT COUNT(*) FROM `caterers` WHERE `email` = :email");
        $checkEmailQuery->execute(array(':email' => $email));
        $emailCount = $checkEmailQuery->fetchColumn();

        if ($emailCount > 0) {
   
            echo "<script>alert('Email already exists');
            window.location.href = 'add-caterer.php';</script>";
          
            exit();
        }

        $checkPhoneQuery = $conn->prepare("SELECT COUNT(*) FROM `caterers` WHERE `phone` = :phone");
        $checkPhoneQuery->execute(array(':phone' => $phone));
        $phoneCount = $checkPhoneQuery->fetchColumn();

        if ($phoneCount > 0) {
    
            echo "<script>alert('Phone number already exists'); 
            window.location.href = 'add-caterer.php';</script>";
            exit();
        }

        $statement = $conn->prepare("INSERT INTO `caterers` 
            (`Name`, `address`, `password`, `phone`, `email`) 
            VALUES (:Name, :address, :password, :phone, :email)");
        $statement->execute(array(':Name' => $Name, ':address' => $address,
            ':password' => $password, ':phone' => $phone, ':email' => $email));
        $catererID = $conn->lastInsertId();

        $conn->commit();

        header('Location: caterer.php');
    } catch (Exception $e) {
    
        $conn->rollBack();
        echo "Failed: " . $e->getMessage();
    }
} else {
 
    header('Location:add-caterer.php');
}
?>
