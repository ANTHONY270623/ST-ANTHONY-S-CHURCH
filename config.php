<?php
// Global configuration for the Church project
// ⚠️ IMPORTANT: Update these settings before deploying to production server

// Admin notification email
define('ADMIN_EMAIL', 'greenparkchurch25@gmail.com');

// Database (MySQL) settings 
// ⚠️ FOR PRODUCTION: Update these with your hosting provider's database credentials
// Local XAMPP defaults are shown below
define('DB_HOST', '127.0.0.1');      // Change to your host's MySQL server (e.g., 'localhost' or provided hostname)
define('DB_USER', 'root');            // Change to your database username
define('DB_PASS', '');                // Change to your database password
define('DB_NAME', 'church_project');  // Keep or change database name as needed

// Optional: SMTP settings for email functionality
// ⚠️ FOR PRODUCTION: Set ENABLE_SMTP to true and fill in your Gmail app password below
// To get a Gmail app password:
//   1. Go to Google Account Settings → Security
//   2. Enable 2-Step Verification
//   3. Go to "App passwords" and create one for "Mail"
//   4. Use that 16-character password below (no spaces)
define('ENABLE_SMTP', true);
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'greenparkchurch25@gmail.com'); // Your Gmail address
define('SMTP_PASSWORD', 'YOUR_APP_PASSWORD_HERE');      // ⚠️ REPLACE with your 16-character Gmail app password
define('SMTP_FROM_EMAIL', 'greenparkchurch25@gmail.com');
define('SMTP_FROM_NAME', "St Anthony's Church Green Park");
