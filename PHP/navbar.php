<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); }
require_once("../PHP/database-handler.php");

$db_handler = new Database();
$setupStatus = $db_handler->checkSetup();

if (!$setupStatus) {
    die("Error setting up the database.");
}

$loggedin = isset($_SESSION["user_id"]);
$isStaff = $loggedin ? $db_handler->isUserStaff($_SESSION["user_id"]) : false;
$name = $loggedin ? $db_handler->getNameFromUserID($_SESSION["user_id"]) : "Guest";
?>

<!-- navbar.php -->
<nav class="navbar navbar-expand-lg bg-light" data-bs-theme="light">
    <div class="container">
        <a class="navbar-brand" href="../Pages/index.php" target="_self">
            <img src="../Images/SpaceTech.png" alt="SpaceTech" width="120" height="30">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
            aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <a class="nav-link" aria-current="page" href="../Pages/index.php" target="_self">Home</a>
                <a class="nav-link" aria-current="page" href="../Pages/products.php" target="_self">Shop</a>
                <a class="nav-link" aria-current="page" href="../Pages/contact.php" target="_self">Contact Us</a>
            </div>
            <div class="navbar-nav dropdown ms-auto">
                <a class="nav-link" href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                        class="bi bi-basket" viewBox="0 0 16 16">
                        <path
                            d="M5.757 1.071a.5.5 0 0 1 .172.686L3.383 6h9.234L10.07 1.757a.5.5 0 1 1 .858-.514L13.783 6H15a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1v4.5a2.5 2.5 0 0 1-2.5 2.5h-9A2.5 2.5 0 0 1 1 13.5V9a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h1.217L5.07 1.243a.5.5 0 0 1 .686-.172zM2 9v4.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V9zM1 7v1h14V7zm3 3a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 4 10m2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 6 10m2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 8 10m2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 1 .5-.5m2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 1 .5-.5" />
                    </svg>
                </a>

                <!-- User account dropdown -->
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                        class="bi bi-person" viewBox="0 0 16 16">
                        <path
                            d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z" />
                    </svg>
                </a>
                <ul class="dropdown-menu">
                    <?php if ($loggedin): ?>
                        <!-- Display for logged-in users -->
                        <?php if ($isStaff): ?>
                            <!-- Additional option for staff members -->
                            <li><a class="dropdown-item" href="admin.php">Staff Panel</a></li>
                        <?php endif; ?>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><span class="dropdown-item-text">
                                <?= htmlspecialchars($name) ?>
                            </span></li>
                    <?php else: ?>
                        <!-- Display for guests -->
                        <li><a class="dropdown-item" href="login.php">Log In</a></li>
                        <li><a class="dropdown-item" href="signup.php">Register</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</nav>