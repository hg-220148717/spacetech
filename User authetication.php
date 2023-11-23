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
