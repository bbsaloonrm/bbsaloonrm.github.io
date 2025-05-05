<?php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = isset($_POST['name']) ? trim($_POST['name']) : null;
    $email = isset($_POST['email']) ? trim($_POST['email']) : null;
    $message = isset($_POST['message']) ? trim($_POST['message']) : null;

    // Validate form data
    if (empty($name) || empty($email) || empty($message)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit();
    }

    // Send email with PHPMailer
    $mail = new PHPMailer(true);

    try {
        // SMTP Settings (Use Gmail, Outlook, or Your Own SMTP)
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Change for Outlook (smtp.office365.com)
        $mail->SMTPAuth = true;
        $mail->Username = 'bbsaloonrm@gmail.com'; // Replace with your email
        $mail->Password = 'pmwaazcogzdhedaa'; // Use App Password for Gmail
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Email Content
        $mail->setFrom('bbsaloonrm@gmail.com', 'BBSaloon');
        $mail->addAddress($email, $name ); // Send email to user
        $mail->addAddress('bbsaloonrm@gmail.com');

        $emailBody = "Your message has been sent
        Name: $name
        Your message: " . $message . "";

        $mail->Subject = "Contact Form Submission";
        $mail->Body = $emailBody;

        if ($mail->send()) {
            echo json_encode(['status' => 'success', 'message' => 'Confirmation email sent!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error sending confirmation email.']);
        }
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Email error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>