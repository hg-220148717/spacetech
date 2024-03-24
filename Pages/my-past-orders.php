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
                                <a class="text-dark" href="#">Order # <?= htmlspecialchars($order["order_id"], ENT_QUOTES) ?></a>
                            </h3>
                            <div class="mb-1 text-muted"><?= date_format(date_create($order["order_creation"]), "jS F Y H:i") ?></div>
                                <p class="card-text mb-auto">Total: £<?= htmlspecialchars($order["order_total"], ENT_QUOTES) ?></p>
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

    <?php include_once("../PHP/footer.php"); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>