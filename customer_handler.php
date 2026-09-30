<?php
include('connection.php');

if(isset($_POST['firstName'])) {
    // Input data
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];
    $email = $_POST['email']; 
    $address = $_POST['address'];

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format'); 
        window.location.href = 'sign-up.php';</script>";
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM customer WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo "<script>alert('Email already exists');
         window.location.href = 'sign-up.php';</script>";
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM customer WHERE phone = :phone");
    $stmt->bindParam(':phone', $phone);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo "<script>alert('Phone number already exists'); 
        window.location.href = 'sign-up.php';</script>";
        exit();
    }
 
    $hashedPassword = sha1($password);

    try {
        $conn->beginTransaction();

        // Insert data into the database
        $statement = $conn->prepare("INSERT INTO `customer` 
            (`firstName`, `lastName`, `password`, `phone`, `email`, `address`) 
            VALUES (:firstName, :lastName, :password, :phone, :email, :address)");
        $statement->execute(array(
            ':firstName' => $firstName,
            ':lastName' => $lastName,
            ':password' => $hashedPassword,
            ':phone' => $phone,
            ':email' => $email,
            ':address' => $address
        ));

        $conn->commit();

        echo "<script>alert('Registration successful!');
         window.location.href = 'index.php';</script>";
        exit();
    } catch (Exception $e) {
        $conn->rollBack();
        echo "<script>alert('Failed: " . $e->getMessage() . "'); 
        window.location.href = 'sign-up.php';</script>";
    }
} else {
    echo "<script>window.location.href = 'sign-up.php';</script>";
    exit();
}
?>


