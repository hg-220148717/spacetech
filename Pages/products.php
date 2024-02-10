<?php
session_start();
require_once("../PHP/database-handler.php");

$db_handler = new Database();
$setupStatus = $db_handler->checkSetup();

if (!$setupStatus) {
    die("Error setting up the database.");
}

// Define variables for pagination
$productsPerPage = 10; // Number of products per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page number
$start = ($page - 1) * $productsPerPage; // Calculate starting point

// Fetch products with pagination
$totalProducts = $db_handler->getAllProducts(true); // Assume this method returns the total number of products
$totalPages = ceil(sizeof($totalProducts)/ $productsPerPage);
$products_list = $totalProducts; // Assume this method fetches products for the page
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products | SpaceTech</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../Styles/products.css">
</head>
<body>
    <?php include_once("../PHP/header.php"); ?>
    <section id="hero">
        <h2>Product Deals</h2>
        <p>List of Products</p>
    </section>
    <section id="products1" class="section-p1">
        <div class="products-container">
            <?php if (!empty($products_list)): ?>
                <?php foreach ($products_list as $product): ?>
                    <div class="pro" onclick="window.location.href='product.php?id=<?= htmlspecialchars($product["product_id"], ENT_QUOTES) ?>'">
                        <img src="images/products/<?= htmlspecialchars($product["product_id"], ENT_QUOTES) ?>.jpg" alt="<?= htmlspecialchars($product["product_name"], ENT_QUOTES) ?>">
                        <div class="description">
                            <span><?= htmlspecialchars($product["product_name"], ENT_QUOTES) ?></span>
                            <h5><?= htmlspecialchars($product["product_desc"], ENT_QUOTES) ?></h5>
                            <h4>£<?= htmlspecialchars($product["product_price"], ENT_QUOTES) ?></h4>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No products found.</p>
            <?php endif; ?>
        </div>
    </section>

    <section id="pagination" class="section-p1">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i ?>" class="<?= $page === $i ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
    </section>

    <script src="../Scripts/products.js"></script>
    <?php include_once("../PHP/footer.php"); ?>
</body>
</html>
