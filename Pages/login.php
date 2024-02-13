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
        $error = "Invalid email or password";
        $error_msg = "<div style='color:red'>" . htmlspecialchars($error, ENT_QUOTES) . "</div>";
    }

}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login</title>
    <link rel="stylesheet" href="../Styles/login.css">
    <link rel="stylesheet" href="../Styles/master-style.css">
</head>

<body>

    <?php include_once("../PHP/header.php"); ?>

    <div class="background">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    <form action="login.php" method="POST">
        <h>Login Here</h>

        <label for="email">Email</label>
        <input type="email" placeholder="Email Address" name="email">

        <?php if (isset($error_msg))
            echo $error_msg; ?>

        <label for="password">Password</label>
        <input type="password" placeholder="Password" name="password">

        <button type="submit">Log In</button>
        <div class="social">
            <div class="fp"><a href="fp.html"> Forgot Password?</a></div>
            <div class="fp"><a href="../Pages/signup.php"> New User?Sign Up</a></div>
        </div>
    </form>
</body>

</html>