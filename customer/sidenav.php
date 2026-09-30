<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"/>
  <title>Side nav</title>
  <style>
    a:hover i{
  color: #3440af;
  transition: 0.5s;
}
  </style>
</head>
<body>
  
</body>
</html>


<div class="container-fluid">
  <div class="row">
    <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
      <div class="position-sticky pt-3">
        <ul class="nav flex-column">
          <li class="nav-item">
            <a class="nav-link" href="customer_dashboard.php">
              <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="customer_order.php">
              <i class="fas fa-shopping-basket"></i> View Order
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="give_feedback.php">
              <i class="fas fa-comment-alt"></i> View Feedback
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../logout.php">
              <i class="fas fa-sign-out-alt"></i> Logout
            </a>
          </li>
        </ul>
      </div>
    </nav>
  </div>
</div>
