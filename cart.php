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
    <div class="checkingout">
        <div class="mainb">
            <h2>Checkout</h2>
        </div>
        <div class="boxes">
        <div class="cart form ">
        <h3>Card Details</h3>

        <div class="basic">
            <span>Full Name:</span>
            <input type="text" placeholder="John Doe" <?php echo "value='" . htmlspecialchars($name, ENT_QUOTES) . "'"; ?> disabled required>
        </div>

        <div class="basic">
            <span>Email</span>
            <input type="text" placeholder="johndoe@gmail.com"  <?php echo "value='" . htmlspecialchars($email, ENT_QUOTES) . "'"; ?> disabled required>
        </div>

        <div class="basic">
            <span>Address:</span>
            <input type="text" placeholder="Number-Street Name"required>
        </div>

        <div class="basic">
            <span>City:</span>
            <input type="text" placeholder="Birmingham"required>
        </div>

        <div class="basic">
            <span>Postcode:</span>
            <input type="text" placeholder="B4 7ET"required>
        </div>

        <div class="basic">
                <span>Card Number:</span>
                <input type="text" placeholder="0000 0000 0000 0000" required>
            </div>

            <div class="basic">
                <span>Expiry Date:</span>
                <input type="text" placeholder="MM/YY" required>
            </div>

            <div class="basic">
                <span>CVV:</span>
                <input type="text" placeholder="123" required>
            </div>

            <div class="basic">
                <span>Cardholder Name:</span>
                <input type="text" placeholder="John Doe" required>
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
        
    </div>
    <div class="side">
        <h2>Summary</h2><br>
            <p>Products:</p>
            <p>Shipping:</p>
            <p>Total Amount:<br>(including VAT)</p>
    </div>
    <?php include_once("footer.php"); ?>
</body>
</html>