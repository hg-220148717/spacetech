<?php

include "database-handler.php";
$db_handler = new Database(); // setup database handler

session_start(); // Start the session

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user input
    $email = $_POST["email"];
    $password = $_POST["password"];
    $name = $_POST["name"];

    // Validate password requirements
    if (!preg_match("/^(?=.*[A-Z])(?=.*[0-9])(?=.*[^a-zA-Z0-9]).{8,}$/", $rawPassword)) {
        // Password does not meet the requirements
        echo "Password must contain at least one uppercase letter, one number, and one special character, and be at least 8 characters long.";
        exit();
    }

    if($db_handler->createUser($email, $password, $name) == "User account created successfully.") {
        // Redirect to a Home Page, login successful

        // may want to autologin/redirect to login page in future?
        header("Location: index.html");
        exit();
    } else {
        echo "User already exists. Please log in.";
        exit();
    }

}

?>
