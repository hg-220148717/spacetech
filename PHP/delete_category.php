<?php

session_start();
require_once("../PHP/database-handler.php");

$db_handler = new Database();
$db_handler->testDatabaseConnection();
$db_handler->checkSetup();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['category_id'])) {
    $categoryId = intval($_POST['category_id']);

    $result = $db_handler->deleteCategory($categoryId);

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete category.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}

exit;
