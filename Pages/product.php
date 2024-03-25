<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once("../PHP/database-handler.php");


if (!isset($_SESSION["user_id"])) {
    header("Location: ../Pages/login.php");

}

$db_handler = new Database();
$db_handler->testDatabaseConnection();
$db_handler->checkSetup();

$product = $db_handler->getProductByID(intval($_GET["id"]));
$reviews = $db_handler->getReviewsByProductID(intval($_GET["id"]));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo htmlspecialchars($product["product_name"], ENT_QUOTES); ?>
    </title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../Styles/master-style.css">
    <link rel="stylesheet" href="../Styles/review.css">
</head>

<body>
    <?php include_once("../PHP/navbar.php"); ?>

    <div class="container py-5">
        <div class="row">
            <div class="col-md-6">
                <img src="../images/products/<?php echo htmlspecialchars($product["product_id"], ENT_QUOTES); ?>.jpg"
                    class="img-fluid" alt="<?php echo htmlspecialchars($product["product_name"], ENT_QUOTES); ?>">
            </div>
            <div class="col-md-6">
                <h1>
                    <?php echo htmlspecialchars($product["product_name"], ENT_QUOTES); ?>
                </h1>
                <p class="h3 py-2">£
                    <?php echo htmlspecialchars($product["product_price"], ENT_QUOTES); ?>
                </p>
                <p>
                    <?php echo htmlspecialchars($product["product_desc"], ENT_QUOTES); ?>
                </p>
                <?php if(intval($product["product_stockcount"]) > 0): ?>
                <h5><?= htmlspecialchars($product["product_stockcount"], ENT_QUOTES) ?> in stock</h5>
                <form action="../PHP/add_to_cart.php" method="POST" class="py-2">
                    <input type="number" name="product_id"
                        value="<?php echo htmlspecialchars($product["product_id"], ENT_QUOTES); ?>" hidden>
                
                    <div class="input-group mb-3" style="width: 160px;">
                        <span class="input-group-text">Qty</span>
                        <input type="number" name="qty" value="1" min="1" max="<?= htmlspecialchars($product["product_stockcount"], ENT_QUOTES) ?>" class="form-control" aria-label="Quantity">
                    </div>
                    <button type="submit" class="btn btn-primary">Add To Cart</button>
                </form>
                <?php else: ?>
                    <h4>Out of stock</h4>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="container py-5">
        <h2>Product Reviews</h2>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#reviewModal">Write a
            Review</button>
        <?php if(isset($_GET["review"])): ?>
            <?php if($_GET["review"] == "success"): ?>
                <div class="alert alert-success">
                            <p>Your review has been successfully recieved. Once our team have moderated and confirmed your review meets our standards to publish, your review will become visible. Please check back in 48-72 hours.</p>
                </div>
            <?php else: ?>
                <div class="alert alert-danger">
                    <p>An error occurred leaving your review. Please try again.</p>
                </div>
            <?php endif; ?>
            <?php endif; ?>
        <!-- Display Reviews -->
        <div id="reviews-list">
            <?php if (!empty($reviews)): ?>
                <?php foreach ($reviews as $review): ?>
                    <div class="review mb-4">
                        <div class="rating">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <?php if ($i <= $review['review_rating']): ?>
                                    <span class="fa fa-star checked" style="color: orange;"></span>
                                <?php else: ?>
                                    <span class="fa fa-star" style="color: lightgray;"></span>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </div>
                        <p class="review-text">
                            <?= htmlspecialchars($review['review_text'], ENT_QUOTES); ?>
                        </p>
                        <small class="text-muted">By
                            <?= htmlspecialchars($db_handler->getNameFromUserID($review['review_userid']), ENT_QUOTES); ?>
                        </small>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No reviews yet.</p>
            <?php endif; ?>
        </div>
    </div>


    <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reviewModalLabel">Create New Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="row" action="../PHP/submit_review.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="product_id"
                            value="<?php echo htmlspecialchars($product["product_id"], ENT_QUOTES); ?>">
                        <div class="mb-3">
                            <div class="rating rating_modal">
                                <input id="star1" name="rating" type="radio" value="1" class="radio-btn hide" required />
                                <label for="star1">☆</label>
                                <input id="star2" name="rating" type="radio" value="2" class="radio-btn hide" required />
                                <label for="star2">☆</label>
                                <input id="star3" name="rating" type="radio" value="3" class="radio-btn hide" required />
                                <label for="star3">☆</label>
                                <input id="star4" name="rating" type="radio" value="4" class="radio-btn hide" required />
                                <label for="star4">☆</label>
                                <input id="star5" name="rating" type="radio" value="5" class="radio-btn hide" required />
                                <label for="star5">☆</label>
                                
                                <div class="clear"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="reviewText" class="form-label">Review</label>
                            <textarea class="form-control" id="reviewText" name="review_text" rows="3"
                                required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit Review</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <?php include_once("../PHP/footer.php"); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>