<?php
// Set headers to prevent caching
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-Type: text/plain');

// Only process POST requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = isset($_POST["name"]) ? $_POST["name"] : '';
    $email = isset($_POST["email"]) ? $_POST["email"] : '';
    $phone = isset($_POST["phone"]) ? $_POST["phone"] : '';
    $subject = isset($_POST["subject"]) ? $_POST["subject"] : '';
    $message = isset($_POST["message"]) ? $_POST["message"] : '';
    
    // Create log entry
    $log_entry = "=== New Contact Form Submission (" . date('Y-m-d H:i:s') . ") ===\n";
    $log_entry .= "Name: $name\n";
    $log_entry .= "Email: $email\n";
    $log_entry .= "Phone: $phone\n";
    $log_entry .= "Subject: $subject\n";
    $log_entry .= "Message: $message\n\n";
    
    // Save to log file
    $result = file_put_contents('contact_submissions.log', $log_entry, FILE_APPEND);
    
    if ($result !== false) {
        echo "success";
    } else {
        echo "Failed to save your message. Please try again.";
    }
} else {
    echo "Invalid request method. Please use the contact form.";
}
?>