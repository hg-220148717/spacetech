<?php

session_start();
include_once("../PHP/database-handler.php");

$db_handler = new Database();
$db_handler->testDatabaseConnection();
$db_handler->checkSetup();

if(!isset($_POST["product_id"]) || !isset($_SESSION["user_id"])) {
    header("Location: ../Pages/products.php");
}

$product_id = intval($_POST["product_id"]);
$qty = 1;

if(isset($_POST["qty"])) {
    $qty = intval($_POST["qty"]);
}

$subtotal = $db_handler->getProductByID($product_id)["product_price"] * $qty;

$db_handler->addToBasket($_SESSION["user_id"], $product_id, $qty, $subtotal);

header("Location: ../Pages/cart.php");