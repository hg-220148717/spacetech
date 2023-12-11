<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    // redirect as no user id found in session, user is not logged i
}

include("database-handler.php");
$db_handler = new Database();
$db_handler->checkSetup();

$email = $db_handler->getEmailFromUserID($_SESSION["user_id"]);
$name = $db_handler->getNameFromUserID($_SESSION["user_id"]);

$basket_count = $db_handler->getBasketCount($_SESSION["user_id"]);
$basket_total = $db_handler->getBasketTotal($_SESSION["user_id"]);

if($basket_total == null) $basket_total = 0.00;

$basket_contents = $db_handler->getBasketContents($_SESSION["user_id"]);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="design.css"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
</head>

<?php include_once("header.php"); ?>

<body>
    <form action="submit_order.php" method="POST">
    <div class="checkingout">
        <div class="mainb">
            <h2>Checkout</h2>
        </div>
        <div class="boxes">
        <div class="cart form ">
        
        <h3>Card Details</h3>
        <div class="basic">
            <span>Full Name:</span>
            <input name="name" type="text" placeholder="John Doe" <?php echo "value='" . htmlspecialchars($name, ENT_QUOTES) . "'"; ?> disabled required>
        </div>

        <div class="basic">
            <span>Email</span>
            <input name="email" type="email" placeholder="johndoe@gmail.com"  <?php echo "value='" . htmlspecialchars($email, ENT_QUOTES) . "'"; ?> disabled required>
        </div>

        <div class="basic">
            <span>Address:</span>
            <input name="address_line1" type="text" placeholder="Number-Street Name"required>
        </div>

        <div class="basic">
            <span>City:</span>
            <input name="address_line2" type="text" placeholder="Birmingham"required>
        </div>

        <div class="basic">
            <span>Postcode:</span>
            <input name="address_line3" type="text" placeholder="B4 7ET"required>
        </div>

        <div class="basic">
                <span>Card Number:</span>
                <input name="card_no" type="text" placeholder="0000 0000 0000 0000" required>
            </div>

            <div class="basic">
                <span>Expiry Date:</span>
                <input name="card_expiry" type="text" placeholder="MM/YY" required>
            </div>

            <div class="basic">
                <span>CVV:</span>
                <input name="card_cvv" type="text" placeholder="123" required>
            </div>

            <div class="basic">
                <span>Cardholder Name:</span>
                <input name="card_name" type="text" placeholder="John Doe" required>
            </div>
        
            <div class="basic">
                <input type="submit" value="Checkout">
            </div>
           </div>
        </form>
    </div>
    </div>
    <div class="other">
        <h2>Shopping Cart</h2><br>  


        <?php 

        if($basket_count > 0) {
            foreach ($basket_contents as $item) {

                $product = $db_handler->getProductByID($item["product_id"]);
                

                echo '<div class="basic">
                        <h4>'. htmlspecialchars($product["product_name"], ENT_QUOTES) . ' (£' . htmlspecialchars($product["product_price"], ENT_QUOTES) . ')</h4>
                        <p>Quantity: ' . htmlspecialchars($item["qty"], ENT_QUOTES) . '</p>
                        <p>Subtotal: £' . htmlspecialchars($item["subtotal"], ENT_QUOTES) . '</p>

                        <form action="remove_from_cart.php" method="POST">
                        <input hidden name="entry_id" value="'.htmlspecialchars($item["entry_id"], ENT_QUOTES) .'">
                        <button type="submit">Remove</button>
                        </form>

                    </div>';
            }
        

        } else {
            if(isset($_GET["success"])) { echo "<h3>Order submitted.</h3>"; } else {echo "<h3>Your basket is empty!</h3>";
        }
    }

        ?>

    </div>
    <div class="side">
        <h2>Summary</h2><br>
            <p>Products: <?php echo htmlspecialchars($basket_count, ENT_QUOTES); ?></p>
            <p>Shipping: FREE</p>
            <p>Total Amount: £<?php echo htmlspecialchars($basket_total, ENT_QUOTES); ?><br>(including VAT)</p>
    </div>
    <?php include_once("footer.php"); ?>
</body>
</html>