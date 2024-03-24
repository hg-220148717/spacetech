<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include "../PHP/database-handler.php";

// check if user already logged in
if (isset($_SESSION['user_id'])) {
    $error = "User already logged in.";
    header("Location: ..\Pages\index.php?error=alreadyloggedin");
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if(isset($_GET["token"])) {

        // Get the form data
        $reset_token = $_GET['token'];

        $db_handler = new Database();
        $isValid = $db_handler->validateResetToken($reset_token);
        if($isValid === false) {
            // token invalid
            header("Location: ..\Pages\index.php?error=invalidtoken");
        }
        
    } else {
        // token not provided
        header("Location: ..\Pages\index.php?error=tokennotfound");
    }

} else if($_SERVER["REQUEST_METHOD"] == "POST") {
    if(!isset($_POST["reset_token"]) && !isset($_POST["password"])) {
        // no reset token or pass entered, something wen't wrong
        header("Location: ../Pages/forgot_password.php");
    }

    $reset_token = $_POST['reset_token'];
    $password = $_POST['password'];

    $db_handler = new Database();
    $isValid = $db_handler->validateResetToken($reset_token);

    if($isValid === false) {
        // token invalid
        header("Location: ..\Pages\index.php?error=invalidtoken");
        return;
    }

    $user_id = $db_handler->getUserIDFromResetToken($reset_token);
    if($user_id === -1) {
        // invalid user ID
        header("Location: ..\Pages\index.php?error=invaliduserid");
        return;
    }

    $reset_success = $db_handler->setPassword($user_id, $password);

    if($reset_success === true) {
        header("Location: ../Pages/login.php");
        exit;
    } else {
        echo $reset_success;
    }

}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
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
                        <h3 class="card-title text-center">Reset Password</h3>
                        <form action="reset.php" method="POST">
                            <?php if (isset($error_msg))
                                echo $error_msg; ?>

                                <input hidden name="reset_token" value="<?= $reset_token; ?>">

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" placeholder="Password" name="password"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label for="password_confirm" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" placeholder="Password" name="password_confirm"
                                    required>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Reset</button>
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