<?php
session_start();


include_once("connection.php");
if(!isset($_SESSION["email"]) && $_SESSION["role"] = 'admin'){
    header("location:./index.php");
}

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.84.0">
    <title>PartyPal_catering_system</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/dashboard/">

    <!-- Bootstrap core CSS -->
    <link href="assets/dist/css/bootstrap.min.css" rel="stylesheet">

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
    <link href="assets/dashboard.css" rel="stylesheet">
  </head>
  <body>
    <?php include_once("header.php"); ?>
    <?php include_once("connection.php"); ?>

    <?php
    // Fetch the number of registered customers
    $customer_sql = "SELECT COUNT(*) AS customer_count FROM customer"; 
    $customer_stmt = $conn->query($customer_sql);
    $customer_row = $customer_stmt->fetch(PDO::FETCH_ASSOC);
    $customer_count = $customer_row['customer_count'];

    // Fetch the number of registered caterers
    $caterer_sql = "SELECT COUNT(*) AS caterer_count FROM caterers";
    $caterer_stmt = $conn->query($caterer_sql);
    $caterer_row = $caterer_stmt->fetch(PDO::FETCH_ASSOC);
    $caterer_count = $caterer_row['caterer_count'];
    ?>

    <div class="container-fluid">
      <div class="row">
        <?php include_once("sidenav.php"); ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Dashboard</h1>
          </div>

          <!-- Cards section -->
          <div class="row">
            <div class="col-md-6 col-lg-3 mb-4">
              <div class="card text-white bg-primary">
                <div class="card-body">
                  <div class="card-title">Registered Customers</div>
                  <h2 class="card-text"><?php echo $customer_count; ?></h2>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
              <div class="card text-white bg-success">
                <div class="card-body">
                  <div class="card-title">Registered Caterers</div>
                  <h2 class="card-text"><?php echo $caterer_count; ?></h2>
                </div>
              </div>
            </div>
          </div>

          <div class="table-responsive">
            <!-- Table content -->
          </div>
        </main>
      </div>
    </div>

    <script src="assets/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script>
    <script src="dashboard.js"></script>
  </body>
</html>
