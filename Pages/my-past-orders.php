<?php

session_start();

// check if user is logged in
if(!isset($_SESSION["user_id"])) {
    header("Location: ../Pages/login.php");
    exit;
}

include_once("../PHP/database-handler.php");
$db_handler = new Database();

$order_history = $db_handler->getOrdersByUser($_SESSION["user_id"]);


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Past Orders</title>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../Styles/master-style.css">
    <!-- JQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <?php include_once("../PHP/navbar.php"); ?>

    <!-- Checkout -->
    <div class="container mt-5">
        <div class="content col">
            <div class="col-md-12 mb-5">
                <h2>My Past Orders</h2>
                <h4>View your past orders and make refund requests.</h4>
                <?php if(isset($_GET["error"])): ?>
                    <div class="alert alert-danger">
                        <p>An error occurred updating your details. Please try again.</p>
                    </div>
                <?php elseif(isset($_GET["success"])): ?>
                    <div class="alert alert-success">
                        <p>Your details have been updated successfully.</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- order history -->
            <?php if(!empty($order_history)): ?>
                <?php foreach($order_history as $order): ?>
                    <div class="col-md-12">
                        <div class="card flex-md-row mb-4 box-shadow h-md-250">
                            <div class="card-body d-flex flex-column align-items-start">
                            <strong class="d-inline-block mb-2" style="color: <?= htmlspecialchars($order["status_colour"], ENT_QUOTES); ?>"><?= htmlspecialchars($order["status_name"], ENT_QUOTES) ?></strong>
                            <h3 class="mb-0">
                                <span class="text-dark">Order # <?= htmlspecialchars($order["order_id"], ENT_QUOTES) ?></span>
                            </h3>
                            <div class="mb-1 text-muted"><?= date_format(date_create($order["order_creation"]), "jS F Y H:i") ?></div>
                                <p class="card-text mb-auto"><b>Total:</b> £<?= htmlspecialchars($order["order_total"], ENT_QUOTES) ?></p>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#viewOrderModal"
                                    data-order-id="<?= htmlspecialchars($order["order_id"], ENT_QUOTES) ?>"
                                    data-user-id="<?= htmlspecialchars($order["user_id"], ENT_QUOTES) ?>"
                                    data-user-addr="<?= htmlspecialchars($order["order_address"], ENT_QUOTES) ?>"
                                    data-order-notes="<?= htmlspecialchars($order["order_comments"], ENT_QUOTES) ?>"
                                    data-order-status="<?= htmlspecialchars($order["status_name"], ENT_QUOTES) ?>"
                                    data-order-contents="<?= base64_encode($db_handler->getJsonOrderContents($order["order_id"])) ?>"
                                    data-order-total="<?= htmlspecialchars($order["order_total"], ENT_QUOTES)?>">
                                    View Order
                            </button>
                            <?php if($order["status_id"] == 5 || $order["status_id"] == 6):?>
                                <?php else: ?>
                            <button class="btn btn-warning mt-1 btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#requestRefundModal"
                                    data-order-id="<?= htmlspecialchars($order["order_id"], ENT_QUOTES) ?>"
                                    data-user-id="<?= htmlspecialchars($order["user_id"], ENT_QUOTES) ?>"
                                    data-user-addr="<?= htmlspecialchars($order["order_address"], ENT_QUOTES) ?>"
                                    data-order-notes="<?= htmlspecialchars($order["order_comments"], ENT_QUOTES) ?>"
                                    data-order-status="<?= htmlspecialchars($order["status_name"], ENT_QUOTES) ?>"
                                    data-order-contents="<?= base64_encode($db_handler->getJsonOrderContents($order["order_id"])) ?>"
                                    data-order-total="<?= htmlspecialchars($order["order_total"], ENT_QUOTES)?>">
                                    Request Refund
                            </button>
                            <?php endif;?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <h4>No orders yet. Shop now!</h4>
                <a href="../Pages/products.php" class="btn btn-primary">Shop now</a>
            <?php endif; ?>
            </div>
        </div>
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
                    <!-- enctype added for file upload -->
                    <input type="hidden" id="order_id" name="order_id">
                    <div class="modal-body">
                    <div class="mb-3">
                            <label for="updateCustomerAddr" class="form-label">Shipping Address</label>
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
                            <input class="form-control" id="updateOrderStatus" value="" disabled>
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
                    </div>
            </div>
        </div>
    </div>

    <!-- Request Refund Modal -->
    <div class="modal fade" id="requestRefundModal" tabindex="-1" aria-labelledby="requestRefundModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="requestRefundModalLabel">Request Refund for Order #</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                    <form method="POST" action="../PHP/request_refund.php">
                    <input type="hidden" id="refundOrderId" name="order_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <h5>Are you sure you want to request a refund for this order?</h5>
                        </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning" data-bs-dismiss="modal">Request Refund</button>
                    </div>
                    </form>
            </div>
            </div>
        </div>
    </div>

    <?php include_once("../PHP/footer.php"); ?>

    <script src="../Scripts/my-past-orders.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>