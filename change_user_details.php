<?php
// Database connection settings
$host = "localhost";
$dbname = " ";
$username = " ";
$password = " ";

// Create a new PDO instance
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate form inputs
    $user_id = $_POST['user_id'];
    $new_username = $_POST['new_username'];
    $new_email = $_POST['new_email'];

    // Update user's details in the database
    $sql = "UPDATE users SET username = :username, email = :email WHERE id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['username' => $new_username, 'email' => $new_email, 'user_id' => $user_id]);

    // Redirect or display success message
    header("Location: users.php?details_changed=success");
    exit();
}
?>