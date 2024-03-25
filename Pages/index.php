<?php
include_once("../PHP/database-handler.php");
$db = new Database();

$categories_list = $db->getAllCategories(false);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SpaceTech</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../Styles/master-style.css">
</head>

<body>
    <!-- Navigation Bar -->
    <?php include '../PHP/navbar.php'; ?>

    <!-- Home Section -->
    <section class="home vh-100 d-flex align-items-center" style="background: linear-gradient(rgba(4,9,30, 0.7), rgba(4,9,30, 0.7)), url('../images/background.png') no-repeat center center; background-size: cover;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-white">
                    <h1>Welcome to SPACETECH</h1>
                    <p class="lead">Where technology meets commerce! Explore the virtual universe of SPACETECH and embark on a cosmic adventure of invention.</p>
                    <a href="../Pages/products.php" class="btn btn-primary">Shop Now</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="categories py-5" id="shop">
        <div class="container">
            <h2 class="heading mb-4 text-center">Explore our products</h2>

            <div class="row justify-content-center">
                <?php foreach($categories_list as $category): ?>
                    <div class="col-3 justify-content-center category-container" onclick="window.location='products.php?category=<?= htmlspecialchars($category["category_id"], ENT_QUOTES) ?>'"style="background-image: url(<?= $category['category_image'] ?>)">
                        <h3 class="category-title text-center"><?= htmlspecialchars($category["category_name"], ENT_QUOTES) ?></h3>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about py-5" id="about">
        <div class="container">
            <h2 class="heading mb-4"> <span>About</span> Us </h2>
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="video-container position-relative">
                        <video src="../images/about-video.mp4" loop autoplay muted class="w-100 rounded-3"></video>
                        <div class="overlay d-flex justify-content-center align-items-center position-absolute w-100 h-100">
                            <h3 class="text-white">Best E-Commerce</h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 d-flex flex-column justify-content-center">
                    <h3>Why choose us?</h3>
                    <p>At Spacetech, our mission is to revolutionize the accessibility of cutting-edge electronics. Explore, select, and purchase the latest in electronic innovation.</p>
                    <a href="../Pages/about.php" class="btn btn-outline-primary mt-3">Learn More</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Icon Section -->
    <section class="icons-container py-5 bg-light">
        <div class="container">
            <div class="row text-center">
                <div class="col-6 col-md-3 mb-4">
                    <img src="../images/icon-1.jpg" alt="" class="mb-2" style="width: 80px;">
                    <h3>Free Delivery</h3>
                    <span>On all orders</span>
                </div>
                <div class="col-6 col-md-3 mb-4">
                    <img src="../images/icon-2.png" alt="" class="mb-2" style="width: 80px;">
                    <h3>10 Days Returns</h3>
                    <span>Moneyback guarantee</span>
                </div>
                <div class="col-6 col-md-3 mb-4">
                    <img src="../images/icon-3.png" alt="" class="mb-2" style="width: 80px;">
                    <h3>Offer & Gifts</h3>
                    <span>On all orders</span>
                </div>
                <div class="col-6 col-md-3 mb-4">
                    <img src="../images/icon-4.png" alt="" class="mb-2" style="width: 80px;">
                    <h3>Secure Payments</h3>
                    <span>Protected by PayPal</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include_once("../PHP/footer.php"); ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
