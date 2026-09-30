<?php
session_start(); // Start session if not already started


include_once("connection.php");
if(!isset($_SESSION["email"]) && $_SESSION["role"] = 'customer'){
    header("location:../index.php");
}
$email = $_SESSION['email'];

$query = "SELECT firstName FROM customer WHERE email = :email";
$stmt = $conn->prepare($query);
$stmt->bindParam(':email', $email, PDO::PARAM_STR);
$stmt->execute();
$name = $stmt->fetchColumn();


$statement = $conn->prepare('SELECT * FROM caterers');
$statement->execute();
$row = $statement->fetch();

// Function to fetch caterers from the database
function fetchCaterers($conn) {
    $stmt = $conn->prepare("SELECT * FROM caterers");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$caterers = fetchCaterers($conn);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.84.0">
    <title>PartyPal Catering System</title>
    <link href="assets/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="assets/dashboard.css" rel="stylesheet">

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
</head>
<body>
    <?php include_once("header.php"); ?>

    <div class="container-fluid">
        <div class="row">
            <?php include_once("sidenav.php"); ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Dashboard</h1>
                </div>

                <!-- caterers -->
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Address</th>
                                <th scope="col">Phone</th>
                                <th scope="col">Email</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($caterers as $index => $caterer): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo htmlspecialchars($caterer['Name']); ?></td>
                                <td><?php echo htmlspecialchars($caterer['address']); ?></td>
                                <td><?php echo htmlspecialchars($caterer['phone']); ?></td>
                                <td><?php echo htmlspecialchars($caterer['email']); ?></td>
                                <td><a href="food.php?catererID=<?php echo $caterer['catererID']; ?>" class="btn btn-primary btn-sm">Make Order</a></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap core JavaScript -->
    <script src="assets/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Your custom script -->
    <script src="dashboard.js"></script>
</body>
</html>
