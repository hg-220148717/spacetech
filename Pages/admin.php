<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
require_once("../PHP/database-handler.php");

$db_handler = new Database();
$setupStatus = $db_handler->checkSetup();

if (!$setupStatus) {
  die("Error setting up the database.");
}

if (!isset($_SESSION["user_id"]) || !$db_handler->isUserStaff($_SESSION["user_id"]))  {
  header("Location: ../Pages/index.php");
}

$name = $db_handler->getNameFromUserID($_SESSION["user_id"])


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="../Styles/master-style.css">
</head>

<body>
  <!-- Navigation -->
  <?php include("../PHP/navbar.php") ?>

  <!-- Main Content -->
  <div class="container mt-5">
    <h1 class="mb-4">Welcome, <?= htmlspecialchars($name) ?></h3>
    <div class="row g-5">
    <div class="col-lg-4 col-md-6">
        <div class="card h-100">
          <div class="card-body">
            <h5 class="card-title"><i class="fas fa-boxes fa-lg"></i> Stock Management</h5>
            <p class="card-text">Manage your inventory and stock levels.</p>
            <a href="stock_management.php" class="btn btn-primary">Manage Stock</a>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-6">
        <div class="card h-100">
          <div class="card-body">
            <h5 class="card-title"><i class="fas fa-shopping-cart fa-lg"></i> Orders</h5>
            <p class="card-text">View and manage customer orders.</p>
            <a href="orders.php" class="btn btn-secondary">View Orders</a>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-6">
        <div class="card h-100">
          <div class="card-body">
            <h5 class="card-title"><i class="fas fa-star fa-lg"></i> Reviews</h5>
            <p class="card-text">Moderate and review customer feedback.</p>
            <a href="reviews.php" class="btn btn-success">Manage Reviews</a>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-6">
        <div class="card h-100">
          <div class="card-body">
            <h5 class="card-title"><i class="fas fa-tags fa-lg"></i> Category Management</h5>
            <p class="card-text">Organize and manage product categories.</p>
            <a href="category_management.php" class="btn btn-danger">Manage Categories</a>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-6">
        <div class="card h-100">
          <div class="card-body">
            <h5 class="card-title"><i class="fas fa-box-open fa-lg"></i> Product Management</h5>
            <p class="card-text">Add, update, or remove products.</p>
            <a href="product_management.php" class="btn btn-warning">Manage Products</a>
          </div>
        </div>
      </div>
    </div>
  </div>



   <!-- Bootstrap JS -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>