<?php

error_reporting(E_ERROR | E_PARSE);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include "../PHP/database-handler.php";

function sendPasswordReset($email) {

    $db_handler = new Database();

    $result = $db_handler->storeAndSendPassResetToken($email);

    if(!$result) {
        // error occurred sending & storing reset token, did not succeed. return false.
        return false;
    } else {
        // reset token stored & sent successfully
        return true;
    }
}

// check if user already logged in
if (isset($_SESSION['user_id'])) {
    $error = "User already logged in.";
    header("Location: ..\Pages\index.php");
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Get the form data
    $email = $_POST['email'];
    $resetSent = sendPasswordReset($email);
    $message = $resetSent ? "If the email address you have supplied is valid, a reset has been sent. Please check your emails and follow the instructions provided." : "An error occurred. Please try again later.";
        

}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Your Custom CSS -->
    <link rel="stylesheet" href="../Styles/backgroundimage.css">
</head>

<body>

    <?php include_once("../PHP/navbar.php"); ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title text-center">Forgot password?</h3>
                        <p>Enter your email below to receive a password reset link.</p>
                        <form action="forgot_password.php" method="POST">
                            <?php if (isset($message))
                                echo $message; ?>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" placeholder="Email Address" name="email"
                                    required>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Request Reset</button>
                            </div>

                            <div class="text-center mt-3">
                                <a href="../Pages/login.php" class="btn btn-outline-secondary btn-sm">Log In</a>
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