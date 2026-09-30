<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" type="text/css" href="index.css">
</head>
<body>
    <form name="customerForm" method="POST" action="customer_handler.php">
        <label for="firstName">First Name: </label>
        <input type="text" name="firstName" maxlength="50" minlength="3" required 
        pattern="[A-Za-z\s\-]+" title="First Name should only contain letters and spaces."><br><br>

        <label for="lastName">Last Name: </label>
        <input type="text" name="lastName" maxlength="50" minlength="3" required 
        pattern="[A-Za-z\s\-]+" title="Last Name should only contain letters and spaces."><br><br>     

        <label for="password">Password: </label>
        <input type="password" name="password" minlength="8" required><br><br>

        <label for="tel">Phone Number: </label>
        <input type="tel" pattern="[0-9]{10}" name="phone" required><br><br>

        <label for="email">Email: </label>
        <input type="email" name="email" required><br><br>

        <label for="address">Address: </label>
        <input type="text" name="address" minlength="5" maxlength="50" required><br><br>

        <input type="submit" value="Sign in">
    </form>
</body>
</html>
