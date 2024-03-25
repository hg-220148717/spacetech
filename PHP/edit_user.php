<?php

session_start();
require_once("../PHP/database-handler.php");

$db_handler = new Database();
$db_handler->testDatabaseConnection();
$db_handler->checkSetup();

// Check if the user is logged in and is staff
if (!isset($_SESSION["user_id"]) || !$db_handler->isUserAdmin($_SESSION["user_id"])) {
    header("Location: ../Pages/index.php");
    exit; // Ensure no further execution happens after a redirect
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = intval($_POST["user_id"]);
    $newName = trim($_POST["name"]);
    $newEmail = trim($_POST["email"]);
    $newPassword = trim($_POST["password"]);
    
    $result = $db_handler->editUser($userId, $newName, $newEmail, $newPassword);
    if ($result == "User updated successfully.") {
        $response = [
            'status' => 'success',
            'message' => 'User updated successfully.',
            'user_id' => $userId
        ];
    } else {
        $response = [
            'status' => 'error',
            'message' => 'UpdateFailed'
        ];
    }


    header('Location: ../Pages/user_management.php?success=true');
    exit;
}
