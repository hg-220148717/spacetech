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

$reviews = $db_handler->getAllReviews();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Management</title>
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
        <h2 class="mb-4">Review Management</h2>
        <div id="alertPlaceholder"></div>
        <?php
        if (isset($_GET['error'])) {

        }
        ?>
        <?php if(count($reviews) > 0): ?>
        <!-- Table to display reviews -->
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">User</th>
                        <th scope="col">Product</th>
                        <th scope="col">Rating</th>
                        <th scope="col">Description</th>
                        <th scope="col">Approved</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reviews as $review): ?>
                        <tr id="review-row-<?= $review['review_id']; ?>">

                            <th scope="row">
                                <?php echo htmlspecialchars($review["review_id"], ENT_QUOTES); ?>
                            </th>
                            <td>
                                <?= htmlspecialchars($review["user_name"], ENT_QUOTES); ?>
                            </td>
                            <td>
                                <a
                                    href="../Pages/product.php?product_id=<?= htmlspecialchars($review["review_productid"], ENT_QUOTES); ?>">
                                    <?= htmlspecialchars($review["product_name"], ENT_QUOTES); ?>
                                </a>
                            </td>
                            <td>
                                <?= htmlspecialchars($review["review_rating"], ENT_QUOTES); ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($review["review_text"], ENT_QUOTES); ?>
                            </td>
                            <td>
                                <?php if ($review["review_approved"]): ?>
                                    <i class="fas fa-check-circle"></i>
                                <?php else: ?>
                                    <i class="fas fa-times-circle"></i>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!$review["review_approved"]): ?>
                                    <button class="btn btn-success btn-sm"
                                    onclick="window.location.href='../PHP/approve_review.php?review_id=<?= $review['review_id']; ?>';">
                                        Approve
                                    </button>
                                <?php endif; ?>
                                <button class="btn btn-danger btn-sm" onclick="window.location.href='../PHP/delete_review.php?review_id=<?= $review['review_id']; ?>';">
                                    Delete
                                </button>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
            <h4>No reviews require moderation currently. Please check back later.</h4>
        <?php endif; ?>
        
        <!-- Modal JS -->
        <script src="../Scripts/category.js"></script>
        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>