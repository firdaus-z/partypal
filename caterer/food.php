<?php
session_start();

include_once("connection.php");

// Correct the session check condition
if (!isset($_SESSION["email"]) || $_SESSION["role"] !== 'caterers') {
    header("location:../index.php");
    exit();
}

$email = $_SESSION['email'];

// Fetch catererID based on the email
$query = "SELECT catererID, Name FROM caterers WHERE email = :email";
$stmt = $conn->prepare($query);
$stmt->bindParam(':email', $email, PDO::PARAM_STR);
$stmt->execute();
$caterer = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$caterer) {
    echo "Caterer not found.";
    exit();
}

$catererID = $caterer['catererID'];
$name = $caterer['Name'];

?>
<!doctype html>
<html lang='en'>
<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <meta name='description' content=''>
    <meta name='author' content='Mark Otto, Jacob Thornton, and Bootstrap contributors'>
    <meta name='generator' content='Hugo 0.84.0'>
    <title>Partypal catering system</title>

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

    <!-- Custom styles for this template -->
    <link href='assets/dashboard.css' rel='stylesheet'>
</head>
<body>

<?php include_once('header.php'); ?>

<div class='container-fluid'>
    <div class='row'>
        <?php include_once('sidenav.php'); ?>
        <main class='col-md-9 ms-sm-auto col-lg-10 px-md-4'>
            <div class='d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom'>
                <h1 class='h2'>Food Dashboard</h1>
                <div class='btn-toolbar mb-2 mb-md-0'>
                    <div class='btn-group me-2'>
                        <a href='add_food.php' class='btn btn-sm btn-outline-primary'>Add Food</a>
                    </div>
                </div>
            </div>

            <div class='table-responsive'>
                <table class='table table-striped table-sm'>
                    <thead>
                    <tr>
                        <th scope='col'>FoodID</th>
                        <th scope='col'>Name</th>
                        <th scope='col'>Price</th>
                        <th scope='col'>Description</th>
                        <th scope='col'>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    require_once('connection.php');

                    if ($catererID) {
                        try {
                            $statement = $conn->prepare('SELECT * FROM food WHERE catererID = :catererID');
                            $statement->bindParam(':catererID', $catererID, PDO::PARAM_INT);
                            $statement->execute();
                            $n = 1;
                            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                                echo '<tr>';
                                echo '<td>' . $n . '</td>';
                                echo '<td>' . htmlspecialchars($row['FoodName']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['price']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['Description']) . '</td>';
                                echo "<td>
                                        <a class='btn btn-sm btn-outline-primary' href='edit_food.php?FoodID={$row['FoodID']}'>Edit</a>
                                        <a class='btn btn-sm btn-outline-danger' href='delete_food.php?FoodID={$row['FoodID']}' onclick=\"return confirmDelete(event)\">Delete</a>
                                      </td>";
                                echo '</tr>';
                                $n++;
                            }
                        } catch (PDOException $e) {
                            echo 'Error: ' . $e->getMessage();
                        }
                    } else {
                        echo 'Error: Caterer ID not found.';
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
<script>
    function confirmDelete(event) {
        return confirm('Are you sure you want to delete this food item?');
    }
</script>
</body>
</html>
