<?php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact St. Anthony's Church</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        /* Basic styling for the contact form */
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #8b0000;
            text-align: center;
            margin-bottom: 30px;
        }
        .form-container {
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        textarea {
            min-height: 120px;
        }
        button {
            background-color: #8b0000;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }
        button:hover {
            background-color: #6d0000;
        }
        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            border-left: 4px solid #155724;
        }
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            border-left: 4px solid #721c24;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #8b0000;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Contact St. Anthony's Church</h1>
        
        <?php
        // Process form submission
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Get form data
            $name = isset($_POST["name"]) ? htmlspecialchars(trim($_POST["name"])) : '';
            $email = isset($_POST["email"]) ? filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL) : '';
            $phone = isset($_POST["phone"]) ? htmlspecialchars(trim($_POST["phone"])) : '';
            $subject = isset($_POST["subject"]) ? htmlspecialchars(trim($_POST["subject"])) : '';
            $message = isset($_POST["message"]) ? htmlspecialchars(trim($_POST["message"])) : '';
            
            // Validate inputs
            $errors = array();
            
            if (empty($name)) {
                $errors[] = "Name is required";
            }
            
            if (empty($email)) {
                $errors[] = "Email is required";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Invalid email format";
            }
            
            if (empty($subject)) {
                $errors[] = "Subject is required";
            }
            
            if (empty($message)) {
                $errors[] = "Message is required";
            }
            
            // If no errors, send email
            if (empty($errors)) {
                $to = "greenparkchurch25@gmail.com";
                $email_subject = "Contact Form: $subject";
                
                // Build email content
                $email_body = "You have received a new message from your church website.\n\n";
                $email_body .= "Name: $name\n";
                $email_body .= "Email: $email\n";
                $email_body .= "Phone: $phone\n\n";
                $email_body .= "Subject: $subject\n\n";
                $email_body .= "Message:\n$message\n";
                
                // Email headers
                $headers = "From: $name <$email>\r\n";
                $headers .= "Reply-To: $email\r\n";
                
                // Send email
                $mail_success = mail($to, $email_subject, $email_body, $headers);
                
                if ($mail_success) {
                    echo '<div class="success-message">';
                    echo '<p><i class="fas fa-check-circle"></i> Thank you for contacting us! Your message has been sent successfully.</p>';
                    echo '</div>';
                } else {
                    echo '<div class="error-message">';
                    echo '<p><i class="fas fa-exclamation-circle"></i> Sorry, there was an error sending your message. Please try again later or contact us directly at 7025727541.</p>';
                    echo '</div>';
                }
            } else {
                // Display errors
                echo '<div class="error-message">';
                echo '<p><i class="fas fa-exclamation-circle"></i> Please correct the following errors:</p>';
                echo '<ul>';
                foreach ($errors as $error) {
                    echo '<li>' . $error . '</li>';
                }
                echo '</ul>';
                echo '</div>';
            }
        }
        ?>
        
        <div class="form-container">
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-group">
                    <label for="name">Your Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo isset($name) ? $name : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address:</label>
                    <input type="email" id="email" name="email" value="<?php echo isset($email) ? $email : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="phone">Phone Number:</label>
                    <input type="tel" id="phone" name="phone" value="<?php echo isset($phone) ? $phone : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="subject">Subject:</label>
                    <input type="text" id="subject" name="subject" value="<?php echo isset($subject) ? $subject : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="message">Your Message:</label>
                    <textarea id="message" name="message" required><?php echo isset($message) ? $message : ''; ?></textarea>
                </div>
                
                <button type="submit">Send Message</button>
            </form>
        </div>
        
        <a href="index.html" class="back-link"><i class="fas fa-arrow-left"></i> Back to Homepage</a>
    </div>
</body>
</html>