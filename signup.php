<?php

include "database-handler.php";
$db_handler = new Database(); // setup database handler

session_start(); // Start the session

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user input
    $email = $_POST["email"];
    $password = $_POST["password"];
    $name = $_POST["fname"] . " " . $POST["sname"];

    // Validate password requirements
    if (!preg_match("/^(?=.*[A-Z])(?=.*[0-9])(?=.*[^a-zA-Z0-9]).{8,}$/", $password)) {
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
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Sign Up | SpaceTech</title>
    <link rel="stylesheet" href="signup.css">
</head>
<body>
    <div class="background">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    <form>
        <h3>Join us to get some Amazing deals!</h3>

        <label for="First Name">First Name</label>
        <input type="text" id="fname" placeholder="First Name">

        <label for="Last Name">Last Name</label>
        <input type="Last Name" id="sname" placeholder="Last Name">

        <label for="Email">Email</label>
        <input type="Email" placeholder="Email">
        <label for="Password">Password</label>
        <input type="Password" placeholder="Password">
        <label for="Confirm Password"> Confirm Password</label>
        <input type="Password" placeholder="Confirm Password">
        <button>Sign Up</button>
        
    </form>
    <?php include_once("footer.php"); ?>
</body>
</html>
