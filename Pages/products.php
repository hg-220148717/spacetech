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

/** FILTER - SEARCH BY PRODUCT NAME **/

// check if user has searched by product name
$filter_search_active = isset($_GET["search"]);

// set default search query to null (blank)
$filter_search_query = null;

if($filter_search_active) {
    $filter_search_query = $_GET["search"];
}

/** FILTERS - MINIMUM PRICE **/

// check if min price get param is set
$filter_min_price_active = isset($_GET["min_price"]) && $_GET["max_price"] !== "";

// set a default min price of £0.00
$filter_min_price = 0.00;

// check if filter is active
if($filter_min_price_active) {
    // extract price from submitted parameter
    $filter_min_price = floatval(str_replace("£","", $_GET["min_price"]));
}

/** FILTERS - MAX PRICE **/

// check if min price get param is set
$filter_max_price_active = isset($_GET["max_price"]) && $_GET["max_price"] !== "";

// set a default min price of £99,999,999,999.00
$filter_max_price = 99999999999.00;

// check if filter is active
if($filter_max_price_active) {
    // extract price from submitted parameter
    $filter_max_price = floatval(str_replace("£","",$_GET["max_price"]));
}

/** CATEGORY FILTER **/

// check if filter is active
$filter_category_active = isset($_GET["category"]) && $_GET["category"] !== "ALL";
$filter_category_id = ($filter_category_active ? intval($_GET["category"]) : 0);


$productsPerPage = 10;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$start = ($page - 1) * $productsPerPage;


$totalProducts = (!$filter_category_active ? (!$filter_search_active ? $db_handler->getAllProducts(false) : $db_handler->getProductsByName($filter_search_query)) : $db_handler->getProductsByCategoryID($filter_category_id));

// database error handling
if(!is_countable($totalProducts)) {
    $totalProducts = array();
}
$totalPages = ceil(count($totalProducts) / $productsPerPage);
$unfiltered_products_list = array_slice($totalProducts, $start, $productsPerPage);

$products_list = array();

// iterate through all products to apply filters
foreach($unfiltered_products_list as $index => $product) {
    $remove_this_product = false;
    // check if product price is less than min price
    if(floatval($product["product_price"]) < $filter_min_price) {
        // remove product if less than min price
        $remove_this_product = true;
    }

    // check if product price is more than max price
    if(floatval($product["product_price"]) > $filter_max_price) {
        // remove product if more than max price
        $remove_this_product = true;
    }

    if(!$remove_this_product) $products_list[] = $products_list + $product;

}

$categories_list = $db_handler->getAllCategories(false);

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

    <!-- Main -->
    
    <div class="container mt-5">

        <h2 class="mb-4">Shop</h2>
        <p>Browse our wide range of products!</p>
        <form method="GET">
            <input name="search" class="form-control" type="text" value='<?= isset($_GET["search"]) ? htmlspecialchars($_GET["search"], ENT_QUOTES) : "" ?>' placeholder="Search by product name...">
            <button type="submit" class="btn btn-primary ml-2">Search</button>
        </form>
        <div class="content row">
        <div class="col g-5 sidebar">
                <h3>Filter your search</h3>
                <form method="GET" class="row row-cols-1">
                    <div class="form-group row g-6" style="margin-bottom: 15px;">
                        <label for="min_price_filter">Minimum Price (£)</label>
                        <input class="form-control" name="min_price" min=0 max=99999999999 placeholder="25.99" id="min_price_filter" <?= isset($_GET["min_price"]) ? "value='". number_format(floatval($_GET["min_price"]), 2, '.', '') . "'": ""; ?>/>

                    </div>
                    <div class="form-group row g-6" style="margin-bottom: 15px;">
                        <label for="max_price_filter">Maximum Price (£)</label>
                        <input class="form-control" name="max_price" min=0 max=99999999999 placeholder="5999.99" id="max_price_filter" <?= isset($_GET["max_price"]) ? "value='". number_format(floatval($_GET["max_price"]), 2, '.', '') . "'": ""; ?>/>
                    </div>
                    <div class="form-group row g-6" style="margin-bottom: 15px;">
                        <label for="category_filter">Filter by category:</label>
                        <select class="form-control" name="category" id="category_filter">
                            <?php if(!empty($categories_list)): ?>
                                <option value="ALL">All Categories</option>
                                <?php foreach($categories_list as $category): ?>
                                    <option <?= intval($category["category_id"]) == intval($_GET["category"]) ? "selected " : "" ?>value="<?= htmlspecialchars($category["category_id"], ENT_QUOTES) ?>"><?= htmlspecialchars($category["category_name"], ENT_QUOTES) ?></option>
                                <?php endforeach; ?>
                                <?php else: ?>
                                    <option id="-1" default hidden>No categories found.</option>
                                <?php endif; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Filter Products</button>
                </form> 

                </div>
            <div class="row row-cols-1 row-cols-md-3 w-75 g-4">
                <?php if (!empty($products_list)): ?>
                    <?php foreach ($products_list as $product): ?>
                        <div class="col">
                            <div class="card h-100" onclick="window.location.href='product.php?id=<?= htmlspecialchars($product["product_id"], ENT_QUOTES) ?>';">
                                <img src="/images/products/<?= htmlspecialchars($product["product_id"], ENT_QUOTES) ?>.jpg" class="card-img-top" alt="<?= htmlspecialchars($product["product_name"], ENT_QUOTES) ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($product["product_name"], ENT_QUOTES) ?></h5>
                                    <p class="card-text"><?= htmlspecialchars($product["product_desc"], ENT_QUOTES) ?></p>
                                    <div class="card-footer">
                                        <h4>£<?= htmlspecialchars($product["product_price"], ENT_QUOTES) ?></h4>
                                    </div>
                                </div>

                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col">
                        <p>No products found.</p>
                    </div>
                <?php endif; ?>
                </div>
            </div>
            
            
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $page === $i ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>">
                            <?= $i ?>
                        </a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>

    <?php include_once("../PHP/footer.php"); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>