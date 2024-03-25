<?php

session_start();

include_once "../PHP/database-handler.php";

// check if user logged in
if(!isset($_SESSION["user_id"])) {
    header("Location: ../Pages/login.php");
    exit;
}

if($_SERVER["REQUEST_METHOD"] !== "POST") {
    // invalid request method
    header("Location: ../Pages/my-past-orders.php?error=invalid_request_method");
    exit;
}

if(!isset($_POST["order_id"])) {
    // no order id, reject request
    header("Location: ../Pages/my-past-orders.php?error=invalid_parameters");
    exit;
}

$db_handler = new Database();
$db_handler->updateOrder($_POST["order_id"], 5, false);

// update order status to "refund requested", leave the order as paid until refund authorised

header("Location: ../Pages/my-past-orders.php?success=true");
exit;

?>