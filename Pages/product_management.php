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
    <!-- JQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
                        <th scope="col">Enabled?</th>
                        <th scope="col">Stock Count</th>
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
                                £<?= htmlspecialchars($db_handler->getProductPriceById($product["product_id"]), ENT_QUOTES); ?>
                            </td>
                            <td>
                                <?php if ($product["product_isdisabled"]): ?>
                                    <i class="fas fa-check-circle"></i>
                                <?php else: ?>
                                    <i class="fas fa-times-circle"></i>
                                <?php endif; ?>
                            </td>
                            <td>
                                    <?= htmlspecialchars($db_handler->getStockLevelOfItem($product["product_id"]), ENT_QUOTES); ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($db_handler->getCategoryNameByProductId($product["product_id"]), ENT_QUOTES); ?>
                            </td>
                            <td>
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#editProductModal" data-product-id="<?= $product["product_id"] ?>"
                                    data-product-name="<?= htmlspecialchars($product["product_name"], ENT_QUOTES); ?>"
                                    data-product-desc="<?= htmlspecialchars($product["product_desc"], ENT_QUOTES); ?>"
                                    data-product-price="<?= htmlspecialchars($product["product_price"], ENT_QUOTES); ?>"
                                    data-product-stock="<?= htmlspecialchars($product["product_stockcount"], ENT_QUOTES); ?>">
                                    Edit
                                </button>
                                <button class="btn btn-<?= $product["product_isdisabled"] ? 'success' : 'warning'; ?> btn-sm toggle-product" data-bs-toggle="modal"
                                    data-bs-target="#toggleProductModal" data-product-id="<?= $product["product_id"] ?>"
                                    data-is-disabled="<?= $product["product_isdisabled"] ? '1' : '0'; ?>">
                                    <?= $product["product_isdisabled"] ? 'Enable' : 'Disable'; ?>
                                </button>
                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#deleteProductModal"
                                    data-product-id="<?= $product["product_id"] ?>">
                                    Delete
                                </button>
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
                <form class="row" action="../PHP/create_product.php" method="POST" enctype="multipart/form-data">
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
                            <label for="productCategory" class="form-label">Product</label>
                            <select class="form-select" id="productCategory" name="category" required>
                                <?php foreach ($categories as $product): ?>
                                    <option value="<?= htmlspecialchars($product["category_id"], ENT_QUOTES); ?>">
                                        <?= htmlspecialchars($product["category_name"], ENT_QUOTES); ?>
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
    <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="row" id="editProductForm" enctype="multipart/form-data">
                    <!-- enctype added for file upload -->
                    <input type="hidden" id="editProductId" name="product_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="productName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="editProductName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="productDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="editProductDesc" name="description"
                                required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="productPrice" class="form-label">Price (£)</label>
                            <input type="number" step="0.01" class="form-control" id="editProductPrice" name="price"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="productStock" class="form-label">Stock Count</label>
                            <input type="number" step="1" class="form-control" id="editProductStock" name="stock"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="productCategory" class="form-label">Category</label>
                            <select class="form-select" id="editProductCategory" name="category" required>
                                <?php foreach ($categories as $product): ?>
                                    <option value="<?= htmlspecialchars($product["category_id"], ENT_QUOTES); ?>">
                                        <?= htmlspecialchars($product["category_name"], ENT_QUOTES); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Edit Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Toggle -->
    <div class="modal fade" id="toggleProductModal" tabindex="-1" aria-labelledby="toggleProductModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="toggleProductModalLabel">Toggle Product Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to <span id="toggleAction">enable</span> this product?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmToggle">Yes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete -->
    <div class="modal fade" id="deleteProductModal" tabindex="-1" aria-labelledby="deleteProductModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteProductModalLabel">Delete Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this product? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal JS -->
    <script src="../Scripts/products.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>