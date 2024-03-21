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
    $productId = intval($_POST["product_id"]);
    $newName = trim($_POST["product_name"]);

    $response = [];
    $productDetails = $db_handler->getProductById($productId);
    if (!is_array($productDetails)) {
        $response = [
            'status' => 'error',
            'message' => 'InvalidProduct' 
        ];
    } else {
        $previousName = $productDetails['product_name'];
        $previousImagePath = $productDetails['product_image'];
        $target_dir = "../images/products/";
        $target_file = $target_dir . $productId . pathinfo(basename($_FILES["categoryImage"]["name"], PATHINFO_EXTENSION));
        $uploadOk = 0;

        if (isset($_FILES['categoryImage']) && $_FILES['categoryImage']['error'] == UPLOAD_ERR_OK) {
            $target_dir . $productId . pathinfo(basename($_FILES["categoryImage"]["name"], PATHINFO_EXTENSION));
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            if (file_exists($target_file)) {
                $uploadOk = 0;
                $response = [
                    'status' => 'error',
                    'message' => 'FileExists'
                ];
            }

            if ($_FILES["categoryImage"]["size"] > 2000000) {
                $uploadOk = 0;
                $response = [
                    'status' => 'error',
                    'message' => 'SizeTooLarge'
                ];
            }

            $check = getimagesize($_FILES["categoryImage"]["tmp_name"]);
            if ($check == false) {
                $uploadOk = 0;
                $response = [
                    'status' => 'error',
                    'message' => 'NotImage'
                ];
            }

            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                $uploadOk = 0;
                $response = [
                    'status' => 'error',
                    'message' => 'NotValidFormat'
                ];
            }

            if (move_uploaded_file($_FILES["categoryImage"]["tmp_name"], $target_file)) {
                $uploadOk = 1;
            }
        }

        if ($db_handler->categoryExists($newName)) {
            $response = [
                'status' => 'error',
                'message' => 'NameExists'
            ];
        } else {
            $result = $db_handler->editProduct($categoryId, (isset($newName) ? $newName : $previousName), (isset($target_file) ? $target_file : $previousImagePath));
            if ($result == "Product updated successfully.") {
                $response = [
                    'status' => 'success',
                    'message' => 'Product updated successfully.',
                    'categoryId' => $categoryId,
                    'newName' => $newName
                ];
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'UpdateFailed'
                ];
            }
        }
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
