<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <meta name='description' content=''>
    <meta name='author' content='Mark Otto, Jacob Thornton, and Bootstrap contributors'>
    <meta name='generator' content='Hugo 0.84.0'>
    <title>Edit caterer</title>
    <link rel="stylesheet" type="text/css" href="index.css">
</head>
<body>
    
    
                        <form method='POST' action='edit_caterer_handler.php'>
                            <input type='hidden' name='catererID' value="<?php echo isset($_GET['catererID']) ? $_GET['catererID'] : ''; ?>">
                           <label for='name'>Name</label><br>
                                <input type='text'  name='Name' value="<?php echo isset($row['Name']) ? $row['Name'] : ''; ?>">
                                
                             <label for='address'>Address</label><br>
                                <input type='text' name='address' value="<?php echo isset($row['address']) ? $row['address'] : ''; ?>" >
                               
                            <label for='password'>Password</label><br>
                                <input type='password'  name='password' value="<?php echo isset($row['password']) ? $row['password'] : ''; ?>" >
                                
                            <label for='phone'>Phone</label><br>
                                <input type='tel'  name='phone' value="<?php echo isset($row['phone']) ? $row['phone'] : ''; ?>" >
                                
                            <label for='email'>Email</label><BR>
                                <input type='email'  name='email' value="<?php echo isset($row['email']) ? $row['email'] : ''; ?>">
                                
                            
                            <input type="submit" value= "Save">
                        </form>

<!-- <script src='assets/dist/js/bootstrap.bundle.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js' integrity='sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE' crossorigin='anonymous'></script>
<script src='dashboard.js'></script> -->
</body>
</html>
