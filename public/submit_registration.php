<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $first_name     = htmlspecialchars($_POST['first_name']);
    $last_name      = htmlspecialchars($_POST['last_name']);
    $email          = htmlspecialchars($_POST['email']);
    $professor_sid  = htmlspecialchars($_POST['professor_sid']);
    $section        = htmlspecialchars($_POST['section']);
    $description    = htmlspecialchars($_POST['description']);

    $mail = new PHPMailer(true);

    try {
        // SMTP settings from environment variables
        $mail->isSMTP();
        $mail->Host       = getenv('MJ_HOST');             // in-v3.mailjet.com
        $mail->SMTPAuth   = true;
        $mail->Username   = getenv('MJ_USER');             // Mailjet API key
        $mail->Password   = getenv('MJ_PASS');             // Mailjet Secret key
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = getenv('MJ_PORT');             // Usually 587

        // Sender & recipient
        $mail->setFrom('sk.rd3337@gmail.com', 'Redox Quiz App');
        $mail->addAddress(getenv('ADMIN_EMAIL'));          // Admin email from env

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'New Professor Registration Request';
        $mail->Body    = "
            <h3>New Professor Registration Request</h3>
            <p><strong>First Name:</strong> $first_name</p>
            <p><strong>Last Name:</strong> $last_name</p>
            <p><strong>Email Address:</strong> $email</p>
            <p><strong>Professor SID:</strong> $professor_sid</p>
            <p><strong>Section:</strong> $section</p>
            <p><strong>Description:</strong> $description</p>
        ";

        $mail->send();
        echo "✅ Registration submitted successfully. Admin will be notified.";
    } catch (Exception $e) {
        echo "❌ Message could not be sent. Error: {$mail->ErrorInfo}";
    }
}
?>
