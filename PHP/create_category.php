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
    $name = trim($_POST['name']); // Trim whitespace
    $is_disabled = isset($_POST['is_disabled']) ? (bool)$_POST['is_disabled'] : false;

    // Validate category name (e.g., ensure it's not empty)
    if (empty($name)) {
        // Redirect with an error parameter
        header("Location: ../Pages/category_management.php?error=emptyname");
        exit;
    }

    // Check if category name already exists
    if ($db_handler->categoryExists($name)) {
        header("Location: ../Pages/category_management.php?error=nameexists");
        exit;
    }

    // Attempt to create the category
    $result = $db_handler->createCategory($name, $is_disabled, $name); 
    
    if ($result) {
        header("Location: ../Pages/category_management.php?success=true");
    } else {
       // header("Location: ../Pages/category_management.php?error=creationfailed");
    }
    exit;
}
