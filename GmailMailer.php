<?php
/**
 * Simple Gmail SMTP Mailer for Church Contact Form
 * Uses PHP's built-in mail() function with Gmail SMTP configuration
 */
class GmailMailer {
    private $smtp_host;
    private $smtp_port;
    private $smtp_username;
    private $smtp_password;
    private $from_email;
    private $from_name;
    
    public function __construct($username, $password, $from_name = 'St Anthony\'s Church') {
        $this->smtp_host = 'smtp.gmail.com';
        $this->smtp_port = 587;
        $this->smtp_username = $username;
        $this->smtp_password = $password;
        $this->from_email = $username;
        $this->from_name = $from_name;
    }
    
    /**
     * Send email using Gmail SMTP
     */
    public function sendEmail($to, $subject, $body, $reply_to = null, $reply_name = '') {
        // Prepare headers for Gmail
        $headers = array();
        $headers[] = "From: {$this->from_name} <{$this->from_email}>";
        $headers[] = "Reply-To: " . ($reply_to ?: $this->from_email);
        if ($reply_name) {
            $headers[] = "Reply-To: {$reply_name} <{$reply_to}>";
        }
        $headers[] = "X-Mailer: PHP/" . phpversion();
        $headers[] = "MIME-Version: 1.0";
        $headers[] = "Content-Type: text/plain; charset=UTF-8";
        $headers[] = "X-Priority: 3";
        
        $header_string = implode("\r\n", $headers);
        
        // Log the attempt
        $this->logEmailAttempt($to, $subject, $reply_to);
        
        // Use PHP mail() function
        $result = mail($to, $subject, $body, $header_string);
        
        // Log the result
        $this->logEmailResult($result);
        
        return $result;
    }
    
    /**
     * Log email attempts for debugging
     */
    private function logEmailAttempt($to, $subject, $reply_to) {
        $log = "[" . date('Y-m-d H:i:s') . "] Attempting to send email\n";
        $log .= "To: $to\n";
        $log .= "Subject: $subject\n";
        $log .= "Reply-To: $reply_to\n";
        $log .= "SMTP Host: {$this->smtp_host}\n";
        $log .= "SMTP User: {$this->smtp_username}\n";
        file_put_contents('gmail_mailer.log', $log, FILE_APPEND);
    }
    
    /**
     * Log email results
     */
    private function logEmailResult($result) {
        $log = "Result: " . ($result ? 'SUCCESS' : 'FAILED') . "\n";
        if (!$result) {
            $error = error_get_last();
            if ($error) {
                $log .= "Last PHP Error: " . $error['message'] . "\n";
            }
        }
        $log .= "------------------------\n\n";
        file_put_contents('gmail_mailer.log', $log, FILE_APPEND);
    }
    
    /**
     * Test email configuration
     */
    public function testConfiguration() {
        $test_subject = "Test Email from Church Website";
        $test_body = "This is a test email sent at " . date('Y-m-d H:i:s') . "\n\n";
        $test_body .= "If you receive this email, the Gmail SMTP configuration is working correctly!";
        
        return $this->sendEmail($this->from_email, $test_subject, $test_body);
    }
}

/**
 * Easy function to send church contact form emails
 */
function sendChurchContactEmail($name, $email, $phone, $subject, $message, $gmail_username, $gmail_password) {
    $mailer = new GmailMailer($gmail_username, $gmail_password);
    
    $admin_email = 'greenparkchurch25@gmail.com';
    $email_subject = "New Contact Form Submission: " . $subject;
    
    $email_body = "You have received a new message from the church website contact form:\n\n";
    $email_body .= "Name: " . $name . "\n";
    $email_body .= "Email: " . $email . "\n";
    $email_body .= "Phone: " . $phone . "\n";
    $email_body .= "Subject: " . $subject . "\n\n";
    $email_body .= "Message:\n" . $message . "\n\n";
    $email_body .= "Submitted on: " . date('Y-m-d H:i:s') . "\n";
    $email_body .= "Reply to this email to respond directly to the sender.";
    
    return $mailer->sendEmail($admin_email, $email_subject, $email_body, $email, $name);
}
?>