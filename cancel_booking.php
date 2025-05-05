<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
$host = 'localhost';
$db = 'saloon';
$user = 'root'; // Change to your DB username
$pass = ''; // Change to your DB password
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die(json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $e->getMessage()]));
}

// Get form data
$name = isset($_POST['name']) ? trim($_POST['name']) : null; // Trim whitespace
$phone = isset($_POST['phone']) ? trim($_POST['phone']) : null; // Trim whitespace
$date = isset($_POST['date']) ? trim($_POST['date']) : null; // Trim whitespace
$email = isset($_POST['email']) ? trim($_POST['email']) : null; // Trim whitespace

// Debug: Log the form data
error_log("Form Data - Name: $name, Phone: $phone, Date: $date, Email: $email");

// SQL query to find and delete the booking
$sql = "DELETE FROM bookings WHERE name = :name AND phone = :phone AND date = :date";
$stmt = $pdo->prepare($sql);

// Bind parameters to prevent SQL injection
$stmt->bindParam(':name', $name);
$stmt->bindParam(':phone', $phone);
$stmt->bindParam(':date', $date);

// Execute the query
try {
    if ($stmt->execute()) {
        // Check if any rows were affected
        if ($stmt->rowCount() > 0) {
            // Return success response
            echo json_encode(['status' => 'success', 'message' => 'Booking canceled!']);
        } else {
            // No matching booking found
            echo json_encode(['status' => 'error', 'message' => 'No booking found with the provided details.']);
        }
    } else {
        // Query execution failed
        echo json_encode(['status' => 'error', 'message' => 'Error canceling booking.']);
    }
} catch (PDOException $e) {
    // Database error
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}
?>