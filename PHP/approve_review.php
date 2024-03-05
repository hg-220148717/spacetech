<?php

session_start();
include_once("../PHP/database-handler.php"); 

if (!isset($_SESSION["user_id"]) && !$db_handler->isUserStaff($_SESSION["user_id"])) {
    header("Location: ../Pages/index.php");
    exit; // Ensure no further execution happens after a redirect
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['review_id'])) {
    $review_id = intval($_GET['review_id']);

    $db_handler = new Database();
    $result = $db_handler->approveReview($review_id);

    if ($result) {
        header("Location: ../Pages/reviews.php?status=success");
    } else {
        header("Location: ../Pages/reviews.php?error=failed");
    }
}

