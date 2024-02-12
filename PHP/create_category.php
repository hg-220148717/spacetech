<?php

session_start();
include_once("../PHP/database-handler.php");

$db_handler = new Database();
$db_handler->testDatabaseConnection();
$db_handler->checkSetup();

if (!isset($_SESSION["user_id"]) || !$db_handler->isUserStaff($_SESSION["user_id"])) {
    header("Location: ../Pages/index.php");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $is_disabled = isset($_POST['is_disabled']) ? (bool)$_POST['is_disabled'] : false;

    // Check if category name exists???
    $result = $db_handler->createCategory($name, $is_disabled, $name);
    header("Location: ../Pages/category_management.php?success=true");
}
