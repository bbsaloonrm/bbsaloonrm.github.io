<?php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $service = $_POST['service'];

    // Create PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'bbsaloonrm@gmail.com';  // Replace with your email
        $mail->Password = 'pmwaazcogzdhedaa';  // Use an app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Email settings
        $mail->setFrom('bbsaloonrm@gmail.com', 'BBSaloon');
        $mail->addAddress($email, $name);
        $mail->addAddress('bbsaloonrm@gmail.com');
        $mail->isHTML(true); // Enable HTML format

        // Email body
        $emailBody = "
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; color: #333; }
                    .container { padding: 20px; border: 1px solid #ddd; border-radius: 10px; background: #f9f9f9; text-align: center; }
                    h2 { color: #555; }
                    .details { font-size: 16px; }
                    .btn {
                        display: inline-block;
                        padding: 10px 20px;
                        font-size: 16px;
                        color: #fff;
                        background-color: #d9534f;
                        text-decoration: none;
                        border-radius: 5px;
                        margin-top: 15px;
                    }
                    .btn:hover { background-color: #c9302c; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <h2>Booking Confirmation</h2>
                    <p>Dear <strong>$name</strong>,</p>
                    <p>Your appointment for <strong>$service</strong> has been confirmed.</p>
                    <p><strong>Date:</strong> $date</p>
                    <p><strong>Time:</strong> $time</p>
                    <p>Thank you for choosing our Barber Shop!</p>
                </div>
            </body>
            </html>
        ";

        $mail->Subject = "Booking Confirmation";
        $mail->Body = $emailBody;

        // Send email
        $mail->send();
        echo json_encode(['status' => 'success', 'message' => 'Confirmation email sent!']);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Email could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
    }
}
?>