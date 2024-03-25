<?php

session_start();
require_once("../PHP/database-handler.php");

$db_handler = new Database();
$db_handler->testDatabaseConnection();
$db_handler->checkSetup();

if(!isset($_SESSION["user_id"]) || !$db_handler->isUserAdmin($_SESSION["user_id"])) {
    echo json_encode(['status' => 'error', 'message' => 'No permission']);
    exit;
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user_id'])) {
    $user_id = intval($_POST['user_id']);

    $result = $db_handler->makeUserStaff($user_id);

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
