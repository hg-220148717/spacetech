<?php

session_start();
require_once("../PHP/database-handler.php");

$db_handler = new Database();
$db_handler->testDatabaseConnection();
$db_handler->checkSetup();

// Check if the user is logged in and is staff
if (!isset($_SESSION["user_id"]) || !$db_handler->isUserStaff($_SESSION["user_id"])) {
    header("Location: ../Pages/index.php");
    exit; // Ensure no further execution happens after a redirect
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $categoryId = intval($_POST["category_id"]);
    $newName = trim($_POST["name"]);

    // Preparing the response array
    $response = [];

    if ($db_handler->categoryExists($newName)) {
        $response = [
            'status' => 'error',
            'message' => 'NameExists'
        ];
    } else {
        $result = $db_handler->editCategory($categoryId, $newName, null, null);
        if ($result == "Category updated successfully.") {
            $response = [
                'status' => 'success',
                'message' => 'Category updated successfully.',
                'categoryId' => $categoryId,
                'newName' => $newName
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'UpdateFailed'
            ];
        }
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
