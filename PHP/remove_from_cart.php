<?php

session_start();
include_once("../PHP/database-handler.php");

$db_handler = new Database();
$db_handler->testDatabaseConnection();
$db_handler->checkSetup();

if(!isset($_POST["entry_id"]) || !isset($_SESSION["user_id"])) {
    header("Location: ../Pages/login.php");
}

$entry_id = intval($_POST["entry_id"]);

$db_handler->removeFromBasket($entry_id, $_SESSION["user_id"]);

header("Location: ../Pages/cart.php");