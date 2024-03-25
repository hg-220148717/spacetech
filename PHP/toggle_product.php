<?php

session_start();
require_once("../PHP/database-handler.php");

$db_handler = new Database();
$db_handler->testDatabaseConnection();
$db_handler->checkSetup();


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'], $_POST['is_disabled'])) {
    $productId = intval($_POST['product_id']);
    $isDisabled = filter_var($_POST['is_disabled'], FILTER_VALIDATE_BOOLEAN);

    $result = $db_handler->toggleProductStatus($productId, $isDisabled);

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}

exit;
