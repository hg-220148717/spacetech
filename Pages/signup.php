<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include "../PHP/database-handler.php";
$db_handler = new Database(); // setup database handler

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user input
    $email = $_POST["email"];
    $password = $_POST["password"];
    $name = $_POST["fname"] . " " . $_POST["sname"];

    // Validate password requirements
    if (!preg_match("/^(?=.*[A-Z])(?=.*[0-9])(?=.*[^a-zA-Z0-9]).{8,}$/", $password)) {
        // Password does not meet the requirements
        $message = "Your password must contain at least one uppercase letter, one number, and one special character, and be at least 8 characters long.";
    } else {

    if ($db_handler->createUser($email, $password, $name) == "User account created successfully.") {
        // Redirect to a Home Page, login successful

        // may want to autologin/redirect to login page in future?

        header("Location: ../Pages/login.php");

        exit();
    } else {
        $message = "An account with that email address already exists. Please log in.";
    }
}

}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" href="../Styles/master-style.css">
    <link rel="stylesheet" href="../Styles/backgroundimage.css">
</head>

<body>
    <?php include_once("../PHP/navbar.php"); ?>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mb-5">
                    <div class="card-body">
                        <h3 class="card-title text-center">Sign Up</h3>

                        <?php if(isset($message)): ?>
                        <div class="alert alert-danger">
                            <p><?= htmlspecialchars($message, ENT_QUOTES); ?>
                        </div>
                        <?php endif; ?>

                        <form action="../Pages/signup.php" method="POST">
                            <div class="mb-3">
                                <label for="fname" class="form-label">First Name</label>
                                <input type="text" class="form-control" name="fname" placeholder="First Name" required>
                            </div>

                            <div class="mb-3">
                                <label for="sname" class="form-label">Last Name</label>
                                <input type="text" class="form-control" name="sname" placeholder="Last Name" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" placeholder="Email" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" placeholder="Password"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label for="confirmPassword" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" name="confirmPassword"
                                    placeholder="Confirm Password" required>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Sign Up</button>
                                <a href="../Pages/login.php" class="btn btn-outline-secondary">Already signed up? Login</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>