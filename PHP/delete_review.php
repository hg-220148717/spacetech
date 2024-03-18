<?php

session_start();
include_once("../PHP/database-handler.php");

if (!isset($_SESSION["user_id"]) && !$db_handler->isUserStaff($_SESSION["user_id"])) {
    header("Location: ../Pages/index.php");
    exit; // Ensure no further execution happens after a redirect
}

$db_handler = new Database();
if (isset($_GET['review_id'])) {
    $review_id = intval($_GET['review_id']);
    $result = $db_handler->deleteReview($review_id);

    if ($result) {
        header('Location: ../Pages/reviews.php?success=true');
    } else {
        header('Location: ../Pages/reviews.php?error=failed');
    }
}
