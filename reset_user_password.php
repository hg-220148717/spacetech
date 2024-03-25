<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "teamP";

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
    $new_password = $_POST['new_password'];

    // Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Update user's password in the database
    $sql = "UPDATE users SET password = :password WHERE id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['password' => $hashed_password, 'user_id' => $user_id]);

    // Redirect or display success message
    header("Location: users.php?password_reset=success");
    exit();
}
?>

