<?php
// Handle contact form submission: store in DB and email admin, then redirect with status
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

function redirect_with($params) {
    $location = 'contact.html';
    $query = http_build_query($params);
    header('Location: ' . $location . (strlen($query) ? ('?' . $query) : ''));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_with(['status' => 'invalid']);
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

$errors = [];
if ($name === '') $errors[] = 'Name is required.';
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required.';
if ($subject === '') $errors[] = 'Subject is required.';
if ($message === '') $errors[] = 'Message is required.';

if ($errors) {
    redirect_with(['status' => 'error', 'msg' => urlencode(implode(' ', $errors))]);
}

try {
    $db = db_connect();
    $stmt = $db->prepare('INSERT INTO contact_messages (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)');
    if (!$stmt) {
        throw new Exception('Prepare failed: ' . $db->error);
    }
    $stmt->bind_param('sssss', $name, $email, $phone, $subject, $message);
    if (!$stmt->execute()) {
        throw new Exception('Execute failed: ' . $stmt->error);
    }
    $insertId = $stmt->insert_id;
    $stmt->close();

    // Build email common parts
    $to = ADMIN_EMAIL;
    $email_subject = 'New Contact Message: ' . $subject;
    $email_body = "New contact message from the website\r\n\r\n" .
                  "Name: $name\r\n" .
                  "Email: $email\r\n" .
                  "Phone: $phone\r\n" .
                  "Subject: $subject\r\n\r\n" .
                  "Message:\r\n$message\r\n";

    $sent = false;
    // Try SMTP via PHPMailer if enabled and available
    if (defined('ENABLE_SMTP') && ENABLE_SMTP) {
        $phpmailerClass = '\\PHPMailer\\PHPMailer\\PHPMailer';
        $exceptionClass = '\\PHPMailer\\PHPMailer\\Exception';
        $smtpClass = '\\PHPMailer\\PHPMailer\\SMTP';
        $exceptionLoaded = false;
        // Attempt to include library if present
        $base = __DIR__ . '/vendor/PHPMailer/src';
        if (file_exists($base . '/Exception.php')) @require_once $base . '/Exception.php';
        if (file_exists($base . '/PHPMailer.php')) @require_once $base . '/PHPMailer.php';
        if (file_exists($base . '/SMTP.php')) @require_once $base . '/SMTP.php';
        if (class_exists($phpmailerClass)) {
            try {
                $mail = new $phpmailerClass(true);
                $mail->isSMTP();
                $mail->Host = defined('SMTP_HOST') ? SMTP_HOST : 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = defined('SMTP_USERNAME') ? SMTP_USERNAME : '';
                $mail->Password = defined('SMTP_PASSWORD') ? SMTP_PASSWORD : '';
                $mail->SMTPSecure = 'tls';
                $mail->Port = defined('SMTP_PORT') ? SMTP_PORT : 587;
                $fromEmail = (defined('SMTP_FROM_EMAIL') && SMTP_FROM_EMAIL) ? SMTP_FROM_EMAIL : (defined('SMTP_USERNAME') ? SMTP_USERNAME : ADMIN_EMAIL);
                $fromName = (defined('SMTP_FROM_NAME') && SMTP_FROM_NAME) ? SMTP_FROM_NAME : 'Church Website';
                $mail->setFrom($fromEmail, $fromName);
                $mail->addAddress($to, 'Admin');
                if ($email) { $mail->addReplyTo($email, $name ?: $email); }
                $mail->Subject = $email_subject;
                $mail->Body = str_replace("\r\n", "\n", $email_body);
                $mail->AltBody = $email_body;
                $sent = $mail->send();
            } catch (\Throwable $e) {
                error_log('SMTP send failed: ' . $e->getMessage());
                $sent = false;
            }
        }
    }

    // Fallback to PHP mail() if SMTP not used or failed
    if (!$sent) {
        $fromName = 'Church Website';
        $headers = '';
        $headers .= 'From: ' . $fromName . ' <' . ADMIN_EMAIL . ">\r\n";
        if ($email) { $headers .= 'Reply-To: ' . $name . ' <' . $email . ">\r\n"; }
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $headers .= 'X-Mailer: PHP/' . phpversion();
        $sent = @mail($to, $email_subject, $email_body, $headers);
    }

    // Log the outcome for diagnostics
    try {
        $logLine = date('Y-m-d H:i:s') . ' | contact_submit | to=' . $to . ' | subject=' . $email_subject . ' | sent=' . ($sent ? 'YES' : 'NO') . "\n";
        @file_put_contents(__DIR__ . '/email_log.txt', $logLine, FILE_APPEND);
    } catch (\Throwable $e) {
        // ignore log failures
    }

    redirect_with(['status' => $sent ? 'ok' : 'saved', 'id' => $insertId]);
} catch (Throwable $ex) {
    error_log('Contact submit error: ' . $ex->getMessage());
    redirect_with(['status' => 'error']);
}
