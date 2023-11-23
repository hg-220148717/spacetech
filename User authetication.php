<?php

session_start(); // Start the session

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Get the form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Connect to the database
    $servername = "localhost";
    $dbusername = "root";
    $dbpassword = "";
    $dbname = "";// e-coomerce database name
    
    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Prepare the SQL statement to check if the credentials match
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    
    // Execute the SQL statement
    $stmt->execute();
    
    // Check if there is a row with the matching credentials
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        // Login successful, redirect to index.html
        $_SESSION['loggedin'] = true;
        header("Location: index.html");
    } else {
        // Login failed, display error message
        $error = "Invalid username or password";
        echo "<div style='color:red'>$error</div>";
    }
    
    // Close the statement and the database connection
    $stmt->close();
    $conn->close();
}
?>
