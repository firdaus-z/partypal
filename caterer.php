<?php
session_start();


include_once("connection.php");
if(!isset($_SESSION["email"]) && $_SESSION["role"] = 'admin'){
    header("location:./index.php");
}

?>
<!doctype html>
<html lang='en'>
<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <meta name='description' content=''>
    <meta name='author' content='Mark Otto, Jacob Thornton, and Bootstrap contributors'>
    <meta name='generator' content='Hugo 0.84.0'>
    <title>PartyPal_catering_system</title>

    <link rel='canonical' href='https://getbootstrap.com/docs/5.0/examples/dashboard/'>

    <!-- Bootstrap core CSS -->
    <link href='assets/dist/css/bootstrap.min.css' rel='stylesheet'>

    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
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
                <h1 class='h2'>Manage Caterers</h1>
                <div class='btn-toolbar mb-2 mb-md-0'>
                    <div class='btn-group me-2'>
                        <a href='add-caterer.php' class='btn btn-sm btn-outline-primary'>Add Caterer</a>
                    </div>
                </div>
            </div>
            <?php
if (isset($_SESSION['message'])) {
    echo "<p class='text-center text-success'><strong>{$_SESSION['message']}</strong></p>";
    unset($_SESSION['message']);
}
?>
<div class='table-responsive'>
<table class='table table-striped table-sm'>
<thead>
<tr>
<th scope='col'>CatererID</th>
<th scope='col'>Name</th>
<th scope='col'>Address</th>
<th scope='col'>Phone</th>
<th scope='col'>Email</th>
<th scope='col'>Action</th>
</tr>
</thead>
<tbody>
<?php
require_once('connection.php');
$statement = $conn->prepare('SELECT catererID, Name, address, phone, email FROM caterers');
$statement->execute();
$n = 1;
while ($row = $statement->fetch()) {
    echo '<tr>';
    echo '<td>'.$n.'</td>';
    echo '<td>'.$row['Name'].'</td>';
    echo '<td>'.$row['address'].'</td>';
    echo '<td>'.$row['phone'].'</td>';
    echo '<td>'.$row['email'].'</td>';
    echo "<td>
    <a class='btn btn-sm btn-outline-primary' href='edit_caterer.php?catererID="
    .$row[ 'catererID' ]."'>Edit</a>
    <a class='btn btn-sm btn-outline-danger' href='delete_caterer.php?catererID=".$row['catererID']."' onclick=\"return confirm('Are you sure you want to delete this caterer?')
    ;\">Delete</a></td>";
    echo '</tr>';
    echo '</tr>';
    $n++;
}
?>
</tbody>
</table>
</div>
</main>
</div>
</div>

<script src='assets/dist/js/bootstrap.bundle.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js' integrity='sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE' crossorigin='anonymous'></script>
<script src='https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js' integrity='sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha' crossorigin='anonymous'></script>
<script src='dashboard.js'></script>
</body>
</html>
