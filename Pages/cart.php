<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../Pages/login.php");
    // redirect as no user id found in session, user is not logged i
}

include("../PHP/database-handler.php");
$db_handler = new Database();
$db_handler->checkSetup();

$email = $db_handler->getEmailFromUserID($_SESSION["user_id"]);
$name = $db_handler->getNameFromUserID($_SESSION["user_id"]);

$basket_count = $db_handler->getBasketCount($_SESSION["user_id"]);
$basket_total = $db_handler->getBasketTotal($_SESSION["user_id"]);

if ($basket_total == null)
    $basket_total = 0.00;

$basket_contents = $db_handler->getBasketContents($_SESSION["user_id"]);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../Styles/master-style.css">
</head>

<body>

    <!-- Navbar -->
    <?php include_once("../PHP/navbar.php"); ?>

    <!-- Checkout -->
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-7">
                <h2>Checkout</h2>
                <form class="row g-3 needs-validation" novalidate>
                    <!-- Full Name/Username -->
                    <div class="col-md-6">
                        <label for="fullName" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="fullName" name="name" placeholder="John Doe"
                            value="<?= htmlspecialchars($name, ENT_QUOTES); ?>" disabled required>
                    </div>
                    <!-- Email -->
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="johndoe@gmail.com"
                            value="<?= htmlspecialchars($email, ENT_QUOTES); ?>" disabled required>
                    </div>
                    <!-- Address -->
                    <div class="col-12">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control" id="address" name="address_line1"
                            placeholder="1234 Main St" required>
                        <div class="invalid-feedback">
                            Please provide an address.
                        </div>
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                    </div>
                    <!-- City -->
                    <div class="col-md-6">
                        <label for="city" class="form-label">City</label>
                        <input type="text" class="form-control" id="city" name="address_line2" required>
                        <div class="invalid-feedback">
                            Please provide a valid city.
                        </div>
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                    </div>
                    <!-- Postcode -->
                    <div class="col-md-6">
                        <label for="postcode" class="form-label">Postcode</label>
                        <input type="text" class="form-control" id="postcode" pattern="^\s*?\d{5}(?:[-\s]\d{4})?\s*?$" name="address_line3" required>
                        <div class="invalid-feedback">
                            Please provide a valid postcode.
                        </div>
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                    </div>
                    <!-- Card Number -->
                    <div class="col-md-6">
                        <label for="cardNumber" class="form-label">Card Number</label>
                        <input type="text" class="form-control" id="cardNumber" name="card_no"
                            placeholder="0000 0000 0000 0000" required>
                        <div class="invalid-feedback">
                            Please provide a valid card number.
                        </div>
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                    </div>
                    <!-- Expiry Date -->
                    <div class="col-md-3">
                        <label for="expiryDate" class="form-label">Expiry Date</label>
                        <input type="text" class="form-control" id="expiryDate" name="card_expiry" placeholder="MM/YY"
                            required>
                        <div class="invalid-feedback">
                            Please provide a valid expiry date.
                        </div>
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                    </div>
                    <!-- CVV -->
                    <div class="col-md-3">
                        <label for="cvv" class="form-label">CVV</label>
                        <input type="text" class="form-control" id="cvv" name="card_cvv" placeholder="123" required>
                        <div class="invalid-feedback">
                            Please provide a valid CVV.
                        </div>
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                    </div>
                    <!-- Cardholder -->
                    <div class="col-12">
                        <label for="cardName" class="form-label">Cardholder Name</label>
                        <input type="text" class="form-control" id="cardName" name="card_name" placeholder="John Doe"
                            required>
                        <div class="invalid-feedback">
                            Please provide a valid cardholder name.
                        </div>
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Checkout</button>
                </form>
            </div>

            <!-- Shopping Cart Summary -->
            <div class="col-md-5">
                <h3 class="mb-3">Shopping Cart</h3>
                <?php if ($basket_count > 0): ?>
                    <div class="list-group mb-3">
                        <?php foreach ($basket_contents as $item): ?>
                            <?php $product = $db_handler->getProductByID($item["product_id"]); ?>
                            <div class="list-group-item d-flex justify-content-between lh-sm">
                                <div>
                                    <h6 class="my-0">
                                        <?= htmlspecialchars($product["product_name"], ENT_QUOTES); ?>
                                    </h6>
                                    <small class="text-muted">Quantity:
                                        <?= htmlspecialchars($item["qty"], ENT_QUOTES); ?>
                                    </small>
                                </div>
                                <span class="text-muted">£
                                    <?= htmlspecialchars($item["subtotal"], ENT_QUOTES); ?>
                                </span>
                                <form action="remove_from_cart.php" method="POST" class="d-inline">
                                    <input type="hidden" name="entry_id"
                                        value="<?= htmlspecialchars($item["entry_id"], ENT_QUOTES); ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <?php if (isset($_GET["success"])): ?>
                        <div class="alert alert-success" role="alert">
                            Order submitted.
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning" role="alert">
                            Your basket is empty!
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <h3 class="mb-3">Summary</h3>
                <ul class="list-group mb-3">
                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                        <div>
                            <h6 class="my-0">Products</h6>
                        </div>
                        <span>
                            <?= htmlspecialchars($basket_count, ENT_QUOTES); ?>
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                        <div>
                            <h6 class="my-0">Shipping</h6>
                        </div>
                        <span>FREE</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Total (GBP)</span>
                        <strong>£
                            <?= htmlspecialchars($basket_total, ENT_QUOTES); ?>
                        </strong>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <?php include_once("../PHP/footer.php"); ?>

    <script src="../Scripts/check.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>