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

if (!isset($_SESSION["user_id"]) || !$db_handler->isUserStaff($_SESSION["user_id"])) {
    header("Location: ../Pages/index.php");
}

$products = $db_handler->getAllProducts(true);
$categories = $db_handler->getAllCategories(false);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../Styles/master-style.css">
</head>

<body>
    <!-- Navigation Bar -->
    <?php include '../PHP/navbar.php'; ?>

    <div class="container mt-5">
        <h2 class="mb-4">Product Management</h2>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createProductModal">Create New
            Product</button>
        <!-- Table to display products -->
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Price</th>
                        <th scope="col">Disabled</th>
                        <th scope="col">Category</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $index => $product): ?>
                        <tr id="product-row-<?= $product['product_id']; ?>">
                            <th scope="row">
                                <?php echo $product["product_id"]; ?>
                            </th>
                            <td>
                                <?= htmlspecialchars($product["product_name"], ENT_QUOTES); ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($db_handler->getProductPriceById($product["product_id"]), ENT_QUOTES); ?>
                            </td>
                            <td>
                                <?php if ($product["product_isdisabled"]): ?>
                                    <i class="fas fa-check-circle"></i>
                                <?php else: ?>
                                    <i class="fas fa-times-circle"></i>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($db_handler->getCategoryNameByProductId($product["product_id"]), ENT_QUOTES); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Product Modal -->
    <div class="modal fade" id="createProductModal" tabindex="-1" aria-labelledby="createProductModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createProductModalLabel">Create New Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="row" action="../PHP/create_product.php" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="productName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="productName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="productDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="productDescription" name="description"
                                required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="productPrice" class="form-label">Price</label>
                            <input type="number" step="0.01" class="form-control" id="productPrice" name="price"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="productCategory" class="form-label">Category</label>
                            <select class="form-select" id="productCategory" name="category" required>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= htmlspecialchars($category["category_id"], ENT_QUOTES); ?>">
                                        <?= htmlspecialchars($category["category_name"], ENT_QUOTES); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit -->

    <!-- Delete -->

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>