<?php

session_start();
require_once("../PHP/database-handler.php");

$db_handler = new Database();
$db_handler->testDatabaseConnection();
$db_handler->checkSetup();

// Check if the user is logged in and is staff
if (!isset($_SESSION["user_id"]) || !$db_handler->isUserStaff($_SESSION["user_id"])) {
    header("Location: ../Pages/index.php");
    exit; // Ensure no further execution happens after a redirect
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']); // Trim whitespace
    $is_disabled = isset($_POST['is_disabled']) ? (bool) $_POST['is_disabled'] : false;

    if (empty($name)) {
        header("Location: ../Pages/category_management.php?error=emptyName");
        exit;
    }

    if ($db_handler->categoryExists($name)) {
        header("Location: ../Pages/category_management.php?error=nameExists");
        exit;
    }

<<<<<<< Updated upstream
    // Attempt to create the category
    $result = $db_handler->createCategory($name, $is_disabled, $name); 
    
    // Check the result of the creation attempt
    echo $result;
    if ($result) {
        header("Location: ../Pages/category_management.php?success=true");
    } else {
       // header("Location: ../Pages/category_management.php?error=creationfailed");
=======

    $target_dir = "../images/categories/";
    $target_file = $target_dir . basename($_FILES["categoryImage"]["name"]);
    $uploadOk = 0;
    if (isset($_FILES['categoryImage']) && $_FILES['categoryImage']['error'] == UPLOAD_ERR_OK) {

        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if (file_exists($target_file)) {
            $uploadOk = 0;
            header("Location: ../Pages/category_management.php?error=fileExists");
            exit;
        }

        if ($_FILES["categoryImage"]["size"] > 2000000) {
            $uploadOk = 0;
            header("Location: ../Pages/category_management.php?error=largeSize");
            exit;
        }

        $check = getimagesize($_FILES["categoryImage"]["tmp_name"]);
        if ($check == false) {
            $uploadOk = 0;
            header("Location: ../Pages/category_management.php?error=notImage");
            exit;
        }

        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            $uploadOk = 0;
            header("Location: ../Pages/category_management.php?error=invalidType");
            exit;
        }

        if (move_uploaded_file($_FILES["categoryImage"]["tmp_name"], $target_file)) {
            $uploadOk = 1;
        }
>>>>>>> Stashed changes
    }

    if ($uploadOk == 1) {
        $result = $db_handler->createCategory($name, $is_disabled, $target_file);
        if ($result) {
            header("Location: ../Pages/category_management.php?success=true");
        } else {
            header("Location: ../Pages/category_management.php?error=creationfailed");
        }
        exit;
    } else {
        header("Location: ../Pages/category_management.php?error=uploadFailed");
    }
}