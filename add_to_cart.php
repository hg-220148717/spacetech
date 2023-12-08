<?php

session_start();

if(!isset($_POST["product_id"]) || !isset($_SESSION["user_id"])) {
    header("Location: products.php");
}

$qty = 0;

if(!isset($_POST["qty"]) || !is_int($_POST["qty"])) {
    $qty = 1;
} else {
    $qty = intval($_POST["qty"]);
}



?>