<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); }
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
    <link rel="stylesheet" a href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../Styles/products.css">
</head>

<body>
    <?php include_once("navbar.php"); ?>
    <section id="productsdetails" class="section-p1">
        <div class="proimage">
            <?php echo '<img src="images/products/' . htmlspecialchars($product["product_id"], ENT_QUOTES) . '.jpg" width="100%" id="bigimage"  alt="">'; ?>
        </div>
        <div class="proimagedetails">
            <h1>
                <?php echo htmlspecialchars($product["product_name"], ENT_QUOTES); ?>
                </h4>
                <h2>£
                    <?php echo htmlspecialchars($product["product_price"], ENT_QUOTES); ?>
                </h2>
                <form action="add_to_cart.php" method="POST">
                    <input type="number" name="product_id"
                        value="<?php echo htmlspecialchars($product["product_id"], ENT_QUOTES); ?>" hidden>
                    <input type="number" name="qty" value="1">
                    <button type="submit">Add To Cart</button>
                </form>
                <h4>Product Description</h4>
                <span>
                    <?php echo htmlspecialchars($product["product_desc"], ENT_QUOTES); ?>
                </span>
        </div>
    </section>

    <!--<script>
    var bigimage = document.getElementById("bigimage");
    var miniimage = document.getElementsByClassName("miniimage");

    miniimage[0].onclick = function(){
        bigimage.src = miniimage[0].src;
    }
    miniimage[1].onclick = function(){
        bigimage.src = miniimage[1].src;
    }
    miniimage[2].onclick = function(){
        bigimage.src = miniimage[2].src;
    }
    miniimage[3].onclick = function(){
        bigimage.src = miniimage[3].src;
    }
</script>-->

    <script src="../Scripts/products.js"></script>
    <?php include_once("../PHP/footer.php"); ?>
</body>

</html>