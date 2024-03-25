<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include "../PHP/database-handler.php";

// check if user already logged in
if (isset($_SESSION['user_id'])) {
    $error = "User already logged in.";
    header("Location: ..\Pages\index.php");
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Get the form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    $db_handler = new Database();
    $request = $db_handler->checkCredentials($email, $password);

    if (is_int($request)) {
        $_SESSION['user_id'] = $request; // set user id to session
        $_SESSION['loggedin'] = true; // set session variable to show user as logged in
        header("Location: index.php");
    } else {
        // Login failed, display error message
        $error = "Invalid email or password. Please check your credentials and try again.";
        $error_msg = htmlspecialchars($error, ENT_QUOTES);
    }

}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Your Custom CSS -->
    <link rel="stylesheet" href="../Styles/master-style.css">
    <link rel="stylesheet" href="../Styles/backgroundimage.css">
</head>

<body>

    <?php include_once("../PHP/navbar.php"); ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title text-center">Login Here</h3>
                        <form action="login.php" method="POST">
                            <?php if (isset($error_msg)): ?>
                                <div class="alert alert-danger">
                                    <p><?= htmlspecialchars($error_msg, ENT_QUOTES); ?>
                                </div>
                            <?php endif; ?>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" placeholder="Email Address" name="email"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" placeholder="Password" name="password"
                                    required>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Log In</button>
                            </div>

                            <div class="text-center mt-3">
                                <a href="../Pages/forgot_password.php" class="btn btn-outline-secondary btn-sm">Forgot Password?</a>
                                <a href="../Pages/signup.php" class="btn btn-outline-primary btn-sm">New User? Sign
                                    Up</a>
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