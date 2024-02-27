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

$categories = $db_handler->getAllCategories(true);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Management</title>
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
        <h2 class="mb-4">Category Management</h2>
        <div id="alertPlaceholder"></div>
        <?php
        if (isset($_GET['error'])) {
            $error_message = '';
            switch ($_GET['error']) {
                case 'emptyName':
                    $error_message = 'Please enter a name for the category.';
                    break;
                case 'nameExists':
                    $error_message = 'Category name already exists.';
                    break;
                case 'fileExists':
                    $error_message = 'File already exists.';
                    break;
                case 'largeSize':
                    $error_message = 'The uploaded file is too large.';
                    break;
                case 'notImage':
                    $error_message = 'The uploaded file is not an image.';
                    break;
                case 'invalidType':
                    $error_message = 'Only JPG, JPEG, and PNG files are allowed.';
                    break;
                case 'creationfailed':
                    $error_message = 'Failed to create the category.';
                    break;
                default:
                    $error_message = 'An unknown error occurred.';
            }
            echo "<script>
                var alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-danger';
                var alertText = document.createTextNode('$error_message');
                alertDiv.appendChild(alertText);
                var alertPlaceholder = document.getElementById('alertPlaceholder');
                alertPlaceholder.appendChild(alertDiv);
            </script>";
        } else if(isset($_GET["success"])) {
            echo "<script>
                var alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-success';
                var alertText = document.createTextNode('Category created!');
                alertDiv.appendChild(alertText);
                var alertPlaceholder = document.getElementById('alertPlaceholder');
                alertPlaceholder.appendChild(alertDiv);
            </script>";
        }
        ?>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createCategoryModal">Create New
            Category</button>
        <!-- Table to display products -->
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Products</th>
                        <th scope="col">Disabled</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $index => $category): ?>
                        <tr id="category-row-<?= $category['category_id']; ?>">

                            <th scope="row">
                                <?php echo $category["category_id"]; ?>
                            </th>
                            <td>
                                <?= htmlspecialchars($category["category_name"], ENT_QUOTES); ?>
                            </td>
                            <td>
                                <?= htmlspecialchars(count($db_handler->getProductsByCategoryID($category['category_id'])), ENT_QUOTES); ?>
                            </td>
                            <td>
                                <?php if ($category["category_isdisabled"]): ?>
                                    <i class="fas fa-check-circle"></i>
                                <?php else: ?>
                                    <i class="fas fa-times-circle"></i>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#editCategoryModal" data-category-id="<?= $category["category_id"] ?>"
                                    data-category-name="<?= htmlspecialchars($category["category_name"], ENT_QUOTES); ?>">
                                    Edit
                                </button>
                                <button class="btn btn-warning btn-sm toggle-category" data-bs-toggle="modal"
                                    data-bs-target="#toggleCategoryModal" data-category-id="<?= $category["category_id"] ?>"
                                    data-is-disabled="<?= $category["category_isdisabled"] ? '1' : '0'; ?>">
                                    <?= $category["category_isdisabled"] ? 'Enable' : 'Disable'; ?>
                                </button>
                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#deleteCategoryModal"
                                    data-category-id="<?= $category["category_id"] ?>">
                                    Delete
                                </button>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Category Modal -->
    <div class="modal fade" id="createCategoryModal" tabindex="-1" aria-labelledby="createCategoryModal"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createProductModalLabel">Create New Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="row" action="../PHP/create_category.php" method="POST" enctype="multipart/form-data">

                    <div class="modal-body">
                        <!-- Form fields -->
                        <div class="mb-3">
                            <label for="categoryName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="categoryName" name="name" required>
                        </div>
<<<<<<< HEAD
                        <div class="mb-3">
                            <label for="categoryImage" class="form-label">Image</label>
                            <input type="text" class="form-control" id="categoryImage" name="image" required>
                        </div>
=======
<<<<<<< Updated upstream
=======
                        <div class="mb-3">
                            <label for="formFileSm" class="form-label">Image</label>
                            <input class="form-control form-control-sm" id="formFileSm" type="file"
                                name="categoryImage" required>

                        </div>
>>>>>>> Stashed changes
>>>>>>> e8888db254a26446bf7e34ec8b0e58277d388c62
                        <div class="mb-3 form-check">
                            <input class="form-check-input" type="checkbox" id="categoryDisable" name="is_disabled">
                            <label class="form-check-label" for="categoryDisable">Disable</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create Category</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCategoryModalLabel">Edit Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="row" id="editCategoryForm" enctype="multipart/form-data">
                    <!-- enctype added for file upload -->
                    <div class="modal-body">
                        <input type="hidden" id="editCategoryId" name="category_id">
                        <!-- Category Name -->
                        <div class="mb-3">
                            <label for="editCategoryName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="editCategoryName" name="name" required>
                        </div>

                        <!-- Image Upload -->
                        <div class="mb-3">
                            <label for="formFileSm" class="form-label">Image</label>
                            <input class="form-control form-control-sm" id="formFileSm" type="file"
                                name="categoryImage">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Edit Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Enable/Disable Modal -->
    <div class="modal fade" id="toggleCategoryModal" tabindex="-1" aria-labelledby="toggleCategoryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="toggleCategoryModalLabel">Toggle Category Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to <span id="toggleAction">enable</span> this category?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmToggle">Yes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteCategoryModal" tabindex="-1" aria-labelledby="deleteCategoryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteCategoryModalLabel">Delete Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this category? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal JS -->
    <script src="../Scripts/category.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>