<?php

session_start();

include_once "../PHP/database-handler.php";

if(!isset($_SESSION["user_id"])) {
    // user not logged in, return to login page
    header("Location: ../Pages/login.php");
    exit;
}

if(!$db_handler->isUserAdmin($_SESSION["user_id"])) {
    // invalid perms
    header("Location: ../Pages/user_management.php?error=invalid_permissions");
    exit;
}

if($_SERVER["REQUEST_METHOD"] !== "POST") {
    // invalid request method
    header("Location: ../Pages/user_management.php?error=invalid_request_method");
    exit;
}

if(!isset($_POST["name"]) || !isset($_POST["email"]) || !isset($_POST["password"])) {
    // not all params supplied
    header("Location: ../Pages/user_management.php?error=invalid_parameters");
    exit;
}

$name = trim($_POST["name"]);
$email = $_POST["email"];
$password = $_POST["password"];

$db_handler->createUser($email, $password, $name);
header("Location: ../Pages/user_management.php?success=true");
exit;

?>