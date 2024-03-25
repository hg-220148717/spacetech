<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once("../PHP/database-handler.php");

$db_handler = new Database();
$setupStatus = $db_handler->checkSetup();

if (!$setupStatus) {
    die("Error setting up the database.");
}

if (!isset($_SESSION["user_id"]) || !$db_handler->isUserStaff($_SESSION["user_id"])) {
    header("Location: ../Pages/index.php");
}

$general_stats = $db_handler->getRevenueStats();


$stock_report_data = array();

if(isset($_GET["stockLevelBelow"])) {

    $stock_report_data = $db_handler->getLowStockReport(intval($_GET["stockLevelBelow"]));
    
}

if(isset($_GET["stockLevelAbove"])) {

    $stock_report_data = $db_handler->getHighStockReport(intval($_GET["stockLevelAbove"]));
    
}

$order_report_data = array();

if(isset($_GET["incomingOrdersLastDays"])) {

    $order_report_data = $db_handler->getRecentOrders(intval($_GET["incomingOrdersLastDays"]));

}

if(isset($_GET["refundedOrdersLastDays"])) {

    $order_report_data = $db_handler->getRecentRefundedOrders(intval($_GET["refundedOrdersLastDays"]));

}



//$reports = $db_handler->getReportsData();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
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
    <!-- Navigation Bar -->
    <?php include '../PHP/navbar.php'; ?>

    <div class="container mt-5">
        <h2 class="mb-4">Reports</h2>
        <div id="alertPlaceholder"></div>
        <?php
        if (isset($_GET['error'])) {

        }
        ?>

        <form method="GET">
            <div class="form-group">
                <label for="stockLevelBelow">Show all products with a stock level lower than:</label>
                <input id="stockLevelBelow" min=0 <?= isset($_GET["stockLevelBelow"]) ? 'value="' . htmlspecialchars($_GET["stockLevelBelow"], ENT_QUOTES) . '"': ""?>type="number" name="stockLevelBelow" class="form-control">
            </div>
            <div class="form-group mt-2">
                <button class="btn btn-primary" type="submit">Generate</button>
            </div>
        </form>

        <form method="GET">
            <div class="form-group">
                <label for="stockLevelAbove">Show all products with a stock level greater than:</label>
                <input id="stockLevelAbove" min=0 <?= isset($_GET["stockLevelAbove"]) ? 'value="' . htmlspecialchars($_GET["stockLevelAbove"], ENT_QUOTES) . '"': ""?>type="number" name="stockLevelAbove" class="form-control">
            </div>
            <div class="form-group mt-2">
                <button class="btn btn-primary" type="submit">Generate</button>
            </div>
        </form>

        <form method="GET">
            <div class="form-group">
                <label for="incomingOrdersLastDays">Show incoming orders in the last ___ days:</label>
                <input id="incomingOrdersLastDays" <?= isset($_GET["incomingOrdersLastDays"]) ? 'value="' . htmlspecialchars($_GET["incomingOrdersLastDays"], ENT_QUOTES) . '"': ""?> type="number" min=1 name="incomingOrdersLastDays" class="form-control">
            </div>
            <div class="form-group mt-2">
                <button class="btn btn-primary" type="submit">Generate</button>
            </div>
        </form>

        <form method="GET">
            <div class="form-group">
                <label for="refundedOrdersLastDays">Show refunded orders in the last ___ days:</label>
                <input id="refundedOrdersLastDays" <?= isset($_GET["refundedOrdersLastDays"]) ? 'value="' . htmlspecialchars($_GET["refundedOrdersLastDays"], ENT_QUOTES) . '"': ""?> type="number" min=1 name="refundedOrdersLastDays" class="form-control">
            </div>
            <div class="form-group mt-2">
                <button class="btn btn-primary" type="submit">Generate</button>
            </div>
        </form>

        <a class="btn btn-secondary mt-2" href="../Pages/reports.php">Clear search</a>


        <?php if(count($stock_report_data) > 0): ?>
        <!-- Table to display reviews -->
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Product #</th>
                        <th scope="col">Product Name</th>
                        <th scope="col">Price</th>
                        <th scope="col">Enabled?</th>
                        <th scope="col">Stock Level</th>
                        <th scope="col">Category</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($stock_report_data as $product): ?>
                <tr id="product-row-<?= $product['product_id']; ?>">
                            <th scope="row">
                                <?php echo $product["product_id"]; ?>
                            </th>
                            <td>
                                <?= htmlspecialchars($product["product_name"], ENT_QUOTES); ?>
                            </td>
                            <td>
                                £<?= htmlspecialchars($db_handler->getProductPriceById($product["product_id"]), ENT_QUOTES); ?>
                            </td>
                            <td>
                                <?php if (!$product["product_isdisabled"]): ?>
                                    <i class="fas fa-check-circle"></i>
                                <?php else: ?>
                                    <i class="fas fa-times-circle"></i>
                                <?php endif; ?>
                            </td>
                            <td>
                                    <?= htmlspecialchars($db_handler->getStockLevelOfItem($product["product_id"]), ENT_QUOTES); ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($db_handler->getCategoryNameByProductId($product["product_id"]), ENT_QUOTES); ?>
                            </td>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php elseif(count($order_report_data) > 0): ?>
            <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Order #</th>
                        <th scope="col">Customer Name</th>
                        <th scope="col">Status</th>
                        <th scope="col">Total</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($order_report_data as $order): ?>
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
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
            <h4 class="mt-3" >Total Sales: </h4>
            <p>£<?= htmlspecialchars($general_stats["revenue"], ENT_QUOTES); ?></p>
            <h4>Total Refunds: </h4>
            <p>-£<?= htmlspecialchars($general_stats["refunds"], ENT_QUOTES); ?></p>
        <?php endif; ?>
        
        <div class="pt-5"></div>
        <!-- Modal JS -->
        <script src="../Scripts/category.js"></script>
        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>