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
    $order_id = intval($_POST["order_id"]);
    $newStatus = trim($_POST["order_status"]);

    $isRefund = false;

    // if refunded, set order status to not paid for reporting purposes
    if($newStatus == 6) {
        $isRefund = true;
    }

    $db_handler->updateOrder($order_id, $newStatus, $isRefund);

    header("Location: ../Pages/orders.php");
    exit;
}
