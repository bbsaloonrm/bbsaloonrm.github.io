<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Include the Composer autoload file

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Get form data
$name = isset($_POST['name']) ? trim($_POST['name']) : null; // Trim whitespace
$phone = isset($_POST['phone']) ? trim($_POST['phone']) : null; // Trim whitespace
$date = isset($_POST['date']) ? trim($_POST['date']) : null; // Trim whitespace
$email = isset($_POST['email']) ? trim($_POST['email']) : null; // Trim whitespace

// Debug: Log the form data
error_log("Email Data - Name: $name, Phone: $phone, Date: $date, Email: $email");

// Create PHPMailer instance
$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP(); // Use SMTP
    $mail->Host = 'smtp.gmail.com'; // SMTP server address
    $mail->SMTPAuth = true;
    $mail->Username = 'bbsaloonrm@gmail.com'; // Your SMTP username (email)
    $mail->Password = 'pmwaazcogzdhedaa'; // Your SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Recipients
    $mail->setFrom('no-reply@example.com', 'BBSaloon');
    $mail->addAddress($email); // Send the email to the customer
    $mail->addAddress('bbsaloonrm@gmail.com'); // Send the email to the admin (or customer if needed)

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Booking Cancellation';
    $mail->Body    = "Booking details:<br><strong>Name:</strong> $name<br><strong>Phone:</strong> $phone<br><strong>Date:</strong> $date<br><br>Your booking has been successfully canceled.";

    // Send email
    if ($mail->send()) {
        echo json_encode(['status' => 'success', 'message' => 'Confirmation email sent!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error sending confirmation email.']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Email error: ' . $e->getMessage()]);
}
?>