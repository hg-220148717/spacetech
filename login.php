<?php

include "database-handler.php";

session_start(); // Start the session

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {


// check if user already logged in
if(isset($_SESSION['loggedin'])) {
    $error = "User already logged in.";
    echo "<div style='color:red'>" . htmlspecialchars($error, ENT_QUOTES) . "</div>";
}


    // Get the form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    $db_handler = new Database();
    $request = $db_handler->checkCredentials($username, $password);

    if(is_int($request)) {
        $_SESSION['user_id'] = $request; // set user id to session
        $_SESSION['loggedin'] = true; // set session variable to show user as logged in
        header("Location: index.html");
    } else {
        // Login failed, display error message
        $error = "Invalid username or password";
        echo "<div style='color:red'>" . htmlspecialchars($error, ENT_QUOTES) . "</div>";
    }

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login | SpaceTech</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>

<?php include_once("header.php"); ?>

    <div class="background">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    <form>
        <h>SpaceTech</h>
        <h3>Login Here</h3>

        <label for="email">Email</label>
        <input type="email" placeholder="Email Address" id="email">

        <label for="password">Password</label>
        <input type="password" placeholder="Password" id="password">

        <button>Log In</button>
        <div class="social">
          <div class="fp"><a href="fp.html"> Forgot Password?</a></div>
          <div class="fp"><a href="signup.html"> New User?Sign Up</a></div>
        </div>
    </form>
</body>
</html>
