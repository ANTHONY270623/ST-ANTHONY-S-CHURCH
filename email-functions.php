<?php
require_once __DIR__ . '/config.php';

// Check if PHPMailer is available, if not provide installation instructions
$phpmailer_available = false;
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
    $phpmailer_available = class_exists('PHPMailer\\PHPMailer\\PHPMailer');
}

function send_email_with_phpmailer($to, $subject, $body, $from_email, $from_name = '') {
    global $phpmailer_available;
    
    if (!$phpmailer_available) {
        return false;
    }
    
    try {
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        
        // Server settings
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USERNAME;
        $mail->Password   = SMTP_PASSWORD;
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = SMTP_PORT;
        
        // Recipients
        $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
        $mail->addAddress($to);
        $mail->addReplyTo($from_email, $from_name);
        
        // Content
        $mail->isHTML(false);
        $mail->Subject = $subject;
        $mail->Body    = $body;
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("PHPMailer Error: " . $mail->ErrorInfo);
        return false;
    }
}

function send_email_basic($to, $subject, $body, $from_email, $from_name = '') {
    $headers = "From: $from_email\r\n";
    $headers .= "Reply-To: $from_email\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    
    return mail($to, $subject, $body, $headers);
}

function send_church_email($name, $email, $phone, $subject, $message) {
    $to = ADMIN_EMAIL;
    $email_subject = "New Contact Form Submission: " . $subject;
    
    $email_body = "You have received a new message from the church website contact form:\n\n";
    $email_body .= "Name: " . $name . "\n";
    $email_body .= "Email: " . $email . "\n";
    $email_body .= "Phone: " . $phone . "\n";
    $email_body .= "Subject: " . $subject . "\n\n";
    $email_body .= "Message:\n" . $message . "\n\n";
    $email_body .= "Submitted on: " . date('Y-m-d H:i:s') . "\n";
    $email_body .= "Reply to this email to respond directly to the sender.";
    
    // Try PHPMailer first if available and SMTP is enabled
    if (ENABLE_SMTP && $phpmailer_available) {
        return send_email_with_phpmailer($to, $email_subject, $email_body, $email, $name);
    } else {
        // Fall back to basic mail function
        return send_email_basic($to, $email_subject, $email_body, $email, $name);
    }
}
?>