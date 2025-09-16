<?php
<?php
// Check if form submitted
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: contact-form.php");
    exit;
}

// Get form data
$name = isset($_POST["name"]) ? htmlspecialchars(trim($_POST["name"])) : '';
$email = isset($_POST["email"]) ? filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL) : '';
$phone = isset($_POST["phone"]) ? htmlspecialchars(trim($_POST["phone"])) : '';
$subject = isset($_POST["subject"]) ? htmlspecialchars(trim($_POST["subject"])) : '';
$message = isset($_POST["message"]) ? htmlspecialchars(trim($_POST["message"])) : '';

// Basic validation
if (empty($name) || empty($email) || empty($subject) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: contact-form.php?error=validation");
    exit;
}

// Set up basic mail function
$to = "greenparkchurch25@gmail.com";
$email_subject = "Church Contact Form: $subject";

// Email content
$email_content = "Name: $name\r\n";
$email_content .= "Email: $email\r\n";
if (!empty($phone)) {
    $email_content .= "Phone: $phone\r\n";
}
$email_content .= "Subject: $subject\r\n\r\n";
$email_content .= "Message:\r\n$message\r\n";

// Headers
$headers = "From: $email\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

// Send email
$success = mail($to, $email_subject, $email_content, $headers);

// Log attempt
$log_entry = date('Y-m-d H:i:s') . " - Email from $name ($email): " . ($success ? "SUCCESS" : "FAILED") . "\n";
file_put_contents('email_log.txt', $log_entry, FILE_APPEND);

// Redirect based on result
if ($success) {
    header("Location: contact-form.php?status=success");
} else {
    header("Location: contact-form.php?status=error");
}
exit;
?>