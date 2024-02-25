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

// Check if the current user is an admin (you need to implement this logic)
$is_admin = true; // Example logic - you need to implement your own logic to check if the current user is an admin

if ($is_admin && $_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate form input
    $user_id = $_POST['user_id'];

    // Promote user to staff by updating the 'role' column in the database
    $sql = "UPDATE users SET role = 'staff' WHERE id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['user_id' => $user_id]);

    // Redirect or display success message
    header("Location: users.php?promotion=success");
    exit();
}
?>
