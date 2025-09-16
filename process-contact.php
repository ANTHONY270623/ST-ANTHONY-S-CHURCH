<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Include PHPMailer files
require 'vendor/PHPMailer/src/Exception.php';
require 'vendor/PHPMailer/src/PHPMailer.php';
require 'vendor/PHPMailer/src/SMTP.php';

// Only process POST requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and sanitize inputs
    $name = filter_var(trim($_POST["name"] ?? ''), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_var(trim($_POST["email"] ?? ''), FILTER_SANITIZE_EMAIL);
    $phone = filter_var(trim($_POST["phone"] ?? ''), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $subject = filter_var(trim($_POST["subject"] ?? ''), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $message = filter_var(trim($_POST["message"] ?? ''), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
    // Validate required fields
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        echo "Please fill all required fields";
        exit;
    }
    
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format";
        exit;
    }
    
    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);
    
    try {
        // Server settings
        // IMPORTANT: Disable debug output for production
        $mail->SMTPDebug = 0; // 0 = no output, 1 = client messages, 2 = client and server messages
        $mail->isSMTP();                           // Send using SMTP
        $mail->Host       = 'smtp.gmail.com';      // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                  // Enable SMTP authentication
        $mail->Username   = 'greenparkchurch25@gmail.com'; // SMTP username
        $mail->Password   = 'YOUR_APP_PASSWORD';   // SMTP password (replace with your app password)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
        $mail->Port       = 587;                   // TCP port to connect to
        
        // Recipients
        $mail->setFrom('greenparkchurch25@gmail.com', 'St. Anthony\'s Church Contact Form');
        $mail->addAddress('greenparkchurch25@gmail.com', 'Church Admin'); // Add a recipient
        $mail->addReplyTo($email, $name);
        
        // Content
        $mail->isHTML(true);                      // Set email format to HTML
        $mail->Subject = "Website Contact: $subject";
        
        // Build email content (HTML)
        $emailContent = "
        <h2>New Contact Form Submission</h2>
        <p><strong>Name:</strong> $name</p>
        <p><strong>Email:</strong> $email</p>
        <p><strong>Phone:</strong> $phone</p>
        <p><strong>Subject:</strong> $subject</p>
        <p><strong>Message:</strong></p>
        <p>$message</p>
        ";
        
        // Plain text version
        $textContent = "
        New Contact Form Submission
        --------------------------
        Name: $name
        Email: $email
        Phone: $phone
        Subject: $subject
        
        Message:
        $message
        ";
        
        $mail->Body = $emailContent;
        $mail->AltBody = $textContent;
        
        $mail->send();
        echo "success";
    } catch (Exception $e) {
        // Log the error for your reference but don't expose details to user
        error_log("Mailer Error: " . $mail->ErrorInfo);
        echo "Sorry, we couldn't send your message. Please try again later.";
    }
} else {
    // Not a POST request
    echo "Invalid request method";
}
?>