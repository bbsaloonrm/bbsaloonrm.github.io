<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection using PDO
try {
    $pdo = new PDO("mysql:host=localhost;dbname=saloon", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die(json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $e->getMessage()]));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $service = $_POST['service'];

    // Validate booking time
    $booking_datetime = $date . ' ' . explode(' - ', $time)[0]; // Extract start time
    $current_datetime = date('Y-m-d H:i');

    if ($booking_datetime < $current_datetime) {
        echo json_encode(['status' => 'error', 'message' => 'The selected date and time are in the past. Please choose a future date and time.']);
        exit();
    }

    // Check for max bookings per time slot
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM bookings WHERE date = ? AND time = ?");
    $stmt->execute([$date, $time]);
    $result = $stmt->fetch();

    if ($result['total'] >= 3) {
        echo json_encode(['status' => 'error', 'message' => 'Booking limit exceeded for this time slot. Please choose another time.']);
        exit();
    }

    // Insert booking into database
    $stmt = $pdo->prepare("INSERT INTO bookings (name, email, phone, date, time, service) VALUES (?, ?, ?, ?, ?, ?)");
    
    try {
        $stmt->execute([$name, $email, $phone, $date, $time, $service]);
        echo json_encode(['status' => 'success', 'message' => 'Booking confirmed!']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
}
?>