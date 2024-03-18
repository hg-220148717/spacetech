<?php

session_start();
include_once("../PHP/database-handler.php");

$db_handler = new Database();
$db_handler->testDatabaseConnection();
$db_handler->checkSetup();

if(!isset($_SESSION["user_id"])) {
    header("Location: ../Pages/login.php");
}

$address = $_POST["address_line1"] . "\n" . $_POST["address_line2"] . "\n" . $_POST["address_line3"];
$comments = "";
$basket_count = $db_handler->getBasketCount($_SESSION["user_id"]);
$basket_contents = $db_handler->getBasketContents($_SESSION["user_id"]);
$order_total = $db_handler->getBasketTotal($_SESSION["user_id"]);
$is_paid = handlePayment($order_total, $_POST["card_no"], $_POST["card_expiry"], $_POST["card_cvv"], $_POST["card_name"]);

if($basket_count <= 0) {
    print("Empty");
    header("Location: ../Pages/cart.php?error=emptyBasket");
    exit;
}

if($is_paid) {

    foreach ($basket_contents as $item) {
        if(!$db_handler->getStockLevelOfItem($item["product_id"]) <= $item["entry_quantity"] ) {
            header("Location: ../Pages/cart.php?error=outOfStock&item_id=" . $item["product_id"]);
            exit;
        }
    }

    $db_handler->submitOrder($_SESSION["user_id"], $address, $comments, $order_total, true);
    header("Location: ../Pages/cart.php?success=true");
} else {
    header("Location: ../Pages/cart.php?error=An+error+occurred+taking+payment.");
}


function handlePayment($amount, $card_no, $card_expiry, $card_cvv, $card_name) {
    // this function would be used to contact a payment gateway to verify payment details.
    // As this is to be implemented as a dummy system, this will always return true.
    return true;
}