<?php
// Database connection parameters
$servername = "localhost";
$username = "";
$password = "";
$dbname = "";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user input
    $username = $_POST["username"];
    $rawPassword = $_POST["password"];

    // Validate password requirements
    if (!preg_match("/^(?=.*[A-Z])(?=.*[0-9])(?=.*[^a-zA-Z0-9]).{8,}$/", $rawPassword)) {
        // Password does not meet the requirements
        echo "Password must contain at least one uppercase letter, one number, and one special character, and be at least 8 characters long.";
        exit();
    }

    // Hash the password using PASSWORD_BCRYPT algorithm
    $password = password_hash($rawPassword, PASSWORD_BCRYPT);

    // Prepare and execute SQL statement to insert user data
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $password);

    if ($stmt->execute()) {
        // Redirect to a Home Page
        header("Location: index.html");
        exit();
    } else {
        // Handle errors,display's an error message.
        echo "Error: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
}

// Close database connection
$conn->close();
?>
