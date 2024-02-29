<?php
// Establish a connection to the MySQL database
$servername = "localhost";
$username = "admin";
$password = "spacetech";
$database = "stock"; // Change this to your actual database name

$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch products from the database
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

// Convert the result set into a JSON array
$products = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

// Close the database connection
$conn->close();

// Output the products as JSON
header('Content-Type: application/json');
echo json_encode($products);
?>
