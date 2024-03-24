<?php

session_start();

include_once "../PHP/database-handler.php";

// check if user logged in
if(!isset($_SESSION["user_id"])) {
    header("Location: ../Pages/login.php");
    exit;
}

if($_SERVER["REQUEST_METHOD"] === "POST") {

    if(isset($_POST["currentPass"]) && isset($_POST["newPass"]) && isset($_POST["confirmNewPass"])) {

        $user_id = $_SESSION["user_id"];
        $current_pass = $_POST["currentPass"];
        $new_pass = $_POST["newPass"];
        $confirm_new_pass = $_POST["confirmNewPass"];

        if($new_pass !== $confirm_new_pass) {
            // desired new password doesnt match confirmation entry, redirect back
            header("Location: ../Pages/my-profile.php?error=passwords_do_not_match");
            exit;
        }

        $db_handler = new Database();

        if($db_handler->checkCredentials($db_handler->getEmailFromUserID($user_id), $current_pass) != $user_id) {
            // incorrect current password
            header("Location: ../Pages/my-profile.php?error=incorrect_password");
            exit;
        }

        $db_handler->changePassword($user_id, $new_pass);
        header("Location: ../Pages/my-profile.php?success=true");

    } else {
        // not all fields submitted, attempted bypass of client side input validation, redirect back
        header("Location: ../Pages/my-profile.php?error=validation_error");
        exit;  
    }

} else {
    // deny get request, redirect back to profile page
    header("Location: ../Pages/my-profile.php?error=invalid_request_type");
    exit;
}