<?php
session_start();
require_once("..\PHP\database-handler.php");

$db_handler = new Database();
$setupStatus = $db_handler->checkSetup();

if (!$setupStatus) {
    die("Error setting up the database.");
}

if (!isset($_SESSION["user_id"]) || !$db_handler->isUserStaff($_SESSION["user_id"])) {
    header("Location: ../Pages/login.php");
    // redirect as no user id found in session or not staff
    exit;
}


$orders = $db_handler->getAllOrders();
$order_statuses = $db_handler->getOrderStatuses();



if(isset($_GET["search"])) {
    $filtered_orders = array();
    foreach($orders as $order) {
        if(str_starts_with(strtolower($order["user_name"]), strtolower($_GET["search"]))) {
            $filtered_orders[] = $order;
        }
    }

    $orders = $filtered_orders;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
        <!-- JQuery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../Styles/master-style.css">
</head>

<body>
    <!-- Navigation -->
    <?php include("../PHP/navbar.php") ?>
    <!-- Main -->
    <div class="container mt-5">
        <h2>Orders List</h2>
        <form method="GET">
            <input name="search" class="form-control" type="text" value='<?= isset($_GET["search"]) ? htmlspecialchars($_GET["search"], ENT_QUOTES) : "" ?>' placeholder="Search by customer name...">
            <button type="submit" class="btn btn-primary ml-2">Search</button>
        </form>
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Order ID</th>
                    <th scope="col">Customer Name</th>
                    <th scope="col">Status</th>
                    <th scope="col">Total</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                    <td>
                            <?= htmlspecialchars($order['order_id'], ENT_QUOTES); ?>
                        </td>
                        <td>
                            <?= htmlspecialchars($order['user_name'], ENT_QUOTES); ?>
                        </td>
                        <td><p style="color: <?= $order['status_colour']; ?>">
                            <?= htmlspecialchars($order['status_name'], ENT_QUOTES); ?>
                            </p>
                        </td>
                        <td>£
                            <?= htmlspecialchars($order['order_total'], ENT_QUOTES); ?>
                        </td>
                        <td>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#viewOrderModal"
                                    data-order-id="<?= htmlspecialchars($order["order_id"], ENT_QUOTES) ?>"
                                    data-user-id="<?= htmlspecialchars($order["user_id"], ENT_QUOTES) ?>"
                                    data-user-name="<?= htmlspecialchars($order["user_name"], ENT_QUOTES) ?>"
                                    data-user-email="<?= htmlspecialchars($order["user_email"], ENT_QUOTES) ?>"
                                    data-user-addr="<?= htmlspecialchars($order["order_address"], ENT_QUOTES) ?>"
                                    data-order-notes="<?= htmlspecialchars($order["order_comments"], ENT_QUOTES) ?>"
                                    data-order-status="<?= htmlspecialchars($order["status_id"], ENT_QUOTES) ?>"
                                    data-order-contents="<?= base64_encode($db_handler->getJsonOrderContents($order["order_id"])) ?>"
                                    data-order-total="<?= htmlspecialchars($order["order_total"], ENT_QUOTES)?>">
                                    View
                        </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- View Modal -->
    <div class="modal fade" id="viewOrderModal" tabindex="-1" aria-labelledby="viewOrderModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewOrderModalLabel">View Order #</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="../PHP/update_order.php" method="POST" class="row" id="updateOrder" enctype="multipart/form-data">
                    <!-- enctype added for file upload -->
                    <input type="hidden" id="order_id" name="order_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="updateCustomerNameEmail" class="form-label">Customer Name & Email</label>
                            <input type="text" class="form-control" id="updateCustomerNameEmail" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="updateCustomerAddr" class="form-label">Customer Address</label>
                            <textarea style="height: 5.5em; resize: none;" class="form-control" id="updateCustomerAddr" disabled></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="updateOrderTotal" class="form-label">Order Total (£)</label>
                            <input type="number" step="0.01" class="form-control" id="updateOrderTotal" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="updateOrderNotes" class="form-label">Order Comments</label>
                            <textarea style="height: 5.5em; resize: none;" class="form-control" id="updateOrderNotes" disabled></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="updateOrderStatus" class="form-label">Order Status</label>
                            <select class="form-control" id="updateOrderStatus" name="order_status">
                                <?php foreach($order_statuses as $status): ?>
                                <option value="<?= htmlspecialchars($status["status_id"]) ?>"><?= htmlspecialchars($status["status_name"]) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <table class="table" id="modalOrderItemsTbl">
                                <thead class="thead-dark">
                                    <th scope="col">Product Name</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Subtotal</th>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Order</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="../Scripts/orders.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>