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
    $newDesc = trim($_POST["description"]);
    $newStock = trim($_POST["stock"]);
    $newPrice = trim($_POST["price"]);
    $newCategoryID = trim($_POST["category"]);

    $response = [];
    $productDetails = $db_handler->getProductById($productId);
    if (!is_array($productDetails)) {
        $response = [
            'status' => 'error',
            'message' => 'InvalidProduct' 
        ];
    } else {
        $previousName = $productDetails['product_name'];
        $previousImagePath = strval($productDetails['product_id']) . ".jpg";
        $target_dir = "../images/products/";
        $target_file = $target_dir . strval($productDetails['product_id']) . ".jpg";
        $uploadOk = 0;
        $previousCategoryID = $productDetails["category_id"];
        $previousDesc = $productDetails["product_desc"];
        $previousStock = $productDetails["product_stockcount"];
        $previousPrice = $productDetails["product_price"];


        if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == UPLOAD_ERR_OK) {
            $target_dir . $productId . ".jpg";
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            if (file_exists($target_file)) {
                $uploadOk = 0;
                $response = [
                    'status' => 'error',
                    'message' => 'FileExists'
                ];
            }

            if ($_FILES["product_image"]["size"] > 2000000) {
                $uploadOk = 0;
                $response = [
                    'status' => 'error',
                    'message' => 'SizeTooLarge'
                ];
            }

            $check = getimagesize($_FILES["product_image"]["tmp_name"]);
            if ($check == false) {
                $uploadOk = 0;
                $response = [
                    'status' => 'error',
                    'message' => 'NotImage'
                ];
            }

            if ($imageFileType != "jpg") {
                $uploadOk = 0;
                $response = [
                    'status' => 'error',
                    'message' => 'NotValidFormat'
                ];
            }

            if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
                $uploadOk = 1;
            }
        } else {
            $result = $db_handler->editProduct($productId,
            (isset($newName) ? $newName : $previousName),
            (isset($newDesc) ? $newDesc : $previousDesc),
            (isset($newPrice) ? $newPrice : $previousPrice),
            (isset($newStock) ? $newStock : $previousStock),
            (isset($newCategoryID) ? $newCategoryID : $previousCategoryID),
            );
            if ($result == "Product updated successfully.") {
                $response = [
                    'status' => 'success',
                    'message' => 'Product updated successfully.',
                    'productId' => $productId,
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
