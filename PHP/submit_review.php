<?php

session_start();
require_once("../PHP/database-handler.php");

$db_handler = new Database();
$db_handler->testDatabaseConnection();
$db_handler->checkSetup();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_SESSION['user_id']; 
    $productId = $_POST['product_id'];
    $rating = $_POST['rating']; 
    $reviewText = $_POST['review_text'];

    $db_handler = new Database();
    $result = $db_handler->createReview($userId, $productId, $rating, $reviewText);

    if ($result) {
        header("Location: ../Pages/product.php?id=$productId&review=success");
    } else {
        header("Location: ../Pages/product.php?id=$productId&review=error");
    }
} else {
    header("Location: index.php");
}