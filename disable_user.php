<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "teamP";

// Create a new PDO instance
try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate user ID
    $user_id = $_POST['user_id'];
    if (!is_numeric($user_id)) {
        die("Invalid user ID.");
    }

    // Disable user in the database
    $sql = "UPDATE users SET role = 'disabled' WHERE id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['user_id' => $user_id]);

    // Redirect or display success message
    header("Location: users.php?status=disabled");
    exit();
}
?>
