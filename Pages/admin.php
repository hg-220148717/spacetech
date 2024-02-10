<?php
session_start();
require_once("../PHP/database-handler.php");

$db_handler = new Database();
$setupStatus = $db_handler->checkSetup();

if (!$setupStatus) {
    die("Error setting up the database.");
}

if (!isset($_SESSION["user_id"]) || !$db_handler->isUserStaff($_SESSION["user_id"])) {
    header("Location: ../Pages/login.php");
    // redirect as no user id found in session or not staff
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
</head>
<body>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg bg-light" data-bs-theme="light">
    <div class="container">
        <a class="navbar-brand" href="../Pages/Home.html" target="_self">
            <img src="../Images/SpaceTech.png" alt="SpaceTech" width="120" height="30">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
            aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <a class="nav-link active" href="#">Home</a>
                <a class="nav-link" href="#">Shop</a>
                <a class="nav-link" aria-current="page" href="../Pages/Contact.Html" target="_self">Contact Us</a>
            </div>
            <form class="d-flex mx-auto">
                <div class="input-group">
                    <input type="search" class="form-control rounded" placeholder="Search" aria-label="Search"
                        aria-describedby="search-addon" />
                </div>
            </form>
            <div class="navbar-nav dropdown">
                <a class="nav-link" href="#"><i class="bi bi-basket"></i></a>
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <i class="bi bi-person"></i>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Your Account</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="#">Guest</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="container mt-5">
    <h2 class="mb-4">Dashboard</h2>
    <div class="row g-4">
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

<!-- Bootstrap Bundle with Popper -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
