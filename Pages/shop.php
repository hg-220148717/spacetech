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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../Styles/master-style.css">
</head>

<body>
    <!-- Navigation Bar -->
    <?php include '../PHP/navbar.php'; ?>

    <!-- Explore Section -->
    <div class="container mt-5">
        <h2>Explore</h2>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php $randomProducts = $db_handler->getRandomProducts(6); ?>
            <?php foreach ($randomProducts as $product): ?>
                <div class="col">
                    <div class="card h-100">
                        <!-- Product Image -->
                        <img src="../images/products/<?= htmlspecialchars($product["product_id"], ENT_QUOTES) ?>.jpg"
                            class="card-img-top product-img" alt="<?= htmlspecialchars($product["product_name"], ENT_QUOTES) ?>">

                        <!-- Card Body -->
                        <div class="card-body">
                            <h5 class="card-title">
                                <?= htmlspecialchars($product["product_name"], ENT_QUOTES) ?>
                            </h5>
                            <p class="card-text">
                                <?= htmlspecialchars($product["product_desc"], ENT_QUOTES) ?>
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">£
                                    <?= htmlspecialchars($product["product_price"], ENT_QUOTES) ?>
                                </span>
                                <a href="product.php?id=<?= htmlspecialchars($product["product_id"], ENT_QUOTES) ?>"
                                    class="btn btn-primary">View Product</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>


        </div>
    </div>

    <!-- Categories Section -->
    <div class="container mt-5">
        <h2>Categories</h2>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php $categories = $db_handler->getAllCategories(false); // Assuming false fetches non-disabled categories      ?>
            <?php foreach ($categories as $category): ?>
                <div class="col">
                    <div class="card h-100">
                        <img src="images/categories/<?= htmlspecialchars($category["category_image"], ENT_QUOTES) ?>"
                            class="card-img-top" alt="<?= htmlspecialchars($category["category_name"], ENT_QUOTES) ?>">
                        <div class="card-body">
                            <h5 class="card-title">
                                <?= htmlspecialchars($category["category_name"], ENT_QUOTES) ?>
                            </h5>
                            <p class="card-text">Explore products in this category.</p>
                        </div>
                        <div class="card-footer">
                            <a href="?category=<?= $category["category_id"] ?>" class="btn btn-primary">View Category</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>


    <?php include_once("../PHP/footer.php"); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>