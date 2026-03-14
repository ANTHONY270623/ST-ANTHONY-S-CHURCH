<?php
require_once __DIR__ . '/config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

// Enhanced logging function
function log_debug($message) {
    $timestamp = date('Y-m-d H:i:s');
    $log_entry = "[$timestamp] $message\n";
    file_put_contents('email_debug.log', $log_entry, FILE_APPEND);
}

// Enhanced email function with better error reporting
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
    
    // Prepare headers
    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Return-Path: $email\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    
    log_debug("Attempting to send email to: $to");
    log_debug("Subject: $email_subject");
    log_debug("From: $email");
    log_debug("Headers: " . str_replace("\r\n", " | ", $headers));
    
    // Check mail function availability
    if (!function_exists('mail')) {
        log_debug("ERROR: mail() function not available");
        return false;
    }
    
    // Attempt to send email
    $result = mail($to, $email_subject, $email_body, $headers);
    
    if ($result) {
        log_debug("SUCCESS: mail() function returned TRUE");
    } else {
        log_debug("ERROR: mail() function returned FALSE");
        
        // Get last error
        $error = error_get_last();
        if ($error) {
            log_debug("Last PHP error: " . $error['message']);
        }
    }
    
    return $result;
}

// Main processing
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

try {
    log_debug("=== New Contact Form Submission ===");
    
    // Get and sanitize form data
    $name = trim($_POST["name"] ?? '');
    $email = trim($_POST["email"] ?? '');
    $phone = trim($_POST["phone"] ?? '');
    $subject = trim($_POST["subject"] ?? '');
    $message = trim($_POST["message"] ?? '');
    
    log_debug("Form data received - Name: $name, Email: $email, Subject: $subject");
    
    // Validate required fields
    $errors = [];
    if (empty($name)) $errors[] = 'Name is required';
    if (empty($email)) $errors[] = 'Email is required';
    if (empty($subject)) $errors[] = 'Subject is required';
    if (empty($message)) $errors[] = 'Message is required';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email format';
    
    if (!empty($errors)) {
        log_debug("Validation errors: " . implode(', ', $errors));
        throw new Exception(implode(', ', $errors));
    }
    
    // Save to simple text file (backup method)
    $log_entry = "\n=== Contact Form " . date('Y-m-d H:i:s') . " ===\n";
    $log_entry .= "Name: $name\nEmail: $email\nPhone: $phone\nSubject: $subject\nMessage: $message\n";
    file_put_contents('contact_messages.txt', $log_entry, FILE_APPEND);
    log_debug("Message saved to text file backup");
    
    // Attempt to send email
    $email_sent = send_church_email($name, $email, $phone, $subject, $message);
    
    // Prepare response
    $response = [
        'success' => true,
        'message' => 'Thank you for your message! We have received your inquiry.',
        'email_sent' => $email_sent,
        'debug_info' => [
            'timestamp' => date('Y-m-d H:i:s'),
            'admin_email' => ADMIN_EMAIL,
            'mail_function_available' => function_exists('mail'),
            'php_version' => phpversion()
        ]
    ];
    
    if (!$email_sent) {
        $response['message'] .= ' (Note: Email notification may have failed, but your message has been saved. Please check the email debug log.)';
        log_debug("WARNING: Email sending failed but form submission was successful");
    }
    
    log_debug("Response prepared: " . json_encode($response));
    echo json_encode($response);
    
} catch (Exception $e) {
    log_debug("ERROR: " . $e->getMessage());
    
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage(),
        'debug_info' => [
            'timestamp' => date('Y-m-d H:i:s'),
            'error_details' => $e->getMessage()
        ]
    ]);
}

log_debug("=== End Contact Form Processing ===\n");
?>