<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once("../PHP/database-handler.php");


if (!isset($_SESSION["user_id"])) {
    header("Location: ../Pages/login.php");

    // redirect user if not logged in
}

//if(isset($_GET["id"])) {
//        header("Location: products.php"); 
// redirect if product id not set
//}

$db_handler = new Database();
$db_handler->testDatabaseConnection();
$db_handler->checkSetup();

$product = $db_handler->getProductByID(intval($_GET["id"]));

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
                <form action="add_to_cart.php" method="POST" class="py-2">
                    <input type="number" name="product_id"
                        value="<?php echo htmlspecialchars($product["product_id"], ENT_QUOTES); ?>" hidden>

                    <div class="input-group mb-3" style="width: 160px;">
                        <span class="input-group-text">Qty</span>
                        <input type="number" name="qty" value="1" class="form-control" aria-label="Quantity" min="1">
                    </div>
                    <button type="submit" class="btn btn-primary">Add To Cart</button>
                </form>

            </div>
        </div>
    </div>

    <script src="../Scripts/products.js"></script>
    <?php include_once("../PHP/footer.php"); ?>
</body>

</html>